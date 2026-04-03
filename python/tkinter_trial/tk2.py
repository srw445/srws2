import tkinter as tk
import pyautogui

def update_coordinates():
    x, y = pyautogui.position()
    label.config(text=f"Mouse Coordinates: X={x}, Y={y}")   
    root.after(100, update_coordinates) # 定期的に座標を更新

root = tk.Tk()
root.title("Mouse Coordinates Tracker with pyautogui")
root.geometry("400x300")  # ウィンドウサイズ

label = tk.Label(root, text="Mouse Coordinates: X=0, Y=0", font=("Arial", 16))
label.pack(pady=20)

# 座標を更新する関数を呼び出し
update_coordinates()

root.mainloop()