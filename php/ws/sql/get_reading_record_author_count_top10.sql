select 作者, count(*) as 読書数
from 読書記録
where 削除F = '0'
and ユーザID = ?
group by 作者
order by 読書数 desc
limit 5;