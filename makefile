test:
	./vendor/bin/phpunit tests/

validate-stan:
	./vendor/bin/phpstan analyse -l 6 src tests

validate-phpcs:
	./vendor/bin/php-cs-fixer fix src -v --dry-run

validate-phpcs-tests:
	./vendor/bin/php-cs-fixer fix tests -v --dry-run