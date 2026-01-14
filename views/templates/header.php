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
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header class="site-header">
  <nav class="nav">
    <a href="index.php" <?= isActive('index.php', $currentPage) ?>>Accueil</a>
    <a href="contact.php" <?= isActive('about.php', $currentPage) ?>>Contact</a>

    <?php if (!$isLoggedIn): ?>
      <a href="inscription.php" <?= isActive('inscription.php', $currentPage) ?>>Inscription</a>
      <a href="connexion.php" <?= isActive('connexion.php', $currentPage) ?>>Connexion</a>
    <?php else: ?>
      <a href="profil.php" <?= isActive('profil.php', $currentPage) ?>>Profil</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
