FROM debian:jessie

# Используем архивные зеркала Debian
RUN echo "deb http://archive.debian.org/debian/ jessie main" > /etc/apt/sources.list
RUN echo "deb http://archive.debian.org/debian-security/ jessie/updates main" >> /etc/apt/sources.list

# Отключаем интерактивные запросы
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --force-yes \
    mysql-server \
    procps \
    mc \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Копируем init.sql (если он есть)
COPY init.sql /docker-entrypoint-initdb.d/init.sql

RUN sed -i 's/^bind-address/#bind-address/' /etc/mysql/my.cnf

# Создаём скрипт-энтрипоинт
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 3306

VOLUME /var/lib/mysql

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["mysqld_safe"]