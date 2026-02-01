use test

declare @cur_テーブル名 varchar(max)
declare @wk_str varchar(max)
set @wk_str = ''

declare cur_wk cursor for
select name
from sys.tables;

open cur_wk;
fetch next from cur_wk
into @cur_テーブル名

while @@FETCH_STATUS = 0
begin
	-- ここにループの内容を書く -s

	print @cur_テーブル名
	select *
	from sys.tables
	where name = @cur_テーブル名

	-- ここにループの内容を書く -e

	fetch next from cur_wk
	into @cur_テーブル名
end

close cur_wk;
deallocate cur_wk;
