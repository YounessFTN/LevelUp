Voici un fichier `README.md` complet, professionnel et adapté à ton projet Symfony **LevelUp**.

Il est conçu pour qu'un développeur (ou toi-même dans 6 mois) puisse installer le projet en **3 commandes** grâce à Docker.

Copie le contenu ci-dessous dans un fichier nommé `README.md` à la racine de ton projet.

---

````markdown
# LevelUp 🚀

Plateforme de gamification et de défis communautaires.

Ce projet est développé avec **Symfony 7** et **PostgreSQL**. L'environnement de développement est entièrement conteneurisé avec **Docker**.

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

-   [Docker Desktop](https://www.docker.com/products/docker-desktop) (Mac/Windows/Linux)
-   [Git](https://git-scm.com/)

## 🛠 Installation Rapide

1.  **Cloner le projet**

    ```bash
    git clone git@github.com:YounessFTN/LevelUp.git
    cd LevelUp
    ```

2.  **Lancer les conteneurs Docker**
    Cette commande va construire les images (PHP, Base de données, Serveur Web) et les lancer en arrière-plan.

    ```bash
    docker compose up -d --build
    ```

3.  **Installer les dépendances PHP**
    Nous exécutons Composer _à l'intérieur_ du conteneur PHP pour éviter les soucis de version locale.

    ```bash
    docker compose exec php composer install
    ```

4.  **Préparer la Base de Données**
    Création de la BDD et exécution des migrations (création des tables Users, Challenges, etc.).

    ```bash
    # Créer la base de données
    docker compose exec php bin/console doctrine:database:create

    # Jouer les migrations (création des tables)
    docker compose exec php bin/console doctrine:migrations:migrate
    ```

    > **Note :** Si on vous demande confirmation, tapez `yes`.

---

## 🌍 Accéder au projet

Une fois l'installation terminée :

-   **Application Web :** [http://localhost:8000](http://localhost:8000) (ou le port défini dans ton docker-compose)
-   **Base de données :** Accessible sur le port `5432` (User: `app`, Password: `!ChangeMe!`, DB: `app` - _vérifier le `.env`_)

---

## 💻 Commandes Utiles (Quotidien)

Comme nous utilisons Docker, toutes les commandes Symfony (`bin/console`) doivent être préfixées par `docker compose exec php`.

### Créer une nouvelle Entité / Table

```bash
docker compose exec php bin/console make:entity
```
````

### Créer une Migration (après modification d'entité)

```bash
docker compose exec php bin/console make:migration
```

### Appliquer les changements en Base de Données

```bash
docker compose exec php bin/console doctrine:migrations:migrate
```

### Voir les routes disponibles

```bash
docker compose exec php bin/console debug:router
```

---

## ❓ Dépannage

**Erreur de permissions sur les dossiers ?**
Si vous ne pouvez pas écrire dans les dossiers `var/cache` ou `var/log` :

```bash
sudo chmod -R 777 var/
```

**Arrêter le projet proprement**

```bash
docker compose down
```

````

---

### Petit Bonus : Configuration Docker (Si tu ne l'as pas encore)

Si tu n'as pas encore de fichier `compose.yaml` (ou `docker-compose.yml`) et `Dockerfile` car tu viens de commencer, Symfony peut les générer pour toi automatiquement avec une configuration "PostgreSQL ready".

Si c'est le cas, lance simplement ceci dans ton terminal (hors docker) :

```bash
composer require symfony/webapp-pack
composer require symfony/docker
````

Cela va générer tous les fichiers Docker nécessaires pour que le README ci-dessus fonctionne parfaitement \!
