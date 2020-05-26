env=dev

compose=docker-compose -f docker-compose.yml

export compose

.PHONY: init
init: erase pull build up composer-install remove-files remove-folders db ## clean current environment, recreate dependencies, update db and spin up again

.PHONY: stop
stop: ## stop environment
		$(compose) stop

.PHONY: down
down: ## down environment
		$(compose) down

.PHONY: rebuild
rebuild: start ## same as start

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		$(compose) stop
		$(compose) rm -v -f

.PHONY: build
build: ## build environment
		$(compose) build

.PHONY: pull
pull: ## pull environment
		$(compose) pull

.PHONY: up
up: ## spin up environment
		$(compose) up -d

.PHONY: restart
restart: ## restart environment
		$(compose) down up

.PHONY: composer-install
composer-install: ## run composer-install
		$(compose) exec php-fpm composer install

.PHONY: composer-update
composer-update: ## run composer-update
		$(compose) exec php-fpm composer update

.PHONY: create-user
create-user: ## create default user
		$(compose) exec php-fpm sh -lc './bin/console app:create-user $(email) $(password) $(uuid)'

.PHONY: remove-files
remove-files: ## remove files which created init fraemwork
		$(compose) exec php-fpm rm -rf src/Kernel.php config/routes/annotations.yaml config/packages/sensio_framework_extra.yaml config/packages/routing.yaml config/packages/test/routing.yaml config/bootstrap.php bin/phpunit tests/.gitignore

.PHONY: remove-folders
remove-folders: ## remove folders which created init fraemwork
		$(compose) exec php-fpm rm -rf src/Controller src/Entity src/Repository src/Migrations

.PHONY: db
db: ## run init doctrine db
		$(compose) exec php-fpm sh -lc './bin/console d:d:d --force --if-exists'
		$(compose) exec php-fpm sh -lc './bin/console d:d:c --if-not-exists'
		$(compose) exec php-fpm sh -lc './bin/console d:m:m -n'

.PHONY: migration-generate
migration-generate: ## create new doctrine migration
		$(compose) exec php-fpm php bin/console doctrine:migrations:generate

.PHONY: migration-migrate
migration-migrate: ## run doctrine migrations
		$(compose) exec php-fpm php bin/console doctrine:migrations:migrate

.PHONY: sh
sh: ## gets inside a container, use 's' variable to select a service. make s=postgres sh
		$(compose) exec $(s) sh -l

.PHONY: logs
logs: ## look for 's' service logs, make s=php logs
		$(compose) logs -f $(s)

.PHONY: tests
tests: tests ## execute project tests
	docker-compose exec php-fpm sh -lc "./vendor/bin/phpunit $(conf)"

.PHONY: style
style: ## execute php analizers
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpmnd src'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpcs --standard=psr2 src'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpcpd src'
	docker-compose run --rm php-fpm sh -lc './vendor/bin/phpstan analyse -l 6 -c phpstan.neon src'

.PHONY: cs
cs: ## executes coding standards
		$(compose) run --rm php-fpm sh -lc './vendor/bin/ecs check src tests --fix'

.PHONY: cs-check
cs-check: ## executes coding standards in dry run mode
		$(compose) run --rm php-fpm sh -lc './vendor/bin/ecs check src tests'

.PHONY: layer
layer: ## Check issues with layers
	docker-compose run --rm php-fpm sh -lc 'php bin/deptrac.phar analyze --formatter-graphviz=0'

.PHONY: psalm
psalm: ## execute psalm analyzer
		$(compose) run --rm php-fpm sh -lc './vendor/bin/psalm --show-info=false'

.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
