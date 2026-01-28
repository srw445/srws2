SELECT a.連番, a.資産区分コード, b.資産区分名,
	a.資産コード, a.資産名, a.資産略名,
	a.国内外区分, k1.コード名 AS 国内外区分名,
	a.通貨区分, k2.コード名 AS 通貨区分名,
	a.口座区分, k3.コード名 AS 口座区分名,
	a.長短区分, k4.コード名 AS 長短区分名,
	a.売買区分, k5.コード名 AS 売買区分名
FROM 資産マスタ a
LEFT JOIN 資産区分マスタ b ON a.資産区分コード = b.資産区分コード AND b.削除F = '0'
LEFT JOIN 区分コード k1 ON a.国内外区分 = k1.コード AND k1.区分コード = '00300' AND k1.削除F = '0'
LEFT JOIN 区分コード k2 ON a.通貨区分 = k2.コード AND k2.区分コード = '00400' AND k2.削除F = '0'
LEFT JOIN 区分コード k3 ON a.口座区分 = k3.コード AND k3.区分コード = '00500' AND k3.削除F = '0'
LEFT JOIN 区分コード k4 ON a.長短区分 = k4.コード AND k4.区分コード = '00600' AND k4.削除F = '0'
LEFT JOIN 区分コード k5 ON a.売買区分 = k5.コード AND k5.区分コード = '00700' AND k5.削除F = '0'
WHERE a.削除F = '0';