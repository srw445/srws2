select
  replace(?, '-', '/') as 発生月,
  categories.勘定科目,
  ifnull(aggregated.データ件数, 0) as データ件数,
  ifnull(aggregated.合計金額, 0) as 合計金額,
  ifnull(aggregated.備考, '') as 備考
from (
  select '売上高' as 勘定科目, 1 as sort_order
  union all select '地代家賃', 2
  union all select '旅費交通費', 3
  union all select '水道光熱費', 4
  union all select '通信費', 5
  union all select '消耗品費', 6
  union all select '交際費', 7
  union all select '事業主貸', 8
  union all select '事業主借', 9
  union all select 'その他', 10
) categories
left join (
  select
    case
      when 勘定科目 in (
        '売上高',
        '地代家賃',
        '旅費交通費',
        '水道光熱費',
        '通信費',
        '消耗品費',
        '交際費',
        '事業主貸',
        '事業主借'
      ) then 勘定科目
      else 'その他'
    end as 勘定科目,
    count(*) as データ件数,
    ifnull(sum(金額), 0) as 合計金額,
    coalesce(group_concat(備考 order by 発生日 separator '/'), '') as 備考
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
  group by
    case
      when 勘定科目 in (
        '売上高',
        '地代家賃',
        '旅費交通費',
        '水道光熱費',
        '通信費',
        '消耗品費',
        '交際費',
        '事業主貸',
        '事業主借'
      ) then 勘定科目
      else 'その他'
    end
) aggregated
  on aggregated.勘定科目 = categories.勘定科目
order by categories.sort_order
