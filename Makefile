.PHONY: bash cake composer deploy down install migrate pull test test-group up

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))
PROJECT := $(shell basename $(CURDIR) | sed s/[\-_\ ]//g)

CMD=""

bash:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		/bin/bash

cake:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		bin/cake $(CMD)

composer:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		composer $(CMD)

deploy:
	make install
	make migrate
	make cake CMD="orm_cache clear"

down:
	docker-compose down

install:
	make composer CMD=install

migrate:
	make cake CMD="migrations migrate"

pull:
	docker-compose pull

test:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		vendor/bin/phpunit

test-group:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		vendor/bin/phpunit --group testing

up:
	docker-compose up -d
