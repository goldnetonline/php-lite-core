#!/usr/bin/env bash
set -euo pipefail

REPO_ROOT="$(git rev-parse --show-toplevel)"
cd "$REPO_ROOT"

if [[ -x "/opt/homebrew/opt/php@8.4/bin/php" ]]; then
  export PATH="/opt/homebrew/opt/php@8.4/bin:$PATH"
fi

if ! command -v php >/dev/null 2>&1; then
  echo "Error: php not found in PATH"
  exit 1
fi

if ! command -v composer >/dev/null 2>&1; then
  echo "Error: composer not found in PATH"
  exit 1
fi

CORE_CHANGED=0
declare -a CORE_PHP_FILES=()

for file in "$@"; do
  case "$file" in
    src/*|tests/*)
      CORE_CHANGED=1
      [[ "$file" == *.php ]] && CORE_PHP_FILES+=("$file")
      ;;
    composer.json|phpunit.xml)
      CORE_CHANGED=1
      ;;
  esac
done

if [[ ${#CORE_PHP_FILES[@]} -gt 0 ]]; then
  echo "pre-commit: linting core PHP files"
  for file in "${CORE_PHP_FILES[@]}"; do
    php -l "$file" >/dev/null
  done
fi

if [[ $CORE_CHANGED -eq 1 ]]; then
  echo "pre-commit: running core tests"
  composer test
fi

echo "pre-commit: scoped checks passed"