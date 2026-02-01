use test

if object_id('tempdb..#wk_tbl','U') is not null
drop table #wk_tbl
;

select *
into #wk_tbl
from テスト
;

select *
from #wk_tbl
;