from src.module import select_from_mysql
import pytest
import os

def test_select_user_id():
    # ユーザ管理テーブルから連番=1の行を取得
    query = """
    SELECT * FROM ユーザ管理 WHERE 連番 = %s
    """
    result = select_from_mysql(query, (1,))
    # 結果が1件以上あることを確認
    assert result, "結果が空です"
    # 1件目のID列が'hogehoge'であることを検証
    assert result[0]['ID'] == 'hogehoge', f"ID列が一致しません: {result[0]['ID']}"

def test_mysql_connection():
    """
    settings.jsonの内容でMySQLに接続できるか検証する
    """
    try:
        # シンプルなクエリで接続確認（テーブル名はinformation_schema.tablesを利用）
        result = select_from_mysql("SELECT 1 AS test")
        assert result[0]['test'] == 1
    except Exception as e:
        pytest.fail(f"MySQL接続に失敗: {e}")

def test_settings_json_exists():
    """
    settings.jsonファイルが存在するかをテストする
    """
    settings_path = os.path.join(os.path.dirname(__file__), '../src/settings.json')
    assert os.path.isfile(settings_path), f"settings.jsonが見つかりません: {settings_path}"
