select
  left(replace(発生日, '-', '/'), 7) as 発生月,
  sum(case when 勘定科目 = '売上高' then 金額 else 0 end) as 売上高,
  sum(case when 勘定科目 = '地代家賃' then 金額 else 0 end) as 地代家賃,
  sum(case when 勘定科目 = '旅費交通費' then 金額 else 0 end) as 旅費交通費,
  sum(case when 勘定科目 = '水道光熱費' then 金額 else 0 end) as 水道光熱費,
  sum(case when 勘定科目 = '通信費' then 金額 else 0 end) as 通信費,
  sum(case when 勘定科目 = '消耗品費' then 金額 else 0 end) as 消耗品費,
  sum(case when 勘定科目 = '交際費' then 金額 else 0 end) as 交際費,
  sum(case when 勘定科目 not in ('売上高', '地代家賃', '旅費交通費', '水道光熱費', '通信費', '消耗品費', '交際費', '事業主貸', '事業主借') then 金額 else 0 end) as その他,
  sum(case when 勘定科目 = '事業主貸' then 金額 else 0 end) as 事業主貸,
  sum(case when 勘定科目 = '事業主借' then 金額 else 0 end) as 事業主借
from freee取引
where 削除F = '0'
  and ユーザID = ?
  and 取込番号 = (
    select max(取込番号)
    from freee取引
    where 削除F = '0'
      and ユーザID = ?
  )
group by left(replace(発生日, '-', '/'), 7)
order by 発生月 desc
