import tkinter as tk
import pyautogui
import json

def update_coordinates():
    # マウスの現在座標を取得
    x, y = pyautogui.position()
    label.config(text=f"Mouse Coordinates: X={x}, Y={y}")
    # 定期的に座標を更新
    root.after(100, update_coordinates)

def save_data():
    # 最新の座標を取得
    x, y = pyautogui.position()
    print(x)
    print(y)

    if x is not None and y is not None:  # 座標が存在する場合のみ保存
        data = {"locate_x": x, "locate_y": y}

        # JSONファイルにデータを書き込む
        with open("data.json", "w", encoding="utf-8") as file:
            json.dump(data, file, ensure_ascii=False, indent=4)

        print("データが保存されました！")

root = tk.Tk()
root.title("座標入力")
root.geometry("400x300")  # ウィンドウサイズ

label = tk.Label(root, text="Mouse Coordinates: X=0, Y=0", font=("Arial", 16))
label.pack(pady=20)

# 座標を更新する関数を呼び出し
update_coordinates()

# 保存ボタン
save_button = tk.Button(root, text="保存", command=save_data)
save_button.pack(pady=10)

root.mainloop()