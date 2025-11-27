# LevelUp 🚀

Plateforme de gamification et de défis de bienveillance communautaires.

Ce projet est développé avec **Symfony 7** et **PostgreSQL**. L'environnement de développement est entièrement conteneurisé avec **Docker**.

---

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

-   [Docker Desktop](https://www.docker.com/products/docker-desktop) (Mac/Windows/Linux)
-   [Git](https://git-scm.com/)

---

## 🛠 Installation en 5 Étapes

### 1. Cloner le projet

```bash
git clone git@github.com:YounessFTN/LevelUp.git
cd LevelUp
```

### 2. Lancer les conteneurs Docker

Cette commande construit et démarre PHP, PostgreSQL et le serveur web.

```bash
docker compose up -d --build
```

### 3. Installer les dépendances PHP

```bash
docker compose exec php composer install
```

### 4. Créer la base de données

```bash
docker compose exec php bin/console doctrine:database:create
docker compose exec php bin/console doctrine:migrations:migrate
```

> Tapez `yes` si une confirmation est demandée.

### 5. Créer ton compte administrateur

**Option A : Compte personnalisé (Recommandé)**

1. Va sur [http://localhost:8000/register](http://localhost:8000/register) et inscris-toi
2. Promouvoir ton compte en admin (remplace l'email) :

```bash
docker compose exec php bin/console dbal:run-sql "UPDATE \"user\" SET roles = '[\"ROLE_ADMIN\"]' WHERE email = 'ton-email@example.com'"
```

3. Déconnecte-toi puis reconnecte-toi : [http://localhost:8000/login](http://localhost:8000/login)

**Option B : Comptes de test (Rapide)**

```bash
docker compose exec php bin/console doctrine:fixtures:load
```

Comptes créés :

-   Admin : `admin@example.com` / `admin123`
-   User : `user@example.com` / `user123`

⚠️ **Supprime toutes les données existantes**

---

## 🌍 Accéder à l'application

-   **Accueil** : [http://localhost:8000](http://localhost:8000)
-   **Inscription** : [http://localhost:8000/register](http://localhost:8000/register)
-   **Connexion** : [http://localhost:8000/login](http://localhost:8000/login)
-   **Admin** : [http://localhost:8000/admin](http://localhost:8000/admin) (nécessite ROLE_ADMIN)

---

## 💻 Commandes Utiles

Toutes les commandes Symfony doivent être préfixées par `docker compose exec php`.

### Base de données

```bash
# Créer une entité
docker compose exec php bin/console make:entity

# Créer une migration
docker compose exec php bin/console make:migration

# Appliquer les migrations
docker compose exec php bin/console doctrine:migrations:migrate

# Vérifier le schéma
docker compose exec php bin/console doctrine:schema:validate
```

### Utilisateurs

```bash
# Promouvoir un utilisateur en admin
docker compose exec php bin/console dbal:run-sql "UPDATE \"user\" SET roles = '[\"ROLE_ADMIN\"]' WHERE email = 'email@example.com'"

# Lister tous les utilisateurs
docker compose exec php bin/console dbal:run-sql 'SELECT id, username, email, roles FROM "user"'

# Charger les fixtures (utilisateurs de test)
docker compose exec php bin/console doctrine:fixtures:load
```

### Développement

```bash
# Voir toutes les routes
docker compose exec php bin/console debug:router

# Voir les routes admin
docker compose exec php bin/console debug:router | grep admin

# Nettoyer le cache
docker compose exec php bin/console cache:clear

# Créer un contrôleur
docker compose exec php bin/console make:controller
```

---

## 🔒 Système de Sécurité

### Rôles disponibles

-   **ROLE_USER** : Utilisateur standard (attribué automatiquement à l'inscription)
-   **ROLE_ADMIN** : Administrateur (accès à `/admin`)

### Hiérarchie

```
ROLE_ADMIN → hérite de ROLE_USER
```

Un admin a automatiquement tous les droits d'un utilisateur standard.

### Protection des routes

| Route                 | Accès      |
| --------------------- | ---------- |
| `/login`, `/register` | Public     |
| `/profile`            | ROLE_USER  |
| `/admin`              | ROLE_ADMIN |

---

## 👑 Espace Administration

Accessible sur [http://localhost:8000/admin](http://localhost:8000/admin) (nécessite ROLE_ADMIN)

**Fonctionnalités :**

-   📊 Tableau de bord avec statistiques
-   👥 Liste de tous les utilisateurs
-   ⬆️ Promouvoir un utilisateur en admin
-   ⬇️ Rétrograder un admin
-   🗑️ Supprimer un utilisateur

---

## ❓ Dépannage

### Erreur "Access Denied" sur /admin

Vérifier les rôles de l'utilisateur :

```bash
docker compose exec php bin/console dbal:run-sql 'SELECT username, email, roles FROM "user"'
```

Si ton utilisateur n'a pas ROLE_ADMIN, promouvoir le compte :

```bash
docker compose exec php bin/console dbal:run-sql "UPDATE \"user\" SET roles = '[\"ROLE_ADMIN\"]' WHERE email = 'ton-email@example.com'"
```

Puis déconnecte-toi et reconnecte-toi.

### Erreur de permissions

```bash
sudo chmod -R 777 var/
```

### Base de données corrompue

```bash
docker compose exec php bin/console doctrine:database:drop --force
docker compose exec php bin/console doctrine:database:create
docker compose exec php bin/console doctrine:migrations:migrate
docker compose exec php bin/console doctrine:fixtures:load
```

### Conteneurs qui ne démarrent pas

```bash
docker compose logs -f
docker compose down
docker compose up -d --build
```

---

## 🛑 Arrêter le projet

```bash
docker compose down
```

## 🔄 Redémarrer le projet

```bash
docker compose up -d
```

---

## 📁 Structure du Projet

```
LevelUp/
├── config/packages/
│   └── security.yaml           # Configuration sécurité
├── migrations/                 # Migrations BDD
├── src/
│   ├── Controller/
│   │   ├── AdminController.php        # Espace admin
│   │   ├── RegistrationController.php
│   │   └── SecurityController.php
│   ├── DataFixtures/
│   │   └── UserFixtures.php    # Données de test
│   ├── Entity/
│   │   └── User.php            # Entité utilisateur
│   ├── Form/
│   │   └── RegistrationFormType.php
│   ├── Repository/
│   │   └── UserRepository.php
│   └── Security/
│       └── LoginAuthenticator.php
├── templates/
│   ├── admin/                  # Vues admin
│   ├── registration/
│   └── security/
└── .env                        # Configuration
```

---

## 📞 Support

-   **GitHub Issues** : [Créer une issue](https://github.com/YounessFTN/LevelUp/issues)
-   **Email** : youness.fatine1@gmail.com

---

## 🙏 Technologies

-   [Symfony 7](https://symfony.com/)
-   [Doctrine ORM](https://www.doctrine-project.org/)
-   [PostgreSQL](https://www.postgresql.org/)
-   [Bootstrap 5](https://getbootstrap.com/)
-   [Docker](https://www.docker.com/)

---

_Dernière mise à jour : Novembre 2024_
