Option Explicit

Call ExecMain()

Sub ExecMain()
	
	'変数宣言
	Dim msgResult
	Dim objWshShell
	Dim objShell
	Dim objFile
	Dim ServerName
	Dim DBName
	Dim UserID
	Dim Password
	Dim objCon
	Dim objRS
	Dim answer
	Dim query
	Dim sqlResult
	Dim cnt
	Dim outputFile
	
	'DB情報設定
	ServerName = "dummy"
	DBName = "dummy"
	UserID = "dummy"
	Password = "dummy"
	
	'オブジェクト生成
	Set objWshShell = CreateObject("WScript.Shell")
	Set objShell = WScript.CreateObject("WScript.Shell")
	Set objFile = WScript.CreateObject("Scripting.FileSystemObject")
	'中間ファイル
	Set outputFile = objFile.OpenTextFile("temp.txt" ,8 ,True)
	
	'ダイアログ表示
	answer = InputBox("IDを指定してください。","ID")
	
	If Len(answer) = 0 Then
		'処理中止
		msgResult = MsgBox("ID未指定のため処理を中止します。",vbOKOnly + vbInformation ,"【情報】")
	Else
		query = ""
		query = query & " select 連番"
		query = query & " from ユーザ管理"
		query = query & " where ID = '" + answer + "'"
		query = query & " and 削除F = '0';"
		
		'DB接続
		On Error Resume Next
			Set objCon = CreateObject("ADODB.Connection")
			objCon.Open "Driver={SQL Server}; server=" & ServerName & "; database=" & DBName & "; uid=" & UserID & "; uid=" & UserID & "; pwd=" & Password & ";"
			'エラー処理
			If Err.Number <> 0 Then
				msgResult = MsgBox("DB接続が失敗しました。　　" & "　エラーナンバー：" & Err.Number & "　エラー詳細：" & Err.Description)
				Set objCon = Nothing
				Exit Sub
			Else
				'MsgBox("DB接続しました。")
			End If
		Err.Clear
		On Error Goto 0
		
		'SQL実行
		On Error Resume Next
			Set objRS = objCon.Execute(query)
			
			'エラー処理
			If Err.Number <> 0 Then
				msgResult = MsgBox("SQL実行が失敗しました。　　" & "　エラーナンバー：" & Err.Number & "　エラー詳細：" & Err.Description)
				objCon.Close
				Set objCon = Nothing
				Set objRS = Nothing
				Exit Sub
			End If
			
		Err.Clear
		On Error Goto 0
		
		'SELECT結果書き出し
		On Error Resume Next
			'SELECT件数分ループ
			do until objRS.eof
				sqlResult = ""
				for cnt = 0 to objRS.fields.count - 1
					sqlResult = objRS("連番")
				next
				'中間ファイル書き出し
				outputFile.WriteLine(sqlResult)
				'インクリメント
				objRS.movenext
			loop
			'エラー処理
			If Err.Number <> 0 Then
				msgResult = MsgBox("SQL実行結果取得が失敗しました。　　" & "　エラーナンバー：" & Err.Number & "　エラー詳細：" & Err.Description)
				objCon.Close
				Set objCon = Nothing
				Set objRS = Nothing
				Exit Sub
			End If
		Err.Clear
		On Error Goto 0
		'ファイル閉じる
		outputFile.Close
	End If
	
	msgResult = MsgBox("!!!処理終了!!!",vbOKOnly + vbInformation ,"【情報】")
	
	'変数初期化
	Set msgResult = Nothing
	Set objWshShell = Nothing
	Set objShell = Nothing
	Set objFile = Nothing
	Set ServerName = Nothing
	Set DBName = Nothing
	Set UserID = Nothing
	Set Password = Nothing
	Set objCon = Nothing
	Set objRS = Nothing
	Set answer = Nothing
	Set query = Nothing
	Set sqlResult = Nothing
	Set cnt = Nothing
	Set outputFile = Nothing
End Sub