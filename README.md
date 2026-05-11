# Crowdfunding Etudiants

Projet final du module Programmation Web 2 : developpement d'un site web dynamique avec Laravel.

Cette application permet de gerer une plateforme de crowdfunding pour des projets etudiants. Les porteurs peuvent creer et publier des projets, les contributeurs peuvent soutenir les projets, commenter et suivre les mises a jour.

## Objectif du projet

L'objectif est de mettre en pratique les notions vues en Laravel :

- architecture MVC ;
- routes, controleurs et vues Blade ;
- operations CRUD ;
- migrations et relations Eloquent ;
- formulaires avec validation cote serveur ;
- authentification et gestion des roles ;
- recherche, filtres et pagination.

## Fonctionnalites principales

- Page d'accueil avec la liste des projets publies.
- Recherche de projets par titre.
- Filtrage des projets par categorie : films, musique, art, startup.
- Pagination des listes de projets.
- Creation, affichage, modification, publication et suppression des projets.
- Ajout de contributions sur les projets publies.
- Affichage des contributions de l'utilisateur connecte.
- Ajout, modification et suppression des mises a jour d'un projet.
- Ajout et suppression des commentaires.
- Tableau de bord administrateur avec statistiques.
- Gestion des utilisateurs et des projets cote administrateur.
- Upload d'images pour les projets et les mises a jour.

## Roles utilisateurs

- **Contributeur** : consulte les projets, contribue et commente.
- **Porteur** : cree et gere ses propres projets.
- **Admin** : accede au tableau de bord, aux statistiques, aux utilisateurs et aux projets.

## Entites et relations

- **User**
  - possede plusieurs projets ;
  - possede plusieurs contributions ;
  - possede plusieurs commentaires ;
  - possede plusieurs mises a jour.

- **Projet**
  - appartient a un porteur (`User`) ;
  - possede plusieurs contributions ;
  - possede plusieurs commentaires ;
  - possede plusieurs mises a jour.

- **Contribution**
  - appartient a un utilisateur ;
  - appartient a un projet.

- **Commentaire**
  - appartient a un utilisateur ;
  - appartient a un projet.

- **Update**
  - appartient a un utilisateur ;
  - appartient a un projet.

## Technologies utilisees

- Laravel 8
- PHP 7.4 ou PHP 8
- MySQL
- Blade
- Laravel Breeze
- Tailwind CSS
- Laravel Mix

## Installation du projet

Cloner le projet, puis entrer dans le dossier du projet :

```bash
cd CrowdfundingEtudiant
```

Installer les dependances PHP :

```bash
composer install
```

Installer les dependances JavaScript :

```bash
npm install
```

Creer le fichier d'environnement :

```bash
cp .env.example .env
```

Generer la cle de l'application :

```bash
php artisan key:generate
```

Configurer la base de donnees dans le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Executer les migrations :

```bash
php artisan migrate
```

Creer le lien symbolique pour les images :

```bash
php artisan storage:link
```

Compiler les assets :

```bash
npm run dev
```

Lancer le serveur local :

```bash
php artisan serve
```

Le site sera disponible sur :

```text
http://127.0.0.1:8000
```

## Compte administrateur de test

Un script est fourni pour creer un compte administrateur :

```bash
php create_admin.php
```

Identifiants par defaut :

```text
Email : admin@campusfund.com
Mot de passe : admin123
```

Apres connexion, le tableau de bord administrateur est accessible ici :

```text
http://127.0.0.1:8000/admin/dashboard
```

## Routes importantes

- `/` : accueil et liste des projets.
- `/login` : connexion.
- `/register` : inscription.
- `/projets` : liste des projets.
- `/projets/create` : creation d'un projet.
- `/mes-contributions` : contributions de l'utilisateur connecte.
- `/admin/dashboard` : tableau de bord administrateur.
- `/admin/users` : gestion des utilisateurs.
- `/admin/projects` : gestion des projets.

## Livrable

Le livrable attendu est le code source complet du projet Laravel, depose sur GitHub ou compresse en fichier `.zip`, avec ce fichier `README.md`.

Chaque membre du groupe doit presenter oralement sa fonctionnalite CRUD pendant la demonstration.

## Membres du groupe

- A completer
