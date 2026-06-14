# PCTO · Maturità 2026 — immagine app
FROM php:8.3-apache

# Estensione PDO per MySQL
RUN docker-php-ext-install pdo_mysql

# Build offline-friendly: tutte le icone usate sono vendorizzate in assets/icons-b64.
RUN mkdir -p /var/www/html/assets/icons

# File dell'app
COPY index.php login.php logout.php hub.php macos-system.css login.js hub.js hub-extras.js sound.js /var/www/html/
COPY assets/ /var/www/html/assets/

# Decodifica le icone ufficiali macOS (vendorizzate come base64 nel repo)
RUN set -eux; \
    for f in /var/www/html/assets/icons-b64/*.b64; do \
      n="$(basename "$f" .b64)"; \
      base64 -d "$f" > "/var/www/html/assets/icons/$n"; \
    done; \
    rm -rf /var/www/html/assets/icons-b64

# Font SF Pro Display: file .otf caricati direttamente in assets/fonts/
# (pulizia di eventuali residui base64 di vecchi commit)
RUN rm -rf /var/www/html/assets/fonts-b64

EXPOSE 80
