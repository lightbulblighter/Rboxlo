@echo off
cls

cd keys
set /p path="Enter Script Path: "

start "" "ScriptSigner.exe" %path%
echo Signed!

PAUSE