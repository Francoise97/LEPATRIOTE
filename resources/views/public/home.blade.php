<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Le Patriote : l'actualité politique, économique, sociale et culturelle en Côte d'Ivoire et dans le monde.">
    <title>Le Patriote - L'actualité, au cœur de l'essentiel</title>
    <style>
        :root {
            color-scheme: light;
            --navy: #102b4e;
            --navy-dark: #0a1d35;
            --red: #a51d35;
            --ink: #17212f;
            --muted: #687386;
            --paper: #f4f5f7;
            --line: #e2e6eb;
            --white: #fff;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: Arial, Helvetica, sans-serif; }
        a { color: inherit; }
        .container { width: min(1180px, calc(100% - 40px)); margin: 0 auto; }
        .topbar { background: var(--navy-dark); color: #d7dfeb; font-size: 12px; }
        .topbar-inner { min-height: 34px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .topbar a { color: #fff; text-decoration: none; }
        .topbar form { display: inline; }
        .topbar button { padding: 0; border: 0; background: none; color: #fff; font: inherit; cursor: pointer; }
        .masthead { background: var(--white); }
        .masthead-inner { min-height: 112px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .brand { display: inline-flex; align-items: center; text-decoration: none; }
        .brand img { display: block; width: auto; max-width: 290px; max-height: 82px; object-fit: contain; }
        .brand-name { color: var(--navy); font-family: Georgia, 'Times New Roman', serif; font-size: clamp(29px, 4vw, 42px); font-weight: 700; letter-spacing: -.06em; }
        .masthead-note { color: var(--muted); font-family: Georgia, 'Times New Roman', serif; font-size: 17px; font-style: italic; }
        .category-nav { position: sticky; top: 0; z-index: 5; background: var(--navy); box-shadow: 0 3px 12px #0a1d3520; }
        .category-nav-inner { display: flex; align-items: center; min-height: 48px; gap: 26px; overflow-x: auto; scrollbar-width: thin; }
        .category-nav a { flex: 0 0 auto; color: #fff; text-decoration: none; font-size: 12px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; }
        .category-nav a:hover, .category-nav a:focus-visible { color: #ffd27b; }
        .category-nav .home-link { color: #ffd27b; }
        .breaking { background: var(--red); color: #fff; }
        .breaking-inner { display: flex; align-items: center; gap: 18px; min-height: 44px; overflow: hidden; }
        .breaking-label { flex: 0 0 auto; font-size: 11px; font-weight: 800; letter-spacing: .1em; }
        .breaking-links { display: flex; gap: 24px; overflow-x: auto; white-space: nowrap; scrollbar-width: none; }
        .breaking-links::-webkit-scrollbar { display: none; }
        .breaking-links a { color: #fff; font-size: 13px; text-decoration: none; }
        .breaking-links a:hover { text-decoration: underline; }
        main { padding: 30px 0 52px; }
        .section-heading { display: flex; align-items: baseline; justify-content: space-between; gap: 16px; margin: 0 0 18px; border-bottom: 1px solid var(--line); }
        .section-heading h2 { margin: 0; padding: 0 0 11px; border-bottom: 3px solid var(--red); color: var(--navy); font-family: Georgia, 'Times New Roman', serif; font-size: 24px; }
        .section-heading a { color: var(--red); font-size: 13px; font-weight: 700; text-decoration: none; }
        .lead-layout { display: grid; grid-template-columns: minmax(0, 1.65fr) minmax(280px, .85fr); gap: 24px; }
        .lead-story { position: relative; min-height: 430px; overflow: hidden; display: flex; align-items: flex-end; background: linear-gradient(135deg, #274a70, #0a1d35); color: #fff; }
        .lead-story::after { position: absolute; inset: 20% 0 0; background: linear-gradient(transparent, #07182de8); content: ''; }
        .lead-story > img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .lead-copy { position: relative; z-index: 1; width: 100%; padding: 34px; }
        .eyebrow { display: inline-block; margin-bottom: 13px; padding: 6px 9px; background: var(--red); color: #fff; font-size: 10px; font-weight: 800; letter-spacing: .1em; text-decoration: none; text-transform: uppercase; }
        .lead-copy h1 { max-width: 740px; margin: 0 0 13px; font-family: Georgia, 'Times New Roman', serif; font-size: clamp(29px, 4vw, 46px); line-height: 1.08; }
        .lead-copy h1 a { color: #fff; text-decoration: none; }
        .lead-copy h1 a:hover { text-decoration: underline; text-decoration-thickness: 1px; }
        .lead-copy p { max-width: 680px; margin: 0 0 17px; color: #e4e9ef; font-size: 15px; line-height: 1.6; }
        .story-meta { color: var(--muted); font-size: 12px; }
        .lead-copy .story-meta { color: #e0e6ee; }
        .side-column { display: flex; flex-direction: column; gap: 22px; }
        .popular-panel { padding: 20px; background: var(--white); border-top: 3px solid var(--navy); }
        .popular-panel h2 { margin: 0 0 8px; color: var(--navy); font-family: Georgia, 'Times New Roman', serif; font-size: 21px; }
        .popular-item { display: grid; grid-template-columns: 35px 1fr; gap: 12px; padding: 15px 0; border-bottom: 1px solid var(--line); }
        .popular-item:last-child { padding-bottom: 0; border-bottom: 0; }
        .popular-number { color: #cbd2dc; font-family: Georgia, 'Times New Roman', serif; font-size: 27px; font-weight: 700; line-height: 1; }
        .popular-item a { color: var(--ink); font-family: Georgia, 'Times New Roman', serif; font-size: 15px; font-weight: 700; line-height: 1.35; text-decoration: none; }
        .popular-item a:hover { color: var(--red); }
        .popular-item .story-meta { margin-top: 7px; }
        .category-strip { display: flex; flex-wrap: wrap; gap: 9px; margin: 28px 0 36px; }
        .category-chip { padding: 9px 14px; border: 1px solid #d5dce5; border-radius: 2px; background: var(--white); color: var(--navy); font-size: 12px; font-weight: 700; text-decoration: none; }
        .category-chip:hover { border-color: var(--red); color: var(--red); }
        .latest-section { margin-top: 8px; }
        .article-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px; }
        .article-card { overflow: hidden; background: var(--white); }
        .article-image { display: block; height: 195px; overflow: hidden; background: linear-gradient(135deg, #dce5ee, #aebed0); }
        .article-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .25s ease; }
        .article-card:hover .article-image img { transform: scale(1.035); }
        .article-copy { padding: 17px 18px 19px; }
        .article-category { color: var(--red); font-size: 10px; font-weight: 800; letter-spacing: .09em; text-decoration: none; text-transform: uppercase; }
        .article-copy h3 { margin: 9px 0; color: var(--navy); font-family: Georgia, 'Times New Roman', serif; font-size: 20px; line-height: 1.25; }
        .article-copy h3 a { text-decoration: none; }
        .article-copy h3 a:hover { color: var(--red); }
        .article-copy p { margin: 0 0 14px; color: #586477; font-size: 14px; line-height: 1.55; }
        .empty-state { padding: 28px; background: var(--white); color: var(--muted); }
        .newsletter { display: grid; grid-template-columns: 1fr minmax(300px, .9fr); align-items: center; gap: 30px; margin-top: 42px; padding: 30px 34px; background: var(--navy); color: #fff; }
        .newsletter h2 { margin: 0 0 8px; font-family: Georgia, 'Times New Roman', serif; font-size: 25px; }
        .newsletter p { margin: 0; color: #d3dce8; font-size: 14px; line-height: 1.5; }
        .newsletter-form { display: flex; gap: 8px; }
        .newsletter-form input { width: 100%; min-width: 0; padding: 12px 13px; border: 1px solid #fff; border-radius: 0; font: inherit; }
        .newsletter-form button { flex: 0 0 auto; padding: 0 17px; border: 0; background: var(--red); color: #fff; font-weight: 700; cursor: pointer; }
        .newsletter-form button:hover { background: #86162b; }
        .form-feedback { grid-column: 1 / -1; color: #fff; font-size: 13px; }
        .form-feedback.error { color: #ffd4d4; }
        .footer { padding: 25px 0; background: var(--navy-dark); color: #d1d9e3; font-size: 13px; }
        .footer-inner { display: flex; justify-content: space-between; gap: 16px; }
        .footer p { margin: 0; }
        @media (max-width: 820px) {
            .masthead-inner { min-height: 90px; }
            .masthead-note { display: none; }
            .lead-layout { grid-template-columns: 1fr; }
            .lead-story { min-height: 390px; }
            .side-column { display: block; }
            .popular-panel { margin-top: 20px; }
            .popular-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 22px; }
            .article-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 560px) {
            .container { width: min(100% - 28px, 1180px); }
            .topbar-inner { min-height: 32px; }
            .masthead-inner { min-height: 76px; }
            .brand img { max-width: 225px; max-height: 62px; }
            .category-nav-inner { min-height: 44px; gap: 20px; }
            .breaking-inner { gap: 12px; min-height: 40px; }
            .breaking-label { font-size: 10px; }
            .breaking-links a { font-size: 12px; }
            main { padding-top: 18px; }
            .lead-story { min-height: 370px; }
            .lead-copy { padding: 22px; }
            .lead-copy h1 { font-size: 30px; }
            .lead-copy p { font-size: 14px; }
            .popular-list, .article-grid { grid-template-columns: 1fr; }
            .article-image { height: 210px; }
            .newsletter { grid-template-columns: 1fr; gap: 18px; padding: 24px 20px; }
            .newsletter-form { flex-direction: column; }
            .newsletter-form button { min-height: 44px; }
            .footer-inner { flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
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
                    <img src="{{ asset('images/logo.png') }}" alt="Le Patriote">
                </a>
                <span class="masthead-note">L'actualité, au cœur de l'essentiel</span>
            </div>
        </div>
        <nav class="category-nav" aria-label="Rubriques">
            <div class="container category-nav-inner">
                <a class="home-link" href="{{ route('home') }}">Accueil</a>
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </nav>
    </header>

    <div class="breaking" aria-label="Dernières nouvelles">
        <div class="container breaking-inner">
            <span class="breaking-label">À LA UNE</span>
            <div class="breaking-links">
                @forelse($breakingArticles as $article)
                    <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                @empty
                    @if($heroArticle)
                        <a href="{{ route('article.show', $heroArticle->slug) }}">{{ $heroArticle->title }}</a>
                    @else
                        <span>Retrouvez bientôt les dernières nouvelles.</span>
                    @endif
                @endforelse
            </div>
        </div>
    </div>

    <main class="container">
        @if($heroArticle)
            <section class="lead-layout" aria-label="À la une et articles populaires">
                <article class="lead-story">
                    @if($heroArticle->featured_image)
                        <img src="{{ asset('storage/' . $heroArticle->featured_image) }}" alt="" fetchpriority="high">
                    @endif
                    <div class="lead-copy">
                        @if($heroArticle->category)
                            <a class="eyebrow" href="{{ route('category.show', $heroArticle->category->slug) }}">{{ $heroArticle->category->name }}</a>
                        @else
                            <span class="eyebrow">À la une</span>
                        @endif
                        <h1><a href="{{ route('article.show', $heroArticle->slug) }}">{{ $heroArticle->title }}</a></h1>
                        @if($heroArticle->excerpt)
                            <p>{{ $heroArticle->excerpt }}</p>
                        @elseif($heroArticle->content)
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($heroArticle->content), 180) }}</p>
                        @endif
                        <div class="story-meta">
                            @if($heroArticle->author) Par {{ $heroArticle->author->name }} · @endif
                            {{ $heroArticle->published_at ? $heroArticle->published_at->format('d/m/Y à H:i') : $heroArticle->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </article>
                <aside class="side-column">
                    <section class="popular-panel">
                        <h2>Les plus lus</h2>
                        @if($popularArticles->isNotEmpty())
                            <div class="popular-list">
                                @foreach($popularArticles as $index => $article)
                                    <article class="popular-item">
                                        <span class="popular-number">{{ sprintf('%02d', $index + 1) }}</span>
                                        <div>
                                            <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                                            <div class="story-meta">{{ number_format($article->views_count, 0, ',', ' ') }} lecture{{ $article->views_count > 1 ? 's' : '' }}</div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <p class="story-meta">Les articles les plus lus apparaîtront ici.</p>
                        @endif
                    </section>
                </aside>
            </section>
        @else
            <div class="empty-state">Aucun article n'est publié pour le moment. Revenez bientôt découvrir l'actualité.</div>
        @endif

        @if($categories->isNotEmpty())
            <nav class="category-strip" aria-label="Accès rapide aux rubriques">
                @foreach($categories as $category)
                    <a class="category-chip" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                @endforeach
            </nav>
        @endif

        <section class="latest-section" aria-labelledby="latest-heading">
            <div class="section-heading">
                <h2 id="latest-heading">Dernières actualités</h2>
            </div>
            @if($latestArticles->isNotEmpty())
                <div class="article-grid">
                    @foreach($latestArticles as $article)
                        <article class="article-card">
                            <a class="article-image" href="{{ route('article.show', $article->slug) }}" aria-label="Lire : {{ $article->title }}">
                                @if($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="" loading="lazy">
                                @endif
                            </a>
                            <div class="article-copy">
                                @if($article->category)
                                    <a class="article-category" href="{{ route('category.show', $article->category->slug) }}">{{ $article->category->name }}</a>
                                @else
                                    <span class="article-category">Actualité</span>
                                @endif
                                <h3><a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a></h3>
                                <p>{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 145) }}</p>
                                <div class="story-meta">{{ $article->published_at ? $article->published_at->format('d/m/Y à H:i') : $article->created_at->format('d/m/Y') }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @elseif(!$heroArticle)
                <div class="empty-state">Les prochains articles apparaîtront ici dès leur publication.</div>
            @endif
        </section>

        <section class="newsletter" aria-labelledby="newsletter-heading">
            <div>
                <h2 id="newsletter-heading">L'essentiel de l'actualité dans votre boîte mail</h2>
                <p>Inscrivez-vous à la newsletter du Patriote pour recevoir notre sélection d'informations.</p>
            </div>
            <form class="newsletter-form" method="POST" action="{{ route('newsletter.subscribe') }}">
                @csrf
                <label for="newsletter-email" class="sr-only" style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">Votre adresse e-mail</label>
                <input id="newsletter-email" type="email" name="email" placeholder="Votre adresse e-mail" value="{{ old('email') }}" required>
                <button type="submit">S'inscrire</button>
            </form>
            @if(session('success'))
                <p class="form-feedback" role="status">{{ session('success') }}</p>
            @endif
            @error('email')
                <p class="form-feedback error" role="alert">{{ $message }}</p>
            @enderror
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <p>© {{ now()->year }} Le Patriote — Tous droits réservés.</p>
            <p>L'information qui engage.</p>
        </div>
    </footer>
</body>
</html>
