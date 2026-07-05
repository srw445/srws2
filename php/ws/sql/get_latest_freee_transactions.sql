select
 取込番号,
 収支区分,
 発生日,
 取引先,
 勘定科目,
 金額,
 備考
from freee取引
where 削除F = '0'
and ユーザID = ?
and 取込番号 = (
	select max(取込番号)
	from freee取引
	where 削除F = '0'
	and ユーザID = ?
)
and left(replace(発生日, '-', '/'), 7) = replace(?, '-', '/')
order by 発生日 desc