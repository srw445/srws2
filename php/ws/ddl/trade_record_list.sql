-- ws.trade_record_list definition

CREATE TABLE `trade_record_list` (
  `約定日時` varchar(50) DEFAULT NULL,
  `取引番号` varchar(50) DEFAULT NULL,
  `取引区分` varchar(50) DEFAULT NULL,
  `銘柄` varchar(50) DEFAULT NULL,
  `注文先（自己または委託の別）` varchar(50) DEFAULT NULL,
  `売買` varchar(50) DEFAULT NULL,
  `数量` varchar(50) DEFAULT NULL,
  `約定レート` varchar(50) DEFAULT NULL,
  `建玉損益` varchar(50) DEFAULT NULL,
  `建玉損益（円）` varchar(50) DEFAULT NULL,
  `取引手数料` varchar(50) DEFAULT NULL,
  `ファンディングレート（円）` varchar(50) DEFAULT NULL,
  `決済損益（円）` varchar(50) DEFAULT NULL,
  `円転レート` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;