start: erase pull build up composer-install remove-files remove-folders

restart: down up

erase:
	docker-compose stop
	docker-compose rm -v -f

up:
	docker-compose up -d

down:
	docker-compose down

pull:
	docker-compose pull

build:
	docker-compose build

console:
	docker-compose exec php-fpm bash

composer-install:
	docker-compose exec php-fpm composer install

db:
	docker-compose exec php-fpm sh -lc './bin/console d:d:d --force'
	docker-compose exec php-fpm sh -lc './bin/console d:d:c'
	docker-compose exec php-fpm sh -lc './bin/console d:m:m -n'

migration-generate:
	docker-compose exec php-fpm php bin/console doctrine:migrations:generate

migration-migrate:
	docker-compose exec php-fpm php bin/console doctrine:migrations:migrate

remove-files:
	docker-compose exec php-fpm rm -rf src/Kernel.php config/routes/annotations.yaml config/packages/sensio_framework_extra.yaml config/packages/routing.yaml config/packages/test/routing.yaml config/bootstrap.php bin/phpunit tests/.gitignore

remove-folders:
	docker-compose exec php-fpm rm -rf src/Controller src/Entity src/Repository src/Migrations

.PHONY: tests
tests: tests ## execute project tests
	docker-compose exec php-fpm sh -lc "./vendor/bin/phpunit $(conf)"

.PHONY: style
style: ## execute php analizers
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpmnd src'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpcs --standard=psr2 src'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpcpd src'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpcdm src --non-zero-exit-on-violation'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpstan analyse -l 6 -c phpstan.neon src'

.PHONY: cs
cs: ## executes php cs fixer
	docker-compose run --rm php-fpm sh -lc './vendor/bin/php-cs-fixer --no-interaction --diff -v fix'

.PHONY: cs-check
cs-check: ## executes php cs fixer in dry run mode
	docker-compose run --rm php-fpm sh -lc './vendor/bin/php-cs-fixer --no-interaction --dry-run --diff -v fix'

.PHONY: layer
layer: ## Check issues with layers
	docker-compose run --rm php-fpm sh -lc 'php bin/deptrac.phar analyze --formatter-graphviz=0'
