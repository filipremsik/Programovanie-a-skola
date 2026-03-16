1.
select b.id,b.hero_id,b.localized_name,b.id_item,b.name,b.count
from
(select *,row_number () over (partition by a.hero_id order by a.hero_id asc,a.count desc,a.name desc)
from(
select m.id,mpd.hero_id,h.localized_name,i.id as id_item,i.name,count(*)
from matches as m
join matches_players_details as mpd ON mpd.match_id = m.id
join heroes as h on h.id=mpd.hero_id
join purchase_logs as pl on pl.match_player_detail_id=mpd.id
join items as i on i.id=pl.item_id
where m.id=21421 and((m.radiant_win='false' and mpd.player_slot>127)or(m.radiant_win='true' and mpd.player_slot<5))
group by m.id,mpd.hero_id,h.localized_name,i.name,i.id
order by mpd.hero_id asc,count desc,i.name desc
	) a ) b
where b.row_number<6
order by b.hero_id asc,b.count desc,b.name desc

2.
select t3.id,t3.name,t3.h_id,t3.localized_name,t3.team,t3.bucket,t3.count
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
where a.id=5004) as t1
group by t1.id,t1.name,t1.h_id,t1.localized_name,t1.bucket,t1.team) as t2) as t3
where t3.row=1
order by t3.h_id asc,t3.team desc

3.
select c.id,c.localized_name,max(seqnum) as kills
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

