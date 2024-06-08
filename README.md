# PROJET 7 : Créez un web service exposant une API

**Date de création** : 7 juin 2024
**Date de la dernière modification** : 7 juin 2024
**Auteur** : Alban VOIRIOT
**Informations techniques** :

- **Technologies** : PHP, Symfony, SQL, MySQL, API REST
- **Version de Symfony** : 6.4.6
- **Version de PHP** : 8.3.7
- **Version de MySQL** : 5.7.11
- **API Plateforme utilisée** : Postman

## Sommaire

- [Contexte](#contexte)
- [Installation](#installation)
  - [Télécharger le projet](#télécharger-le-projet)
  - [Configurer le fichier .env](#configurer-le-fichier-env)
  - [Dossier upload](#dossier-upload)
- [Guide d'utilisation](#guide-dutilisation)
  - [Documentation API](#documentation-api)

## Contexte

Ce projet a été conçu dans le cadre de ma formation de développeur d'applications PHP/Symfony (OpenClassrooms) sur la création d'une API REST afin de permettre à des clients de pouvoir consulter la liste des produits (téléphones mobiles de l'entreprise BileMo) ainsi que d'ajouter leurs propres utilisateurs.

## Installation

### Télécharger le projet

=> Pour télécharger le dossier, veuillez effectuer la commande GIT : `git clone https://github.com/Alban2001/ProjetAPI.git`

=> Dans le terminal de Symfony, effectuer les commandes : `cd ProjetAPI` puis `composer install` afin de pouvoir installer les fichiers manquants de composer et mettre à jour. Un message d'erreur va apparaître, car dans le fichier .env, il manque la variable **_DATABASE_URL_**.

### Configurer le fichier .env

- => Remplissez la variable **_DATABASE_URL_** afin de permettre au projet de communiquer avec la base de données.
  Exemple :

```
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```

Afin d'ajouter la base de données du projet dans votre SGBDR, veuillez simplement lancer les commandes dans le terminal de Symfony : `php bin/console make:migration` puis `php bin/console doctrine:migrations:migrate`.

- Afin d'ajouter des insertions automatique de 20 produits, lancez la commande : `php bin/console doctrine:fixtures:load`. Lors de ces insertions, 2 clients ont été créé aussi et le **_Client 01_** est le client par défaut dans la documentation API lors de l'authentification.

- Dans le même fichier, vous allez vous apercevoir qu'il vous manque la valeur pour la variable **_JWT_PASSPHRASE_**. Pour obtenir votre propre mot de passe, vous devez lancer la commande : `php bin/console lexik:jwt:generate-keypair` afin de générer des clés puis obtenir votre mot de passe PASSPHRASE.

## Guide d'utilisation

### Documentation API

Afin de pouvoir tester l'API, cliquer sur le lien afin de pouvoir y accéder : https://127.0.0.1:8000/api/doc 
