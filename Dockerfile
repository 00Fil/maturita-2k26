# ============================================
# PCTO · Maturità 2026 — immagine app
# PHP 8.3 + Apache, con driver MySQL per PDO
# ============================================
FROM php:8.3-apache

# Driver MySQL per PDO (unica estensione necessaria)
RUN docker-php-ext-install pdo_mysql

# Copia il sito (login + desktop)
COPY index.php login.php logout.php hub.php hub.css hub.js /var/www/html/

EXPOSE 80
