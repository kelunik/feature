all: install test

install:
	composer -n install

update:
	composer -n update

test: install
	phpdbg -qrr vendor/bin/phpunit --coverage-html=coverage/
	php vendor/bin/php-cs-fixer --diff --dry-run -v fix