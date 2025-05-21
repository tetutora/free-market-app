.PHONY: create_dirs up down restart logs ps

create_dirs:
	# 必要なディレクトリを作成
	mkdir -p docker/mysql/data
	mkdir -p docker/nginx
	mkdir -p docker/php
	mkdir -p src

	# 必要なファイルを作成
	touch docker-compose.yml
	touch docker/mysql/my.cnf
	touch docker/nginx/default.conf
	touch docker/php/Dockerfile
	touch docker/php/php.ini

	# docker-compose.yml の内容を追加
	echo "services:" >> docker-compose.yml
	echo "    nginx:" >> docker-compose.yml
	echo "        image: nginx:1.21.1" >> docker-compose.yml
	echo "        ports:" >> docker-compose.yml
	echo "            - \"80:80\"" >> docker-compose.yml
	echo "        volumes:" >> docker-compose.yml
	echo "            - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf" >> docker-compose.yml
	echo "            - ./src:/var/www/" >> docker-compose.yml
	echo "        depends_on:" >> docker-compose.yml
	echo "            - php" >> docker-compose.yml
	echo "" >> docker-compose.yml
	echo "    php:" >> docker-compose.yml
	echo "        build: ./docker/php" >> docker-compose.yml
	echo "        volumes:" >> docker-compose.yml
	echo "            - ./src:/var/www/" >> docker-compose.yml
	echo "" >> docker-compose.yml
	echo "    mysql:" >> docker-compose.yml
	echo "        image: mysql:8.0.26" >> docker-compose.yml
	echo "        platform: linux/amd64" >> docker-compose.yml
	echo "        environment:" >> docker-compose.yml
	echo "            MYSQL_ROOT_PASSWORD: root" >> docker-compose.yml
	echo "            MYSQL_DATABASE: laravel_db" >> docker-compose.yml
	echo "            MYSQL_USER: laravel_user" >> docker-compose.yml
	echo "            MYSQL_PASSWORD: laravel_pass" >> docker-compose.yml
	echo "        command:" >> docker-compose.yml
	echo "            mysqld --default-authentication-plugin=mysql_native_password" >> docker-compose.yml
	echo "        volumes:" >> docker-compose.yml
	echo "            - ./docker/mysql/data:/var/lib/mysql" >> docker-compose.yml
	echo "            - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf" >> docker-compose.yml
	echo "" >> docker-compose.yml
	echo "    phpmyadmin:" >> docker-compose.yml
	echo "        image: phpmyadmin/phpmyadmin:5.2.1" >> docker-compose.yml
	echo "        platform: linux/amd64" >> docker-compose.yml
	echo "        environment:" >> docker-compose.yml
	echo "            - PMA_ARBITRARY=1" >> docker-compose.yml
	echo "            - PMA_HOST=mysql" >> docker-compose.yml
	echo "            - PMA_USER=laravel_user" >> docker-compose.yml
	echo "            - PMA_PASSWORD=laravel_pass" >> docker-compose.yml
	echo "        depends_on:" >> docker-compose.yml
	echo "            - mysql" >> docker-compose.yml
	echo "        ports:" >> docker-compose.yml
	echo "            - 8080:80" >> docker-compose.yml

	# MySQL設定ファイル（my.cnf）の内容を追加
	echo "[mysqld]" > docker/mysql/my.cnf
	echo "character-set-server = utf8mb4" >> docker/mysql/my.cnf
	echo "collation-server = utf8mb4_unicode_ci" >> docker/mysql/my.cnf
	echo "default-time-zone = 'Asia/Tokyo'" >> docker/mysql/my.cnf

	# Nginxの設定ファイル（default.conf）の内容を追加
	echo "server {" > docker/nginx/default.conf
	echo "    listen 80;" >> docker/nginx/default.conf
	echo "    index index.php index.html;" >> docker/nginx/default.conf
	echo "    server_name localhost;" >> docker/nginx/default.conf
	echo "    root /var/www/public;" >> docker/nginx/default.conf
	echo "" >> docker/nginx/default.conf
	echo "    location / {" >> docker/nginx/default.conf
	echo "        try_files $$uri $$uri/ /index.php$$is_args$$args;" >> docker/nginx/default.conf
	echo "    }" >> docker/nginx/default.conf
	echo "" >> docker/nginx/default.conf
	echo "    location ~ \.php$$ {" >> docker/nginx/default.conf
	echo "        fastcgi_split_path_info ^(.+\.php)(/.+)$$;" >> docker/nginx/default.conf
	echo "        fastcgi_pass php:9000;" >> docker/nginx/default.conf
	echo "        fastcgi_index index.php;" >> docker/nginx/default.conf
	echo "        include fastcgi_params;" >> docker/nginx/default.conf
	echo "        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;" >> docker/nginx/default.conf
	echo "        fastcgi_param PATH_INFO $fastcgi_path_info;" >> docker/nginx/default.conf
	echo "    }" >> docker/nginx/default.conf
	echo "}" >> docker/nginx/default.conf

	# PHPのDockerfileを作成
	echo "FROM php:8.2-fpm" > docker/php/Dockerfile
	echo "" >> docker/php/Dockerfile
	echo "COPY php.ini /usr/local/etc/php/" >> docker/php/Dockerfile
	echo "" >> docker/php/Dockerfile
	echo "RUN apt update \\" >> docker/php/Dockerfile
	echo "    && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \\" >> docker/php/Dockerfile
	echo "    && docker-php-ext-install pdo_mysql zip" >> docker/php/Dockerfile
	echo "" >> docker/php/Dockerfile
	echo "RUN curl -sS https://getcomposer.org/installer | php \\" >> docker/php/Dockerfile
	echo "    && mv composer.phar /usr/local/bin/composer \\" >> docker/php/Dockerfile
	echo "    && composer self-update" >> docker/php/Dockerfile
	echo "" >> docker/php/Dockerfile
	echo "WORKDIR /var/www" >> docker/php/Dockerfile

	# PHPの設定ファイル（php.ini）の内容を追加
	echo "date.timezone = \"Asia/Tokyo\"" > docker/php/php.ini
	echo "" >> docker/php/php.ini
	echo "[mbstring]" >> docker/php/php.ini
	echo "mbstring.language = \"Japanese\"" >> docker/php/php.ini

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d

logs:
	docker-compose logs -f

ps:
	docker-compose ps