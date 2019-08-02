.DEFAULT_GOAL := help
# print description
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'

install: ## Composer install etc
	docker run --rm --interactive --tty \
       --volume $(PWD):/app \
       --user $(id -u):$(id -g) \
       composer install

run-incorrect: ## Run with incorrect input
	docker run -it --rm --name my-running-script \
	-v "$(PWD)":/app -w /app php:7.2-cli \
	cat input_incorrect.json | php src/main.php

run-incorrect-errors-only: ## Run with incorrect input, print STDERR only
	docker run -it --rm --name my-running-script \
	-v "$(PWD)":/app -w /app php:7.2-cli \
	cat input_incorrect.json | php src/main.php 2>&1 >/dev/null

run-broken-json-errors-only: ## Run with incorrect json format
	docker run -it --rm --name my-running-script \
	-v "$(PWD)":/app -w /app php:7.2-cli \
	echo '{jSonMan' | xargs php src/main.php  2>&1 >/dev/null

run: ## Run with correct input
	docker run -it --rm --name my-running-script \
	-v "$(PWD)":/app -w /app php:7.2-cli \
	cat input.json | php src/main.php

remove-unused-images: ## remove images: composer php etc/
	docker rmi composer \
	docker rmi php:7.2-cli
