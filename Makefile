PHP84_BIN ?= /opt/homebrew/opt/php@8.4/bin
COMPOSER ?= composer

DIST_DIR := dist

export PATH := $(PHP84_BIN):$(PATH)

.PHONY: help php-version install update validate test pre-commit-install pre-commit-run package clean-dist

help:
	@echo "Available targets:"
	@echo "  php-version        Show active PHP and Composer versions"
	@echo "  install            Install core dependencies"
	@echo "  update             Update core dependencies"
	@echo "  validate           Validate core composer manifest"
	@echo "  test               Run core tests"
	@echo "  pre-commit-install Install hooks using pre-commit install"
	@echo "  pre-commit-run     Run pre-commit on all files"
	@echo "  package            Build core tar.gz archive"
	@echo "  clean-dist         Remove built archives"

php-version:
	@php -v
	@$(COMPOSER) --version

install:
	@$(COMPOSER) install --no-interaction --prefer-dist

update:
	@$(COMPOSER) update

validate:
	@$(COMPOSER) validate --strict

test:
	@$(COMPOSER) test

pre-commit-install:
	@git config --local --unset-all core.hooksPath || true
	@pre-commit install

pre-commit-run:
	@pre-commit run --all-files

package: clean-dist
	@mkdir -p $(DIST_DIR)
	@tar -czf $(DIST_DIR)/php-lite-core-local.tar.gz --exclude='.git' --exclude='vendor' .
	@echo "Built archive in $(DIST_DIR)/"

clean-dist:
	@rm -rf $(DIST_DIR)