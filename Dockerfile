FROM php:7.4-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copier les fichiers de l'application
COPY . /var/www/html/

# Configurer les droits pour les fichiers copiés (facultatif, ajustez selon vos besoins)
RUN chown -R www-data:www-data /var/www/html

