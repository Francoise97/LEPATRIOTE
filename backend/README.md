<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Imports RSS

Les flux de Togo First, de Republic of Togo et du Monde sont vérifiés toutes les heures. Les nouveaux éléments reconnus sont importés en brouillon pour validation par la rédaction. Les éléments déjà importés ne sont pas remplacés ni mis à jour automatiquement, et le texte disponible dépend du contenu fourni par chaque flux.

## Administration éditoriale

Après déploiement, exécuter les migrations et ajouter les pays de départ :

```bash
php artisan migrate
php artisan db:seed --class=CountrySeeder
```

Créer d’abord un compte de connexion, puis lui accorder le rôle administrateur depuis le terminal du backend :

```bash
php artisan admin:promote admin@example.com
```

L’accès à `/admin` est réservé aux comptes éditeur et administrateur. L’administration permet de modifier le logo, les couleurs et la typographie du slogan, de gérer les publicités et les sources RSS, et d’éditer les articles avec leur pays et leur rubrique. Une source RSS doit être vérifiée et active avant import ; ses articles sont créés en brouillon pour contrôle éditorial. Les articles marqués « Afficher dans le bandeau défilant » apparaissent dans le bandeau de la page d’accueil. Les mentions J’aime sont limitées à une réaction réversible par session et par article.

Les visuels envoyés par l’administration sont stockés sur le disque public Laravel. Pour les servir, créer le lien de stockage une fois :

```bash
php artisan storage:link
```

Pour vérifier la planification :

```bash
php artisan schedule:list
```

Le planificateur Laravel doit être exécuté chaque minute. Sur ce poste Windows, la tâche « LePatriote RSS Scheduler » appelle `schedule:run` chaque minute pendant que l’utilisateur est connecté. En production Linux, ajouter au crontab :

```cron
* * * * * cd /chemin/vers/backend && php artisan schedule:run >> /dev/null 2>&1
```
