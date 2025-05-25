#!/bin/bash

HOOK_SOURCE="githooks/pre-commit"
HOOK_TARGET=".git/hooks/pre-commit"

if [ ! -d ".git/hooks" ]; then
  echo "The folder .git/hooks is not here :(. You should run this script from a Git repo root."
  exit 1
fi

cp "$HOOK_SOURCE" "$HOOK_TARGET"
chmod +x "$HOOK_TARGET"

echo "Pre-commit hook successfully installed in .git/hooks"
