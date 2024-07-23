# depending of the required php version.
PHP_MIN_VERSION=8.2

.PHONY: support
support:
	@./vendor/bin/phpcs -p ./src ./examples ./generators --colors \
	--standard=PHPCompatibility --runtime-set testVersion $(PHP_MIN_VERSION)-