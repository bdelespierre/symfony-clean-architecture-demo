qa: phpcs phpstan

# -----------------------------------------------------------------------------
# Code Quality
# -----------------------------------------------------------------------------

QA_PATHS = src/ config/ domain/
QA_STANDARD = psr12

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
	vendor/bin/phpunit tests/Unit
