#!/usr/bin/env bash
set -euo pipefail

REPO_ROOT="$(git rev-parse --show-toplevel)"
cd "$REPO_ROOT"

if [[ -x "/opt/homebrew/opt/php@8.4/bin/php" ]]; then
  export PATH="/opt/homebrew/opt/php@8.4/bin:$PATH"
fi

for file in "$@"; do
  case "$file" in
    composer.json)
      echo "pre-commit: validating composer.json"
      composer validate --strict
      ;;
  esac
done