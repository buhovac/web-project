<?php
declare(strict_types=1);

// No output before HTML! (no echo/debug here)

$currentPage = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '');

function isActive(string $page, string $currentPage): string
{
  return $page === $currentPage ? 'aria-current="page" class="active"' : '';
}

require_once __DIR__ . '/../../src/gestionAuthentification.php';

// simple auth-aware nav
$isLoggedIn = est_connecte();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Web Project</title>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="styles/variables.css">
  <link rel="stylesheet" href="styles/base.css">
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
      <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="main-nav">
        <span class="hamburger"></span>
      </button>
      <nav id="main-nav" class="nav">
        <a href="/index" <?= isActive('index', $currentPage) ?>>Accueil</a>
        <a href="/contact" <?= isActive('about', $currentPage) ?>>Contact</a>

        <?php if (!$isLoggedIn): ?>
          <a href="/inscription" <?= isActive('inscription', $currentPage) ?>>Inscription</a>
          <a href="/connexion" <?= isActive('connexion', $currentPage) ?>>Connexion</a>
        <?php else: ?>
          <a href="/profil" <?= isActive('profil', $currentPage) ?>>Profil</a>
        <?php endif; ?>
      </nav>
      <p id="weather" aria-live="polite"></p>
  </div>
</header>
<main class="container">
