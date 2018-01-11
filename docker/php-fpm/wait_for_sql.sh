#!/bin/sh
set -e

echo ">> Waiting for MySQL"
WAIT=0
while ! nc -z db 3306; do
  sleep 1
  echo "   MySQL not ready yet"
  WAIT=$(($WAIT + 1))
  if [ "$WAIT" -gt 30 ]; then
    echo "Error: Timeout when waiting for MySQL socket"
    exit 1
  fi
done

echo ">> MySQL socket available, resuming command execution"

"$@"
