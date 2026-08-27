# Site UCO — Union du Congo Ouest

Site vitrine dynamique + panneau d’administration (PHP 8+, MVC, MySQL/PDO, Composer PSR-4).

## Prérequis

- PHP 8.1+
- MySQL 8+
- Composer

## Installation

```bash
composer install
cp .env.example .env
```

Importer la base :

```bash
mysql -u root -p < database/schema.sql
```

Configurer `.env` (`DB_*`, `APP_URL`).

Définir le mot de passe admin :

```bash
php bin/set-admin-password.php "VotreMotDePasseSecurise"
```

Compte par défaut : `admin@uco.local`

## Lancer le serveur

```bash
php -S localhost:8000 -t public public/router.php
```

- Site : http://localhost:8000  
- Admin : http://localhost:8000/admin/login  

## Structure

```
config/          Configuration & routes
database/        Script SQL
public/          Point d’entrée web + assets
src/Core/        App, Router, Database, Auth, Csrf…
src/Controllers/ Front + Admin
src/Models/      Accès données PDO
views/           Templates PHP
```

## Mandats dirigeants (historique)

Dans **Admin → Dirigeants → Modifier** :

1. Ajouter une affectation (poste + périmètre : Union / Département / Association / Comité)
2. Cocher **Clôturer les mandats en cours** lors d’une mutation
3. L’ancien mandat passe en `former` (historique conservé)
4. Le front affiche « Actuel » / « Ancien dirigeant »

## Carousel d’accueil

Dans **Admin → Carousel accueil** : ajouter / modifier / supprimer les images qui défilent sur la page d’accueil (titre, sous-titre, lien, ordre, actif).

Si la base existait déjà avant cette fonctionnalité :

```bash
mysql -u root -p uco_website < database/migrations/001_carousel_slides.sql
```

## Sécurité

- PDO requêtes préparées
- CSRF sur tous les POST
- XSS via `e()`
- Sessions `httponly` + `samesite`
- Mots de passe : `password_hash` / `password_verify`
