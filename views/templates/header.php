<?php

// 1. Uključivanje funkcija i konfiguracije
 require_once __DIR__ . DIRECTORY_SEPARATOR . '../../config/constants.php';
 require_once __DIR__ . DIRECTORY_SEPARATOR . '../../core/functions.php';

// 2. Određivanje trenutne putanje (isti kod kao gore)
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (BASE_URL !== '') {
  $currentPath = str_replace(BASE_URL, '', $currentPath);
}
$currentPath = ltrim($currentPath, '/');

echo $currentPath;
function isActive(string $linkPath, string $currentPath): string {
  // Ako se putanja linka podudara s trenutnom putanjom

  if ('public/'.$linkPath === $currentPath) {
    return 'active';
  }
  // Poseban slučaj: Ako je korisnik na rootu, index.php je aktivan
  if ('public/'.$linkPath === 'public/index.php' && $currentPath === '') {
    return 'active';
  }
  return '';
}
// Uobičajene rute projekta
$homePath = 'index.php';
$aboutPath = 'index.php';
$profilePath = 'views/user/profile.php';
$loginPath = 'views/user/login.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Nom de l'Application</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<header role="banner">
  <div class="logo">
    <a href="index.php" aria-label="Accueil du site">Nom de l'App</a>
  </div>

  <nav aria-label="Navigation principale du site">
    <ul class="main-nav">
      <li><a class="<?php echo isActive("index.html", $currentPath); ?>" href="index.php" aria-current="page">Accueil</a></li>
      <li><a class="<?php echo isActive("about.php", $currentPath); ?>" href="about.php">À propos</a></li>
      <li><a class="<?php echo isActive("blog.php", $currentPath); ?>" href="blog.php">Blog</a></li>
      <li><a class="<?php echo isActive($profilePath, $currentPath); ?>" href="contact.php">Contact</a></li>
    </ul>
  </nav>
</header>

<main id="main-content">
