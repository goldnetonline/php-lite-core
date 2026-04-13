# php lite core

Core framework package for `goldnetonline/php-lite-core`.

This repository contains reusable runtime components used by the php-lite starter.

## maintainer setup

```bash
make install
make validate
make test
```

## pre-commit

```bash
make pre-commit-install
make pre-commit-run
```

## release flow

1. Merge changes to `main`
2. Create and push a semantic tag (example: `v1.0.0`)
3. Wait for `release-packages` workflow to finish
4. Download the core tarball from the GitHub release
