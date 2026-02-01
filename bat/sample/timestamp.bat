@echo off

echo.
echo * -------------------------------------------------- *
echo   timestamp.bat
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

set YYYY=%DATE:~0,4%
set MM=%DATE:~5,2%
set DD=%DATE:~8,2%
set TIME2=%TIME: =0%
set HH=%TIME2:~0,2%
set MN=%TIME2:~3,2%
set SS=%TIME2:~6,2%
set STAMP=%YYYY%%MM%%DD%%HH%%MN%%SS%

echo %STAMP%

pause

