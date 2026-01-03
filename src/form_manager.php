<?php
declare(strict_types=1);

/**
 * Shared form manager:
 * - messages catalog
 * - validation helpers
 * - template helpers (old/hasErr/errId)
 */

function getMessages(string $lang = 'fr'): array
{
  $messages = [
    'fr' => [
      'required'           => "Ce champ est requis !",
      'email_invalid'      => "Veuillez entrer une adresse email valide !",
      'length_range'       => "Ce champ doit comprendre entre {min} et {max} caractères !",
      'password_mismatch'  => "Les mots de passe ne correspondent pas !",

      'form_ok'            => "Le formulaire a bien été envoyé !",
      'form_ko'            => "Le formulaire n'a pas été envoyé !",

      // kasnije za DB:
      'register_ok'   => "Inscription réussie ! Vous pouvez vous connecter.",
      'register_ko'   => "Impossible de créer le compte. Veuillez réessayer.",
      'login_ok'      => "Connexion réussie !",
      'login_ko'      => "Pseudo ou mot de passe incorrect !",
      'login_inactive'=> "Compte désactivé !",
    ],
  ];

  return $messages[$lang] ?? $messages['fr'];
}

function h(string $s): string
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function renderMessage(array $catalog, string $key, array $params = []): string
{
  $template = $catalog[$key] ?? $key;
  foreach ($params as $k => $v) {
    $template = str_replace('{' . $k . '}', (string)$v, $template);
  }
  return '<p class="error-text">' . h($template) . '</p>';
}

function renderStatus(array $catalog, string $key, array $params = []): string
{
  $template = $catalog[$key] ?? $key;
  foreach ($params as $k => $v) {
    $template = str_replace('{' . $k . '}', (string)$v, $template);
  }
  return '<p class="status-text">' . h($template) . '</p>';
}

// ---- validation primitives ----

function nettoyerEntreeUtilisateur(array $entreesUtilisateur, string $nomChamp): string
{
  return trim($entreesUtilisateur[$nomChamp] ?? '');
}

function estRempli(string $entreeUtilisateur): bool
{
  return $entreeUtilisateur !== '';
}

function estEmailValide(string $entreeUtilisateur): bool
{
  return filter_var($entreeUtilisateur, FILTER_VALIDATE_EMAIL) !== false;
}

function respecteLongueurMinEtMax(string $entreeUtilisateur, int $min, int $max): bool
{
  $len = mb_strlen($entreeUtilisateur);
  return $len >= $min && $len <= $max;
}

function verifierValiditeChamps(array $reglesDesChamps, array $entreesUtilisateur, array $messages): array
{
  $erreurs = [];

  foreach ($reglesDesChamps as $nomDuChamp => $reglesDuChamp) {
    $valeur = nettoyerEntreeUtilisateur($entreesUtilisateur, $nomDuChamp);
    $msgKeys = $reglesDuChamp['messages'] ?? [];

    // required
    if (!estRempli($valeur)) {
      if (!empty($reglesDuChamp['requis'])) {
        $key = $msgKeys['required'] ?? 'required';
        $erreurs[$nomDuChamp] = renderMessage($messages, $key);
      }
      continue;
    }

    // type=email
    if (
      isset($reglesDuChamp['type']) &&
      $reglesDuChamp['type'] === 'email' &&
      !estEmailValide($valeur)
    ) {
      $key = $msgKeys['email'] ?? 'email_invalid';
      $erreurs[$nomDuChamp] = renderMessage($messages, $key);
      continue;
    }

    // length range
    if (
      isset($reglesDuChamp['longueurMin'], $reglesDuChamp['longueurMax']) &&
      !respecteLongueurMinEtMax($valeur, (int)$reglesDuChamp['longueurMin'], (int)$reglesDuChamp['longueurMax'])
    ) {
      $key = $msgKeys['length'] ?? 'length_range';
      $erreurs[$nomDuChamp] = renderMessage($messages, $key, [
        'min' => (int)$reglesDuChamp['longueurMin'],
        'max' => (int)$reglesDuChamp['longueurMax'],
      ]);
    }
  }

  return $erreurs;
}

// ---- template helpers ----

function old(array $old, string $key): string
{
  return h(trim($old[$key] ?? ''));
}

function hasErr(array $erreurs, string $key): bool
{
  return isset($erreurs[$key]) && $erreurs[$key] !== '';
}

function errId(string $fieldName): string
{
  return $fieldName . '-error';
}
