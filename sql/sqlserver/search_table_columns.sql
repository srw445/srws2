use test

-- 変数宣言
declare @name varchar(max)
set @name = 'テスト'


-- sysテーブル
select
	 t.name
	,c.name
	--,*
from sys.tables as t
inner join sys.columns as c
on t.object_id = c.object_id
where 1 = 1
and t.name like @name
and c.name like '%'
;

/*
-- バックアップは除く
select *
from sys.tables as t
where name not like '%[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]%'

*/