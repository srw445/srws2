-- ws.`試験問題` definition

CREATE TABLE `試験問題` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `試験ID` varchar(5) DEFAULT NULL,
  `問題ID` varchar(5) DEFAULT NULL,
  `問題種別` varchar(5) DEFAULT NULL,
  `問題文` TEXT DEFAULT NULL,
  `問題文_ファイル名` varchar(255) DEFAULT NULL,
  `解答` varchar(500) DEFAULT NULL,
  `解答文_ファイル名` varchar(255) DEFAULT NULL,
  `解説` TEXT DEFAULT NULL,
  `解説文_ファイル名` varchar(255) DEFAULT NULL,
  `表示順` int DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
