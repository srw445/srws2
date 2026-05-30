-- ws.`メッセージ` definition

CREATE TABLE `メッセージ` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `メッセージID` varchar(5) DEFAULT NULL,
  `区分` varchar(5) DEFAULT NULL,
  `メッセージ` varchar(4000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `設定値1` varchar(200) DEFAULT NULL,
  `設定値2` varchar(200) DEFAULT NULL,
  `設定値3` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;