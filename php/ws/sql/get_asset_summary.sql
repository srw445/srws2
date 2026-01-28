-- 対象を全抽出
drop temporary table if exists 資産明細;
create temporary table 資産明細 as
select
管理.履歴番号
,管理.年月日
,管理.ユーザID
,明細.資産区分コード
,資産区分.資産区分略名
,資産区分.設定値1
,資産区分.設定値2
,資産区分.設定値3
,明細.資産コード
,資産.資産略名
,資産.国内外区分
,資産.通貨区分
,資産.長短区分
,明細.金額
,明細.評価損益
from 資産管理 as 管理
inner join 資産管理明細 as 明細
on 管理.履歴番号 = 明細.履歴番号
and 明細.削除F = '0'
inner join 資産区分マスタ as 資産区分
on 明細.資産区分コード = 資産区分.資産区分コード
and 資産区分.削除F = '0'
inner join 資産マスタ as 資産
on 明細.資産区分コード = 資産.資産区分コード
and 明細.資産コード = 資産.資産コード
and 資産区分.削除F = '0'	
where 管理.削除F = '0'
order by 管理.履歴番号 ,明細.資産区分コード ,明細.資産コード;

-- 履歴番号毎に集計
drop temporary table if exists 資産集計;
create temporary table 資産集計 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 履歴番号毎に集計（前回比取得用）
drop temporary table if exists 資産集計_前回;
create temporary table 資産集計_前回 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 資産区分=現金で集計
drop temporary table if exists 資産集計_現金;
create temporary table 資産集計_現金 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'GENKIN'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 資産区分=株式で集計
drop temporary table if exists 資産集計_株式;
create temporary table 資産集計_株式 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'KABU'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 資産区分=仮想通貨で集計
drop temporary table if exists 資産集計_仮想通貨;
create temporary table 資産集計_仮想通貨 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'KASOU'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 資産区分=債券で集計
drop temporary table if exists 資産集計_債券;
create temporary table 資産集計_債券 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'SAIKEN'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 資産区分=年金で集計
drop temporary table if exists 資産集計_年金;
create temporary table 資産集計_年金 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'NENKIN'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 資産区分=コモディティで集計
drop temporary table if exists 資産集計_コモディティ;
create temporary table 資産集計_コモディティ as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'COMMODITY'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;


-- 資産区分=その他で集計
drop temporary table if exists 資産集計_その他;
create temporary table 資産集計_その他 as
select
履歴番号
,年月日
,sum(金額) as 金額_合計
,sum(評価損益) as 評価損益_合計
from 資産明細
where 設定値1 = 'SONOTA'
group by 履歴番号 ,年月日
order by 履歴番号 desc,年月日 desc;

-- 表示前WK
drop temporary table if exists 資産集計_結果;
create temporary table 資産集計_結果 as
select
集計.履歴番号
,集計.年月日
,集計.金額_合計
,集計.評価損益_合計
,集計.金額_合計 - ifnull(集計_前回.金額_合計 ,0) as 前回比
,ifnull(集計_現金.金額_合計 ,0) as 現金
,ifnull(集計_株式.金額_合計 ,0) as 株式
,ifnull(集計_仮想通貨.金額_合計 ,0) as 仮想通貨
,ifnull(集計_債券.金額_合計 ,0) as 債券
,ifnull(集計_年金.金額_合計 ,0) as 年金
,ifnull(集計_コモディティ.金額_合計 ,0) as コモディティ
,ifnull(集計_その他.金額_合計 ,0) as その他
from 資産集計 as 集計
left join 資産集計_前回 as 集計_前回
on 集計.履歴番号 = 集計_前回.履歴番号 + 1
left join 資産集計_現金 as 集計_現金
on 集計.履歴番号 = 集計_現金.履歴番号
left join 資産集計_株式 as 集計_株式
on 集計.履歴番号 = 集計_株式.履歴番号
left join 資産集計_仮想通貨 as 集計_仮想通貨
on 集計.履歴番号 = 集計_仮想通貨.履歴番号
left join 資産集計_債券 as 集計_債券
on 集計.履歴番号 = 集計_債券.履歴番号
left join 資産集計_年金 as 集計_年金
on 集計.履歴番号 = 集計_年金.履歴番号
left join 資産集計_コモディティ as 集計_コモディティ
on 集計.履歴番号 = 集計_コモディティ.履歴番号
left join 資産集計_その他 as 集計_その他
on 集計.履歴番号 = 集計_その他.履歴番号
;

select
履歴番号
,年月日
,金額_合計
,評価損益_合計
,前回比
,concat(format(前回比 / 金額_合計 * 100, 1), '%') as 前回比割合
,現金
,concat(format(現金 / 金額_合計 * 100, 1), '%') as 現金割合
,株式
,concat(format(株式 / 金額_合計 * 100, 1), '%') as 株式割合  
,仮想通貨
,concat(format(仮想通貨 / 金額_合計 * 100, 1), '%') as 仮想通貨割合  
,債券
,concat(format(債券 / 金額_合計 * 100, 1), '%') as 債券割合
,年金
,concat(format(年金 / 金額_合計 * 100, 1), '%') as 年金割合
,コモディティ
,concat(format(コモディティ / 金額_合計 * 100, 1), '%') as コモディティ割合
,その他
,concat(format(その他 / 金額_合計 * 100, 1), '%') as その他割合
from 資産集計_結果
;
