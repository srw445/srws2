# notify.py

## 概要
指定した曜日・時間に通知を表示し、通知やエラーの内容をログファイルに記録するPythonアプリです。

## 機能
- config.jsonで複数の通知設定が可能
- 指定した曜日・時間にメッセージを通知
- 通知やエラー発生時にnotify.logへ記録
- Windowsでexe化してタスクスケジューラ常駐可能

## 設定方法
1. `config.json` を以下のように編集します。
```
[
  {
    "message": "缶・びん・ペットボトルの回収日です。",
    "days": ["Sunday"],
    "time": "21:00"
  }
]
```
- `message`: 通知内容
- `days`: 曜日（英語: Monday, Tuesday, ...）
- `time`: 時刻（24時間表記: HH:MM）

## 実行方法
1. 必要なライブラリをインストール
```
pip install notifypy
```
2. notify.pyを実行
```
python notify.py
```

## exe化（Windows）
1. pyinstallerをインストール
```
pip install pyinstaller
```
2. exeファイル作成
```
pyinstaller --onefile notify.py
```
3. distフォルダ内のnotify.exeをタスクスケジューラ等で常駐実行

## ログ
- 通知やエラーは `notify.log` に記録されます。

## 注意
- config.jsonが存在しない場合やnotifypy未インストールの場合はアプリが終了します。
- exe化した場合、config.jsonとnotify.logはexeと同じフォルダに置いてください。

## 動作環境
- Python 3.8 
