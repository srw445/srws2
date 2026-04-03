import tkinter as tk
import pyautogui
from pynput import mouse

# 初期化
start_x = 0
start_y = 0
end_x = 0
end_y = 0

# 画面全体を覆うモーダルウィンドウ用
modal = None

def on_click(x, y, button, pressed):
    global start_x, start_y, end_x, end_y, modal
    if pressed:  # ボタンが押されたとき（開始位置）
        start_x, start_y = x, y
        label_start.config(text=f"Start Position: X={start_x}, Y={start_y}")
        print(f"Start Position: X={start_x}, Y={start_y}")
        # モーダルウィンドウを表示して他ウィンドウ操作をブロック
        if modal is None:
            modal = tk.Toplevel(root)
            modal.attributes('-fullscreen', True)
            modal.attributes('-alpha', 0.01)  # ほぼ透明
            modal.lift()
            modal.grab_set()
    else:  # ボタンが離されたとき（終了位置）
        end_x, end_y = x, y
        label_end.config(text=f"End Position: X={end_x}, Y={end_y}")
        print(f"End Position: X={end_x}, Y={end_y}")
        # モーダル解除
        if modal is not None:
            modal.grab_release()
            modal.destroy()
            modal = None

# tkinterウィンドウ作成
root = tk.Tk()
root.title("ドラッグ座標取得（ウィンドウ外対応）")
root.geometry("400x200")

# ラベル（クリック位置とドラッグ終了位置を表示）
label_start = tk.Label(root, text="Start Position: X=0, Y=0", font=("Arial", 12))
label_start.pack(pady=10)

label_end = tk.Label(root, text="End Position: X=0, Y=0", font=("Arial", 12))
label_end.pack(pady=10)

# マウス監視用スレッドの開始
listener = mouse.Listener(on_click=on_click)
listener.start()

root.mainloop()