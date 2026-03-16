from tracemalloc import start
from types import NoneType
from flask import Flask
import psycopg2
import json
import os

app=Flask(__name__)

app.config['JSON_SORT_KEYS'] = False

HOST=os.environ['DBHOST']
PRT=os.environ['DBPRT']
DAT=os.environ['DBDAT']
USER=os.environ['DBUSER']
PAS=os.environ['DBPAS']


@app.route("/v1/health")



def v1():

    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        "SELECT version();"
    )
    version=cursor.fetchall()
    cursor.execute(
        "SELECT pg_database_size('dota2')/1024/1024 as dota2_db_size;"
    )
    data=cursor.fetchall()

    version=str( version)
    data=str(data)
    version= version[2:len( version)-2]
    data=data[2:6]
    dump={
        "version":version,
        "dota2_db_size":data

    }
    dump={
        "pgsql":dump
    }

    cursor.close()
    conn.close()
    return dump

@app.route("/v2/patches")

def patches():
    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select o.name,o.patch_start_date,o.patch_end_date,m.id,(extract(epoch from to_timestamp(m.duration)::timestamp)::float/60) as duration
from(select patches.name,cast(extract(epoch from patches.release_date)as integer)as patch_start_date,
	lead(cast(extract(epoch from patches.release_date)as integer),1)over(order by patches.name) patch_end_date
	from patches)
	as o left join matches as m on m.start_time between o.patch_start_date and o.patch_end_date
order by o.name '''
    )
    input=cursor.fetchone()
    matches=[]
    allarray=[]
    matches=[]
    allarray=[]
    while input is not None:
        patch=(input[0])
        lpatch=(input[0])
        verpatch=input[0]
        start=input[1]
        end=input[2]

        while patch==lpatch:
            if input[3] is None:
                matches=[]
            else:
                match={
                    "match_id":input[3],
                    "duration":float(format(round(input[4],2),'2f'))
                }
                matches.append(match)
           
            input=cursor.fetchone() 
            if input is None:
                break
            lpatch=(input[0])

           
        patchout={
            "patch_version":verpatch,
            "patch_start_date":start,
            "patch_end_date":end,
            "matches":matches
        }
        allarray.append(patchout)
        matches=[]
    patches_all={
        "patches":allarray
    }

   
    
    cursor.close()
    conn.close()
    
    return(patches_all)
#/////////////////////////////////////////////////////////////////////////////////////////
@app.route("/v2/players/<name>/game/game_exp")

def game_exp(name):
    
    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select md.player_id,coalesce(p.nick,'unknown'),md.match_id,h.localized_name as hero_localized_name ,
            (extract(epoch from to_timestamp(m.duration)::timestamp)::float/60) as match_duration_minutes,
            (coalesce(md.xp_hero,0)+coalesce(md.xp_creep,0)+coalesce(md.xp_other,0)+coalesce(md.xp_roshan,0))as experiences_gained,
            md.level as level_gained,
            (case when md.player_slot<5 then m.radiant_win
                when md.player_slot>5 and m.radiant_win then 'false'
                when md.player_slot>5 and not m.radiant_win then 'true'      
            end) as winner
            from matches_players_details as md
            join players as p on p.id=md.player_id
            join matches as m on m.id=md.match_id
            join heroes as h on h.id=md.hero_id
            where player_id =%s
            order by match_id'''%name
    )
   
    input=cursor.fetchone()
    if input is None:
        playerdata={
            "No_data":0
        }
        return(playerdata)

    id=input[0]
    nick=input[1]
    matches=[]
    while input is not None:
        match={
            "match_id":input[2],
            "hero_localized_name":input[3],
            "match_duration_minutes":float(format(round(input[4],2),'2f')),
            "experiences_gained":input[5],
            "level_gained":input[6],
            "winner":input[7]
        }
        matches.append(match)
        input=cursor.fetchone()
    playerdata={
        "id":id,
        "player_nick":nick,
        "matches":matches
    }




    cursor.close()
    conn.close()
    
    return(playerdata)
#/////////////////////////////////////////////////////////////////////////////////////////
@app.route("/v2/players/<name>/game_objectives")

def game_objectives(name): 
    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select  md.player_id,coalesce(p.nick,'unknown') as nick,md.match_id,h.localized_name,coalesce(gao.subtype,'NO_ACTION') as subtype,count(*)
            from matches_players_details as md
            join players as p on p.id=md.player_id
            join matches as m on m.id=md.match_id
            join heroes as h on h.id=md.hero_id
            full join game_objectives as gao on gao.match_player_detail_id_1=md.id
            where player_id =%s
            group by md.player_id,nick,h.localized_name,md.match_id,subtype
            order by match_id'''%name
    )
    input=cursor.fetchone()
    if input is None:
        playerdata={
            "No_data":0
        }
        return(playerdata) 
    id=input[0]
    nick=input[1]
    matches=[]
    while input is not None:
        match=(input[2])
        lmatch=(input[2])
        matchid=input[2]
        hero=input[3]
        actions=[]
        while match==lmatch:
            action={
                "hero_action":input[4],
                "count":input[5]
            }
            actions.append(action)
            input=cursor.fetchone()
            if input is None:
                break
            lmatch=(input[2])
        matches_={
            "match_id":matchid,
            "hero_localized_name":hero,
            "actions":actions
        }
        matches.append(matches_)
    gamedata={
        "id":id,
        "player_nick":nick,
        "matches":matches
    }
    

    cursor.close()
    conn.close()

    
    return(gamedata)
#/////////////////////////////////////////////////////////////////////////////////////////
@app.route("/v2/players/<name>/abilities")

def abilities(name):
    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select  md.player_id,coalesce(p.nick,'unknown') as nick,md.match_id,h.localized_name,a.name,count(*),max(au.level)
        from matches_players_details as md
        join players as p on p.id=md.player_id
        join matches as m on m.id=md.match_id
        join heroes as h on h.id=md.hero_id
        full join ability_upgrades as au on au.match_player_detail_id=md.id
        join abilities as a on a.id=au.ability_id
        where player_id =%s
        group by md.player_id,nick,h.localized_name,md.match_id,a.name
        order by match_id'''%name
    )
    input=cursor.fetchone()
    if input is None:
        playerdata={
            "No_data":0
        }
        return(playerdata) 
    id=input[0]
    nick=input[1]
    matches=[]
    while input is not None:
        match=(input[2])
        lmatch=(input[2])
        matchid=input[2]
        hero=input[3]
        abilities=[]
        while match==lmatch:
            ability={
                "ability_name":input[4],
                "count":input[5],
                "upgrade_level":input[6]
            }
            abilities.append(ability)
            input=cursor.fetchone()
            if input is None:
                break
            lmatch=(input[2])
        matches_={
            "match_id":matchid,
            "hero_localized_name":hero,
            "abilities":abilities
        }
        matches.append(matches_)
    gamedata={
        "id":id,
        "player_nick":nick,
        "matches":matches
    }
    

    cursor.close()
    conn.close()

    
    
    return(gamedata)
#/////////////////////////////////////////////////////////////////////////////////////////

if __name__ =="__main__":
    app.run(debug=True)
