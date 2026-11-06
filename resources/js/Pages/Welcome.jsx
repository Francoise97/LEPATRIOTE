import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';

function SearchIcon() {
    return (
        <svg aria-hidden="true" viewBox="0 0 24 24">
            <circle cx="10.8" cy="10.8" r="6.8" />
            <path d="m16 16 5 5" />
        </svg>
    );
}

function ArrowIcon() {
    return (
        <svg aria-hidden="true" viewBox="0 0 24 24">
            <path d="M5 12h14M13 5l7 7-7 7" />
        </svg>
    );
}

function LockIcon() {
    return (
        <svg aria-hidden="true" viewBox="0 0 24 24">
            <rect x="4" y="10" width="16" height="11" rx="2" />
            <path d="M8 10V7a4 4 0 0 1 8 0v3m-4 5v2" />
        </svg>
    );
}

function EyeIcon() {
    return (
        <svg aria-hidden="true" viewBox="0 0 24 24">
            <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6S2 12 2 12Z" />
            <circle cx="12" cy="12" r="2.5" />
        </svg>
    );
}

function HeartIcon() {
    return (
        <svg aria-hidden="true" viewBox="0 0 24 24">
            <path d="M20.8 8.7c0 4.2-8.8 10-8.8 10s-8.8-5.8-8.8-10A4.7 4.7 0 0 1 12 6.1a4.7 4.7 0 0 1 8.8 2.6Z" />
        </svg>
    );
}

function LikeButton({ article, csrfToken }) {
    const [liked, setLiked] = useState(article.liked);
    const [likes, setLikes] = useState(article.likes);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState('');

    async function toggleLike() {
        if (busy) return;
        setBusy(true);
        setError('');

        try {
            const response = await fetch(article.likeUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (!response.ok) throw new Error('Impossible d’enregistrer votre réaction.');
            const result = await response.json();
            setLiked(result.liked);
            setLikes(result.likes);
        } catch {
            setError('Votre réaction n’a pas pu être enregistrée. Réessayez.');
        } finally {
            setBusy(false);
        }
    }

    return (
        <span className="like-control">
            <button type="button" className={`like-button${liked ? ' is-liked' : ''}`} onClick={toggleLike} disabled={busy} aria-pressed={liked} aria-label={`${liked ? 'Retirer votre mention J’aime' : 'Aimer cet article'} — ${likes} mention${likes === 1 ? '' : 's'} J’aime`}>
                <HeartIcon /> {new Intl.NumberFormat('fr-FR').format(likes)}
            </button>
            {error && <span className="like-error" role="status">{error}</span>}
        </span>
    );
}

function ArticleCard({ article, csrfToken }) {
    return (
        <article className="article-card">
            <a
                className={`article-image${article.image ? '' : ' article-image--empty'}`}
                href={article.url}
                aria-label={`Lire : ${article.title}`}
            >
                {article.image ? <img src={article.image} alt="" loading="lazy" /> : <span>Le Patriote</span>}
                {article.category && <span className="image-category">{article.category}</span>}
            </a>
            <div className="article-copy">
                {article.categoryUrl ? (
                    <a className="article-category" href={article.categoryUrl}>{article.category}</a>
                ) : (
                    <span className="article-category">{article.category}</span>
                )}
                {article.country && <span className="article-country">{article.country}</span>}
                <h3><a href={article.url}>{article.title}</a></h3>
                <p>{article.excerpt}</p>
                <div className="article-meta">
                    <time dateTime={article.publishedAt}>
                        {new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(article.publishedAt))}
                    </time>
                    <span className="view-count" aria-label={`${article.views} vues`}>
                        <EyeIcon />
                        {new Intl.NumberFormat('fr-FR').format(article.views)}
                    </span>
                    <LikeButton article={article} csrfToken={csrfToken} />
                </div>
            </div>
        </article>
    );
}

export default function Welcome({
    heroArticles = [],
    relatedArticles = [],
    latestArticles = [],
    breakingItems = [],
    breakingLabel = 'LE FIL INFO',
    categories = [],
    countries = [],
    selectedCountry = null,
    advertisements = [],
    search = '',
    csrfToken,
    homeUrl,
    newsletterUrl,
    adminUrl,
    currentDate,
    siteAppearance = {},
}) {
    const [activeSlide, setActiveSlide] = useState(0);
    const hero = heroArticles[activeSlide];

    useEffect(() => {
        if (heroArticles.length < 2) return undefined;
        const timer = window.setInterval(() => {
            setActiveSlide((current) => (current + 1) % heroArticles.length);
        }, 7000);
        return () => window.clearInterval(timer);
    }, [heroArticles.length]);

    const changeSlide = (direction) => {
        setActiveSlide((current) => (current + direction + heroArticles.length) % heroArticles.length);
    };

    return (
        <>
            <Head title="Accueil" />

            <div className="news-site" style={{ '--navy': siteAppearance.primaryColor || '#102b4e', '--red': siteAppearance.accentColor || '#a51d35' }}>
                <div className="topbar">
                    <div className="site-container topbar-inner">
                        <time>{currentDate}</time>
                        <div className="topbar-tools">
                            <span className="language-label"><span aria-hidden="true">◎</span> Français</span>
                            <a href="#newsletter">La newsletter</a>
                        </div>
                    </div>
                </div>

                <header className="masthead">
                    <div className="site-container masthead-inner">
                        <Link className="brand" href={homeUrl} aria-label="Le Patriote, accueil">
                            <img src={siteAppearance.logo || '/images/logo.png'} alt="Le Patriote" />
                        </Link>

                        <p className={`masthead-slogan${siteAppearance.font === 'sans' ? ' masthead-slogan--sans' : ''}`}>{siteAppearance.slogan || 'L’information qui engage'}</p>

                        <form className="search-form" method="get" action={homeUrl} role="search">
                            <label className="sr-only" htmlFor="site-search">Rechercher une actualité</label>
                            <input id="site-search" name="q" type="search" defaultValue={search} placeholder="Rechercher une actualité..." />
                            <label className="sr-only" htmlFor="country-filter">Filtrer par pays</label>
                            <select id="country-filter" name="country" defaultValue={selectedCountry || ''} aria-label="Filtrer par pays">
                                <option value="">Tous les pays</option>
                                {countries.map((country) => <option key={country.id} value={country.id}>{country.name}</option>)}
                            </select>
                            <button type="submit" aria-label="Rechercher"><SearchIcon /></button>
                        </form>

                        <a className="newsletter-button" href="#newsletter">Newsletter <ArrowIcon /></a>
                    </div>
                </header>

                <nav className="main-nav" aria-label="Navigation principale">
                    <div className="site-container nav-inner">
                        <Link className="nav-home" href={homeUrl}>Accueil</Link>
                        {categories.map((category) => (
                            <a key={category.id} href={category.url}>{category.name}</a>
                        ))}
                    </div>
                </nav>

                {breakingItems.length > 0 && (
                    <section className="breaking" aria-label={breakingLabel}>
                        <div className="site-container breaking-inner">
                            <span className="breaking-label">{breakingLabel}</span>
                            <div className="breaking-window">
                                <div className="breaking-track">
                                    {[0, 1].map((copy) => (
                                        <div className="breaking-group" key={copy} aria-hidden={copy === 1}>
                                            {breakingItems.map((item) => item.url ? (
                                                <a key={item.id} href={item.url} tabIndex={copy === 1 ? -1 : undefined}>{item.text}</a>
                                            ) : (
                                                <span key={item.id} tabIndex={copy === 1 ? -1 : undefined}>{item.text}</span>
                                            ))}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </section>
                )}

                <main className="site-container page-content">
                    {search && (
                        <div className="search-summary">
                            Résultats de recherche pour <strong>« {search} »</strong>
                            <a href={homeUrl}>Effacer</a>
                        </div>
                    )}

                    <section className="lead-layout" aria-label="À la une">
                        <div className="lead-column">
                            {hero ? (
                                <article className="lead-story">
                                    {hero.image ? <img className="lead-image" src={hero.image} alt="" /> : <div className="lead-image lead-image--empty" />}
                                    <div className="lead-shade" />
                                    <div className="lead-copy">
                                        {hero.categoryUrl ? (
                                            <a className="eyebrow" href={hero.categoryUrl}>{hero.category}</a>
                                        ) : (
                                            <span className="eyebrow">{hero.category}</span>
                                        )}
                                        <h1><a href={hero.url}>{hero.title}</a></h1>
                                        <p>{hero.excerpt}</p>
                                        <div className="lead-bottom">
                                            <span>{new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(hero.publishedAt))}</span>
                                            {heroArticles.length > 1 && (
                                                <div className="carousel-controls" aria-label="Navigation des articles à la une">
                                                    <button type="button" onClick={() => changeSlide(-1)} aria-label="Article précédent">←</button>
                                                    <span>{String(activeSlide + 1).padStart(2, '0')} / {String(heroArticles.length).padStart(2, '0')}</span>
                                                    <button type="button" onClick={() => changeSlide(1)} aria-label="Article suivant">→</button>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </article>
                            ) : (
                                <div className="empty-feature">
                                    <span className="empty-kicker">LE PATRIOTE</span>
                                    <h1>Aucun article n’est publié pour le moment.</h1>
                                    <p>Revenez bientôt découvrir l’actualité.</p>
                                </div>
                            )}
                        </div>

                        <aside className="related-panel" aria-labelledby="related-heading">
                            <div className="section-title section-title--compact">
                                <span className="section-kicker">À LIRE AUSSI</span>
                                <h2 id="related-heading">Les plus lus</h2>
                            </div>
                            {relatedArticles.length > 0 ? (
                                <ol className="related-list">
                                    {relatedArticles.slice(0, 3).map((article, index) => (
                                        <li key={article.id}>
                                            <span className="related-number">{String(index + 1).padStart(2, '0')}</span>
                                            <div>
                                                {article.category && <span className="related-category">{article.category}</span>}
                                                <a href={article.url}>{article.title}</a>
                                                <span className="related-views"><EyeIcon /> {new Intl.NumberFormat('fr-FR').format(article.views)} vues</span>
                                            </div>
                                        </li>
                                    ))}
                                </ol>
                            ) : (
                                <p className="related-empty">Les articles les plus lus apparaîtront ici.</p>
                            )}
                            {advertisements.filter((advertisement) => advertisement.placement === 'sidebar').map((advertisement, index) => (
                                <a className="sidebar-ad" key={`${advertisement.url}-${index}`} href={advertisement.url} target="_blank" rel="noopener noreferrer">
                                    <span className="ad-label">PUBLICITÉ</span>
                                    <img src={advertisement.image} alt={advertisement.title} loading="lazy" />
                                </a>
                            ))}
                        </aside>
                    </section>

                    {categories.length > 0 && (
                        <section className="categories-section" aria-labelledby="categories-heading">
                            <div className="section-title section-title--row">
                                <div>
                                    <span className="section-kicker">EXPLORER</span>
                                    <h2 id="categories-heading">Nos rubriques</h2>
                                </div>
                                <span className="section-note">L’actualité, sous tous ses angles</span>
                            </div>
                            <div className="category-list">
                                {categories.map((category) => (
                                    <a className="category-chip" key={category.id} href={category.url}>
                                        {category.name}<ArrowIcon />
                                    </a>
                                ))}
                            </div>
                        </section>
                    )}

                    {advertisements.some((advertisement) => advertisement.placement === 'home') && (
                        <aside className="advertisement-strip" aria-label="Publicité">
                            {advertisements.filter((advertisement) => advertisement.placement === 'home').map((advertisement, index) => (
                                <a key={`${advertisement.url}-${index}`} href={advertisement.url} target="_blank" rel="noopener noreferrer" aria-label={advertisement.title}>
                                    <span className="ad-label">PUBLICITÉ</span>
                                    <img src={advertisement.image} alt={advertisement.title} loading="lazy" />
                                </a>
                            ))}
                        </aside>
                    )}

                    <section className="latest-section" aria-labelledby="latest-heading">
                        <div className="section-title section-title--row">
                            <div>
                                <span className="section-kicker">EN CONTINU</span>
                                <h2 id="latest-heading">{search ? 'Articles trouvés' : 'Dernières actualités'}</h2>
                            </div>
                            <span className="section-note">{latestArticles.length} article{latestArticles.length === 1 ? '' : 's'}</span>
                        </div>
                        {latestArticles.length > 0 ? (
                            <div className="article-grid">
                                {latestArticles.map((article) => <ArticleCard key={article.id} article={article} csrfToken={csrfToken} />)}
                            </div>
                        ) : (
                            <p className="empty-state">
                                {search ? 'Aucun article ne correspond à votre recherche.' : 'Les prochains articles apparaîtront ici dès leur publication.'}
                            </p>
                        )}
                    </section>

                    <section className="newsletter" id="newsletter" aria-labelledby="newsletter-heading">
                        <div className="newsletter-mark" aria-hidden="true">LP</div>
                        <div className="newsletter-copy">
                            <span className="section-kicker">RESTEZ INFORMÉ</span>
                            <h2 id="newsletter-heading">L’essentiel de l’actualité, dans votre boîte mail.</h2>
                            <p>Recevez notre sélection d’informations et nos dernières actualités.</p>
                        </div>
                        <form className="newsletter-form" method="post" action={newsletterUrl}>
                            <input type="hidden" name="_token" value={csrfToken} />
                            <label className="sr-only" htmlFor="newsletter-email">Votre adresse e-mail</label>
                            <input id="newsletter-email" type="email" name="email" placeholder="Votre adresse e-mail" required />
                            <button type="submit">S’inscrire <ArrowIcon /></button>
                        </form>
                    </section>
                </main>

                {advertisements.filter((advertisement) => advertisement.placement === 'footer').length > 0 && (
                    <aside className="footer-ad-strip" aria-label="Publicité">
                        {advertisements.filter((advertisement) => advertisement.placement === 'footer').map((advertisement, index) => (
                            <a key={`${advertisement.url}-${index}`} href={advertisement.url} target="_blank" rel="noopener noreferrer" aria-label={advertisement.title}>
                                <span className="ad-label">PUBLICITÉ</span>
                                <img src={advertisement.image} alt={advertisement.title} loading="lazy" />
                            </a>
                        ))}
                    </aside>
                )}

                <footer className="site-footer">
                    <div className="site-container footer-inner">
                        <a className="footer-brand" href={homeUrl}>LE PATRIOTE</a>
                        <p>© {new Date().getFullYear()} Le Patriote — Tous droits réservés.</p>
                        <a className="admin-entry" href={adminUrl} aria-label="Accès administrateur">
                            <LockIcon /><span>Administration</span>
                        </a>
                    </div>
                </footer>
            </div>
        </>
    );
}
