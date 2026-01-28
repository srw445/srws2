select v.連番, v.年月日, v.映像区分, v.タイトル, v.監督, v.主演, v.評価,
			 k.コード名 as 評価コード名,
			 ek.コード名 as 映像区分コード名
from 映像記録 v
left join 区分コード k
	on k.区分コード = '00200' and k.コード = v.評価 and k.削除F = 0
left join 区分コード ek
	on ek.区分コード = '00800' and ek.コード = v.映像区分 and ek.削除F = 0
where v.削除F = '0' and v.ユーザID = ?