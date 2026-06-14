# PCTO · Maturità 2026 — immagine app
FROM php:8.3-apache

# Estensione PDO per MySQL
RUN docker-php-ext-install pdo_mysql

# File dell'app — CSS/JS modulari, senza inline style/script nei PHP
COPY index.php login.php logout.php hub.php /var/www/html/
COPY macos-system.css login.js hub.js sound.js /var/www/html/
COPY assets/ /var/www/html/assets/

EXPOSE 80
