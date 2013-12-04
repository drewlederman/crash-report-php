::
:: Usage: UploadDebugFiles <exe_path> <pdb_path> <product_id>
::

@echo off

:: zip the exe and pdb
7z a temp.zip %1 %2

:: find the version of the exe
for /f "delims=" %%a in ('versioninfo "%1"') do @set version=%%a

:: upload the zip to crashreport.kayako.com
echo.
echo Uploading file... please wait.

curl -F "debugfiles=@temp.zip" -F "productid=%3" -F "version=%version%" http://crashreport.kayako.com/admin/debug/symbols.php

:: delete the zip file
del temp.zip

@echo on