<?php
declare(strict_types=1);

require_once __DIR__ . '/session.php';

function demarrer_session_si_necessaire(): void
{
  start_secure_session();
}

function connecter_utilisateur(int $utilisateurId): void
{
  demarrer_session_si_necessaire();
  session_regenerate_id(true); // security best practice
  $_SESSION['utilisateurId'] = $utilisateurId;
}

function est_connecte(): bool
{
  demarrer_session_si_necessaire();
  return isset($_SESSION['utilisateurId']) && is_numeric($_SESSION['utilisateurId']);
}

function deconnecter_utilisateur(): void
{
  demarrer_session_si_necessaire();
  unset($_SESSION['utilisateurId']);

  session_regenerate_id(true);
}
function rediriger_si_connecte(string $vers = 'profil.php'): void
{
  if (est_connecte()) {
    header("Location: {$vers}");
    exit;
  }
}

function rediriger_si_non_connecte(string $vers = 'connexion.php'): void
{
  if (!est_connecte()) {
    header("Location: {$vers}");
    exit;
  }
}
