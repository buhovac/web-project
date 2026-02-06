<?php
http_response_code(404);
require_once __DIR__ . '/../views/templates/header.php';
?>

<section data-aos="fade-up" class="hero-section">
    <h1>Oups… cette page n’existe pas</h1>
    <p>La page demandée est introuvable ou a été déplacée.</p>
    <p><a href="/index">Retour à l’accueil</a></p>
</section>

<?php require_once __DIR__ . '/../views/templates/footer.php'; ?>
