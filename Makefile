.PHONY: run
run:
	docker-compose run --rm php

.PHONY: analyse
analyse:
	docker-compose run --rm php ./composer.phar analyse

.PHONY: lint
lint:
	docker-compose run --rm php ./composer.phar lint

.PHONY: lint-fix
lint-fix:
	docker-compose run --rm php ./composer.phar lint-fix

.PHONY: test
test:
	docker-compose run --rm php ./composer.phar test
