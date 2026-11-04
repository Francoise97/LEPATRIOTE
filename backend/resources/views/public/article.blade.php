<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $article->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?: $article->content), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?: $article->content), 160) }}">
    @if($article->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $article->featured_image) }}">
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <title>{{ $article->meta_title ?: $article->title }} - Le Patriote</title>
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
        .container { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        .topbar { background: var(--navy-deep); color: #d7dfeb; font-size: 12px; }
        .topbar-inner { min-height: 34px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .topbar a, .topbar button { color: #fff; text-decoration: none; }
        .topbar form { display: inline; }
        .topbar button { padding: 0; border: 0; background: none; font: inherit; cursor: pointer; }
        .masthead { background: var(--white); }
        .masthead-inner { min-height: 94px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand img { display: block; width: auto; max-width: 240px; height: 72px; object-fit: contain; }
        .brand-text { color: var(--navy); font: 700 34px Georgia, 'Times New Roman', serif; text-decoration: none; }
        .masthead-note { color: var(--muted); font: italic 15px Georgia, 'Times New Roman', serif; }
        .category-nav { position: sticky; top: 0; z-index: 4; background: var(--navy); box-shadow: 0 3px 12px #0a1d3520; }
        .category-nav ul { min-height: 47px; display: flex; align-items: center; gap: 25px; margin: 0; padding: 0; overflow-x: auto; list-style: none; scrollbar-width: thin; }
        .category-nav a { color: #fff; font-size: 11px; font-weight: 700; letter-spacing: .07em; text-decoration: none; text-transform: uppercase; white-space: nowrap; }
        .category-nav a:hover, .category-nav a.active { color: #ffd27b; }
        main { padding: 30px 0 50px; }
        .back-link { display: inline-block; margin-bottom: 22px; color: var(--navy); font-size: 13px; font-weight: 700; text-decoration: none; }
        .back-link:hover { color: var(--red); }
        .article-header { max-width: 850px; margin: 0 auto 24px; }
        .eyebrow { display: inline-block; padding: 6px 9px; background: var(--red); color: #fff; font-size: 10px; font-weight: 800; letter-spacing: .08em; text-decoration: none; text-transform: uppercase; }
        .article-header h1 { margin: 14px 0 12px; color: var(--navy); font: 700 clamp(32px, 5vw, 52px)/1.08 Georgia, 'Times New Roman', serif; }
        .article-excerpt { margin: 0 0 22px; color: #586477; font: 19px/1.55 Georgia, 'Times New Roman', serif; }
        .article-byline { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; padding: 15px 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .author-avatar { width: 42px; height: 42px; display: grid; flex: 0 0 auto; place-items: center; overflow: hidden; border-radius: 50%; background: #e8edf3; color: var(--navy); font: 700 17px Georgia, 'Times New Roman', serif; }
        .author-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .author-details { flex: 1 1 180px; }
        .author-details strong, .author-details span { display: block; }
        .author-details strong { color: var(--navy); font-size: 13px; }
        .author-details span, .published-date, .article-stats { margin-top: 5px; color: var(--muted); font-size: 11px; }
        .featured-image { margin: 0 0 28px; background: var(--white); }
        .featured-image img { display: block; width: 100%; max-height: 570px; object-fit: cover; }
        .featured-image figcaption { padding: 10px 13px; color: var(--muted); font-size: 11px; line-height: 1.5; }
        .article-layout { display: grid; grid-template-columns: minmax(0, 1fr) 215px; gap: 34px; align-items: start; }
        .article-content { min-width: 0; color: #293545; font: 18px/1.85 Georgia, 'Times New Roman', serif; overflow-wrap: anywhere; }
        .article-content p { margin: 0 0 22px; }
        .article-content h2, .article-content h3 { margin: 32px 0 12px; color: var(--navy); line-height: 1.3; }
        .article-content h2 { font-size: 27px; }
        .article-content h3 { font-size: 22px; }
        .article-content img, .article-content video { max-width: 100%; height: auto; }
        .article-content blockquote { margin: 25px 0; padding: 8px 0 8px 18px; border-left: 4px solid var(--red); color: var(--navy); font-size: 21px; font-style: italic; }
        .share-box { position: sticky; top: 68px; padding: 18px; background: var(--white); border-top: 3px solid var(--navy); }
        .share-box h2 { margin: 0 0 13px; color: var(--navy); font: 700 19px Georgia, 'Times New Roman', serif; }
        .share-actions { display: grid; gap: 8px; }
        .share-actions a, .share-actions button { padding: 10px; border: 1px solid var(--line); background: #fff; color: var(--navy); font-size: 12px; text-align: left; text-decoration: none; cursor: pointer; }
        .share-actions a:hover, .share-actions button:hover { border-color: var(--red); color: var(--red); }
        .share-status { min-height: 16px; margin: 8px 0 0; color: var(--muted); font-size: 11px; }
        .article-tags { display: flex; flex-wrap: wrap; gap: 8px; margin: 24px 0; }
        .article-tag { padding: 7px 10px; background: #e9edf2; color: var(--navy); font-size: 11px; text-decoration: none; }
        .related-section, .comments-section { margin-top: 38px; }
        .section-heading { margin: 0 0 16px; border-bottom: 1px solid var(--line); }
        .section-heading h2 { display: inline-block; margin: 0; padding: 0 0 10px; border-bottom: 3px solid var(--red); color: var(--navy); font: 700 24px Georgia, 'Times New Roman', serif; }
        .related-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 15px; }
        .related-card { padding: 17px; background: var(--white); }
        .related-category { color: var(--red); font-size: 10px; font-weight: 800; letter-spacing: .06em; text-decoration: none; text-transform: uppercase; }
        .related-card h3 { margin: 9px 0; color: var(--navy); font: 700 17px/1.35 Georgia, 'Times New Roman', serif; }
        .related-card h3 a { text-decoration: none; }
        .related-card h3 a:hover { color: var(--red); }
        .comments-section { padding: 22px; background: var(--white); }
        .comment { margin: 16px 0; padding: 16px; background: #f7f8fa; }
        .comment-author { color: var(--navy); font-weight: 700; }
        .comment-date { margin: 4px 0 10px; color: var(--muted); font-size: 11px; }
        .comment-content { line-height: 1.6; overflow-wrap: anywhere; }
        .comments-empty { color: var(--muted); font-size: 13px; }
        .comment-form { margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--line); }
        .comment-form h3 { margin: 0 0 14px; color: var(--navy); font: 700 19px Georgia, 'Times New Roman', serif; }
        .comment-form label { display: block; margin: 12px 0 5px; color: var(--ink); font-size: 12px; font-weight: 700; }
        .comment-form input, .comment-form textarea { width: 100%; padding: 11px; border: 1px solid #cbd2dc; border-radius: 0; font: inherit; }
        .comment-form textarea { resize: vertical; }
        .comment-form button { margin-top: 12px; padding: 11px 18px; border: 0; background: var(--red); color: #fff; font-weight: 700; cursor: pointer; }
        .form-error { margin-top: 5px; color: #b42318; font-size: 12px; }
        footer { margin-top: 20px; padding: 25px 0; background: var(--navy-deep); color: #d1d9e3; font-size: 12px; }
        footer p { margin: 0; }
        @media (max-width: 760px) {
            .masthead-inner { min-height: 78px; }
            .masthead-note { display: none; }
            .category-nav ul { gap: 20px; }
            .article-layout { grid-template-columns: 1fr; gap: 12px; }
            .share-box { position: static; grid-row: 1; }
            .share-actions { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .related-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 520px) {
            .container { width: calc(100% - 28px); }
            .brand img { max-width: 190px; height: 64px; }
            main { padding-top: 20px; }
            .article-header h1 { font-size: 34px; }
            .article-excerpt { font-size: 17px; }
            .article-content { font-size: 17px; line-height: 1.75; }
            .featured-image img { max-height: 320px; }
            .share-actions { grid-template-columns: 1fr; }
            .related-grid { grid-template-columns: 1fr; }
            .comments-section { padding: 17px; }
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
            <ul class="container">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                @foreach($categories as $category)
                    <li><a class="{{ $article->category_id === $category->id ? 'active' : '' }}" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </nav>
    </header>

    <main class="container">
        <a href="{{ $article->category ? route('category.show', $article->category->slug) : route('home') }}" class="back-link">← Retour {{ $article->category ? 'à la rubrique ' . $article->category->name : 'aux actualités' }}</a>

        <article>
            <header class="article-header">
                @if($article->category)
                    <a class="eyebrow" href="{{ route('category.show', $article->category->slug) }}">{{ $article->category->name }}</a>
                @else
                    <span class="eyebrow">Actualité</span>
                @endif
                <h1>{{ $article->title }}</h1>
                @if($article->excerpt)
                    <p class="article-excerpt">{{ $article->excerpt }}</p>
                @endif
                <div class="article-byline">
                    <div class="author-avatar">
                        @if($article->author?->avatar)
                            <img src="{{ asset('storage/' . $article->author->avatar) }}" alt="">
                        @else
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($article->author?->name ?? 'R', 0, 1)) }}
                        @endif
                    </div>
                    <div class="author-details">
                        <strong>{{ $article->author?->name ?? 'La rédaction' }}</strong>
                        @if($article->author?->bio)
                            <span>{{ $article->author->bio }}</span>
                        @else
                            <span>Rédaction du Patriote</span>
                        @endif
                    </div>
                    @if($article->published_at)
                        <time class="published-date" datetime="{{ $article->published_at->toIso8601String() }}">
                            Publié le {{ $article->published_at->translatedFormat('d F Y à H:i') }}
                        </time>
                    @endif
                </div>
                <p class="article-stats">{{ $readingTime }} min de lecture</p>
            </header>

            @if($article->featured_image)
                <figure class="featured-image">
                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                    @if($article->excerpt)
                        <figcaption>{{ $article->excerpt }}</figcaption>
                    @endif
                </figure>
            @endif

            <div class="article-layout">
                <div>
                    <div class="article-content">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    @if($article->tags->isNotEmpty())
                        <nav class="article-tags" aria-label="Mots-clés">
                            @foreach($article->tags as $tag)
                                <a class="article-tag" href="{{ route('category.show', $article->category->slug) }}">#{{ $tag->name }}</a>
                            @endforeach
                        </nav>
                    @endif

                    @if($article->source_url)
                        <p class="article-stats">
                            Source :
                            <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer">
                                consulter la référence de cet article
                            </a>
                        </p>
                    @endif
                </div>

                <aside class="share-box" aria-label="Partager cet article">
                    <h2>Partager l’article</h2>
                    <div class="share-actions">
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer">Facebook ↗</a>
                        <button type="button" id="copy-article-link">Copier le lien</button>
                    </div>
                    <p class="share-status" id="share-status" role="status" aria-live="polite"></p>
                </aside>
            </div>
        </article>

        @if($relatedArticles->isNotEmpty())
            <section class="related-section" aria-labelledby="related-heading">
                <div class="section-heading"><h2 id="related-heading">À lire aussi</h2></div>
                <div class="related-grid">
                    @foreach($relatedArticles as $relatedArticle)
                        <article class="related-card">
                            @if($relatedArticle->category)
                                <a class="related-category" href="{{ route('category.show', $relatedArticle->category->slug) }}">{{ $relatedArticle->category->name }}</a>
                            @endif
                            <h3><a href="{{ route('article.show', $relatedArticle->slug) }}">{{ $relatedArticle->title }}</a></h3>
                            <span class="published-date">{{ $relatedArticle->published_at?->translatedFormat('d F Y') }}</span>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="comments-section" aria-labelledby="comments-heading">
            <div class="section-heading"><h2 id="comments-heading">Commentaires ({{ $article->comments->count() }})</h2></div>

            @forelse($article->comments as $comment)
                <article class="comment">
                    <div class="comment-author">{{ $comment->author_name ?: 'Lecteur' }}</div>
                    <time class="comment-date" datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->translatedFormat('d F Y à H:i') }}</time>
                    <div class="comment-content">{{ $comment->content }}</div>
                </article>
            @empty
                <p class="comments-empty">Aucun commentaire pour le moment. Votre réaction sera soumise à modération avant publication.</p>
            @endforelse

            <div class="comment-form">
                <h3>Donner votre avis</h3>
                @if(session('success'))
                    <p role="status">{{ session('success') }}</p>
                @endif
                <form method="POST" action="{{ route('article.comment', $article->slug) }}">
                    @csrf
                    <label for="author_name">Votre nom</label>
                    <input id="author_name" type="text" name="author_name" value="{{ old('author_name') }}" maxlength="255" required>
                    @error('author_name') <p class="form-error" role="alert">{{ $message }}</p> @enderror

                    <label for="author_email">Votre adresse e-mail (non publiée)</label>
                    <input id="author_email" type="email" name="author_email" value="{{ old('author_email') }}" maxlength="255" required>
                    @error('author_email') <p class="form-error" role="alert">{{ $message }}</p> @enderror

                    <label for="comment_content">Votre commentaire</label>
                    <textarea id="comment_content" name="content" rows="5" minlength="10" required>{{ old('content') }}</textarea>
                    @error('content') <p class="form-error" role="alert">{{ $message }}</p> @enderror

                    <button type="submit">Envoyer pour modération</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container"><p>© {{ now()->year }} Le Patriote — Tous droits réservés.</p></div>
    </footer>

    <script>
        document.getElementById('copy-article-link').addEventListener('click', async () => {
            const status = document.getElementById('share-status');
            try {
                await navigator.clipboard.writeText(window.location.href);
                status.textContent = 'Lien copié.';
            } catch {
                status.textContent = 'Copie indisponible dans ce navigateur. Copiez l’adresse depuis la barre d’adresse.';
            }
        });
    </script>
</body>
</html>
