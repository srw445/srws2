-- ws.`資産マスタ` definition

CREATE TABLE `資産マスタ` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `資産区分コード` varchar(5) DEFAULT NULL,
  `資産コード` varchar(5) DEFAULT NULL,
  `資産名` varchar(200) DEFAULT NULL,
  `資産略名` varchar(50) DEFAULT NULL,
  `国内外区分` varchar(5) DEFAULT NULL,
  `通貨区分` varchar(5) DEFAULT NULL,
  `口座区分` varchar(5) DEFAULT NULL,
  `長短区分` varchar(5) DEFAULT NULL,
  `売買区分` varchar(5) DEFAULT NULL,
  `備考` varchar(200) DEFAULT NULL,
  `設定値1` varchar(200) DEFAULT NULL,
  `設定値2` varchar(200) DEFAULT NULL,
  `設定値3` varchar(200) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;