# Collaboration et Processus de Qualité

## Collaboration

### 1. Utilisation de Git

- **Branches :**

  - Créez une branche pour chaque nouvelle fonctionnalité ou correction de bug.
  - Utilisez le format `issue-[number]-[name]` pour les noms de branches.

Exemple:

```bash
git checkout -b issue-20-my-issue-name-to-the-new-feature
```

- **Commits :**
  Faites des commits fréquents et descriptifs.
  Utilisez le format conventionnel : type(scope): description.

Exemple:

```bash
git commit -m "feat(login): add remember me functionality"
git commit -m "fix(login): correct password encryption issue"
```

### 2. Pull Requests (PR)

- **Création de PR :**

Une fois votre travail terminé, ouvrez une Pull Request vers la branche principale "main".

Exemple:

```bash
git push origin issue-20-my-issue-name-to-the-new-feature
```

Accédez au dépôt sur GitHub et créez une nouvelle PR.

- **Revue de PR :**

Un autre développeur doit examiner votre PR.
Assurez-vous que tous les tests passent et que le code respecte les normes de style avant de fusionner.

## Processus de Qualité

### 1. Tests Unitaires et Fonctionnels

- **Utilisation de PHPUnit :**

Écrivez des tests unitaires pour chaque nouvelle fonctionnalité ou correction de bug.
Écrivez des tests fonctionnels pour vérifier le comportement global de l'application.
Vérifiez le bon fonctionnement des tests.

Exemple:

```bash
php bin/phpunit
```

### 2. Linting et Code Style

- **PHP-CS-Fixer :**

Utilisez PHP-CS-Fixer pour garantir que le code respecte les normes de style de Symfony.

Exemple:

```bash
php-cs-fixer fix
```

- **Intégration Continue (CI) :**

Configurez un outil d'intégration continue (comme GitHub Actions) pour exécuter automatiquement les tests et le linting.

### 3. Documentation

- **Commentaires :**

Commentez votre code pour expliquer les parties complexes ou importantes.
