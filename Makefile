SHELL := /bin/bash
DEFAULT_GOAL := help
THIS_FILE := $(lastword $(MAKEFILE_LIST))

help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: start
start: ## Start application
	@echo -e "\033[33mstart\033[0m"
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose --file docker-compose.yml up

.PHONY: build
build: ## Build application
	@echo -e "\033[33mstart\033[0m"
	@docker build .docker/php -f .docker/php/Dockerfile --tag subtitles-php-base

	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose --file docker-compose.yml build

.PHONY: rebuild
rebuild: ## Rebuild application
	@echo -e "\033[33mrebuild\033[0m"
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose --file docker-compose.yml rm -sf
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose --file docker-compose.yml build --pull --no-cache
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose --file docker-compose.yml up --force-recreate

.PHONY: clean
clean: ## Clean containers and application cache
	@echo -e "\033[33mclean\033[0m"
	@echo -e "\033[33mclean-docker\033[0m"
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose --file docker-compose.yml down --volumes --remove-orphans --rmi local
	@if [ "$$(docker images -q -f dangling=true)" != "" ]; then docker rmi $$(docker images -q -f dangling=true) ;fi

	@rm -rf "./vendor"
	@rm -rf "./var/cache"
	@rm -rf "./var/log"
	@rm -rf "./var/sessions"

.PHONY: shell
shell: ## Runs php container shell
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose exec php bash

.PHONY: test
test: ## Runs all tests
	@USERID=$$(id -u) GROUPID=$$(id -g) docker compose exec php \
		php /app/vendor/phpunit/phpunit/phpunit \
		--testdox \
		--configuration /app/phpunit.xml

.PHONY: phpstan
phpstan: ## Run phpstan
	@echo -e "\033[33mphpstan\033[0m"
	@docker run --rm \
				-t \
				--volume $(HOME)/.cache:/home/docker/.cache:delegated \
				--volume $(HOME)/.npm:/home/docker/.npm:delegated \
				--volume $(PWD):/app:delegated \
				--env USERID=$$(id -u) \
				--env GROUPID=$$(id -g) \
				--name subtitles-php-runtime \
				subtitles-php \
				bash -c "composer phpstan"

.PHONY: npm-install
npm-install: ## Run npm install
	@echo -e "\033[33mnpm-install\033[0m"
	@docker run --rm \
				-t \
				--volume $(HOME)/.cache:/home/docker/.cache:delegated \
				--volume $(HOME)/.npm:/home/docker/.npm:delegated \
				--volume $(PWD):/app:delegated \
				--env USERID=$$(id -u) \
				--env GROUPID=$$(id -g) \
				--name subtitles-node-runtime \
				subtitles-node \
				bash -c "npm install"

.PHONY: npm-dev
npm-dev: ## Run vite
	@echo -e "\033[33mnpm-dev\033[0m"
	@docker run --rm \
				-i \
				-t \
				--volume $(HOME)/.cache:/home/docker/.cache:delegated \
				--volume $(HOME)/.npm:/home/docker/.npm:delegated \
				--volume $(PWD):/app:delegated \
				--env USERID=$$(id -u) \
				--env GROUPID=$$(id -g) \
				--name subtitles-node-runtime \
				subtitles-node \
				bash -c "npm run dev"

.PHONY: npm-build
npm-build: ## Run vite build
	@echo -e "\033[33mnpm-build\033[0m"
	@docker run --rm \
				-t \
				--volume $(HOME)/.cache:/home/docker/.cache:delegated \
				--volume $(HOME)/.npm:/home/docker/.npm:delegated \
				--volume $(PWD):/app:delegated \
				--env USERID=$$(id -u) \
				--env GROUPID=$$(id -g) \
				--name subtitles-node-runtime \
				subtitles-node \
				bash -c "npm run build"

.PHONY: npm-watch
npm-watch: ## Run vite build --watch
	@echo -e "\033[33mnpm-watch\033[0m"
	@docker run --rm \
				-i \
				-t \
				--init \
				--volume $(HOME)/.cache:/home/docker/.cache:delegated \
				--volume $(HOME)/.npm:/home/docker/.npm:delegated \
				--volume $(PWD):/app:delegated \
				--env USERID=$$(id -u) \
				--env GROUPID=$$(id -g) \
				--name subtitles-node-runtime \
				subtitles-node \
				bash -c "npm run watch"
