-- ws.freee取引 definition

CREATE TABLE `freee取引` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `ユーザID` varchar(200) DEFAULT NULL,
  `取込番号` int NOT NULL,
  `収支区分` varchar(500) DEFAULT NULL,
  `管理番号` varchar(500) DEFAULT NULL,
  `発生日` varchar(500) DEFAULT NULL,
  `支払期日` varchar(500) DEFAULT NULL,
  `取引先` varchar(500) DEFAULT NULL,
  `勘定科目` varchar(500) DEFAULT NULL,
  `税区分` varchar(500) DEFAULT NULL,
  `金額` varchar(500) DEFAULT NULL,
  `税計算区分` varchar(500) DEFAULT NULL,
  `税額` varchar(500) DEFAULT NULL,
  `備考` varchar(500) DEFAULT NULL,
  `品目` varchar(500) DEFAULT NULL,
  `部門` varchar(500) DEFAULT NULL,
  `メモタグ` varchar(500) DEFAULT NULL,
  `支払日` varchar(500) DEFAULT NULL,
  `支払口座` varchar(500) DEFAULT NULL,
  `支払金額` varchar(500) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;