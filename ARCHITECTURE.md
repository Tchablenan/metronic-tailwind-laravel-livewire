**Architecture du projet Metronic-Tailwind-Laravel-Livewire**

Bref schéma décrivant les composants principaux, responsabilités et flux.

**Vue d'ensemble**
- **Frontend (navigateur)** : Charge les assets compilés par Vite (CSS Tailwind, JS Alpine) et interagit via HTTP/Livewire.
- **Vite / Tailwind / Alpine** : Tooling pour le build et la couche UI (dev server + build production).
- **Laravel (PHP 8.2)** : Application serveur — routes, contrôleurs, Livewire components, modèles Eloquent, providers.
- **Livewire (3.x)** : Composants réactifs gérés côté serveur (interactions temps réel sans SPA complète).
- **Public / Metronic HTML** : Templates statiques (dans `html/` et `public/html/`) à intégrer à Blade/Livewire.
- **Database** : MySQL / Postgres / SQLite (migrations, seeders, factories).
- **Tests & CI** : PHPUnit, workflows CI pour tests, build et déploiement.



**Couche par couche (rôles)**
- **Presentation**: `public/`, `resources/views/`, assets compilés (CSS/JS) — gérés par Vite/Tailwind.
- **Interaction**: Livewire components (`app/Http/Livewire/`) — état, événements, communication AJAX long-polling/sse/websockets selon config.
- **Application**: `app/Http/Controllers/`, `app/Models/`, `app/Providers/` — logique métier, validation, jobs.
- **Persistance**: `database/migrations`, `factories`, `seeders`.
- **Ops**: `vite.config.js`, `tailwind.config.js`, `composer.json`, `package.json` — scripts de dev/build; CI/Docker possibles.

**Points d'intégration importants**
- `resources/js/app.js` et `resources/css/app.css` : points d'entrée Vite.
- `routes/web.php` : point d'entrée HTTP pour pages et composants Livewire.
- `public/html/` et `html/` : originaux Metronic — à adapter en Blade/Livewire.


