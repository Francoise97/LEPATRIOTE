@extends('admin.layout')

@section('title', 'Tableau de bord')

@push('styles')
    <style>
        .dashboard { display: grid; gap: 25px; }
        .dashboard-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; }
        .dashboard-kicker { margin: 0 0 8px; color: var(--red); font-size: 10px; font-weight: 750; letter-spacing: 1.5px; text-transform: uppercase; }
        .dashboard-heading h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: clamp(26px, 3vw, 34px); font-weight: 700; letter-spacing: -.5px; }
        .dashboard-heading p { margin: 8px 0 0; color: var(--muted); font-size: 13px; }
        .primary-action { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; gap: 8px; padding: 0 16px; border-radius: 8px; color: white; background: var(--red); box-shadow: 0 5px 12px rgba(165,31,53,.15); font-size: 12px; font-weight: 700; text-decoration: none; transition: background .18s, transform .18s; }
        .primary-action:hover { transform: translateY(-1px); background: #8d192d; }
        .primary-action svg { width: 16px; height: 16px; }
        .welcome-card { position: relative; overflow: hidden; min-height: 144px; padding: 25px 28px; border-radius: 13px; color: white; background: linear-gradient(110deg, #173c5d 0%, #102b46 65%, #254e6e 100%); }
        .welcome-card::after { position: absolute; top: -64px; right: 7%; width: 250px; height: 250px; border: 1px solid rgba(255,255,255,.09); border-radius: 50%; box-shadow: 0 0 0 24px rgba(255,255,255,.025), 0 0 0 50px rgba(255,255,255,.02); content: ""; }
        .welcome-card > * { position: relative; z-index: 1; }
        .welcome-card .welcome-label { margin: 0 0 9px; color: #b7c9d9; font-size: 11px; font-weight: 650; letter-spacing: .9px; text-transform: uppercase; }
        .welcome-card h2 { margin: 0; font-family: Georgia, "Times New Roman", serif; font-size: 22px; font-weight: 600; }
        .welcome-card p { margin: 8px 0 0; color: #d3deea; font-size: 12px; }
        .stats-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 14px; }
        .stat-card { display: flex; min-height: 112px; align-items: flex-start; gap: 13px; padding: 19px 17px; border: 1px solid var(--line); border-radius: 11px; background: var(--paper); }
        .stat-icon { display: grid; width: 38px; height: 38px; flex: none; place-items: center; border-radius: 10px; color: #315a7d; background: #edf4fa; }
        .stat-card:nth-child(2) .stat-icon { color: #22734d; background: #eaf6ef; }
        .stat-card:nth-child(3) .stat-icon { color: #a76a1b; background: #fff5e5; }
        .stat-card:nth-child(4) .stat-icon { color: #974258; background: #f9edf0; }
        .stat-card:nth-child(5) .stat-icon { color: #6b58a3; background: #f1eef9; }
        .stat-icon svg { width: 19px; height: 19px; }
        .stat-label { margin: 1px 0 7px; color: var(--muted); font-size: 11px; }
        .stat-value { color: var(--navy); font-size: 23px; font-weight: 750; line-height: 1; letter-spacing: -.5px; }
        .dashboard-columns { display: grid; grid-template-columns: minmax(0, 1.65fr) minmax(235px, .85fr); gap: 16px; align-items: start; }
        .panel { overflow: hidden; border: 1px solid var(--line); border-radius: 11px; background: var(--paper); }
        .panel-heading { display: flex; min-height: 62px; align-items: center; justify-content: space-between; gap: 12px; padding: 0 19px; border-bottom: 1px solid var(--line); }
        .panel-heading h2 { margin: 0; color: var(--navy); font-size: 14px; font-weight: 750; }
        .panel-link { color: var(--red); font-size: 11px; font-weight: 700; text-decoration: none; }
        .panel-link:hover { text-decoration: underline; }
        .article-list { display: grid; }
        .article-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 10px 14px; align-items: center; padding: 14px 19px; border-bottom: 1px solid #eef2f6; text-decoration: none; transition: background .15s; }
        .article-row:last-child { border-bottom: 0; }
        .article-row:hover { background: #f9fbfd; }
        .article-title { overflow: hidden; color: #263b51; font-size: 12px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .article-meta { margin-top: 5px; color: var(--muted); font-size: 10px; }
        .article-date { color: var(--muted); font-size: 10px; white-space: nowrap; }
        .status-badge { display: inline-flex; align-items: center; gap: 5px; margin-top: 7px; padding: 4px 8px; border-radius: 999px; font-size: 9px; font-weight: 750; }
        .status-badge::before { width: 5px; height: 5px; border-radius: 50%; background: currentColor; content: ""; }
        .status-published { color: #24744d; background: #eaf6ef; }
        .status-draft { color: #94641f; background: #fff4e2; }
        .status-archived { color: #69798a; background: #edf1f5; }
        .empty-list { padding: 28px 20px; color: var(--muted); font-size: 12px; text-align: center; }
        .quick-actions { display: grid; gap: 10px; padding: 16px; }
        .quick-action { display: flex; min-height: 59px; align-items: center; gap: 12px; padding: 10px 12px; border: 1px solid var(--line); border-radius: 9px; color: var(--ink); text-decoration: none; transition: border-color .16s, background .16s; }
        .quick-action:hover { border-color: #c8d6e3; background: #f8fafc; }
        .quick-action-icon { display: grid; width: 34px; height: 34px; flex: none; place-items: center; border-radius: 8px; color: #315a7d; background: #edf4fa; }
        .quick-action-icon svg { width: 17px; height: 17px; }
        .quick-action strong { display: block; font-size: 11px; }
        .quick-action small { display: block; margin-top: 4px; color: var(--muted); font-size: 10px; }
        @media (max-width: 1050px) {
            .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .dashboard-columns { grid-template-columns: minmax(0, 1fr); }
        }
        @media (max-width: 560px) {
            .dashboard { gap: 18px; }
            .dashboard-heading { align-items: flex-start; flex-direction: column; }
            .dashboard-heading .primary-action { width: 100%; }
            .welcome-card { min-height: 132px; padding: 21px; }
            .welcome-card h2 { font-size: 19px; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 9px; }
            .stat-card { min-height: 98px; gap: 9px; padding: 13px 10px; }
            .stat-icon { width: 32px; height: 32px; border-radius: 8px; }
            .stat-value { font-size: 20px; }
            .stat-label { font-size: 10px; }
            .article-row { padding: 13px 14px; }
            .panel-heading { padding: 0 14px; }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard">
        <header class="dashboard-heading">
            <div>
                <p class="dashboard-kicker">Espace rédaction</p>
                <h1>Tableau de bord</h1>
                <p>Suivez l’activité et gérez les contenus du journal.</p>
            </div>
            <a class="primary-action" href="{{ route('admin.articles.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Écrire un article
            </a>
        </header>

        <section class="welcome-card" aria-label="Bienvenue">
            <p class="welcome-label">Votre rédaction, en un coup d’œil</p>
            <h2>Bonjour {{ auth()->user()->name }}.</h2>
            <p>Voici le point sur les contenus de votre espace éditorial.</p>
        </section>

        <section class="stats-grid" aria-label="Statistiques éditoriales">
            <article class="stat-card">
                <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H20v17H7.5A2.5 2.5 0 0 0 5 21.5z"/><path d="M5 4.5v17M9 7h7M9 11h7"/></svg></span>
                <div><p class="stat-label">Tous les articles</p><div class="stat-value">{{ number_format($stats['articles'], 0, ',', ' ') }}</div></div>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg></span>
                <div><p class="stat-label">Publiés</p><div class="stat-value">{{ number_format($stats['published'], 0, ',', ' ') }}</div></div>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/></svg></span>
                <div><p class="stat-label">En brouillon</p><div class="stat-value">{{ number_format($stats['drafts'], 0, ',', ' ') }}</div></div>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="8" height="7" rx="1.5"/><rect x="13" y="4" width="8" height="7" rx="1.5"/><rect x="3" y="13" width="8" height="7" rx="1.5"/><rect x="13" y="13" width="8" height="7" rx="1.5"/></svg></span>
                <div><p class="stat-label">Rubriques</p><div class="stat-value">{{ number_format($stats['categories'], 0, ',', ' ') }}</div></div>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span>
                <div><p class="stat-label">Lectures cumulées</p><div class="stat-value">{{ number_format($stats['views'], 0, ',', ' ') }}</div></div>
            </article>
        </section>

        <section class="dashboard-columns">
            <div class="panel">
                <div class="panel-heading">
                    <h2>Articles récemment modifiés</h2>
                    <a class="panel-link" href="{{ route('admin.articles.index') }}">Tous les articles&nbsp; →</a>
                </div>
                <div class="article-list">
                    @forelse($recentArticles as $article)
                        <a class="article-row" href="{{ route('admin.articles.edit', $article) }}">
                            <div>
                                <div class="article-title">{{ $article->title }}</div>
                                <div class="article-meta">{{ $article->category?->name ?? 'Sans rubrique' }}</div>
                                <span @class([
                                    'status-badge',
                                    'status-published' => $article->status === 'published',
                                    'status-draft' => $article->status === 'draft',
                                    'status-archived' => $article->status === 'archived',
                                ])>
                                    {{ ['published' => 'Publié', 'draft' => 'Brouillon', 'archived' => 'Archivé'][$article->status] ?? ucfirst($article->status) }}
                                </span>
                            </div>
                            <time class="article-date" datetime="{{ $article->updated_at->toIso8601String() }}">{{ $article->updated_at->format('d/m/Y') }}</time>
                        </a>
                    @empty
                        <p class="empty-list">Aucun article pour le moment. Créez votre premier article pour commencer.</p>
                    @endforelse
                </div>
            </div>

            <aside class="panel">
                <div class="panel-heading"><h2>Raccourcis</h2></div>
                <div class="quick-actions">
                    <a class="quick-action" href="{{ route('admin.articles.create') }}">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></span>
                        <span><strong>Créer un article</strong><small>Ajouter un nouveau contenu</small></span>
                    </a>
                    <a class="quick-action" href="{{ route('admin.articles.index') }}">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 5h16M4 12h16M4 19h16"/></svg></span>
                        <span><strong>Gérer les articles</strong><small>Modifier ou publier un contenu</small></span>
                    </a>
                    <a class="quick-action" href="{{ route('admin.categories.index') }}">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="8" height="7" rx="1.5"/><rect x="13" y="4" width="8" height="7" rx="1.5"/><rect x="3" y="13" width="8" height="7" rx="1.5"/><rect x="13" y="13" width="8" height="7" rx="1.5"/></svg></span>
                        <span><strong>Gérer les rubriques</strong><small>{{ number_format($stats['categories'], 0, ',', ' ') }} rubrique{{ $stats['categories'] > 1 ? 's' : '' }}</small></span>
                    </a>
                    <a class="quick-action" href="{{ route('home') }}">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9v12h14V9M9 21v-7h6v7"/></svg></span>
                        <span><strong>Voir le site</strong><small>Ouvrir la page d’accueil publique</small></span>
                    </a>
                </div>
            </aside>
        </section>
    </div>
@endsection
