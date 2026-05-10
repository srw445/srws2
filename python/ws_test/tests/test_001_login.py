import json
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
import time
import ws_util
from ws_util import export_sql_to_csv

# エビデンス番号
evidence_no = 1
# テストコード名
script_name = os.path.splitext(os.path.basename(__file__))[0]  # 例: test_001_login
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

        # 成功判定（URLのパス部分で判定）
        expected_path = 'ws/public/?action=main'
        actual_url = driver.current_url
        print('ログイン後タイトル:', driver.title)
        print('ログイン後URL:', actual_url)

        if expected_path in actual_url:
            comment = '【1-1】【OK】ログインできること'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='OK', comment=comment)
        else:
            comment = '【1-1】【NG】ログインできないこと'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='NG', comment=comment)
 
        # ログアウトボタンクリック
        logout_btn = driver.find_element(By.XPATH, "//button[text()='ログアウト']")
        logout_btn.click()
        time.sleep(1)
        comment = 'ログアウトボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # user_id入力
        user_input = driver.find_element(By.NAME, 'user_id')
        user_input.clear()
        user_input.send_keys('test')
        time.sleep(1)
        comment = 'ユーザーID入力'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # password入力（NG）
        pass_input = driver.find_element(By.NAME, 'password')
        pass_input.clear()
        pass_input.send_keys('dummy')
        time.sleep(1)
        comment = 'パスワード入力（NG）'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # ログインボタンクリック
        login_btn = driver.find_element(By.ID, 'login-submit')
        login_btn.click()
        time.sleep(1)
        comment = 'ログインボタンクリック'
        evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, comment=comment)

        # 成功判定（URLのパス部分で判定）
        expected_path = 'ws/public/?action=main'
        actual_url = driver.current_url
        print('ログイン後タイトル:', driver.title)
        print('ログイン後URL:', actual_url)

        if expected_path in actual_url:
            comment = '【1-2】【NG】ログインできること'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='OK', comment=comment)
        else:
            comment = '【1-2】【OK】ログインできないこと'
            evidence_no = ws_util.save_screenshot(driver, script_name, evidence_no, label='NG', comment=comment)

    finally:
        driver.quit()

    print('test_code() 完了')

def before():
    """事前処理"""
    global evidence_no
    print('before() 開始')
    sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_001_login_01.sql')
    ws_util.execute_sql_file(sql_path)
    # CSV出力
    sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_001_login_03.sql')
    evidence_no = ws_util.export_sql_to_csv(sql_path, script_name, evidence_no)
    print('before() 完了')

def after():
    """事後処理"""
    global evidence_no
    print('after() 開始')
    sql_path = os.path.join(os.path.dirname(__file__), '../sqls/test_001_login_02.sql')
    ws_util.execute_sql_file(sql_path)
    print('after() 完了')

if __name__ == "__main__":
    main()
