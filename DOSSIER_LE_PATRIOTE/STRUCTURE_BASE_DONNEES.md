# STRUCTURE DE LA BASE DE DONNÉES - LE PATRIOTE
## Laravel + MySQL (XAMPP)

---

## 📊 Vue d'ensemble

**Nom de la base de données** : `lepatriote`
**SGBD** : MySQL (via XAMPP)
**Framework** : Laravel 11
**ORM** : Eloquent

---

## 🗂️ Liste des Tables

### 1. **users** - Utilisateurs et Auteurs
Gère les comptes utilisateurs, auteurs et administrateurs.

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire (auto-increment) |
| name | VARCHAR(255) | Nom complet de l'utilisateur |
| email | VARCHAR(255) | Email unique |
| password | VARCHAR(255) | Mot de passe hashé |
| role | ENUM('admin','editor','author','subscriber') | Rôle de l'utilisateur |
| bio | TEXT | Biographie de l'auteur |
| avatar | VARCHAR(255) | URL de l'avatar |
| is_active | BOOLEAN | Compte actif ou non |
| email_verified_at | TIMESTAMP | Date de vérification email |
| remember_token | VARCHAR(100) | Token "se souvenir de moi" |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- UNIQUE INDEX (email)
- INDEX (role)

---

### 2. **categories** - Catégories d'articles
Organise les articles par catégories (Politique, Économie, etc.)

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| name | VARCHAR(255) | Nom de la catégorie |
| slug | VARCHAR(255) | Slug URL-friendly (unique) |
| description | TEXT | Description de la catégorie |
| parent_id | BIGINT UNSIGNED | ID de la catégorie parent (null = racine) |
| order | INT | Ordre d'affichage |
| is_active | BOOLEAN | Catégorie active |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)
- INDEX (parent_id)
- INDEX (is_active)

---

### 3. **articles** - Articles
Contient tous les articles du site.

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| title | VARCHAR(255) | Titre de l'article |
| slug | VARCHAR(255) | Slug URL-friendly (unique) |
| content | LONGTEXT | Contenu complet de l'article (HTML/Markdown) |
| excerpt | TEXT | Extrait/résumé de l'article |
| featured_image | VARCHAR(255) | URL de l'image à la une |
| category_id | BIGINT UNSIGNED | ID de la catégorie |
| user_id | BIGINT UNSIGNED | ID de l'auteur |
| status | ENUM('draft','published','archived') | Statut de l'article |
| is_featured | BOOLEAN | Article en vedette |
| is_breaking | BOOLEAN | Actualité urgente |
| views_count | INT UNSIGNED | Nombre de vues |
| reading_time | INT UNSIGNED | Temps de lecture estimé (minutes) |
| meta_title | VARCHAR(255) | Titre SEO |
| meta_description | TEXT | Description SEO |
| published_at | TIMESTAMP | Date de publication |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)
- INDEX (category_id)
- INDEX (user_id)
- INDEX (status)
- INDEX (published_at)
- INDEX (is_featured)

**Relations** :
- BelongsTo Category
- BelongsTo User
- BelongsToMany Tag
- HasMany Comment

---

### 4. **tags** - Tags
Étiquettes pour classifier les articles.

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| name | VARCHAR(255) | Nom du tag |
| slug | VARCHAR(255) | Slug URL-friendly (unique) |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)

---

### 5. **article_tag** - Relation Articles-Tags
Table pivot pour la relation many-to-many.

| Colonne | Type | Description |
|---------|------|-------------|
| article_id | BIGINT UNSIGNED | ID de l'article |
| tag_id | BIGINT UNSIGNED | ID du tag |

**Index** :
- PRIMARY KEY (article_id, tag_id)
- INDEX (article_id)
- INDEX (tag_id)

---

### 6. **comments** - Commentaires
Commentaires des utilisateurs sur les articles.

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| article_id | BIGINT UNSIGNED | ID de l'article commenté |
| user_id | BIGINT UNSIGNED | ID de l'utilisateur (nullable pour anonymes) |
| parent_id | BIGINT UNSIGNED | ID du commentaire parent (réponses) |
| author_name | VARCHAR(255) | Nom (si anonyme) |
| author_email | VARCHAR(255) | Email (si anonyme) |
| content | TEXT | Contenu du commentaire |
| status | ENUM('pending','approved','rejected','spam') | Statut de modération |
| ip_address | VARCHAR(45) | Adresse IP de l'auteur |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- INDEX (article_id)
- INDEX (user_id)
- INDEX (parent_id)
- INDEX (status)

**Relations** :
- BelongsTo Article
- BelongsTo User
- HasMany Comment (réponses)

---

### 7. **media** - Médias et Images
Gère tous les fichiers uploadés (images, documents).

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| file_name | VARCHAR(255) | Nom du fichier |
| file_path | VARCHAR(255) | Chemin du fichier |
| file_type | VARCHAR(100) | Type MIME (image/jpeg, etc.) |
| file_size | INT UNSIGNED | Taille en octets |
| model_type | VARCHAR(255) | Type du modèle lié (App\Models\Article) |
| model_id | BIGINT UNSIGNED | ID du modèle lié |
| collection_name | VARCHAR(255) | Nom de la collection (featured, gallery, etc.) |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- INDEX (model_type, model_id)
- INDEX (collection_name)

---

### 8. **settings** - Configuration du Site
Paramètres globaux du site.

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| key | VARCHAR(255) | Clé de configuration (unique) |
| value | TEXT | Valeur (JSON ou texte) |
| type | VARCHAR(50) | Type de valeur (string, json, boolean, integer) |
| group | VARCHAR(50) | Groupe (general, social, seo) |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- UNIQUE INDEX (key)
- INDEX (group)

**Exemples de settings** :
- site_name : "Le Patriote"
- site_description : "Votre source d'information"
- facebook_url : "https://facebook.com/lepatriote"
- whatsapp_channel : "https://whatsapp.com/channel/..."
- seo_title_template : "%title% - Le Patriote"

---

### 9. **newsletters** - Abonnés Newsletter
Gère les abonnements à la newsletter.

| Colonne | Type | Description |
|---------|------|-------------|
| id | BIGINT UNSIGNED | Clé primaire |
| email | VARCHAR(255) | Email de l'abonné (unique) |
| status | ENUM('active','unsubscribed','bounced') | Statut |
| token | VARCHAR(255) | Token de désabonnement |
| subscribed_at | TIMESTAMP | Date d'abonnement |
| unsubscribed_at | TIMESTAMP | Date de désabonnement |
| created_at | TIMESTAMP | Date de création |
| updated_at | TIMESTAMP | Date de modification |

**Index** :
- PRIMARY KEY (id)
- UNIQUE INDEX (email)
- INDEX (status)

---

## 🔗 Relations Entre Tables

```
users (1) ────── (∞) articles
categories (1) ── (∞) articles
articles (∞) ──── (∞) tags (via article_tag)
articles (1) ──── (∞) comments
users (1) ─────── (∞) comments
articles (1) ──── (∞) media
```

---

## 📝 Exemples de Données

### Users (exemples)
```sql
INSERT INTO users (name, email, password, role, bio) VALUES
('Admin Principal', 'admin@lepatriote.tg', '$2y$10$...', 'admin', 'Administrateur du site'),
('Jean Kouassi', 'jean@lepatriote.tg', '$2y$10$...', 'editor', 'Rédacteur en chef'),
('Marie Aho', 'marie@lepatriote.tg', '$2y$10$...', 'author', 'Journaliste politique');
```

### Categories (exemples)
```sql
INSERT INTO categories (name, slug, description) VALUES
('Politique', 'politique', 'Actualité politique nationale et internationale'),
('Économie', 'economie', 'Nouvelles économiques et financières'),
('Société', 'societe', 'Faits de société et actualité locale'),
('Culture', 'culture', 'Art, musique et événements culturels'),
('Sport', 'sport', 'Actualités sportives');
```

### Articles (exemple)
```sql
INSERT INTO articles (title, slug, content, category_id, user_id, status, is_featured) VALUES
('Partenariat Public-Privé : Ecobank précise les critères de financement',
 'partenariat-public-prive-ecobank-criteres',
 '<p>Le financement reste l\'un des principaux enjeux...</p>',
 2, 3, 'published', 1);
```

---

## 🛠️ Migrations Laravel

Les migrations Laravel correspondantes seront créées automatiquement avec :

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

Puis exécutées avec :

```bash
php artisan migrate
```

---

## 📋 Résumé

- **9 tables principales** pour gérer tout le site
- **Relations bien définies** pour l'intégrité des données
- **Index optimisés** pour la performance
- **Flexible** pour évolutions futures
- **Compatible** avec XAMPP MySQL local

Les tables WordPress existantes dans la base `lepatriote` seront ignorées et ne créeront pas de conflits.
