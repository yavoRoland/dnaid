@echo off
set arg1=%1
set arg2=%2
set arg3=%3
curl -X POST -H "Authorization: Bearer %arg3%" -d "id=%arg1%&code=%arg2%" http:localhost:8888/dnaid/system