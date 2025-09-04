@echo off
:: run-generate.bat
:: Change these paths to match your system
set PHP_EXE=C:\xampp\php\php.exe
set SCRIPT=C:\xampp\htdocs\color_game\api\generate_round.php
set LOG=C:\xampp\htdocs\color_game\api\cron_log.txt

:: ensure logs folder exists
if not exist "%~dp0logs" mkdir "%~dp0logs"

:loop
"%PHP_EXE%" "%SCRIPT%" >> "%LOG%" 2>&1
timeout /t 30 /nobreak >nul
goto loop
