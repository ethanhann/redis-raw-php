default: build

build: fmt test

test:
    ./vendor/bin/phpunit

fmt:
    ./vendor/bin/php-cs-fixer fix src

lint:
    ./vendor/bin/php-cs-fixer check src --diff
    ./vendor/bin/phpstan analyse

check: lint test
