import json
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
import time

# settings.jsonからChromeDriverパス、テスト対象URLを取得
with open('settings.json', encoding='utf-8') as f:
    settings = json.load(f)
CHROMEDRIVER_PATH = settings['chromedriver_path']
TEST_URL = settings.get('test_url')

options = Options()
# options.add_argument('--headless')  # 画面非表示で実行したい場合は有効化
options.add_argument('--no-sandbox')
options.add_argument('--disable-dev-shm-usage')

# WebDriver起動
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

    # password入力
    pass_input = driver.find_element(By.NAME, 'password')
    pass_input.clear()
    pass_input.send_keys('test')
    time.sleep(1)

    # login-submitクリック
    login_btn = driver.find_element(By.ID, 'login-submit')
    login_btn.click()
    time.sleep(1)

    # 成功判定（URLのパス部分で判定）
    expected_path = 'ws/public/?action=main'
    actual_url = driver.current_url
    print('ログイン後タイトル:', driver.title)
    print('ログイン後URL:', actual_url)
    if expected_path in actual_url:
        print('ログインテスト成功')
    else:
        print('ログインテスト失敗')
        exit(1)

    print('ログインテスト完了')
finally:
    driver.quit()
