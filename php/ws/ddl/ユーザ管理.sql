-- ws.`ユーザ管理` definition

CREATE TABLE `ユーザ管理` (
  `連番` int NOT NULL,
  `ID` varchar(200) NOT NULL,
  `パスワード` varchar(200) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`,`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;