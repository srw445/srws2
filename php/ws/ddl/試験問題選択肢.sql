-- ws.`試験問題選択肢` definition

CREATE TABLE `試験問題選択肢` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `試験ID` varchar(5) DEFAULT NULL,
  `問題ID` varchar(5) DEFAULT NULL,
  `選択肢番号` int DEFAULT NULL,
  `選択肢文` varchar(1000) DEFAULT NULL,
  `選択肢_ファイル名` varchar(255) DEFAULT NULL,
  `正解F` varchar(1) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
