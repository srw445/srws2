@echo off

echo.
echo * -------------------------------------------------- *
echo   loop.bat
echo * -------------------------------------------------- *

echo.
set /p answer="処理を開始します。よろしいですか(y/n)？"
if /i {%answer%}=={y} (goto :YES) else (goto :NO)

:NO
exit

:YES
echo.
echo ★★処理開始★★
echo.

setlocal enabledelayedexpansion
for /f "DELIMS=" %%A IN (list.txt) do (
    echo %%A
)
endlocal

rem pause
pause

