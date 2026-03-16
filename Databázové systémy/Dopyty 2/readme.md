1.
select o.name,o.patch_start_date,o.patch_end_date,m.id,(extract(epoch from to_timestamp(m.duration)::timestamp)::float/60) as duration
from(select patches.name,cast(extract(epoch from patches.release_date)as integer)as patch_start_date,
	lead(cast(extract(epoch from patches.release_date)as integer),1)over(order by patches.name) patch_end_date
	from patches)
	as o left join matches as m on m.start_time between o.patch_start_date and o.patch_end_date
order by o.name
2.
select md.player_id,coalesce(p.nick,'unknown'),md.match_id,h.localized_name as hero_localized_name ,
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
            where player_id =14944
            order by match_id
3.
select  md.player_id,coalesce(p.nick,'unknown') as nick,md.match_id,h.localized_name,coalesce(gao.subtype,'NO_ACTION') as subtype,count(*)
            from matches_players_details as md
            join players as p on p.id=md.player_id
            join matches as m on m.id=md.match_id
            join heroes as h on h.id=md.hero_id
            full join game_objectives as gao on gao.match_player_detail_id_1=md.id
            where player_id =14944
            group by md.player_id,nick,h.localized_name,md.match_id,subtype
            order by match_id
4.
select  md.player_id,coalesce(p.nick,'unknown') as nick,md.match_id,h.localized_name,a.name,count(*),max(au.level)
        from matches_players_details as md
        join players as p on p.id=md.player_id
        join matches as m on m.id=md.match_id
        join heroes as h on h.id=md.hero_id
        full join ability_upgrades as au on au.match_player_detail_id=md.id
        join abilities as a on a.id=au.ability_id
        where player_id =14944
        group by md.player_id,nick,h.localized_name,md.match_id,a.name
        order by match_id