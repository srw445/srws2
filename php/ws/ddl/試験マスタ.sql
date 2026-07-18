-- ws.`試験マスタ` definition

CREATE TABLE `試験マスタ` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `試験ID` varchar(5) DEFAULT NULL,
  `試験名` varchar(255) DEFAULT NULL,
  `試験説明` varchar(1000) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
