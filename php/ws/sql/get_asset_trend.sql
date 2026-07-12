select
 管理.年月日
,管理.履歴番号
,ifnull(ALLC2.金額, 0) as ALLC_金額
,ifnull(ALLC2.評価損益, 0) as ALLC_損益金額
,ifnull(ALLC2.評価損益 / ALLC2.金額, 0) as ALLC_損益割合
,ifnull(SP502.金額, 0) as SP500_金額
,ifnull(SP502.評価損益, 0) as SP500_損益金額
,ifnull(SP502.評価損益 / SP502.金額, 0) as SP500_損益割合
,ifnull(FANG.金額, 0) as FANG_金額
,ifnull(FANG.評価損益, 0) as FANG_損益金額
,ifnull(FANG.評価損益 / FANG.金額, 0) as FANG_損益割合
from 資産管理 as 管理
left join 資産管理明細 as ALLC2
on 管理.履歴番号 = ALLC2.履歴番号
and ALLC2.削除F = '0'
and ALLC2.資産コード in ('ALLC2')
left join 資産管理明細 as SP502
on 管理.履歴番号 = SP502.履歴番号
and SP502.削除F = '0'
and SP502.資産コード in ('SP502')
left join 資産管理明細 as FANG
on 管理.履歴番号 = FANG.履歴番号
and FANG.削除F = '0'
and FANG.資産コード in ('FANG1')
where 管理.削除F = '0'
and (ALLC2.金額 is not null or SP502.金額 is not null or FANG.金額 is not null)
order by 管理.履歴番号

