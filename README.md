# Task Manager - Application Symfony

## Description

Task Manager est une application web construite avec Symfony permettant de gérer des tâches. L'application permet la création, modification, suppression et la recherche de tâches. Elle expose une API RESTful pour interagir avec les données via des requêtes HTTP.

## Prérequis

Avant de pouvoir exécuter l'application, vous devez avoir les prérequis suivants installés sur votre machine :

- **PHP 8.0** ou supérieur
- **Composer** (outil de gestion des dépendances PHP)
- **PostgreSQL** pour la gestion de la base de données

## Installation

### 1. Cloner le projet

Commencez par cloner ce projet dans votre répertoire local :

```bash
git clone https://github.com/username/task-manager.git
cd task-manager

2. Installer les dépendances avec Composer
Exécutez la commande suivante pour installer toutes les dépendances nécessaires au bon fonctionnement de l'application :

bash
composer install

3. Configurer la base de données
Modifiez le fichier .env ou .env.local pour configurer votre connexion à la base de données PostgreSQL :

dotenv
DATABASE_URL="pgsql://username:password@127.0.0.1:5432/task_manager?serverVersion=13&charset=utf8"
Remplacez username et password par vos informations de connexion PostgreSQL.

Ensuite, exécutez les commandes suivantes pour créer les tables de la base de données :

bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

4. Lancer l'application
Après l'installation des dépendances et la configuration de la base de données, vous pouvez démarrer l'application avec la commande suivante :

bash
php bin/console server:run
L'application sera disponible à l'adresse http://127.0.0.1:8000.

Exécution des tests
L'application utilise PHPUnit pour les tests unitaires et fonctionnels. Voici comment exécuter les tests.

1. Installer les dépendances de test
Si vous ne l'avez pas déjà fait, installez les dépendances nécessaires pour les tests avec Composer :

bash
composer require --dev symfony/test-pack
composer require --dev phpunit/phpunit

2. Exécuter les tests
Pour exécuter l'ensemble des tests (tests unitaires et fonctionnels), utilisez la commande suivante :

bash
php bin/phpunit

Si vous souhaitez exécuter un test spécifique, vous pouvez préciser son fichier comme suit :

bash
php bin/phpunit tests/TaskTest.php

Cela lancera uniquement les tests définis dans TaskTest.php.

Explication des choix techniques
1. Symfony Framework
Symfony a été choisi pour sa robustesse, sa flexibilité et ses fonctionnalités intégrées. Il fournit un système de routage très puissant, un moteur de templating (Twig), ainsi qu'un ORM (Doctrine) qui facilite la gestion des bases de données. Symfony permet également de structurer l'application de manière modulaire, rendant le code plus maintenable.

2. Architecture MVC
Le modèle MVC (Modèle-Vue-Contrôleur) est utilisé pour séparer clairement la logique métier, la présentation et les interactions avec l'utilisateur. Ce choix rend l'application extensible et facilite la gestion des différentes couches de l'application.

Modèle (Entity) : Les entités représentent les données de l'application (par exemple, une tâche dans notre cas).
Vue (API) : La vue est une API RESTful qui permet de communiquer avec le front-end.
Contrôleur : Les contrôleurs traitent la logique métier et la gestion des requêtes HTTP.

3. Tests avec PHPUnit
Les tests unitaires et fonctionnels sont effectués à l'aide de PHPUnit. Cela garantit que les différentes parties de l'application fonctionnent comme prévu et permet de vérifier la stabilité du code. Les tests sont écrits pour vérifier la création, la mise à jour, la suppression, ainsi que la recherche des tâches.

4. Gestion des erreurs
Les erreurs sont gérées de manière centralisée dans les contrôleurs. Si une tâche n'est pas trouvée lors d'une mise à jour ou d'une suppression, une réponse JSON explicite est renvoyée avec un code d'état approprié. Cela permet d'améliorer l'expérience utilisateur en fournissant des informations claires en cas de problème.

### Instructions à suivre :

1. **Personnalisez le lien GitHub** : Remplacez `https://github.com/username/task-manager.git` par l'URL de votre propre repository GitHub.
2. **Configurer les détails de votre base de données** : Assurez-vous de mettre à jour le lien de base de données dans le fichier `.env`.

Ce fichier `README.md` vous fournira toutes les informations nécessaires pour exécuter et tester l'application.
