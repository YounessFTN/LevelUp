# LevelUp 🚀 - Guide d'installation rapide

Plateforme de gamification et de défis de bienveillance communautaires.

---

## 📋 Prérequis

-   [Docker Desktop](https://www.docker.com/products/docker-desktop)
-   [Git](https://git-scm.com/)

---

## 🚀 Installation rapide en 7 commandes

### 1️⃣ Cloner le projet

```bash
git clone git@github.com:YounessFTN/LevelUp.git
cd LevelUp
```

### 2️⃣ Démarrer l'environnement Docker

```bash
docker compose up -d --build
```

Construit et lance les conteneurs (PHP, PostgreSQL, Nginx).

### 3️⃣ Installer les dépendances PHP

```bash
docker compose exec php composer install
```

### 4️⃣ Créer la base de données

```bash
docker compose exec php bin/console doctrine:database:create
```

### 5️⃣ Appliquer les migrations (création des tables)

```bash
docker compose exec php bin/console doctrine:migrations:migrate
```

Répondre yes si demandé.

### 6️⃣ Charger les jeux de données (fixtures)

```bash
docker compose exec php bin/console doctrine:fixtures:load
```

Répondre yes.

### 7️⃣ Vérifier les utilisateurs insérés

```bash
docker compose exec php bin/console dbal:run-sql 'SELECT id, username, email, roles FROM "user"'
```

Résultat attendu :

```
| id | username   | email                             | roles            |
|----|------------|-----------------------------------|------------------|
| 1  | youness    | youness.fatine1@gmail.com         | ["ROLE_ADMIN"]   |
| 2  | zakariya   | zakariya.belkassem@next-u.fr      | ["ROLE_ADMIN"]   |
| 3  | Frédéric   | frederic@gmail.com                | ["ROLE_USER"]    |
```

---

## 🌐 Accès à l'application

-   Accueil : http://localhost:8000
-   Connexion : http://localhost:8000/login
-   Inscription : http://localhost:8000/register
-   Administration : http://localhost:8000/admin

Comptes de test :

-   Admin : admin@example.com / admin123
-   User : user@example.com / user123

---

## 🔐 Créer un compte administrateur (alternative)

1. Inscription via http://localhost:8000/register
2. Promotion en administrateur :

```bash
docker compose exec php bin/console dbal:run-sql "UPDATE \"user\" SET roles='[\"ROLE_ADMIN\"]' WHERE email='ton-email@example.com'"
```

3. Déconnexion puis reconnexion (login), accès à /admin.

---

## 🛑 Commandes utiles

Arrêter :

```bash
docker compose down
```

Redémarrer :

```bash
docker compose up -d
```

Logs PHP :

```bash
docker compose logs -f php
```

Réinitialiser base :

```bash
docker compose exec php bin/console doctrine:database:drop --force
docker compose exec php bin/console doctrine:database:create
docker compose exec php bin/console doctrine:migrations:migrate
docker compose exec php bin/console doctrine:fixtures:load
```

---

## ❓ Problèmes courants

Accès refusé /admin :

```bash
docker compose exec php bin/console dbal:run-sql 'SELECT username, email, roles FROM "user"'
```

Vérifier présence de ROLE_ADMIN.

Permissions (Mac/Linux) :

```bash
sudo chmod -R 777 var/
```

(À ajuster selon besoins réels.)

Conteneurs bloqués :

```bash
docker compose down
docker compose up -d --build
```

---

## 📞 Support

-   GitHub : https://github.com/YounessFTN/LevelUp
-   Email : youness.fatine1@gmail.com

---

## 🎉 Fin

Application prête. Bon développement. 🚀

_Dernière mise à jour : Novembre 2024_
