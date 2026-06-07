declare @cur_db nvarchar(max)
declare @query nvarchar(max)
declare @search_table nvarchar(max)
declare @search_column nvarchar(max)
set @cur_db = ''
set @query = ''

/* ****************************** 
 検索条件
 ****************************** */
set @search_table = '%'
set @search_column = 'テスト'


declare cur_db cursor for
select name
from sys.databases
where name not in ('master','tempdb','model','msdb')

open cur_db
fetch next from cur_db
into @cur_db

while @@FETCH_STATUS = 0
begin
	set @query = @query + 'use ' + @cur_db + ';';
	set @query = @query + 'select DB_NAME() as [DB], t.name as [TABLE], c.name as [COLUMN] ';
	set @query = @query + 'from sys.tables as t ';
	set @query = @query + 'inner join sys.columns as c ';
	set @query = @query + 'on t.object_id = c.object_id ';
	set @query = @query + 'where t.name like ''' + @search_table + ''' ';
	set @query = @query + 'and c.name like ''' + @search_table + ''' ';
	fetch next from cur_db
	into @cur_db
end
close cur_db
deallocate cur_db

print(@query)
exec(@query)
