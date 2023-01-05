.RECIPEPREFIX +=
.DEFAULT_GOAL := help
PROJECT_NAME=jump
include .env

help:
	@echo "Welcome to $(PROJECT_NAME) IT Support, have you tried turning it off and on again?"

install:
	@composer install

migrate:
	@docker exec $(PROJECT_NAME)_php php artisan migrate

seed:
	@docker exec $(PROJECT_NAME)_php php artisan db:seed

fresh:
	@docker exec $(PROJECT_NAME)_php artisan migrate:fresh

artisan:
	@docker exec $(PROJECT_NAME)_php artisan

nginx:
	@docker exec -it $(PROJECT_NAME)_nginx /bin/sh

php:
	@docker exec -it $(PROJECT_NAME)_php /bin/sh

mysql:
	@docker exec -it $(PROJECT_NAME)_mysql /bin/sh

redis:
	@docker exec -it $(PROJECT_NAME)_redis /bin/sh

container:
	@docker container ps

up:
	@docker-compose up -d

down:
	@docker-compose down

test:
	@docker exec -it $(PROJECT_NAME)_php  ./vendor/bin/phpunit

html:
	XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html coverage/
