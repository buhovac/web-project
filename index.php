<?php
$a = 5;
$b = 62;
$c = $a + $b;
echo "Hola mundo ". $c ;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Nom de l'Application</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header role="banner">
  <div>
    <a href="index.html" aria-label="Accueil du site">Nom de l'App</a>
  </div>

  <nav aria-label="Navigation principale du site">
    <ul>
      <li><a href="index.html" aria-current="page">Accueil</a></li>
      <li><a href="about.html">À propos</a></li>
      <li><a href="blog.html">Blog</a></li>
      <li><a href="contact.html">Contact</a></li>
    </ul>
  </nav>
</header>

<main id="main-content">
  <h1>Bienvenue sur notre application</h1>

  <section aria-labelledby="intro-heading">
    <h2 id="intro-heading">Découvrez nos services</h2>
    <p>Ceci est le contenu de la page d'accueil. Il devrait être engageant et présenter l'objectif principal de l'application.</p>
    <a href="contact.html" class="button-primary">S'inscrire Maintenant</a>
  </section>

  <section aria-labelledby="features-heading">
    <h2 id="features-heading">Fonctionnalités clés</h2>
    <ul>
      <li>Fonctionnalité A</li>
      <li>Fonctionnalité B</li>
    </ul>
  </section>
</main>

<footer role="contentinfo">
  <nav aria-label="Liens secondaires et légaux">
    <ul>
      <li><a href="#">Politique de Confidentialité</a></li>
      <li><a href="#">Conditions d'Utilisation</a></li>
    </ul>
  </nav>
  <p>&copy; <span id="current-year">2025</span> Nom de l'Application. Tous droits réservés.</p>
  <script>document.getElementById('current-year').textContent = new Date().getFullYear();</script>
</footer>

</body>
</html>