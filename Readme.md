# TheFilmApp

Une app pour chercher, noter des films et suivre d'autres utilisateurs.

## Ce qu'il y a dans le projet

- **API** (`/api`) : films, catégories, recherche, pagination, inscription, connexion (JWT), notes, follows
- **Front** (`/`) : Twig + Vite + Vue.js, avec la liste des films, les notes et les follows
- **Admin** (`/admin`) : EasyAdmin, réservé aux admins

## Ce qu'il n'y a pas

Pas de Mailer. Envoyer des mails, vérifier qu'ils arrivent bien, cliquer sur des liens de confirmation... On a préféré garder notre énergie pour les films. Vos boîtes mail nous remercient.

## 1. Installer les outils

Il faut avoir sur sa machine :

- PHP 8.5 ou plus
- Composer
- Symfony CLI
- Node.js avec npm

Vue.js et Vite n'ont pas besoin d'être installés à part : `npm install` (étape 2) les installe avec le projet.

La base de données est un fichier SQLite : pas de serveur de base de données à installer.

### Sur macOS

Avec [Homebrew](https://brew.sh). S'il n'est pas installé :

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Puis :

```bash
brew install php composer node
brew install symfony-cli/tap/symfony-cli
```

### Sur Windows

Il n'y a pas de section Windows, parce que Mac c'est mieux. On rigole. Enfin, à moitié.

### Vérifier l'installation

```bash
php -v
composer -V
node -v
npm -v
symfony check:requirements
```

## 2. Installer le projet

Dans le dossier du projet :

```bash
# Dépendances PHP
composer install

# Dépendances front : Vue.js, Vite
npm install

# Clés qui servent à créer les tokens de connexion (JWT)
php bin/console lexik:jwt:generate-keypair

# Création de la base de données
php bin/console doctrine:migrations:migrate
```

Mettre le fichier `movies-db.json` du TP dans le dossier `src/Command/`, puis :

```bash
# Import des films
php bin/console app:import-movies

# Création des comptes de test (voir plus bas)
php bin/console doctrine:fixtures:load --append
```

## 3. Lancer le projet

Dans un terminal, lancer le serveur Symfony :

```bash
symfony server:start
```

Dans un deuxième terminal, lancer le front (Vue.js avec Vite), et laisser ce terminal ouvert :

```bash
npm run dev
```

Ouvrir http://127.0.0.1:8000 et pas le localhost de npm 

Pour tout arrêter : `Ctrl + C` dans le terminal de `npm run dev`, puis `symfony server:stop`.

## Comptes de test

> Alban, ces identifiants sont là seulement pour toi, pour corriger le TP. On ne met pas de mots de passe dans un README, :0 c'est juste pour toi. :) 

| Email | Mot de passe | Rôle |
|---|---|---|
| admin@admin.com | password | Admin : accès à `/admin` |
| user@user.com | password | Utilisateur |

## Tester l'API

Les requêtes sont dans le dossier `http/`, à lancer avec l'extension [httpYac](https://httpyac.github.io/) dans VS Code. La connexion se fait toute seule.

## Routes

| Route | Connexion | Description |
|---|---|---|
| `POST /api/register` | | Créer un compte |
| `POST /api/login_check` | | Se connecter |
| `GET /api/movies?title=&year=&page=&limit=` | | Chercher des films |
| `GET` `POST` `PUT` `DELETE` `/api/movies` | | CRUD des films |
| `GET` `POST` `PUT` `DELETE` `/api/categories` | | CRUD des catégories |
| `GET` `PUT` `DELETE` `/api/movies/{id}/rating` | oui | Ma note sur un film |
| `GET /api/me/ratings` | oui | Mes notes |
| `GET /api/users` | oui | Les utilisateurs |
| `PUT` `DELETE` `/api/users/{id}/follow` | oui | Suivre / ne plus suivre |
| `GET /api/me/following` et `/api/me/followers` | oui | Mes abonnements et mes abonnés |
