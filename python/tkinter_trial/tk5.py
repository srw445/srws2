import tkinter as tk
import pyautogui
from pynput import mouse

# 初期化
start_x = 0
start_y = 0
end_x = 0
end_y = 0

def on_click(x, y, button, pressed):
    global start_x, start_y, end_x, end_y
    if pressed:  # ボタンが押されたとき（開始位置）
        start_x, start_y = x, y
        label_start.config(text=f"Start Position: X={start_x}, Y={start_y}")
        print(f"Start Position: X={start_x}, Y={start_y}")
    else:  # ボタンが離されたとき（終了位置）
        end_x, end_y = x, y
        label_end.config(text=f"End Position: X={end_x}, Y={end_y}")
        print(f"End Position: X={end_x}, Y={end_y}")
        # グローバル座標→ウィンドウ座標に変換しCanvasに四角形描画
        win_x = root.winfo_rootx()
        win_y = root.winfo_rooty()
        x1 = start_x - win_x
        y1 = start_y - win_y
        x2 = end_x - win_x
        y2 = end_y - win_y
        canvas.delete("rect")
        # Canvasサイズ外でも描画できるようにスクロールリージョンを調整
        min_x = min(x1, x2)
        min_y = min(y1, y2)
        max_x = max(x1, x2)
        max_y = max(y1, y2)
        canvas.config(scrollregion=(min_x, min_y, max_x, max_y))
        canvas.create_rectangle(x1, y1, x2, y2, outline="red", width=2, tag="rect")


# tkinterウィンドウ作成
root = tk.Tk()
root.title("ドラッグ座標取得（ウィンドウ外対応）")
root.geometry("800x600")  # ウィンドウサイズを大きめに

# Canvas追加（ドラッグ範囲を描画）
canvas = tk.Canvas(root, bg="white")
canvas.pack(fill=tk.BOTH, expand=True)

# ラベル（クリック位置とドラッグ終了位置を表示）
label_start = tk.Label(root, text="Start Position: X=0, Y=0", font=("Arial", 12))
label_start.pack()
label_end = tk.Label(root, text="End Position: X=0, Y=0", font=("Arial", 12))
label_end.pack()

# マウス監視用スレッドの開始
listener = mouse.Listener(on_click=on_click)
listener.start()

root.mainloop()