@echo off

echo.
echo * -------------------------------------------------- *
echo   rename.bat
echo * -------------------------------------------------- *

rem ---------- 置換前後の文字列を設定 ----------
set TARGET=20250118
set REPLACE_WITH=yyyymmdd
rem --------------------------------------------

echo TARGET=[%TARGET%]
echo REPLACE_WITH=[%REPLACE_WITH%]

echo.
set /p answer="処理を開始します。よろしいですか(y/n)？"
if /i {%answer%}=={y} (goto :YES) else (goto :NO)

:NO
exit

:YES
echo.
echo ★★処理開始★★
echo.

for %%F in ( * ) do call :sub "%%F"
exit /b

:sub
set FILE_NAME=%1
call set FILE_NAME=%%FILE_NAME:%TARGET%=%REPLACE_WITH%%%
ren %1 %FILE_NAME%

goto :EOF

pause

