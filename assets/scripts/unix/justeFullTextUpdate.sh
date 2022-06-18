#!/usr/bin/env zsh
arg1="$1"
arg2="$2"
arg3="$3"
curl -X POST \
http://localhost:8888/dnaid/system \
-H "Content-Type: application/json" \
-H "Authorization: Bearer $arg3" \
-d "code=$arg2&id=$arg1"