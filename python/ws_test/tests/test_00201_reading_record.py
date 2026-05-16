"""
test_00201_reading_record.py
「読書記録画面」テストコード

【1-1】読書記録が登録できること
"""

import json
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import Select
import time
import ws_util

# エビデンス番号
evidence_no = 1
# テストコード名
script_name = os.path.splitext(os.path.basename(__file__))[0]  # 例: test_00201_reading_record
# コメント
comment = ''

def main():
    """メイン処理"""
    before()
    test_code()
    after()

def test_code():
    """テスト内容"""
    print('test_code() 開始')
    global evidence_no

    # 設定ファイル取得
    settings_path = os.path.join(os.path.dirname(__file__), '../settings.json')
    with open(settings_path, encoding='utf-8') as f:
        settings = json.load(f)
    CHROMEDRIVER_PATH = settings['chromedriver_path']
    TEST_URL = settings.get('test_url')

    # WebDriver起動
    options = ws_util.get_chrome_options()
    service = Service(CHROMEDRIVER_PATH)
    driver = webdriver.Chrome(service=service, options=options)
    driver.get(TEST_URL)
    time.sleep(1)

    try:
        # user_id入力
        user_input = driver.find_element(By.NAME, 'user_id')
        user_input.clear()
        user_input.send_keys('test')
        time.sleep(1)
        comment = 'ユーザーID入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # password入力
        pass_input = driver.find_element(By.NAME, 'password')
        pass_input.clear()
        pass_input.send_keys('test')
        time.sleep(1)
        comment = 'パスワード入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # ログインボタンクリック
        login_btn = driver.find_element(By.ID, 'login-submit')
        login_btn.click()
        time.sleep(1)
        comment = 'ログインボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 読書記録ボタンクリック
        reading_record_btn = driver.find_element(By.XPATH, "//button[text()='読書記録']")
        reading_record_btn.click()
        time.sleep(1)
        comment = '読書記録ボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 追加ボタンクリック
        add_btn = driver.find_element(By.XPATH, "//button[text()='追加']")
        add_btn.click()
        time.sleep(1)
        comment = '追加ボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # タイトル入力
        title_input = driver.find_element(By.NAME, 'タイトル')
        title_input.clear()
        title_input.send_keys('テストタイトル名')
        time.sleep(1)
        comment = 'タイトル入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 作者入力
        author_input = driver.find_element(By.NAME, '作者')
        author_input.clear()
        author_input.send_keys('テスト作者名')
        time.sleep(1)
        comment = '作者入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 出版社入力
        publisher_input = driver.find_element(By.NAME, '出版社')
        publisher_input.clear()
        publisher_input.send_keys('テスト出版社名')
        time.sleep(1)
        comment = '出版社入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 形式入力
        select_elem = driver.find_element(By.NAME, "形式")
        select = Select(select_elem)
        select.select_by_visible_text("文庫")
        time.sleep(1)
        comment = '形式入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # ジャンル入力
        genre_input = driver.find_element(By.NAME, 'ジャンル')
        genre_input.clear()
        genre_input.send_keys('テストジャンル名')
        time.sleep(1)
        comment = 'ジャンル入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # ページ数入力
        pages_input = driver.find_element(By.NAME, 'ページ数')
        pages_input.clear()
        pages_input.send_keys('200')
        time.sleep(1)
        comment = 'ページ数入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 定価入力
        price_input = driver.find_element(By.NAME, '定価')
        price_input.clear()
        price_input.send_keys('1000')
        time.sleep(1)
        comment = '定価入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 受賞入力
        award_input = driver.find_element(By.NAME, '受賞')
        award_input.clear()
        award_input.send_keys('テスト受賞名')
        time.sleep(1)
        comment = '受賞入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 初版日入力
        first_pub_date_input = driver.find_element(By.NAME, '初版日')
        first_pub_date_input.clear()
        driver.execute_script("arguments[0].value = arguments[1]", first_pub_date_input, "2026-05-17")
        time.sleep(1)
        comment = '初版日入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 読了日入力
        read_date_input = driver.find_element(By.NAME, '読了日')
        read_date_input.clear()
        driver.execute_script("arguments[0].value = arguments[1]", read_date_input, "2026-05-17")
        time.sleep(1)
        comment = '読了日入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 備考入力
        remarks_input = driver.find_element(By.NAME, '備考')
        remarks_input.clear()
        remarks_input.send_keys('テスト備考')
        time.sleep(1)
        comment = '備考入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 評価入力
        select_elem = driver.find_element(By.NAME, "評価")
        select = Select(select_elem)
        select.select_by_visible_text("★☆☆☆☆")
        time.sleep(1)
        comment = '評価入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 追加ボタンクリック
        add_btn = driver.find_element(By.XPATH, "//button[text()='追加']")
        add_btn.click()
        time.sleep(1)
        comment = '追加ボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 登録データ確認
        sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00201_reading_record_01.sql')
        evidence_no = ws_util.export_sql_to_csv(sql_path, script_name, evidence_no)
        # SQLの件数チェック
        count = ws_util.get_select_count_from_sqlfile(sql_path)
        if count >= 1:
            comment = '【1-1】【OK】読書記録が登録できること'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='OK', comment=comment)
        else:
            comment = '【1-1】【NG】読書記録が登録できないこと'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='NG', comment=comment)

    finally:
        driver.quit()

    print('test_code() 完了')

def before():
    """事前処理"""
    global evidence_no
    print('before() 開始')
    # ユーザ管理追加
    sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00101_login_01.sql')
    ws_util.execute_sql_file(sql_path)
    # 追加データ確認(CSV出力)
    sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00101_login_03.sql')
    evidence_no = ws_util.export_sql_to_csv(sql_path, script_name, evidence_no)
    print('before() 完了')

def after():
    """事後処理"""
    global evidence_no
    print('after() 開始')
    # ユーザ管理削除
    sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00101_login_02.sql')
    ws_util.execute_sql_file(sql_path)
    # 読書記録削除
    # sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00201_reading_record_02.sql')
    # ws_util.execute_sql_file(sql_path)
    print('after() 完了')

if __name__ == "__main__":
    main()
