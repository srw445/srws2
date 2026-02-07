import os
import sys
import time
import datetime
import json


# PyInstaller対応: 実行ファイルと同じディレクトリを参照
if getattr(sys, 'frozen', False):
    BASE_DIR = os.path.dirname(sys.executable)
else:
    BASE_DIR = os.path.dirname(__file__)

LOG_PATH = os.path.join(BASE_DIR, "notify.log")

def write_log(message):
    now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    with open(LOG_PATH, "a", encoding="utf-8") as log_file:
        log_file.write(f"[{now}] {message}\n")

CONFIG_PATH = os.path.join(BASE_DIR, "config.json")

def load_config():
    try:
        with open(CONFIG_PATH, "r", encoding="utf-8") as f:
            return json.load(f)
    except Exception as e:
        error_msg = f"設定ファイルの読み込みに失敗しました: {e}"
        print(error_msg)
        write_log(error_msg)
        sys.exit(1)

def notify(message):
    try:
        from notifypy import Notify
        notification = Notify()
        notification.title = "通知"
        notification.message = message
        notification.send()
        write_log(f"通知: {message}")
    except ImportError:
        error_msg = "notifypyがインストールされていません。'pip install notifypy'でインストールしてください。"
        print(error_msg)
        write_log(error_msg)
        sys.exit(1)


def main():
    config_list = load_config()
    days_map = {
        0: "Monday",
        1: "Tuesday",
        2: "Wednesday",
        3: "Thursday",
        4: "Friday",
        5: "Saturday",
        6: "Sunday"
    }
    while True:
        now = datetime.datetime.now()
        current_time = now.strftime("%H:%M")
        today = days_map[now.weekday()]
        notified = False
        for config in config_list:
            if today in config["days"] and current_time == config["time"]:
                notify(config["message"])
                notified = True
        if notified:
            time.sleep(61)  # 1分以上待つことで重複通知を防ぐ
        else:
            time.sleep(30)  # 30秒ごとにチェック


if __name__ == "__main__":
    main()
