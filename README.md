KS_Inventory est un projet de gestion de stock développé avec PHP et MySQL. L'application utilise Docker pour faciliter la configuration de l'environnement et inclut un conteneur web (Apache avec PHP), une base de données MySQL, ainsi que PhpMyAdmin pour la gestion de la base de données.
Prérequis

Avant de démarrer, assurez-vous d'avoir installé les éléments suivants sur votre machine :

    Docker
    Docker Compose
    Installation
1. Cloner le dépôt

Clonez ce dépôt GitHub sur votre machine locale :

bash
git clone https://github.com/Hasnae25/KS_Inventory.git
cd KS_Inventory

2. Charger l'image Docker

Si vous avez téléchargé une image Docker de l'application, chargez-la avec la commande suivante :

bash
docker load -i ks_inventory_image.tar

3. Lancer l'application avec Docker Compose

Dans le répertoire racine du projet (là où se trouve le fichier docker-compose.yml), exécutez la commande suivante pour démarrer l'application :

bash
docker-compose up

Cela démarrera les services suivants :

    Web (PHP/Apache) : Disponible sur http://localhost:8080
    Base de données MySQL : MySQL sera exposé sur le port 3307
    PhpMyAdmin : Disponible sur http://localhost:8081

4. Accéder à l'application

    Application KS_Inventory : Accédez à l'interface web en vous rendant sur http://localhost:8080.
   
    PhpMyAdmin : Utilisez http://localhost:8081 pour accéder à l'interface de gestion de la base de données MySQL.



Environnement

Les variables d'environnement suivantes sont définies dans le fichier docker-compose.yml :

yaml

MYSQL_ROOT_PASSWORD: rootpassword  # Mot de passe root MySQL
MYSQL_DATABASE: ks                 # Base de données par défaut
MYSQL_USER: ks_user                # Utilisateur MySQL
MYSQL_PASSWORD: ks_password        # Mot de passe de l'utilisateur

Sauvegarde de la base de données

Pour effectuer une sauvegarde de la base de données, vous pouvez utiliser la commande suivante :

bash

docker exec [nom_du_conteneur_mysql] mysqldump -u root -p ks > backup_ks.sql

Dépendances

Le projet utilise les dépendances suivantes :

    PHP 8.x
    MySQL 5.7
    PhpMyAdmin
    Bootstrap (pour l'interface utilisateur)
    jQuery (pour les fonctionnalités interactives)

