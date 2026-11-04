<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $category->description ?: 'Toute l’actualité ' . $category->name . ' du Patriote.' }}">
    <title>{{ $category->name }} - Le Patriote</title>
    <style>
        :root {
            --navy: #102b4e;
            --navy-deep: #091b31;
            --red: #a51d35;
            --ink: #202b39;
            --muted: #748093;
            --line: #e2e6eb;
            --paper: #f5f6f8;
            --white: #fff;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: Arial, Helvetica, sans-serif; }
        a { color: inherit; }
        .container { width: min(1180px, calc(100% - 40px)); margin: 0 auto; }
        .topbar { background: var(--navy-deep); color: #d7dfeb; font-size: 12px; }
        .topbar-inner { min-height: 34px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .topbar a, .topbar button { color: white; text-decoration: none; }
        .topbar form { display: inline; }
        .topbar button { padding: 0; border: 0; background: none; font: inherit; cursor: pointer; }
        .masthead { background: var(--white); }
        .masthead-inner { min-height: 94px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand img { display: block; width: auto; max-width: 240px; height: 72px; object-fit: contain; }
        .brand-text { color: var(--navy); font: 700 34px Georgia, 'Times New Roman', serif; text-decoration: none; }
        .masthead-note { color: var(--muted); font: italic 15px Georgia, 'Times New Roman', serif; }
        .category-nav { position: sticky; top: 0; z-index: 4; background: var(--navy); box-shadow: 0 3px 12px #0a1d3520; }
        .category-nav-inner { min-height: 47px; display: flex; align-items: center; gap: 25px; overflow-x: auto; scrollbar-width: thin; }
        .category-nav a { flex: 0 0 auto; color: white; font-size: 11px; font-weight: 700; letter-spacing: .07em; text-decoration: none; text-transform: uppercase; }
        .category-nav a:hover, .category-nav a.active { color: #ffd27b; }
        main { padding: 28px 0 52px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--navy); font-size: 12px; font-weight: 700; text-decoration: none; }
        .back-link:hover { color: var(--red); }
        .category-heading { margin-bottom: 27px; padding: 24px 27px; border-left: 4px solid var(--red); background: white; }
        .category-heading p { margin: 0 0 7px; color: var(--red); font-size: 10px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .category-heading h1 { margin: 0; color: var(--navy); font: 700 clamp(30px, 5vw, 42px) Georgia, 'Times New Roman', serif; }
        .category-heading .description { max-width: 760px; margin: 10px 0 0; color: #596679; font-size: 14px; line-height: 1.6; }
        .article-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px; }
        .article-card { overflow: hidden; background: white; }
        .article-image { display: block; height: 195px; overflow: hidden; background: linear-gradient(135deg, #dce5ee, #aebed0); }
        .article-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .25s ease; }
        .article-card:hover .article-image img { transform: scale(1.035); }
        .article-content { padding: 16px 18px 19px; }
        .article-category { color: var(--red); font-size: 10px; font-weight: 800; letter-spacing: .08em; text-decoration: none; text-transform: uppercase; }
        .article-content h2 { margin: 9px 0; color: var(--navy); font: 700 19px/1.3 Georgia, 'Times New Roman', serif; }
        .article-content h2 a { text-decoration: none; }
        .article-content h2 a:hover { color: var(--red); }
        .excerpt { margin: 0 0 13px; color: #586477; font-size: 13px; line-height: 1.6; }
        .meta { color: var(--muted); font-size: 11px; }
        .empty-state { padding: 30px; background: white; color: var(--muted); text-align: center; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; }
        .pagination nav { display: flex; justify-content: center; }
        .pagination nav > div { display: flex; flex-wrap: wrap; gap: 5px; }
        .pagination a, .pagination span[aria-current] > span, .pagination button {
            display: inline-flex; min-width: 38px; min-height: 38px; align-items: center; justify-content: center;
            padding: 7px 11px; border: 1px solid var(--line); background: white; color: var(--navy); font-size: 12px; text-decoration: none;
        }
        .pagination a:hover { border-color: var(--navy); }
        .pagination span[aria-current] > span { border-color: var(--navy); background: var(--navy); color: white; }
        .footer { margin-top: 10px; padding: 25px 0; background: var(--navy-deep); color: #d1d9e3; font-size: 12px; }
        .footer p { margin: 0; }
        @media (max-width: 800px) { .article-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 540px) {
            .container { width: calc(100% - 28px); }
            .masthead-inner { min-height: 78px; }
            .brand img { max-width: 190px; height: 64px; }
            .masthead-note { display: none; }
            .category-nav-inner { min-height: 44px; gap: 20px; }
            main { padding-top: 20px; }
            .category-heading { padding: 19px; }
            .article-grid { grid-template-columns: 1fr; }
            .article-image { height: 210px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="topbar">
            <div class="container topbar-inner">
                <span>{{ now()->translatedFormat('l d F Y') }}</span>
                <span>
                    @auth
                        <a href="{{ url('/admin') }}">Administration</a>
                        <span aria-hidden="true"> · </span>
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <button type="submit">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ url('/login') }}">Connexion</a>
                    @endauth
                </span>
            </div>
        </div>
        <div class="masthead">
            <div class="container masthead-inner">
                <a class="brand" href="{{ route('home') }}" aria-label="Le Patriote, accueil">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Le Patriote">
                    @else
                        <span class="brand-text">LE PATRIOTE</span>
                    @endif
                </a>
                <span class="masthead-note">L'actualité, au cœur de l'essentiel</span>
            </div>
        </div>
        <nav class="category-nav" aria-label="Rubriques">
            <div class="container category-nav-inner">
                <a href="{{ route('home') }}">Accueil</a>
                @foreach($categories as $navCategory)
                    <a class="{{ $category->is($navCategory) ? 'active' : '' }}" href="{{ route('category.show', $navCategory->slug) }}">{{ $navCategory->name }}</a>
                @endforeach
            </div>
        </nav>
    </header>

    <main class="container">
        <a href="{{ route('home') }}" class="back-link">← Retour à l’accueil</a>
        <header class="category-heading">
            <p>Rubrique</p>
            <h1>{{ $category->name }}</h1>
            @if($category->description)
                <p class="description">{{ $category->description }}</p>
            @endif
        </header>

        @if($articles->isNotEmpty())
            <section class="article-grid" aria-label="Articles de la rubrique {{ $category->name }}">
                @foreach($articles as $article)
                    <article class="article-card">
                        <a class="article-image" href="{{ route('article.show', $article->slug) }}" aria-label="Lire : {{ $article->title }}">
                            @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="" loading="lazy">
                            @endif
                        </a>
                        <div class="article-content">
                            <a class="article-category" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            <h2><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h2>
                            @if($article->excerpt)
                                <p class="excerpt">{{ $article->excerpt }}</p>
                            @else
                                <p class="excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 160) }}</p>
                            @endif
                            <div class="meta">
                                @if($article->author) {{ $article->author->name }} · @endif
                                {{ $article->published_at?->translatedFormat('d F Y à H:i') ?: $article->created_at->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
            @if($articles->hasPages())
                <nav class="pagination" aria-label="Pagination">
                    {{ $articles->links() }}
                </nav>
            @endif
        @else
            <div class="empty-state">Aucun article n’est publié dans cette rubrique pour le moment.</div>
        @endif
    </main>

    <footer class="footer">
        <div class="container"><p>© {{ now()->year }} Le Patriote — Tous droits réservés.</p></div>
    </footer>
</body>
</html>
