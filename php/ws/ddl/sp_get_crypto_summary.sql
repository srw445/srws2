CREATE DEFINER=`root`@`localhost` PROCEDURE `ws`.`sp_get_crypto_summary`(
    IN p_ticker VARCHAR(20),       -- @ティッカー
    IN p_current_price VARCHAR(50),-- @現在価格
    IN p_target_date VARCHAR(20)   -- @基準日
)
BEGIN
    SELECT 
        wk.*,
        (合計数量 * 現在価格) AS 評価額,
        (合計数量 * 現在価格) - 総入出金額_円 AS 評価損益金額,
        CASE 
            WHEN 総入出金額_円 = 0 THEN 0
            ELSE ((合計数量 * 現在価格) - 総入出金額_円) / 総入出金額_円 * 100 
        END AS 評価損益割合
    FROM (
        SELECT 
            SUM(CAST(REPLACE(数量, ',', '') AS DECIMAL(20, 8))) AS 合計数量,
            SUM(CAST(REPLACE(`入出金額（円）`, ',', '') AS SIGNED)) AS 総入出金額_円,
            AVG(CAST(REPLACE(`評価レート`, ',', '') AS DECIMAL(20, 8))) AS 平均評価レート,
            CAST(REPLACE(p_current_price, ',', '') AS DECIMAL(20, 8)) AS 現在価格
        FROM ws.cashflow 
        WHERE `暗号資産/通貨` = p_ticker
          AND 日時 < REPLACE(p_target_date, '-', '/')
    ) AS wk;
END