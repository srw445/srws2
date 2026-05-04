from src.module import select_with_connection
import pytest
import json
import os
import pymysql

@pytest.fixture
def mysql_connection():
    print("事前処理")
    # settings.jsonのパスを取得
    settings_path = os.path.join(os.path.dirname(__file__), '../src/settings.json')
    with open(settings_path, encoding='utf-8') as f:
        settings = json.load(f)

    connection = pymysql.connect(
        host=settings['host'],
        port=settings['port'],
        user=settings['user'],
        password=settings['password'],
        database=settings['database'],
        cursorclass=pymysql.cursors.DictCursor
    )
    try:
        yield connection
        print("事後処理")
    finally:
        connection.close()

def test_select_with_connection(mysql_connection):
    """
    select_with_connectionを使ってユーザ管理テーブルから連番=1の行を取得し、ID列が'hogehoge'か検証
    """
    query = (
        "SELECT * FROM ユーザ管理 WHERE 連番 = %s"
    )
    result = select_with_connection(mysql_connection, query, (1,))
    assert result, "結果が空です"
    assert result[0]['ID'] == 'hogehoge', f"ID列が一致しません: {result[0]['ID']}"

