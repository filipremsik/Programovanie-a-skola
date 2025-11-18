# ultra_fast_importer.py
import gzip
import orjson  # 🔥 fastest JSON parser in Python
import time
import glob
import re
import os
import multiprocessing
from datetime import datetime
from concurrent.futures import ProcessPoolExecutor, as_completed
from sqlalchemy import text
from db import SessionLocal
from models import (
    User, Place, Hashtag, Tweet,
    TweetHashtag, TweetURL, TweetUserMention, TweetMedia
)

# ---------- Config ----------
BATCH_SIZE = int(os.environ.get("BATCH_SIZE", 300_000))
PROCESSES = int(os.environ.get("PROCESSES", max(1, min((os.cpu_count() or 1), 8))))

# ---------- Precompiled regex ----------
_SANITIZE_RE = re.compile(r"[\x00-\x08\x0B-\x1F\x7F]")

def sanitize_text(obj):
    """Recursively clean strings (remove NUL/control chars)."""
    if isinstance(obj, str):
        return _SANITIZE_RE.sub("", obj).strip()
    if isinstance(obj, dict):
        return {k: sanitize_text(v) for k, v in obj.items()}
    if isinstance(obj, list):
        return [sanitize_text(v) for v in obj]
    return obj

def parse_date(date_str):
    if not date_str:
        return None
    try:
        return datetime.strptime(date_str, "%a %b %d %H:%M:%S %z %Y")
    except Exception:
        return None

# ------------------------------
# PASS 1 — Extract users, places, hashtags
# ------------------------------
def extract_users_places_hashtags(filename):
    opener = gzip.open if filename.endswith(".gz") else open
    users, places, hashtags = {}, {}, set()

    with opener(filename, "rt", encoding="utf-8", errors="replace") as f:
        for line in f:
            try:
                tweet = sanitize_text(orjson.loads(line))
            except Exception:
                continue

            u = tweet.get("user")
            if u:
                uid = u.get("id")
                if uid not in users:
                    users[uid] = {
                        "id": uid,
                        "screen_name": u.get("screen_name"),
                        "name": u.get("name"),
                        "description": u.get("description"),
                        "verified": u.get("verified", False),
                        "protected": u.get("protected", False),
                        "followers_count": u.get("followers_count", 0),
                        "friends_count": u.get("friends_count", 0),
                        "statuses_count": u.get("statuses_count", 0),
                        "created_at": parse_date(u.get("created_at")),
                        "location": u.get("location"),
                        "url": u.get("url"),
                    }

            p = tweet.get("place")
            if p:
                pid = p.get("id")
                if pid not in places:
                    places[pid] = {
                        "id": pid,
                        "full_name": p.get("full_name"),
                        "country": p.get("country"),
                        "country_code": p.get("country_code"),
                        "place_type": p.get("place_type"),
                    }

            for ht in tweet.get("entities", {}).get("hashtags", []):
                txt = ht.get("text")
                if txt:
                    hashtags.add(txt.lower())

            for m in tweet.get("entities", {}).get("user_mentions", []):
                mid = m.get("id")
                if mid and mid not in users:
                    users[mid] = {
                        "id": mid,
                        "screen_name": m.get("screen_name"),
                        "name": m.get("name"),
                    }

    return list(users.values()), list(places.values()), list(hashtags)


def insert_users_places_hashtags(batch):
    users, places, hashtags = batch
    session = SessionLocal()
    try:
        if users:
            session.bulk_insert_mappings(User, users, render_nulls=True)
        if places:
            session.bulk_insert_mappings(Place, places, render_nulls=True)
        if hashtags:
            session.bulk_insert_mappings(Hashtag, [{"tag": h} for h in hashtags])
        session.commit()
    except Exception as e:
        session.rollback()
        print(f"⚠️ Insert failed: {e}")
    finally:
        session.close()

# ------------------------------
# PASS 2 — Insert tweets + relations
# ------------------------------

def import_tweets_from_file(filename):
    opener = gzip.open if filename.endswith(".gz") else open
    session = SessionLocal()

    # Cache hashtag IDs once per worker
    rows = session.execute(text("SELECT id, tag FROM hashtags")).fetchall()
    hashtag_map = {r[1]: r[0] for r in rows}

    tweets, tht, urls, mentions, media, rels = [], [], [], [], [], []
    counter = 0

    with opener(filename, "rt", encoding="utf-8", errors="replace") as f:
        for line in f:
            try:
                t = sanitize_text(orjson.loads(line))
            except Exception:
                continue

            u = t.get("user", {})
            place_id = t.get("place", {}).get("id") if t.get("place") else None
            tid = t.get("id")

            tweets.append({
                "id": tid,
                "created_at": parse_date(t.get("created_at")),
                "full_text": t.get("full_text"),
                "display_from": t.get("display_text_range", [None, None])[0],
                "display_to": t.get("display_text_range", [None, None])[1],
                "lang": t.get("lang"),
                "user_id": u.get("id"),
                "place_id": place_id,
                "source": t.get("source"),
                "in_reply_to_status_id": t.get("in_reply_to_status_id"),
                "retweet_count": t.get("retweet_count", 0),
                "favorite_count": t.get("favorite_count", 0),
                "possibly_sensitive": t.get("possibly_sensitive", False),
                "retweeted_status_id": None,
                "quoted_status_id": None,
            })

            rels.append({
                "tweet_id": tid,
                "retweeted_status_id": t.get("retweeted_status", {}).get("id"),
                "quoted_status_id": t.get("quoted_status_id"),
            })

            for ht in t.get("entities", {}).get("hashtags", []):
                tag = (ht.get("text") or "").lower()
                hid = hashtag_map.get(tag)
                if hid:
                    tht.append({"tweet_id": tid, "hashtag_id": hid})

            for uobj in t.get("entities", {}).get("urls", []):
                urls.append({
                    "tweet_id": tid,
                    "url": uobj.get("url"),
                    "expanded_url": uobj.get("expanded_url"),
                    "display_url": uobj.get("display_url"),
                    "unwound_url": uobj.get("unwound_url"),
                })

            for m in t.get("entities", {}).get("user_mentions", []):
                mentions.append({
                    "tweet_id": tid,
                    "mentioned_user_id": m.get("id"),
                    "mentioned_screen_name": m.get("screen_name"),
                    "mentioned_name": m.get("name"),
                })

            for media_item in t.get("entities", {}).get("media", []):
                media.append({
                    "tweet_id": tid,
                    "media_id": media_item.get("id"),
                    "type": media_item.get("type"),
                    "media_url": media_item.get("media_url"),
                    "media_url_https": media_item.get("media_url_https"),
                    "display_url": media_item.get("display_url"),
                    "expanded_url": media_item.get("expanded_url"),
                })

            counter += 1
            if counter % BATCH_SIZE == 0:
                flush_tweet_batches(session, tweets, tht, urls, mentions, media)
                print(f"[{filename}] {counter:,} tweets processed...")

    flush_tweet_batches(session, tweets, tht, urls, mentions, media)
    update_relations(session, rels)
    session.close()
    print(f"[{filename}]  Done inserting tweets.")


def flush_tweet_batches(session, tweets, tht, urls, mentions, media):
    try:
        if tweets:
            session.bulk_insert_mappings(Tweet, tweets)
            tweets.clear()
        if tht:
            session.bulk_insert_mappings(TweetHashtag, tht)
            tht.clear()
        if urls:
            session.bulk_insert_mappings(TweetURL, urls)
            urls.clear()
        if mentions:
            session.bulk_insert_mappings(TweetUserMention, mentions)
            mentions.clear()
        if media:
            session.bulk_insert_mappings(TweetMedia, media)
            media.clear()
        session.commit()
    except Exception as e:
        session.rollback()
        print(f"Batch failed: {e}")


def update_relations(session, rels, chunk_size=10_000):
    rels = [r for r in rels if r.get("retweeted_status_id") or r.get("quoted_status_id")]
    if not rels:
        return

    for i in range(0, len(rels), chunk_size):
        chunk = rels[i:i + chunk_size]
        vals = []
        params = {}
        for j, r in enumerate(chunk):
            vals.append(f"(:id{j}, :r{j}, :q{j})")
            params[f"id{j}"] = r["tweet_id"]
            params[f"r{j}"] = r.get("retweeted_status_id")
            params[f"q{j}"] = r.get("quoted_status_id")
        sql = f"""
        UPDATE tweets AS t
        SET
            retweeted_status_id = v.retweeted_status_id,
            quoted_status_id = v.quoted_status_id
        FROM (VALUES {','.join(vals)}) AS v(id, retweeted_status_id, quoted_status_id)
        WHERE t.id = v.id;
        """
        try:
            session.execute(text(sql), params)
            session.commit()
        except Exception as e:
            session.rollback()
            print(f"⚠️relation update failed: {e}")

# ------------------------------
# MAIN
# ------------------------------
if __name__ == "__main__":
    total_start = time.time()
    files = sorted(glob.glob(r"C:\Users\42190\Desktop\FIIT\Pdt\data\*.gz"))
    print(f"Found {len(files)} .gz files to import...")

    # === PASS 1: PREPARATION ===
    print("\n=== PASS 1: USERS, PLACES, HASHTAGS ===")
    prepare_start = time.time()
    extracted = []
    with ProcessPoolExecutor(max_workers=PROCESSES) as exe:
        futures = [exe.submit(extract_users_places_hashtags, f) for f in files]
        for fut in as_completed(futures):
            extracted.append(fut.result())
    prepare_end = time.time()

    users, places, hashtags = {}, {}, set()
    for u, p, h in extracted:
        users.update({x["id"]: x for x in u})
        places.update({x["id"]: x for x in p})
        hashtags.update(h)

    insert_start = time.time()
    print(f"Total unique: {len(users):,} users, {len(places):,} places, {len(hashtags):,} hashtags")
    insert_users_places_hashtags((list(users.values()), list(places.values()), list(hashtags)))
    insert_end = time.time()

    print(f"PASS 1 done. Data preparation took {prepare_end - prepare_start:.2f}s, inserts took {insert_end - insert_start:.2f}s.\n")

    # === PASS 2: INSERT TWEETS ===
    print("=== PASS 2: TWEETS ===")
    tweet_insert_start = time.time()
    with ProcessPoolExecutor(max_workers=PROCESSES) as exe:
        futures = [exe.submit(import_tweets_from_file, f) for f in files]
        for fut in as_completed(futures):
            fut.result()
    tweet_insert_end = time.time()

    total_end = time.time()
    print(f"\nPASS 2 done. Tweet inserts took {tweet_insert_end - tweet_insert_start:.2f}s.")
    print(f"All done in {total_end - total_start:.2f}s total.")
