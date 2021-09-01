qa: phplint phpcs phpstan

todolist:
	git grep -C2 -p -E '[@]todo'

# -----------------------------------------------------------------------------
# Code Quality
# -----------------------------------------------------------------------------

QA_PATHS = config/ domain/ infrastructure/ src/
QA_STANDARD = psr12

phplint:
	find $(QA_PATHS) -name "*.php" -print0 | xargs -0 -n1 -P8  php -l > /dev/null

phpstan:
	vendor/bin/phpstan analyse $(QA_PATHS)

phpcs:
	vendor/bin/phpcs --standard=$(QA_STANDARD) $(QA_PATHS)

phpcbf:
	vendor/bin/phpcbf --stanrdard=$(QA_STANDARD) $(QA_PATHS)

# -----------------------------------------------------------------------------
# Tests
# -----------------------------------------------------------------------------

test:
	vendor/bin/phpunit

.PHONY: coverage
coverage:
	vendor/bin/phpunit --coverage-html coverage
