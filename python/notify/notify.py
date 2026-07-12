import os
import sys
import time
import datetime
import json
import ctypes
import winsound


# PyInstaller対応: 実行ファイルと同じディレクトリを参照
if getattr(sys, 'frozen', False):
    BASE_DIR = os.path.dirname(sys.executable)
else:
    BASE_DIR = os.path.dirname(__file__)

LOG_PATH = os.path.join(BASE_DIR, "notify.log")
MCI_ALIASES = []
AUDIO_VOLUME_PERCENT = 75

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


def resolve_audio_path(audio_file):
    if not audio_file:
        return None

    candidates = []
    if os.path.isabs(audio_file):
        candidates.append(audio_file)
    else:
        candidates.extend([
            os.path.join(BASE_DIR, audio_file),
            os.path.join(BASE_DIR, "resources", audio_file),
        ])

    for path in candidates:
        if os.path.exists(path):
            return path

    return None


def play_audio(audio_file):
    audio_path = resolve_audio_path(audio_file)
    if not audio_path:
        write_log(f"音声ファイルが見つかりません: {audio_file}")
        return

    try:
        # wav/mp3ともにWindows標準のMCIで再生し、音量を50%に固定する
        for alias in MCI_ALIASES[:]:
            ctypes.windll.winmm.mciSendStringW(f"close {alias}", None, 0, None)
            MCI_ALIASES.remove(alias)

        alias = f"notify_audio_{int(time.time() * 1000)}"
        ext = os.path.splitext(audio_path)[1].lower()
        media_type = "waveaudio" if ext == ".wav" else "mpegvideo"
        open_cmd = f'open "{audio_path}" type {media_type} alias {alias}'
        open_result = ctypes.windll.winmm.mciSendStringW(open_cmd, None, 0, None)
        if open_result != 0:
            raise RuntimeError(f"MCI open failed: {open_result}")

        mci_volume = max(0, min(1000, int(AUDIO_VOLUME_PERCENT * 10)))
        ctypes.windll.winmm.mciSendStringW(f"setaudio {alias} volume to {mci_volume}", None, 0, None)

        play_result = ctypes.windll.winmm.mciSendStringW(f"play {alias}", None, 0, None)
        if play_result != 0:
            ctypes.windll.winmm.mciSendStringW(f"close {alias}", None, 0, None)
            raise RuntimeError(f"MCI play failed: {play_result}")

        MCI_ALIASES.append(alias)
        write_log(f"音声再生: {audio_path}")
    except Exception as e:
        write_log(f"音声再生に失敗しました: {e}")

def get_up_audio(config_data):
    resources = config_data.get("resources")
    if not isinstance(resources, list) or not resources:
        return None

    first_resource = resources[0]
    if not isinstance(first_resource, dict):
        return None

    return first_resource.get("up")


def main():
    config_data = load_config()
    # config_dataがリストの場合は先頭要素を使う
    if isinstance(config_data, list):
        config_data = config_data[0]
    if not isinstance(config_data, dict) or "schedule" not in config_data:
        error_msg = "設定ファイルに'schedule'キーがありません。config.jsonの形式を確認してください。"
        print(error_msg)
        write_log(error_msg)
        sys.exit(1)

    audio_enabled = False
    app_config = config_data.get("config")
    up_audio = get_up_audio(config_data)
    if isinstance(app_config, list) and app_config and isinstance(app_config[0], dict):
        audio_enabled = bool(app_config[0].get("is_audio", False))
    elif isinstance(app_config, dict):
        audio_enabled = bool(app_config.get("is_audio", False))

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
        for config in config_data["schedule"]:
            days = config["days"]
            # "Everyday"が含まれていれば毎日通知
            is_everyday = "Everyday" in days
            if (is_everyday or today in days) and current_time == config["time"]:
                notify(config["message"])
                if audio_enabled:
                    if up_audio:
                        play_audio(up_audio)
                        time.sleep(3)
                    play_audio(config.get("audio"))
                notified = True
        if notified:
            time.sleep(61)  # 1分以上待つことで重複通知を防ぐ
        else:
            time.sleep(30)  # 30秒ごとにチェック


if __name__ == "__main__":
    main()
