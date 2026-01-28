-- ws.`映像記録` definition

CREATE TABLE `映像記録` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `年月日` date DEFAULT NULL,
  `タイトル` varchar(200) DEFAULT NULL,
  `監督` varchar(200) DEFAULT NULL,
  `主演` varchar(200) DEFAULT NULL,
  `制作会社` varchar(200) DEFAULT NULL,
  `映像区分` varchar(2) DEFAULT NULL,
  `ジャンル` varchar(2) DEFAULT NULL,
  `公開日` date DEFAULT NULL,
  `映画館` varchar(200) DEFAULT NULL,
  `映像時間` int DEFAULT NULL,
  `話数` int DEFAULT NULL,
  `評価` varchar(1) DEFAULT NULL,
  `表紙ファイル名` varchar(200) DEFAULT NULL,
  `備考` varchar(200) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ユーザID` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;