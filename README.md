# ToDo & Co

ToDo & Co est une application de gestion des tâches quotidiennes. Initialement développée en tant que Minimum Viable Product (MVP) pour attirer des investisseurs, elle entre maintenant dans une phase d'amélioration de la qualité et de développement de nouvelles fonctionnalités.

## Fonctionnalités principales

- Gestion des tâches
- Authentification des utilisateurs
- Attribution de rôles aux utilisateurs (ROLE_USER, ROLE_ADMIN)
- Autorisations spécifiques pour l'accès et la gestion des tâches et des utilisateurs
- Tests automatisés avec PHPUnit

## Améliorations prévues

### Corrections d'anomalies

- Attachement des tâches à un utilisateur authentifié lors de leur création
- Attribution des tâches existantes à un utilisateur "anonyme"
- Prévention de la modification de l'auteur d'une tâche

### Nouvelles fonctionnalités

- Sélection de rôles lors de la création et la modification d'un utilisateur
- Accès aux pages de gestion des utilisateurs restreint aux administrateurs
- Suppression des tâches par leur créateur ou par les administrateurs pour les tâches anonymes

### Tests automatisés

- Implémentation de tests unitaires et fonctionnels avec PHPUnit
- Couverture de code supérieure à 70%

### Documentation technique

- Documentation sur l'implémentation de l'authentification
- Processus de collaboration pour les développeurs
- Audit de la qualité du code et de la performance de l'application

## Prérequis

- PHP 8.3.6
- Composer
- Symfony 7.1

## Installation

Pour cloner et utiliser ce projet, suivez ces étapes :

1. Clonez le dépôt GitHub :

```bash
git clone https://github.com/ThibDel8/Projet_8.git
cd Projet_8
```

2. Installez les dépendances PHP avec Composer :

```bash
composer install
```

3. Configurez les variables d'environnement :

Copiez le fichier .env et renommez-le en .env.local, puis modifiez les valeurs selon votre configuration

4. Créez la base de données et exécutez les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Chargez les fixtures (données de test) :

```bash
php bin/console doctrine:fixtures:load
```

6. Lancez le serveur de développement Symfony :

```bash
symfony serve
```

L'application est maintenant accessible sur http://localhost:8000.

7. Tests
   Pour exécuter les tests unitaires et fonctionnels :

```bash
php bin/phpunit
```
