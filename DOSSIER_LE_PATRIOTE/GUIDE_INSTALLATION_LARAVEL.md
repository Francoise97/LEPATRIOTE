# GUIDE D'INSTALLATION - LARAVEL + REACT + XAMPP
## Le Patriote - Site d'Actualité

---

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

### 1. XAMPP (Déjà installé ✓)
- Apache Server
- MySQL Database
- PHP (vérifier la version, PHP 8.2+ recommandé)

### 2. Composer (Gestionnaire de dépendances PHP)
Télécharger : https://getcomposer.org/download/

Vérifier l'installation :
```bash
composer --version
```

### 3. Node.js + npm (Pour React et Vite)
Télécharger : https://nodejs.org/

Vérifier l'installation :
```bash
node --version
npm --version
```

### 4. Git (Optionnel mais recommandé)
Télécharger : https://git-scm.com/downloads

---

## 🚀 Étape 1 : Vérifier la base de données XAMPP

1. **Démarrer XAMPP**
   - Ouvrir XAMPP Control Panel
   - Démarrer Apache
   - Démarrer MySQL

2. **Vérifier la base de données**
   - Ouvrir phpMyAdmin : http://localhost/phpmyadmin
   - Vérifier que la base `lepatriote` existe
   - Les tables WordPress peuvent rester, on créera nos propres tables

---

## 🚀 Étape 2 : Créer le projet Laravel

### Option A : Via Composer (Recommandée)
Ouvrir un terminal (PowerShell ou CMD) dans le dossier où vous voulez créer le projet :

```bash
cd C:\Users\DELL\Desktop\LEPATRIOTE
composer create-project laravel/laravel backend
```

### Option B : Via Laravel Installer
```bash
composer global require laravel/installer
laravel new backend
```

---

## 🚀 Étape 3 : Configurer la base de données

1. **Ouvrir le fichier .env**
   ```bash
   cd backend
   notepad .env
   ```

2. **Modifier les lignes de configuration de la base de données**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lepatriote
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Sauvegarder et fermer**

---

## 🚀 Étape 4 : Installer Inertia.js + React

Inertia.js permet d'utiliser React avec Laravel sans créer une API séparée.

```bash
cd backend

# Installer Inertia
composer require inertiajs/inertia-laravel

# Installer le middleware
php artisan inertia:middleware

# Enregistrer le middleware dans app/Http/Kernel.php
# Ajouter dans le tableau $middleware:
# \App\Http\Middleware\HandleInertiaRequests::class,

# Installer React
npm install react react-dom

# Installer le client Inertia pour React
npm install @inertiajs/react

# Installer Vite (build tool)
npm install --save-dev vite @vitejs/plugin-react
```

---

## 🚀 Étape 5 : Configurer Vite

1. **Modifier le fichier vite.config.js**
   ```javascript
   import { defineConfig } from 'vite';
   import laravel from 'laravel-vite-plugin';
   import react from '@vitejs/plugin-react';

   export default defineConfig({
       plugins: [
           laravel({
               input: ['resources/css/app.css', 'resources/js/app.js'],
               refresh: true,
           }),
           react(),
       ],
   });
   ```

2. **Modifier resources/js/app.js**
   ```javascript
   import '../css/app.css'
   import { createInertiaApp } from '@inertiajs/react'
   import { createRoot } from 'react-dom/client'
   import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

   createInertiaApp({
       title: (title) => `${title} - ${'Le Patriote'}`,
       resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
       setup({ el, App, props }) {
           createRoot(el).render(<App {...props} />)
       },
       progress: {
           color: '#4B5563',
       },
   })
   ```

---

## 🚀 Étape 6 : Installer Tailwind CSS

```bash
# Installer Tailwind CSS
npm install -D tailwindcss postcss autoprefixer

# Initialiser Tailwind
npx tailwindcss init -p
```

Modifier `tailwind.config.js` :
```javascript
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.jsx",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Ajouter dans `resources/css/app.css` :
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

---

## 🚀 Étape 7 : Créer les migrations Laravel

Créer les migrations pour toutes les tables :

```bash
php artisan make:migration create_users_table
php artisan make:migration create_categories_table
php artisan make:migration create_articles_table
php artisan make:migration create_tags_table
php artisan make:migration create_article_tag_table
php artisan make:migration create_comments_table
php artisan make:migration create_media_table
php artisan make:migration create_settings_table
php artisan make:migration create_newsletters_table
```

**Note** : La migration `create_users_table` existe déjà par défaut dans Laravel.

---

## 🚀 Étape 8 : Exécuter les migrations

```bash
php artisan migrate
```

Cela créera toutes les tables dans votre base `lepatriote` de XAMPP.

---

## 🚀 Étape 9 : Créer les modèles Laravel

```bash
php artisan make:model Category
php artisan make:model Article
php artisan make:model Tag
php artisan make:model Comment
php artisan make:model Media
php artisan make:model Setting
php artisan make:model Newsletter
```

---

## 🚀 Étape 10 : Installer Filament PHP (Admin Panel)

Filament est un excellent panel d'administration gratuit pour Laravel.

```bash
composer require filament/filament:"^3.0" -W
php artisan filament:install --panels
php artisan make:filament-user
```

L'admin panel sera accessible à : `http://localhost:8000/admin`

---

## 🚀 Étape 11 : Lancer le serveur de développement

```bash
# Terminal 1 : Serveur Laravel
php artisan serve

# Terminal 2 : Vite (pour React)
npm run dev
```

Le site sera accessible à : `http://localhost:8000`

---

## 📁 Structure du Projet Final

```
LEPATRIOTE/
├── backend/                    # Projet Laravel
│   ├── app/
│   │   ├── Models/            # Modèles Eloquent
│   │   ├── Http/
│   │   │   ├── Controllers/   # Contrôleurs
│   │   │   └── Middleware/    # Middleware (Inertia)
│   │   └── Services/          # Services métier
│   ├── database/
│   │   ├── migrations/        # Migrations
│   │   └── seeders/           # Seeders
│   ├── resources/
│   │   ├── js/
│   │   │   ├── Pages/         # Pages React
│   │   │   ├── Components/    # Composants React
│   │   │   └── Layouts/       # Layouts React
│   │   ├── css/
│   │   │   └── app.css        # Tailwind CSS
│   │   └── views/
│   │       └── app.blade.php  # Template principal
│   ├── routes/
│   │   ├── web.php            # Routes web
│   │   └── api.php            # Routes API
│   ├── public/                # Assets publics
│   ├── .env                   # Configuration
│   ├── composer.json
│   ├── package.json
│   └── vite.config.js
└── DOSSIER_LE_PATRIOTE/       # Documentation
    ├── CAHIER_DES_CHARGES.md
    ├── STRUCTURE_BASE_DONNEES.md
    └── GUIDE_INSTALLATION_LARAVEL.md
```

---

## 🧪 Tester l'installation

1. **Ouvrir** http://localhost:8000
2. **Vérifier** que la page Laravel par défaut s'affiche
3. **Ouvrir** http://localhost:8000/admin
4. **Se connecter** avec l'utilisateur Filament créé
5. **Vérifier** phpMyAdmin que les tables sont créées

---

## 🎯 Prochaines étapes

1. ✅ Installation terminée
2. ⏳ Créer les premières pages React (Accueil, Article, etc.)
3. ⏳ Configurer Filament pour gérer les articles
4. ⏳ Créer le design avec Tailwind CSS
5. ⏳ Tester et déployer

---

## ❓ Problèmes courants

### PHP version trop ancienne
Si XAMPP a PHP < 8.2, mettre à jour PHP dans XAMPP ou utiliser Laravel 10.

### Port 8000 déjà utilisé
Changer le port :
```bash
php artisan serve --port=8080
```

### Erreur de connexion MySQL
Vérifier que MySQL est démarré dans XAMPP Control Panel.

### Permissions sur Windows
Exécuter le terminal en tant qu'Administrateur si nécessaire.

---

## 📚 Ressources utiles

- Documentation Laravel : https://laravel.com/docs
- Documentation Inertia.js : https://inertiajs.com
- Documentation Tailwind CSS : https://tailwindcss.com
- Documentation Filament : https://filamentphp.com

---

*Bon développement ! 🚀*
