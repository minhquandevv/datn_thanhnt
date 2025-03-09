# Makefile cho Laravel

# Đặt các đường dẫn phổ biến
PHP = php
ARTISAN = php artisan
COMPOSER = composer
NPM = npm

# Tên các environment và các command
APP_ENV = .env

# Lệnh để chạy server
serve:
	$(ARTISAN) serve

# Lệnh để cài đặt các phụ thuộc (dependencies)
install:
	$(COMPOSER) install

# Lệnh để chạy migrate
migrate:
	$(ARTISAN) migrate

# Lệnh để seed database
seed:
	$(ARTISAN) db:seed

# Lệnh để tạo các migration file mới
make-migration:
	$(ARTISAN) make:migration

# Lệnh để chạy test (nếu có)
test:
	$(ARTISAN) test

# Lệnh để clear cache
cache-clear:
	$(ARTISAN) cache:clear

# Lệnh để tạo controller mới
make-controller:
	$(ARTISAN) make:controller

# Lệnh để tạo model mới
make-model:
	$(ARTISAN) make:model

# Lệnh để cài đặt front-end dependencies (npm)
install-npm:
	$(NPM) install

# Lệnh để chạy front-end build (npm run dev)
npm-dev:
	$(NPM) run dev

# Lệnh để build front-end (npm run production)
npm-prod:
	$(NPM) run production

# Lệnh để chạy các tests với PHPUnit
test-phpunit:
	$(PHP) vendor/bin/phpunit

# Lệnh để tạo app key mới
key-generate:
	$(ARTISAN) key:generate
