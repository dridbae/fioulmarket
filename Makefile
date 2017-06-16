install:
	php composer.phar install
	docker-compose up -d
	rm -rf app/cache/*

install-database:
	php bin/console doctrine:database:create --quiet
	php bin/console doctrine:schema:update --force --quiet

update-database:
	php bin/console doctrine:schema:update --force --quiet

start:
	php bin/console server:start

stop:
	php bin/console server:stop
	docker-compose down

test:
	php vendor/symfony/phpunit-bridge/bin/simple-phpunit  --coverage-html index.html
