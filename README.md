# PHP Lite Core

![Tests](https://github.com/goldnetonline/php-lite-core/actions/workflows/tests.yml/badge.svg)
![Release](https://github.com/goldnetonline/php-lite-core/actions/workflows/release-packages.yml/badge.svg)

Core framework package providing runtime components for PHP Lite applications.

## Overview

- **Core Package**: This repository
- **Starter Repository**: [`php-lite`](https://github.com/goldnetonline/php-lite) - Use this to create new projects
- **Package Distribution**: Available on [Packagist](https://packagist.org/packages/goldnetonline/php-lite-core)

## Installation

### For Project Developers

The core framework is automatically installed when using the starter template:

```bash
composer create-project goldnetonline/php-lite my-site
```

### For Framework Contributors

```bash
make install
make validate
make test
```

## Development

```bash
make pre-commit-install
make pre-commit-run
```

## Architecture

PHP Lite Core provides:

- **Application Runtime** - Request/response handling and routing
- **View Engine** - Twig-based template rendering
- **Mail System** - SMTP and Mailgun driver support
- **Error Handling** - Integrated exception handling via Whoops
- **Configuration** - Environment-based application configuration

See [Core Components](https://github.com/goldnetonline/php-lite-core/tree/main/src/Core) for implementation details.

## Using GitHub Packages

To use this package from GitHub Packages:

1. Configure authentication in your `composer.json`:

    ```json
    {
        "repositories": {
            "github-packages": {
                "type": "composer",
                "url": "https://composer.github.com/goldnetonline"
            }
        }
    }
    ```

2. Or via token in `~/.composer/auth.json`:
    ```json
    {
        "github-oauth": {
            "github.com": "YOUR_GITHUB_TOKEN"
        }
    }
    ```

## Release Process

### Creating a Release

1. Ensure all tests pass: `make qa`
2. Merge changes to `main`
3. Create and push a semantic version tag:
    ```bash
    git tag v1.0.0
    git push origin v1.0.0
    ```
4. Wait for the `release-packages` workflow to complete

### Release Distribution

- **GitHub Releases**: Archives available at [Releases](https://github.com/goldnetonline/php-lite-core/releases)
- **Packagist**: Published to [Packagist](https://packagist.org/packages/goldnetonline/php-lite-core)
- **GitHub Packages**: Available at `composer.github.com/goldnetonline`

## Distribution & Exclusions

Development files are excluded from distribution archives:

- `/.github/workflows/release*` - Release workflows
- `/tests` - Unit and integration tests
- `/scripts` - Build automation scripts
- `/Makefile` - Development targets
- `/.pre-commit-config.yaml` - Git hooks config

These are available in the GitHub repository but not in Composer-installed packages.

## Starter Integration

The PHP Lite Starter (`goldnetonline/php-lite`) automatically depends on this core package.
Starter projects inherit all core capabilities and should reference [Starter repository](https://github.com/goldnetonline/php-lite) for usage documentation.

## License

MIT License - see LICENSE file
