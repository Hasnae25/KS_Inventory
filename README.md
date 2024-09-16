# KS_Inventory

KS_Inventory est un projet de gestion de stock développé avec PHP et MySQL, qui utilise Docker pour simplifier la configuration de l'environnement. Ce projet inclut un conteneur web, une base de données MySQL et phpMyAdmin pour la gestion de la base de données.

## Prérequis

Assurez-vous d'avoir les éléments suivants installés sur votre machine avant de démarrer :

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Installation

### 1. Cloner le dépôt

Commencez par cloner ce dépôt GitHub sur votre machine locale :

```bash
git clone https://github.com/Hasnae25/KS_Inventory.git
cd KS_Inventory

## 2. Configuration

Assurez-vous que Docker et Docker Compose sont installés sur votre machine. Le fichier `docker-compose.yml` est déjà configuré pour inclure les services nécessaires à l'exécution de l'application, tels que le serveur web, la base de données MySQL et phpMyAdmin.

### 3. Démarrage des services

Après avoir cloné le dépôt, exécutez la commande suivante pour démarrer les services Docker :

```bash
docker-compose up --build

