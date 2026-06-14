# PCTO · Maturità 2026 — immagine app
FROM php:8.3-apache

# Estensione PDO per MySQL
RUN docker-php-ext-install pdo_mysql

# Icona Terminal (WhiteSur — non presente nel set ufficiale fornito)
ARG ICONS=https://raw.githubusercontent.com/vinceliuice/WhiteSur-icon-theme/3cc051a4709e67921a9d47cd2a3e0111bbe5e2bd
RUN mkdir -p /var/www/html/assets/icons && \
    curl -fsSL "$ICONS/src/apps/scalable/terminal.svg" -o /var/www/html/assets/icons/terminal.svg

# File dell'app
COPY index.php login.php logout.php hub.php macos.css login.js hub.js sound.js /var/www/html/
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
