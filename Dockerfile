# Utiliser une image de base
FROM php:8.0-apache

# Copier le code source de l'application dans le conteneur
COPY . /var/www/html/

# Installer les dépendances  nécessaire
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configurer les droits d'accès
RUN chown -R www-data:www-data /var/www/html/


