-- for xml pathで複数行のレコードをひとつにまとめる
use test

declare @str varchar(max)

set @str = (
	select [ID] + CHAR(10)
	from ユーザ管理
	for xml path('')
)

select @str

print(@str)
print(char(13) + char(10))
print(char(13) + char(10))

set @str = (
		select [ID] + '!#$%&'
		from ユーザ管理
		for xml path('') ,type
	).value('.', 'NVARCHAR(MAX)')
	
print @str
select @str
