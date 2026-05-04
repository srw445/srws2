import pymysql
import json
import os

def select_from_mysql(query, params=None):
	"""
	MySQLデータベースからデータをSELECTする関数。
	:param query: 実行するSQLクエリ（例: 'SELECT * FROM table WHERE id=%s'）
	:param params: クエリのパラメータ（タプルまたはリスト）
	:return: 検索結果のリスト（各行は辞書型）
	"""
	# settings.jsonのパスを取得
	settings_path = os.path.join(os.path.dirname(__file__), 'settings.json')
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
		with connection.cursor() as cursor:
			cursor.execute(query, params)
			result = cursor.fetchall()
		return result
	finally:
		connection.close()

def select_with_connection(connection, query, params=None):
	"""
	外部から渡されたMySQLコネクションでSELECTを実行する関数。
	:param connection: pymysqlのコネクションオブジェクト
	:param query: 実行するSQLクエリ
	:param params: クエリのパラメータ（タプルまたはリスト）
	:return: 検索結果のリスト（各行は辞書型）
	"""
	with connection.cursor() as cursor:
		cursor.execute(query, params)
		result = cursor.fetchall()
	return result

