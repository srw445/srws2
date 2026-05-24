use ws;

drop temporary table if exists 20260524_資産管理;

create temporary table 20260524_資産管理 as
select *
from 資産管理;

select *
from 20260524_資産管理;
