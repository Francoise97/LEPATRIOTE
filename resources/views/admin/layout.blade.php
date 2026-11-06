<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - Le Patriote</title>
    <style>
        :root {
            color-scheme: light;
            --navy: #102b46;
            --navy-deep: #0a2037;
            --red: #a51f35;
            --ink: #17283b;
            --muted: #718096;
            --line: #e7edf3;
            --paper: #fff;
            --canvas: #f4f7fa;
        }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: var(--canvas); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; }
        .admin-shell { min-height: 100vh; }
        .sidebar {
            position: fixed; inset: 0 auto 0 0; z-index: 5; display: flex; width: 252px; flex-direction: column;
            padding: 27px 18px 20px; color: #e6edf5; background: linear-gradient(165deg, var(--navy) 0%, var(--navy-deep) 100%);
        }
        .brand { display: flex; align-items: center; gap: 12px; padding: 0 10px 31px; color: white; text-decoration: none; }
        .brand-mark { display: grid; width: 40px; height: 40px; place-items: center; border-radius: 12px; background: var(--red); font-family: Georgia, serif; font-size: 24px; font-weight: 700; }
        .brand-name { font-family: Georgia, serif; font-size: 20px; font-weight: 700; letter-spacing: -.3px; }
        .brand-caption { margin-top: 3px; color: #a9bacb; font-size: 10px; letter-spacing: 1.15px; text-transform: uppercase; }
        .nav-label { padding: 0 12px; margin: 7px 0 10px; color: #8299ae; font-size: 10px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; }
        .side-nav { display: grid; gap: 5px; }
        .side-nav a { display: flex; min-height: 44px; align-items: center; gap: 12px; padding: 0 12px; border-radius: 9px; color: #d3deea; font-size: 13px; font-weight: 550; text-decoration: none; transition: background .18s, color .18s; }
        .side-nav a:hover, .side-nav a.active { color: white; background: rgba(255,255,255,.11); }
        .side-nav a.active { box-shadow: inset 3px 0 #e46b78; }
        .side-nav svg { width: 18px; height: 18px; flex: none; opacity: .9; }
        .sidebar-bottom { margin-top: auto; }
        .sidebar-divider { height: 1px; margin: 18px 8px; background: rgba(255,255,255,.12); }
        .sidebar-footer { padding: 0 12px; color: #91a5b8; font-size: 11px; line-height: 1.6; }
        .sidebar-footer a { display: inline-block; margin-bottom: 12px; color: #e6edf5; font-size: 12px; text-decoration: none; }
        .admin-main { min-height: 100vh; margin-left: 252px; }
        .topbar { display: flex; min-height: 74px; align-items: center; justify-content: flex-end; gap: 18px; padding: 0 38px; border-bottom: 1px solid var(--line); background: var(--paper); }
        .topbar-site { margin-right: auto; color: var(--muted); font-size: 12px; text-decoration: none; }
        .topbar-site:hover { color: var(--red); }
        .topbar-user { color: #4a5b6d; font-size: 12px; }
        .logout-button { border: 0; padding: 0; color: var(--red); background: none; cursor: pointer; font: inherit; font-size: 12px; }
        .content { width: min(1200px, 100%); margin: 0 auto; padding: 38px; }
        @media (max-width: 760px) {
            .sidebar { position: static; width: auto; padding: 16px 18px 12px; }
            .brand { padding: 0 2px 14px; }
            .nav-label, .sidebar-bottom { display: none; }
            .side-nav { display: flex; overflow-x: auto; gap: 6px; padding-bottom: 2px; }
            .side-nav a { min-height: 39px; flex: none; padding: 0 11px; font-size: 12px; }
            .side-nav a.active { box-shadow: inset 0 -2px #e46b78; }
            .admin-main { margin-left: 0; }
            .topbar { min-height: 58px; padding: 0 20px; }
            .content { padding: 26px 18px; }
        }
        @media (max-width: 420px) {
            .topbar { gap: 10px; }
            .topbar-user { display: none; }
            .side-nav a { gap: 7px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark" aria-hidden="true">P</span>
                <span>
                    <span class="brand-name">Le Patriote</span>
                    <span class="brand-caption" style="display:block">Espace rédaction</span>
                </span>
            </a>
            <p class="nav-label">Espace de travail</p>
            <nav class="side-nav" aria-label="Navigation d’administration">
                <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="11" width="7" height="10" rx="1.5"/><rect x="3" y="14" width="8" height="7" rx="1.5"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.articles.index') }}" @class(['active' => request()->routeIs('admin.articles.*')])>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H20v17H7.5A2.5 2.5 0 0 0 5 21.5z"/><path d="M5 4.5v17M9 7h7M9 11h7"/></svg>
                    Articles
                </a>
                <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="8" height="7" rx="1.5"/><rect x="13" y="4" width="8" height="7" rx="1.5"/><rect x="3" y="13" width="8" height="7" rx="1.5"/><rect x="13" y="13" width="8" height="7" rx="1.5"/></svg>
                    Catégories
                </a>
            </nav>
            <div class="sidebar-bottom">
                <div class="sidebar-divider"></div>
                <div class="sidebar-footer">
                    <a href="{{ route('home') }}">← &nbsp;Voir le site</a>
                    <div>Le Patriote · Administration</div>
                </div>
            </div>
        </aside>
        <div class="admin-main">
            <header class="topbar">
                <a class="topbar-site" href="{{ route('home') }}">← Retour au site</a>
                @auth
                    <span class="topbar-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="logout-button" type="submit">Déconnexion</button>
                    </form>
                @endauth
            </header>
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
