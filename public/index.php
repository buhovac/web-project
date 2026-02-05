<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php'; ?>

  <h1>Bienvenue sur notre application</h1>

  <section aria-labelledby="intro-heading">
    <h2 id="intro-heading">Découvrez nos services</h2>
    <p>Ceci est le contenu de la page d'accueil. Il devrait être engageant et présenter l'objectif principal de l'application.</p>
    <a href="inscription.php" class="button-primary">S'inscrire Maintenant</a>
  </section>

  <section aria-labelledby="features-heading">
    <h2 id="features-heading">Fonctionnalités clés</h2>
    <ul>
      <li>Fonctionnalité A</li>
      <li>Fonctionnalité B</li>
    </ul>
  </section>
    <section class="section" aria-labelledby="carousel-title">
        <h2 id="carousel-title">Fonctionnalités (carousel)</h2>

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
            </div>

            <button class="carousel__btn carousel__btn--prev" type="button" data-carousel-prev aria-label="Précédent">‹</button>
            <button class="carousel__btn carousel__btn--next" type="button" data-carousel-next aria-label="Suivant">›</button>

            <div class="carousel__dots" data-carousel-dots></div>
        </div>
    </section>


<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>