select r.連番, IFNULL(r.読了日, '') as 読了日, r.タイトル, r.作者, r.出版社, IFNULL(r.初版日, '') as 初版日, r.評価,
			 k.コード名 as 評価コード名
from 読書記録 r
left join 区分コード k
	on k.区分コード = '00200' and k.コード = r.評価 and k.削除F = 0
where r.削除F = '0' and r.ユーザID = ?