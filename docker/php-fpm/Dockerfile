FROM debian:stretch-20210721

# Используем архивные зеркала Debian
RUN echo "deb http://archive.debian.org/debian/ stretch main" > /etc/apt/sources.list
RUN echo "deb http://archive.debian.org/debian-security/ stretch/updates main" >> /etc/apt/sources.list

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    apt-transport-https \
    lsb-release \
    ca-certificates \
    wget \
    curl \
    git \
    mc \
    unzip \
    zlib1g-dev \
    libzip-dev \
    build-essential

# Устанавливаем PHP 7.0
RUN apt-get update && apt-get install -y \
    php7.0-fpm \
    php7.0-mysql \
    php7.0-cli \
    php7.0-curl \
    php7.0-gd \
    php7.0-json \
    php7.0-mbstring \
    php7.0-xml \
    php7.0-zip \
    php-pear \
    php7.0-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ставим Xdebug
RUN pecl install xdebug-2.7.2
# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

# Создаем необходимые директории
RUN mkdir -p /run/php /var/log/php
RUN chown -R www-data:www-data /run/php /var/log/php

# Простая настройка PHP-FPM без использования PID файла
RUN echo "[global]" > /etc/php/7.0/fpm/php-fpm.conf
RUN echo "daemonize = no" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "error_log = /proc/self/fd/2" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "[www]" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "user = www-data" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "group = www-data" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "listen = 9000" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo ";listen.allowed_clients = 0.0.0.0" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "pm = dynamic" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "pm.max_children = 5" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "pm.start_servers = 2" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "pm.min_spare_servers = 1" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "pm.max_spare_servers = 3" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "access.log = /var/log/php/access.log" >> /etc/php/7.0/fpm/php-fpm.conf
RUN echo "catch_workers_output = yes" >> /etc/php/7.0/fpm/php-fpm.conf
# Убираем access.log чтобы избежать ошибок
RUN echo ";access.log = /var/log/php/access.log" >> /etc/php/7.0/fpm/php-fpm.conf

# создаём конфиг Xdebug в mods-available
RUN echo "zend_extension=/usr/lib/php/20151012/xdebug.so" > /etc/php/7.0/mods-available/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php/7.0/mods-available/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php/7.0/mods-available/xdebug.ini \
    && echo "xdebug.remote_host=host.docker.internal" >> /etc/php/7.0/mods-available/xdebug.ini \
    && echo "xdebug.remote_port=9003" >> /etc/php/7.0/mods-available/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /etc/php/7.0/mods-available/xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /etc/php/7.0/mods-available/xdebug.ini

# активируем модуль
RUN phpenmod xdebug

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm7.0", "-F"]