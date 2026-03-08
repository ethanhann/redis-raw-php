# CLAUDE.md

## Project Overview

`redis-raw-php` is a PHP library providing a unified interface for issuing raw Redis commands across multiple client adapters: PhpRedis (PHP extension), Predis (pure PHP), and cheprasov/php-redis-client.

## Development Commands

Tasks are run with [just](https://github.com/casey/just):

| Command       | Description                              |
|---------------|------------------------------------------|
| `just build`  | Format code then run tests               |
| `just test`   | Run the PHPUnit test suite               |
| `just fmt`    | Auto-fix code style with php-cs-fixer    |
| `just lint`   | Check style (no changes) + phpstan       |
| `just check`  | Lint + test (no auto-fix, CI-equivalent) |

## Setup

```bash
composer install
```

## Testing

Tests require a running Redis instance. The adapter under test is selected via the `REDIS_LIBRARY` env var (`PhpRedis`, `Predis`, or `RedisClient`):

```bash
REDIS_LIBRARY=Predis REDIS_HOST=localhost just test
```

Default env values are defined in `phpunit.xml`.

## Architecture

- `RedisRawClientInterface` — contract all adapters implement
- `AbstractRedisRawClient` — shared logic (logging, connection state)
- `PhpRedisAdapter` — wraps the `phpredis` PHP extension
- `PredisAdapter` — wraps `predis/predis`
- `RedisClientAdapter` — wraps `cheprasov/php-redis-client`

## Tooling

- **PHPUnit 11** — test runner (`./vendor/bin/phpunit`)
- **php-cs-fixer** — code style (`./vendor/bin/php-cs-fixer`)
- **phpstan** — static analysis (`./vendor/bin/phpstan`)
