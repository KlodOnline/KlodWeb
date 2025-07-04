DEV-COMPOSE_FILE=docker-compose-dev.yml
JS-TOOLS-DOCKERFILE=tools/js/Dockerfile
PHP-TOOLS-DOCKERFILE=tools/php/Dockerfile

up:
	docker compose -f $(DEV-COMPOSE_FILE) up -d

down:
	docker compose -f $(DEV-COMPOSE_FILE) down

clean-db:
	docker compose -f $(DEV-COMPOSE_FILE) down
	docker volume rm klodweb_db_data
	docker compose -f $(DEV-COMPOSE_FILE) up -d

logs:
	docker compose -f $(DEV-COMPOSE_FILE) logs -f

ps:
	docker compose -f $(DEV-COMPOSE_FILE) ps

sh-php:
	docker compose -f $(DEV-COMPOSE_FILE) exec php bash

sh-apache:
	docker compose -f $(DEV-COMPOSE_FILE) exec apache bash
	
sh-db:
	docker compose -f $(DEV-COMPOSE_FILE) exec db bash

fix-js:
	docker build -f ${JS-TOOLS-DOCKERFILE} -t klodweb-eslint tools/js/eslint
	docker run --rm -v ${PWD}:/app klodweb-eslint sh -c "cd tools/js/eslint && npm install && cd ../../../ && npx eslint --fix -c tools/js/eslint/eslint.config.js www/js"

fix-php:
	docker build -f ${PHP-TOOLS-DOCKERFILE} -t klodweb-csfixer tools/php/csfixer
	docker run --rm -v ${PWD}:/app klodweb-csfixer sh -c "composer install --working-dir=tools/php/csfixer && tools/php/csfixer/vendor/bin/php-cs-fixer fix --config=tools/php/csfixer/.php-cs-fixer.dist.php ./www"

fix:
	make fix-js && make fix-php