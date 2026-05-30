select a.メッセージ
from メッセージ as a
inner join ユーザ管理 as b
on 1 = 1
where a.削除F = '0'
and a.区分 = '2'
and b.ID = ?
and b.設定値1 = '1'
ORDER BY RAND()
LIMIT 1;
