<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php';
// simple auth-aware nav
$isLoggedIn = est_connecte(); ?>
<section data-aos="fade-down" class="hero-section">
    <h1>Projet web basé sur les technologies PHP, SQL et JavaScript</h1>

    <p>
        Ce projet repose sur les technologies HTML, CSS, JavaScript et PHP, avec une base
        de données SQL, afin de démontrer l’intégration de fonctionnalités interactives et sécurisées.
    </p>

    <?php if (!$isLoggedIn): ?>
        <div class="hero-actions">
            <a href="/inscription" class="button-primary">Commencer maintenant</a>
            <a href="/connexion" class="button-secondary">Se connecter</a>
        </div>
    <?php else: ?>
        <div class="hero-actions">
            <a href="/profil" class="button-primary">Accéder à mon profil</a>
        </div>
    <?php endif; ?>

</section>

<div class="grid-layout">
    <section data-aos="fade-right" class="card" aria-labelledby="intro-heading">
        <h2 id="intro-heading">Fonctionnalités côté client</h2>
        <p>Carrousel développé from scratch en JavaScript à l’aide d’une classe dédiée.</p>
        <p>Menu hamburger responsive développé from scratch en JavaScript et CSS.</p>
        <p>Animations au défilement implémentées à l’aide de la bibliothèque AOS (Animate On Scroll).</p>
    </section>

    <section data-aos="fade-left" class="card" aria-labelledby="features-heading">
        <h2 id="features-heading">Fonctionnalités clés</h2>

        <ul class="feature-list">
            <li class="feature-item">
                <span class="feature-icon">✓</span>
                Système d’authentification avec gestion des sessions
            </li>

            <li class="feature-item">
                <span class="feature-icon">✓</span>
                Sécurisation des formulaires et des échanges de données
            </li>

            <li class="feature-item">
                <span class="feature-icon">✓</span>
                Développement de fonctionnalités côté client en JavaScript (classe dédiée)
            </li>

            <li class="feature-item">
                <span class="feature-icon">✓</span>
                Utilisation de bibliothèques pour les animations et l’UX
            </li>

            <li class="feature-item">
                <span class="feature-icon">✓</span>
                Communication asynchrone avec une API externe
            </li>

            <li class="feature-item">
                <span class="feature-icon">✓</span>
                Gestion des routes et des pages d’erreur personnalisées
            </li>
        </ul>
    </section>
</div>

<section data-aos="fade-up" class="section" aria-labelledby="carousel-title">
    <h2 id="carousel-title" class="section-center">Aperçu des Fonctionnalités</h2>

    <div class="carousel" data-carousel>
        <div class="carousel__track" data-carousel-track>
            <article class="carousel__slide">
                <h3>Inscription</h3>
                <p>Création de compte + sécurité (CSRF, sessions).</p>
            </article>

            <article class="carousel__slide">
                <h3>Connexion</h3>
                <p>Authentification + protection brute force.</p>
            </article>

            <article class="carousel__slide">
                <h3>Profil</h3>
                <p>Page utilisateur avec email + pseudo.</p>
            </article>

            <article class="carousel__slide">
                <h3>Sécurité renforcée</h3>
                <p>Jeton CSRF et limitation de fréquence des requêtes.</p>
            </article>

            <article class="carousel__slide">
                <h3>API externe</h3>
                <p>Communication avec une API via fetch() sans rechargement.</p>
            </article>

            <article class="carousel__slide">
                <h3>Interface moderne</h3>
                <p>Design responsive et accessible avec animations AOS.</p>
            </article>
        </div>

        <button
                class="carousel__btn carousel__btn--prev"
                type="button"
                data-carousel-prev
                aria-label="Précédent"
        >
            ‹
        </button>

        <button
                class="carousel__btn carousel__btn--next"
                type="button"
                data-carousel-next
                aria-label="Suivant"
        >
            ›
        </button>

        <div class="carousel__dots" data-carousel-dots></div>
    </div>
</section>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>
