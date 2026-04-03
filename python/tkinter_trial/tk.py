import tkinter as tk
import pyautogui
import time

root = tk.Tk()

root.mainloop()

try:
    while True:
        x, y = pyautogui.position()  # 現在のマウスの座標を取得
        print(f"現在のマウス座標: X={x}, Y={y}")
        time.sleep(0.1)  # 0.1秒ごとに更新
except KeyboardInterrupt:
    print("プログラムを終了しました。")

