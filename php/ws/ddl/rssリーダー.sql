-- ws.rssリーダー definition

CREATE TABLE `rssリーダー` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `分類` varchar(5) DEFAULT NULL,
  `名称` text,
  `フィード` text,
  `形式` varchar(10) DEFAULT NULL,
  `表示項目1` varchar(50) DEFAULT NULL,
  `表示順` int DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;