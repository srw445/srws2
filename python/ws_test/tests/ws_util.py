import csv
import pymysql
import json
import os
from PIL import Image, ImageDraw, ImageFont
from selenium.webdriver.chrome.options import Options

def get_db_settings():
    """データベース接続設定を取得"""
    settings_path = os.path.join(os.path.dirname(__file__), '../settings.json')
    with open(settings_path, encoding='utf-8') as f:
        settings = json.load(f)
    return {
        'host': settings.get('db_host'),
        'port': int(settings.get('db_port')),
        'user': settings.get('db_user'),
        'password': settings.get('db_pass'),
        'database': settings.get('db_name'),
    }

def get_chrome_options():
    """テスト用Chrome Optionsを返す"""
    options = Options()
    # options.add_argument('--headless')  # 画面非表示で実行したい場合は有効化
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    # パスワード自動入力プロンプト抑制
    options.add_argument('--disable-blink-features=AutomationControlled')
    options.add_argument('--disable-infobars')
    options.add_argument('--disable-save-password-bubble')
    # パスワード保存アラートを無効化
    prefs = {
        "credentials_enable_service": False,
        "profile.password_manager_enabled": False,
        "profile.password_manager_leak_detection": False
    }
    options.add_experimental_option("prefs", prefs)
    return options

def execute_sql_file(sql_file_path):
    """SQLファイルを実行"""
    db_conf = get_db_settings()
    conn = pymysql.connect(
        host=db_conf['host'],
        port=db_conf['port'],
        user=db_conf['user'],
        password=db_conf['password'],
        database=db_conf['database'],
        charset='utf8mb4',
        autocommit=True
    )
    print(f'SQL実行: {sql_file_path}')
    try:
        with conn.cursor() as cursor:
            with open(sql_file_path, encoding='utf-8') as f:
                sql = f.read()
                for statement in sql.split(';'):
                    statement = statement.strip()
                    if statement:
                        cursor.execute(statement)
    finally:
        conn.close()

def export_sql_to_csv(sql_file_path, script_name, evidence_no):
    """
    SQLファイルを実行し、結果をCSVで保存し、evidence_noを+1して返す
    """
    results_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), '../results', script_name))
    os.makedirs(results_dir, exist_ok=True)
    csv_filename = f'{script_name}_{evidence_no}.csv'
    csv_path = os.path.join(results_dir, csv_filename)
    db_conf = get_db_settings()
    conn = pymysql.connect(
        host=db_conf['host'],
        port=db_conf['port'],
        user=db_conf['user'],
        password=db_conf['password'],
        database=db_conf['database'],
        charset='utf8mb4',
        autocommit=True
    )
    try:
        with conn.cursor() as cursor:
            with open(sql_file_path, encoding='utf-8') as f:
                sql = f.read().strip().rstrip(';')
            cursor.execute(sql)
            rows = cursor.fetchall()
            field_names = [desc[0] for desc in cursor.description]
            with open(csv_path, 'w', encoding='utf-8', newline='') as csvfile:
                writer = csv.writer(csvfile)
                writer.writerow(field_names)
                for row in rows:
                    writer.writerow(row)
    finally:
        conn.close()
    print(f'CSV出力: {csv_path}')
    return evidence_no + 1

def get_select_count_from_sqlfile(sql_file_path):
    """SQLファイルのSELECT件数を返す"""
    db_conf = get_db_settings()
    conn = pymysql.connect(
        host=db_conf['host'],
        port=db_conf['port'],
        user=db_conf['user'],
        password=db_conf['password'],
        database=db_conf['database'],
        charset='utf8mb4',
        autocommit=True
    )
    with open(sql_file_path, encoding='utf-8') as f:
        sql = f.read().strip().rstrip(';')
    with conn.cursor() as cursor:
        cursor.execute(sql)
        rows = cursor.fetchall()
    conn.close()
    return len(rows)

def save_screenshot(driver, script_name, evidence_no, label=None, comment=None):
    """
    driver: seleniumのWebDriver
    script_name: 実行pyファイル名（拡張子なし）
    evidence_no: 連番（int）
    label: 任意のラベル（str, 省略可）
    comment: 任意のコメント（str, 省略可）
    return: 次の連番（int）
    """
    results_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), '../results', script_name))
    os.makedirs(results_dir, exist_ok=True)
    if label:
        filename = f'{script_name}_{evidence_no}_{label}.png'
    else:
        filename = f'{script_name}_{evidence_no}.png'
    path = os.path.join(results_dir, filename)
    driver.save_screenshot(path)
    # コメントを画像に埋め込む
    if comment:
        try:
            image = Image.open(path)
            draw = ImageDraw.Draw(image)
            # フォント設定（日本語対応フォントを優先）
            font = None
            font_candidates = [
                "C:/Windows/Fonts/meiryo.ttc"  # Windows: メイリオ
            ]
            for font_path in font_candidates:
                try:
                    font = ImageFont.truetype(font_path, 20)
                    break
                except Exception:
                    continue
            if font is None:
                font = ImageFont.load_default()
            # 文字色・背景色
            text_color = (255, 0, 0)
            bg_color = (255, 255, 255, 180)
            # テキストサイズ
            bbox = draw.textbbox((0, 0), comment, font=font)
            text_width = bbox[2] - bbox[0]
            text_height = bbox[3] - bbox[1]
            # 画像左上に余白付きで描画
            margin = 10
            # 背景を描画（半透明）
            if image.mode != 'RGBA':
                image = image.convert('RGBA')
            overlay = Image.new('RGBA', image.size, (255,255,255,0))
            overlay_draw = ImageDraw.Draw(overlay)
            overlay_draw.rectangle(
                [margin-5, margin-5, margin+text_width+5, margin+text_height+5],
                fill=bg_color
            )
            overlay_draw.text((margin, margin), comment, font=font, fill=text_color)
            out = Image.alpha_composite(image, overlay)
            out = out.convert('RGB')
            out.save(path)
        except Exception as e:
            print(f'コメント埋め込み失敗: {e}')
    print(f'スクリーンショット保存: {path}')
    if comment:
        print(f'コメント: {comment}')
    return evidence_no + 1
