#!/bin/bash
set -e

# Запускаем MariaDB в фоне для инициализации
service mysql start

# Проверим, инициализирована ли база
if [ ! -d "/var/lib/mysql/init_done" ]; then
    echo "Инициализация базы..."
    mysql -u root < /docker-entrypoint-initdb.d/init.sql
    mkdir /var/lib/mysql/init_done
fi

# Останавливаем фоновый сервис
service mysql stop

# Запускаем основной процесс
exec "$@"
