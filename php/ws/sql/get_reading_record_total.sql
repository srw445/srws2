select count(*) as 読書合計 ,sum(ページ数) as ページ数合計 ,sum(定価) as 定価合計
from 読書記録
where 削除F = '0'
and ユーザID = ?