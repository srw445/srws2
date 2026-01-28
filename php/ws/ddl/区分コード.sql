-- ws.`区分コード` definition

CREATE TABLE `区分コード` (
  `連番` int NOT NULL AUTO_INCREMENT,
  `区分コード` varchar(5) DEFAULT NULL,
  `区分名` varchar(200) DEFAULT NULL,
  `コード` varchar(5) DEFAULT NULL,
  `コード名` varchar(200) DEFAULT NULL,
  `区分説明` varchar(200) DEFAULT NULL,
  `削除F` varchar(1) DEFAULT NULL,
  `登録日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `更新日時` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `コード名2` varchar(200) DEFAULT NULL,
  `設定値1` varchar(200) DEFAULT NULL,
  `設定値2` varchar(200) DEFAULT NULL,
  `設定値3` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`連番`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;