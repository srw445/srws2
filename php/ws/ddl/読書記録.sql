-- ws.`読書記録` definition

CREATE TABLE `読書記録` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `タイトル` varchar(200) DEFAULT NULL,
  `作者` varchar(200) DEFAULT NULL,
  `出版社` varchar(200) DEFAULT NULL,
  `形式` varchar(200) DEFAULT NULL,
  `ジャンル` varchar(200) DEFAULT NULL,
  `ページ数` int DEFAULT NULL,
  `定価` int DEFAULT NULL,
  `受賞` varchar(200) DEFAULT NULL,
  `初版日` date DEFAULT NULL,
  `読了日` date DEFAULT NULL,
  `表紙ファイル名` varchar(200) DEFAULT NULL,
  `備考` varchar(200) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `評価` varchar(1) DEFAULT NULL,
  `ユーザID` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=280 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;