from argparse import ZERO_OR_MORE
from socket import has_dualstack_ipv6
from xml.sax.handler import feature_external_ges
from django.forms import NullBooleanField, NullBooleanSelect
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


@app.route("/v1/health/")



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

@app.route("/v2/patches/")

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
@app.route("/v2/players/<name>/game_exp/")

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
@app.route("/v2/players/<name>/game_objectives/")

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
@app.route("/v2/players/<name>/abilities/")

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

@app.route("/v3/matches/<game>/top_purchases/")



def matches_purchases(game):

    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select b.id,b.hero_id,b.localized_name,b.id_item,b.name,b.count
from
(select *,row_number () over (partition by a.hero_id order by a.hero_id asc,a.count desc,a.name desc)
from(
select m.id,mpd.hero_id,h.localized_name,i.id as id_item,i.name,count(*)
from matches as m
join matches_players_details as mpd ON mpd.match_id = m.id
join heroes as h on h.id=mpd.hero_id
join purchase_logs as pl on pl.match_player_detail_id=mpd.id
join items as i on i.id=pl.item_id
where m.id=%s and((m.radiant_win='false' and mpd.player_slot>127)or(m.radiant_win='true' and mpd.player_slot<5))
group by m.id,mpd.hero_id,h.localized_name,i.name,i.id
order by mpd.hero_id asc,count desc,i.name desc
	) a ) b
where b.row_number<6
order by b.hero_id asc,b.count desc,b.name desc
'''%game
    )
    input=cursor.fetchone()
    if input is None:
        gamedata={
            "No_data":0
        }
        return(gamedata) 
    id=input[0]
    heroes=[]
    while input is not None:
        hero_id=input[1]
        lhero_id=input[1]
        hero_name=input[2]
        items=[]
        while hero_id==lhero_id:
            item={
                "id":input[3],
                "name":input[4],
                "count":input[5]
            }
            items.append(item)
            input=cursor.fetchone()
            if input is None:
                break
            lhero_id=input[1]
        hero={
            "id":hero_id,
            "name":hero_name,
            "top_purchases":items
        }
        heroes.append(hero)
    gamedata={
        "id":id,
        "heroes":heroes
    }

    return gamedata
 

@app.route("/v3/abilities/<ability>/usage/")



def ability_data(ability):

    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select t3.id,t3.name,t3.h_id,t3.localized_name,t3.team,t3.bucket,t3.count
from(
select row_number () over (partition by t2.h_id,t2.team order by t2.count desc) as row,*
from
(select t1.id,t1.name,t1.h_id,t1.localized_name,t1.bucket,t1.team,count(*)
from
(select a.id,a.name,h.id as h_id,h.localized_name,
CASE WHEN ((au.time * 100)/ m.duration) <= 9 THEN '0-9'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 10 AND 19 THEN '10-19'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 20 AND 29 THEN '20-29'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 30 AND 39 THEN '30-39'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 40 AND 49 THEN '40-49'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 50 AND 59 THEN '50-59'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 60 AND 69 THEN '60-69'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 70 AND 79 THEN '70-79'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 80 AND 89 THEN '80-89'
              WHEN ((au.time * 100)/ m.duration) BETWEEN 90 AND 99 THEN '90-99'
              WHEN ((au.time * 100)/ m.duration) >= 100 THEN '100-109'
      END AS bucket,
 (case when m.radiant_win=false and mpd.player_slot <5 then 'usage_loosers'
       when m.radiant_win=true and mpd.player_slot >127 then 'usage_loosers'
       else 'usage_winners'
 end) as team
from abilities as a
join ability_upgrades as au on au.ability_id=a.id
join matches_players_details as mpd ON mpd.id = au.match_player_detail_id
join matches as m on m.id = mpd.match_id
join heroes as h on h.id=mpd.hero_id
where a.id=%s) as t1
group by t1.id,t1.name,t1.h_id,t1.localized_name,t1.bucket,t1.team) as t2) as t3
where t3.row=1
order by t3.h_id asc,t3.team desc
'''%ability
        
    )
    input=cursor.fetchone()
    if input is None:
        gamedata={
            "No_data":0
        }
        return(gamedata) 
    
    id=input[0]
    name=input[1]
    heroes=[]
    while input is not None:
        h_id=input[2]
        lhero_id=input[2]
        h_name=input[3]
        abilities=[]
        abil1=None
        abil2=None
        while h_id==lhero_id:
            if input[4]=="usage_winners":
                  abil1={
                "bucket":input[5],
                "count":input[6]
                
            }
            if input[4]=="usage_loosers":
                   abil2={
                "bucket":input[5],
                "count":input[6]
                
            }
            input=cursor.fetchone()
            if input is None:
                break
            lhero_id=input[1]
        if abil1 is None:
            hero={
                "id":h_id,
                "name":h_name,
                "usage_loosers":abil2
            }
        elif abil2 is None:
            hero={
                "id":h_id,
                "name":h_name,
                "usage_winners":abil1
            }
        else: 
            hero={
                "id":h_id,
                "name":h_name,
                "usage_winners":abil1,
                "usage_loosers":abil2
            }
        heroes.append(hero)
    gamedata={
        "id":id,
        "name":name,
        "heroes":heroes
    }
        
        
    
   
    return gamedata


@app.route("/v3/statistics/tower_kills/")



def tower_kills():

    conn=psycopg2.connect(
        host=HOST,
        port=PRT,
        database=DAT,
        user=USER,
        password=PAS
    )

    cursor=conn.cursor()
    cursor.execute(
        '''select c.id,c.localized_name,max(seqnum) as kills
from(
select *,row_number() over (partition by b.localized_name, b.seq order by b.list) as seqnum
from
(select *,row_number() over (order by list)-row_number() over(partition by a.localized_name order by list) as seq
from(
select h.id,h.localized_name,gon.subtype,mpd.match_id,gon.time,row_number() over ( order by mpd.match_id,gon.time) as list
from heroes as h
join matches_players_details as mpd ON mpd.hero_id = h.id
join game_objectives as gon ON gon.match_player_detail_id_1 = mpd.id
join matches as m ON m.id=mpd.match_id
where   gon.subtype  like 'CHAT_MESSAGE_TOWER_KILL' and time<=m.duration
	) a)b)c
group by c.localized_name,c.id
order by kills desc
'''
    )
    input=cursor.fetchone()
    if input is None:
        gamedata={
            "No_data":0
        }
        return(gamedata) 
    heroes=[]
    while input is not None:
        hero={
            "id":input[0],
            "name":input[1],
            "tower_kills":input[2]            
        }    
        heroes.append(hero)
        input=cursor.fetchone()
    gamedata={
        "heroes":heroes
    }
    return(gamedata)
    

#/////////////////////////////////////////////////////////////////////////////////////////

if __name__ =="__main__":
    app.run(debug=True,port=8000)
