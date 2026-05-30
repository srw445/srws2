SELECT *
FROM テスト

-- 連番
SELECT ROW_NUMBER() OVER (ORDER BY ID ,名前) AS 連番
FROM テスト

-- 名前ごとに連番
SELECT ROW_NUMBER() OVER (PARTITION BY 名前 ORDER BY ID) AS 連番
FROM テスト

-- 真の場合
SELECT IIF(1 = 1 ,1 ,0)

-- 偽の場合
SELECT IIF(1 <> 1 ,1 ,0)
