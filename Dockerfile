# ============================================
# PCTO · Maturità 2026 — immagine app
# PHP 8.3 + Apache, con driver MySQL per PDO
# ============================================
FROM php:8.3-apache

# Driver MySQL per PDO (unica estensione necessaria)
RUN docker-php-ext-install pdo_mysql

# Icone reali delle app di macOS (WhiteSur-icon-theme di vinceliuice, GPL-3.0):
# riproduzione fedele 1:1 delle icone di Big Sur, scaricate alla build
# e servite in locale da assets/icons/ (così funzionano anche offline)
ARG ICONS=https://raw.githubusercontent.com/vinceliuice/WhiteSur-icon-theme/3cc051a4709e67921a9d47cd2a3e0111bbe5e2bd
RUN mkdir -p /var/www/html/assets/icons && cd /var/www/html/assets/icons \
 && curl -fsSL -o finder.svg   "$ICONS/original/file-manager.svg" \
 && curl -fsSL -o contacts.svg "$ICONS/src/apps/scalable/addressbook.svg" \
 && curl -fsSL -o calendar.svg "$ICONS/original/calendar.svg" \
 && curl -fsSL -o appstore.svg "$ICONS/src/apps/scalable/software-store.svg" \
 && curl -fsSL -o terminal.svg "$ICONS/src/apps/scalable/terminal.svg" \
 && curl -fsSL -o safari.svg   "$ICONS/src/apps/scalable/safari.svg" \
 && curl -fsSL -o maps.svg     "$ICONS/original/gnome-maps.svg" \
 && curl -fsSL -o preview.svg  "$ICONS/src/apps/scalable/accessories-document-viewer.svg" \
 && curl -fsSL -o trash.svg    "$ICONS/src/places/scalable/user-trash.svg"

# Copia il sito (login + desktop) e gli asset locali (cursori macOS)
COPY index.php login.php logout.php hub.php hub.css hub.js /var/www/html/
COPY assets/ /var/www/html/assets/

EXPOSE 80
