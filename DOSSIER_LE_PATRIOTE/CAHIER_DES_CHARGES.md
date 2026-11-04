# CAHIER DES CHARGES - LE PATRIOTE
## Site d'Actualité

---

## 1. PRÉSENTATION DU PROJET

### 1.1 Contexte
Le Patriote est un site d'actualité francophone inspiré des médias d'information comme Togo Breaking News. Le site se veut une plateforme d'information fiable, moderne et dynamique couvrant l'actualité politique, économique, sociale et culturelle.

### 1.2 Objectifs
- Informer le public sur l'actualité nationale et internationale
- Proposer des articles de qualité avec une lisibilité optimale
- Créer une expérience utilisateur moderne et dynamique
- Faciliter le partage sur les réseaux sociaux (WhatsApp, Facebook, etc.)
- Attirer et fidéliser une audience variée

### 1.3 Public Cible
- Lecteurs francophones intéressés par l'actualité
- Professionnels (entrepreneurs, fonctionnaires, étudiants)
- Population générale cherchant une information fiable

---

## 2. FONCTIONNALITÉS PRINCIPALES

### 2.1 Page d'Accueil
- **Header** : Logo, navigation, recherche, bouton d'abonnement
- **Section Hero** : Article principal en vedette avec image haute qualité
- **Dernières actualités** : Grille des articles récents
- **Catégories** : Politique, Économie, Société, Culture, Sport, International
- **Articles tendance** : Section "Les plus lus"
- **Newsletter** : Formulaire d'inscription
- **Footer** : Liens utiles, réseaux sociaux, informations légales

### 2.2 Page Article
- Titre accrocheur avec typographie distinctive
- Image principale avec légende
- Métadonnées (auteur, date, temps de lecture)
- Corps de l'article avec typographie soignée
- Articles suggérés / "À lire aussi"
- Boutons de partage social (WhatsApp, Facebook, Twitter, LinkedIn)
- Section commentaires (optionnel)

### 2.3 Pages Catégories
- Filtrage par catégorie
- Tri par date / popularité
- Pagination

### 2.4 Page À Propos
- Histoire de Le Patriote
- Équipe éditoriale
- Charte rédactionnelle
- Contact

### 2.5 Fonctionnalités Avancées (Phase 2)
- Système de recherche avancée
- Mode sombre/clair
- Notifications push
- Application mobile PWA
- Abonnement premium

---

## 3. IDENTITÉ VISUELLE

### 3.1 Nom
**Le Patriote**

### 3.2 Slogan Propositions
- "L'information qui engage"
- "Votre source d'information de confiance"
- "Au cœur de l'actualité"
- "L'info, claire et libre"

### 3.3 Options de Palettes de Couleurs

#### OPTION A : "Confiance & Autorité" (Style classique journalisme)
- **Couleur principale** : Bleu marine profond (#1A365D)
- **Couleur secondaire** : Rouge bordeaux (#C53030)
- **Accent** : Or ambré (#D69E2E)
- **Neutres** : Gris clair (#F7FAFC), Gris foncé (#2D3748)
- **Avantages** : Inspire confiance, sérieux, professionnalisme
- **Idéal pour** : Actualité politique, économique

#### OPTION B : "Moderne & Dynamique" (Style média digital)
- **Couleur principale** : Violet électrique (#6B46C1)
- **Couleur secondaire** : Cyan vif (#06B6D4)
- **Accent** : Orange vif (#F97316)
- **Neutres** : Blanc pur (#FFFFFF), Gris ardoise (#1E293B)
- **Avantages** : Moderne, attractif, énergique
- **Idéal pour** : Public jeune, actualité culturelle, tech

#### OPTION C : "Nature & Authenticité" (Style africain contemporain)
- **Couleur principale** : Vert forêt (#2F855A)
- **Couleur secondaire** : Ocre jaune (#D69E2E)
- **Accent** : Terracotta (#C05621)
- **Neutres** : Crème (#FFFBEB), Brun foncé (#3D2914)
- **Avantages** : Identité locale, chaleur, authenticité
- **Idéal pour** : Actualité sociale, environnement, culture locale

#### OPTION D : "Minimaliste Chic" (Style magazine premium)
- **Couleur principale** : Noir pur (#000000)
- **Couleur secondaire** : Blanc (#FFFFFF)
- **Accent** : Rouge carmin (#DC2626)
- **Neutres** : Gris perle (#F3F4F6), Anthracite (#374151)
- **Avantages** : Élégant, intemporel, luxueux
- **Idéal pour** : Magazine d'investigation, culture, luxe

---

## 4. TYPOGRAPHIE

### 4.1 Recommandations

#### Option A : Google Fonts (Gratuit)
- **Titres** : Playfair Display (sérieux, élégant) ou Montserrat (moderne)
- **Corps** : Inter ou Source Sans Pro (lisibilité optimale)
- **Accents** : Oswald (pour les titres courts)

#### Option B : Adobe Fonts (Premium)
- **Titres** : Georgia Pro ou Minion Pro
- **Corps** : Myriad Pro ou Source Serif Pro

---

## 5. ALTERNATIVES DE DESIGN

### ALTERNATIVE 1 : "GRID CLASSIC"
**Style** : Journal traditionnel moderne
- Mise en page en grille 3 colonnes
- Article principal en large en haut
- Sidebar à droite avec articles populaires
- Lignes de séparation subtiles
- Typographie serif pour les titres

**Avantages** :
- Familiarité pour les lecteurs
- Maximise le contenu visible
- Structure claire et organisée

---

### ALTERNATIVE 2 : "MAGAZINE HERO"
**Style** : Magazine visuel impactant
- Hero section plein écran avec article principal
- Scroll horizontal pour les catégories
- Cartes d'articles avec grandes images
- Animations fluides au scroll
- Design minimaliste

**Avantages** :
- Très visuel et moderne
- Expérience immersive
- Idéal pour contenu visuel

---

### ALTERNATIVE 3 : "CARD FLOW"
**Style** : Flux de cartes (type Instagram)
- Articles présentés en cartes verticales
- Masonry layout (style Pinterest)
- Focus sur les images
- Navigation par swipe sur mobile
- Design très mobile-first

**Avantages** :
- Excellent sur mobile
- Engagement visuel fort
- Parfait pour les réseaux sociaux

---

### ALTERNATIVE 4 : "SPLIT VIEW"
**Style** : Vue divisée asymétrique
- Layout asymétrique moderne
- Mix de grandes et petites sections
- Contraste visuel fort
- Utilisation créative de l'espace blanc
- Design avant-gardiste

**Avantages** :
- Unique et mémorable
- Différenciation concurrentielle
- Très contemporain

---

## 6. TECHNOLOGIES RECOMMANDÉES

### 6.1 Stack Technique : Laravel + React + MySQL

#### Frontend : React avec Laravel
- **React 18** : Bibliothèque JavaScript pour l'interface utilisateur
- **Inertia.js** : Intégration React + Laravel sans API REST
- **Vite** : Build tool ultra-rapide
- **Tailwind CSS** : Framework CSS utilitaire pour le design
- **React Router** : Navigation SPA

#### Backend : Laravel 11
- **PHP 8.2+** : Langage backend
- **Laravel Framework** : Framework PHP robuste
- **Eloquent ORM** : Manipulation de la base de données
- **Laravel Sanctum** : Authentification API
- **Laravel Media Library** : Gestion des images
- **Filament PHP** : Panel d'administration (gratuit et moderne)

#### Base de Données : MySQL (XAMPP)
- **MySQL** : Base de données relationnelle
- **Hébergement local** : XAMPP (déjà installé)
- **Nom de la base** : `lepatriote`
- **Tables personnalisées** : Créées spécifiquement pour Le Patriote

### 6.2 Architecture du Projet

```
lepatriote/
├── app/                    # Code Laravel
│   ├── Models/            # Modèles Eloquent
│   ├── Http/Controllers/  # Contrôleurs
│   └── Services/          # Services métier
├── database/
│   ├── migrations/        # Migrations base de données
│   └── seeders/           # Données de test
├── resources/
│   ├── js/               # Code React
│   │   ├── Components/   # Composants React
│   │   ├── Pages/        # Pages React
│   │   └── Layouts/      # Layouts
│   └── views/            # Vues Blade (Inertia)
├── public/               # Assets publics
└── routes/               # Routes Laravel
```

### 6.3 Structure de la Base de Données

#### Tables principales pour Le Patriote :

**users** (Utilisateurs/Auteurs)
- id, name, email, password, role (admin/editor/author), bio, avatar, created_at, updated_at

**categories** (Catégories d'articles)
- id, name, slug, description, parent_id, order, created_at, updated_at

**articles** (Articles)
- id, title, slug, content, excerpt, featured_image, category_id, user_id, status (draft/published/archived), is_featured, views_count, published_at, created_at, updated_at

**tags** (Tags)
- id, name, slug, created_at, updated_at

**article_tag** (Relation articles-tags)
- article_id, tag_id

**comments** (Commentaires)
- id, article_id, user_id, parent_id, content, status (pending/approved/rejected), created_at, updated_at

**media** (Médias/images)
- id, file_name, file_path, file_type, file_size, model_type, model_id, created_at, updated_at

**settings** (Configuration du site)
- id, key, value, type, created_at, updated_at

**newsletters** (Abonnés newsletter)
- id, email, status (active/unsubscribed), token, created_at, updated_at

### 6.4 Hébergement

#### Développement (Local)
- **XAMPP** : Serveur local Apache + MySQL + PHP
- **PHP 8.2+** : Via XAMPP ou installation séparée
- **Composer** : Gestionnaire de dépendances PHP
- **Node.js + npm** : Pour React et Vite

#### Production
- **Hébergement mutualisé** : OVH, Hostinger, etc. (supporte Laravel)
- **VPS** : DigitalOcean, Linode, Hetzner (plus de contrôle)
- **Laravel Forge** : Gestion de serveur Laravel (optionnel)
- **Envoyer** : Monitoring Laravel (optionnel)

### 6.5 Outils Additionnels
- **Images** : Intervention Image (traitement images côté serveur)
- **Analytics** : Google Analytics 4 ou Plausible
- **Newsletter** : Laravel Newsletter (intégré) ou Mailchimp API
- **SEO** : Laravel SEO Tools
- **Cache** : Redis (optionnel pour la performance)
- **File Storage** : Local (développement) ou S3 (production)

---

## 7. EXPÉRIENCE UTILISATEUR (UX)

### 7.1 Navigation
- Menu hamburger sur mobile
- Navigation sticky (fixe) au scroll
- Breadcrumbs (fil d'Ariane)
- Liens rapides vers catégories

### 7.2 Performance
- Temps de chargement < 3 secondes
- Images optimisées (WebP)
- Lazy loading des images
- Minification CSS/JS

### 7.3 Accessibilité
- Contraste WCAG AA minimum
- Navigation clavier
- Tags alt sur images
- Taille de police lisible (16px minimum)

### 7.4 Mobile First
- Design responsive
- Touch-friendly
- Version mobile optimisée
- PWA (Progressive Web App) optionnelle

---

## 8. CONTENU & RÉDACTION

### 8.1 Types d'Articles
- Actualités brèves (200-300 mots)
- Articles d'analyse (800-1200 mots)
- Reportages (1500+ mots)
- Interviews
- Éditoriaux

### 8.2 Images
- Format horizontal 16:9 pour hero
- Format carré 1:1 pour réseaux sociaux
- Qualité HD minimum
- Compression optimisée

### 8.3 SEO
- Titres SEO optimisés
- Meta descriptions
- Balises Open Graph (partage social)
- Structure sémantique HTML

---

## 9. LIVRABLES

### Phase 1 : Maquette
- [ ] Wireframes desktop et mobile
- [ ] Design system (couleurs, typographie, composants)
- [ ] Maquettes haute fidélité (Figma/Adobe XD)
- [ ] Prototype interactif

### Phase 2 : Développement
- [ ] Configuration du projet
- [ ] Intégration du design
- [ ] Développement CMS
- [ ] Tests et optimisation

### Phase 3 : Lancement
- [ ] Déploiement en production
- [ ] Configuration DNS
- [ ] Analytics
- [ ] Formation équipe

---

## 10. CALENDRIER ESTIMATIF

- **Semaine 1** : Validation cahier des charges, choix design
- **Semaine 2-3** : Création maquettes et prototype
- **Semaine 4-6** : Développement frontend
- **Semaine 7-8** : Intégration CMS et backend
- **Semaine 9** : Tests et optimisation
- **Semaine 10** : Lancement

---

## 11. BUDGET ESTIMATIF (Indicatif)

### Option Freelance
- Design (maquettes) : 500-1500€
- Développement : 2000-5000€
- CMS setup : 500-1000€
- **Total** : 3000-7500€

### Option Agence
- **Total** : 8000-20000€

### Coûts récurrents mensuels
- Hébergement : 20-100€
- Domaine : 15€/an
- Images CDN : 10-50€
- Newsletter : 0-50€
- **Total** : 30-200€/mois

---

## 12. PROCHAINES ÉTAPES

1. ✅ Valider le cahier des charges
2. ⏳ Choisir l'option de design (A, B, C ou D)
3. ⏳ Choisir la palette de couleurs
4. ⏳ Valider les technologies
5. ⏳ Créer les wireframes
6. ⏳ Développer les maquettes

---

## QUESTIONS POUR AVANCER

1. Quelle palette de couleurs préférez-vous (Option A, B, C ou D) ?
2. Quel style de design vous attire le plus (Grid Classic, Magazine Hero, Card Flow, Split View) ?
3. Avez-vous une préférence technologique (Next.js, Nuxt, Astro) ?
4. Quel est votre budget approximatif ?
5. Quelle date de cible de lancement ?

---

*Document créé pour Le Patriote - Site d'Actualité*
