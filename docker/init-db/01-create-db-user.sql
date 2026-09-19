CREATE DATABASE IF NOT EXISTS anayoga_anayoga CHARACTER SET utf8mb4;
CREATE USER IF NOT EXISTS 'anayoga_catalog'@'%' IDENTIFIED BY 'acc3ss';
GRANT ALL PRIVILEGES ON anayoga_anayoga.* TO 'anayoga_catalog'@'%';
FLUSH PRIVILEGES;
