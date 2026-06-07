select count(*) as 鑑賞合計
from 映像記録
where 削除F = '0'
and ユーザID = ?