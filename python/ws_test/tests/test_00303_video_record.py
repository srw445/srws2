"""
test_00303_video_record.py
「映像記録画面」テストコード

【1-3】映像記録が削除できること
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
script_name = os.path.splitext(os.path.basename(__file__))[0]  # 例: test_00303_video_record
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

        # 映像記録ボタンクリック
        video_record_btn = driver.find_element(By.XPATH, "//button[text()='映像記録']")
        video_record_btn.click()
        time.sleep(1)
        comment = '映像記録ボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 編集リンククリック
        edit_links = driver.find_elements(By.LINK_TEXT, "編集")
        edit_links[0].click()  # 1つ目をクリック
        time.sleep(1)
        comment = '編集リンククリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 削除ボタンクリック
        delete_btn = driver.find_element(By.XPATH, "//button[text()='削除']")
        delete_btn.click()
        driver.switch_to.alert.accept()  # OKを押す
        time.sleep(1)
        comment = '削除ボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 更新データ確認
        sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00303_video_record_01.sql')
        evidence_no = ws_util.export_sql_to_csv(sql_path, script_name, evidence_no)
        # SQLの件数チェック
        count = ws_util.get_select_count_from_sqlfile(sql_path)
        if count < 1:
            comment = '【1-3】【OK】映像記録が削除できること'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='OK', comment=comment)
        else:
            comment = '【1-3】【NG】映像記録が削除できないこと'
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
    # 映像記録削除
    # sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_00303_video_record_02.sql')
    # ws_util.execute_sql_file(sql_path)
    print('after() 完了')

if __name__ == "__main__":
    main()
