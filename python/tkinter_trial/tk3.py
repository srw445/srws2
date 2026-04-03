import tkinter as tk
import json

def save_data():
    # 入力されたデータを取得
    name = name_entry.get()
    age = age_entry.get()

    if name and age:  # 両方のフィールドが入力されている場合のみ保存
        data = {"Name": name, "Age": age}

        # JSONファイルにデータを書き込む
        with open("data.json", "w", encoding="utf-8") as file:
            json.dump(data, file, ensure_ascii=False, indent=4)

        status_label.config(text="データが保存されました！")
    else:
        status_label.config(text="すべてのフィールドを入力してください！")

# tkinterウィンドウを作成
root = tk.Tk()
root.title("データ入力と保存")
root.geometry("300x200")

# 名前入力フィールド
name_label = tk.Label(root, text="名前:")
name_label.pack()
name_entry = tk.Entry(root)
name_entry.pack()

# 年齢入力フィールド
age_label = tk.Label(root, text="年齢:")
age_label.pack()
age_entry = tk.Entry(root)
age_entry.pack()

# 保存ボタン
save_button = tk.Button(root, text="保存", command=save_data)
save_button.pack(pady=10)

# ステータスラベル
status_label = tk.Label(root, text="")
status_label.pack()

root.mainloop()