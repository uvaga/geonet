CREATE DATABASE IF NOT EXISTS geonet_db CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'geonet_user'@'%' IDENTIFIED BY 'geonet_password';
GRANT ALL PRIVILEGES ON geonet_db.* TO 'geonet_user'@'%' IDENTIFIED BY 'geonet_password';
FLUSH PRIVILEGES;