INSERT INTO `ユーザ管理` (ID, `パスワード`, `削除F`, `登録日時`, `更新日時`)
SELECT 'test', 'test', '0', NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `ユーザ管理` WHERE ID = 'test'
);