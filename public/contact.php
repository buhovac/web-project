<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php'; ?>
<?php

/**
 * Single-file demo: server-side validation + centralized messages (i18n-ready)
 * - required / minlength / maxlength / type=email
 * - shows field errors under inputs
 * - shows global message under submit button
 * - repopulates user inputs on failed submit
 * - bonus: aria-invalid, aria-describedby, CSS error class
 */

// -------------------- Helpers: messages (catalog) --------------------

function getMessages(string $lang = 'fr'): array
{
  $messages = [
    'fr' => [
      // Field-level
      'required'      => "Ce champ est requis !",
      'email_invalid' => "Veuillez entrer une adresse email valide !",
      'length_range'  => "Ce champ doit comprendre entre {min} et {max} caractères !",

      // Form-level
      'form_ok'       => "Le formulaire a bien été envoyé !",
      'form_ko'       => "Le formulaire n'a pas été envoyé !",
    ],
    // Example (optional)
    'hr' => [
      'required'      => "Ovo polje je obavezno!",
      'email_invalid' => "Unesite ispravnu email adresu!",
      'length_range'  => "Ovo polje mora imati između {min} i {max} znakova!",
      'form_ok'       => "Forma je uspješno poslana!",
      'form_ko'       => "Forma nije poslana!",
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
  // Same as renderMessage but without "error-text" class
  $template = $catalog[$key] ?? $key;

  foreach ($params as $k => $v) {
    $template = str_replace('{' . $k . '}', (string)$v, $template);
  }

  return '<p class="status-text">' . h($template) . '</p>';
}

// -------------------- Helpers: validation primitives --------------------

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

    // REQUIRED
    if (!estRempli($valeur)) {
      if (!empty($reglesDuChamp['requis'])) {
        $key = $msgKeys['required'] ?? 'required';
        $erreurs[$nomDuChamp] = renderMessage($messages, $key);
      }
      continue;
    }

    // TYPE=email
    if (
      isset($reglesDuChamp['type']) &&
      $reglesDuChamp['type'] === 'email' &&
      !estEmailValide($valeur)
    ) {
      $key = $msgKeys['email'] ?? 'email_invalid';
      $erreurs[$nomDuChamp] = renderMessage($messages, $key);
      continue;
    }

    // LENGTH range
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

// -------------------- Config: language + rules --------------------

$lang = 'fr'; // change to 'hr' to test
$messages = getMessages($lang);

$reglesDesChamps = [
  'nom' => [
    'requis' => true,
    'longueurMin' => 2,
    'longueurMax' => 255,
    'messages' => [
      'required' => 'required',
      'length'   => 'length_range',
    ],
  ],
  'prenom' => [
    // optional
    'longueurMin' => 2,
    'longueurMax' => 255,
    'messages' => [
      'length' => 'length_range',
    ],
  ],
  'pseudo' => [
    'requis' => true,
    'longueurMin' => 5,
    'longueurMax' => 50,
    'messages' => [
      'required' => 'required',
      'length'   => 'length_range',
    ],
  ],
  'email' => [
    'requis' => true,
    'type' => 'email',
    'messages' => [
      'required' => 'required',
      'email'    => 'email_invalid',
    ],
  ],
  'message' => [
    'requis' => true,
    'longueurMin' => 10,
    'longueurMax' => 3000,
    'messages' => [
      'required' => 'required',
      'length'   => 'length_range',
    ],
  ],
];

// -------------------- Controller: POST handling --------------------

$erreurs = [];
$formMessage = '';
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old = $_POST; // for repopulation
  $erreurs = verifierValiditeChamps($reglesDesChamps, $_POST, $messages);

  $formMessage = empty($erreurs)
    ? renderStatus($messages, 'form_ok')
    : renderStatus($messages, 'form_ko');
}

// Helpers for template rendering
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
?>

  <h1>Inscription Utilisateur</h1>

  <form method="post" novalidate aria-labelledby="form-heading">
    <h2 id="form-heading">Veuillez remplir vos informations</h2>

    <fieldset>
      <legend>Informations personnelles</legend>

      <div class="field">
        <label for="nom">Nom : <span aria-hidden="true">*</span></label>
        <input
          type="text"
          id="nom"
          name="nom"
          minlength="2"
          maxlength="255"
          required
          value="<?= old($old, 'nom') ?>"
          aria-invalid="<?= hasErr($erreurs, 'nom') ? 'true' : 'false' ?>"
          aria-describedby="<?= hasErr($erreurs, 'nom') ? errId('nom') : '' ?>"
          class="<?= hasErr($erreurs, 'nom') ? 'field-error' : '' ?>"
        >
        <?php if (hasErr($erreurs, 'nom')): ?>
          <div id="<?= errId('nom') ?>" role="alert"><?= $erreurs['nom'] ?></div>
        <?php endif; ?>
      </div>

      <div class="field">
        <label for="prenom">Prénom :</label>
        <input
          type="text"
          id="prenom"
          name="prenom"
          minlength="2"
          maxlength="255"
          value="<?= old($old, 'prenom') ?>"
          aria-invalid="<?= hasErr($erreurs, 'prenom') ? 'true' : 'false' ?>"
          aria-describedby="<?= hasErr($erreurs, 'prenom') ? errId('prenom') : '' ?>"
          class="<?= hasErr($erreurs, 'prenom') ? 'field-error' : '' ?>"
        >
        <?php if (hasErr($erreurs, 'prenom')): ?>
          <div id="<?= errId('prenom') ?>" role="alert"><?= $erreurs['prenom'] ?></div>
        <?php endif; ?>
      </div>

      <div class="field">
        <label for="pseudo">Pseudo : <span aria-hidden="true">*</span></label>
        <input
          type="text"
          id="pseudo"
          name="pseudo"
          minlength="5"
          maxlength="50"
          required
          value="<?= old($old, 'pseudo') ?>"
          aria-invalid="<?= hasErr($erreurs, 'pseudo') ? 'true' : 'false' ?>"
          aria-describedby="<?= hasErr($erreurs, 'pseudo') ? errId('pseudo') : '' ?>"
          class="<?= hasErr($erreurs, 'pseudo') ? 'field-error' : '' ?>"
        >
        <?php if (hasErr($erreurs, 'pseudo')): ?>
          <div id="<?= errId('pseudo') ?>" role="alert"><?= $erreurs['pseudo'] ?></div>
        <?php endif; ?>
      </div>

      <div class="field">
        <label for="email">Email : <span aria-hidden="true">*</span></label>
        <input
          type="email"
          id="email"
          name="email"
          required
          value="<?= old($old, 'email') ?>"
          aria-invalid="<?= hasErr($erreurs, 'email') ? 'true' : 'false' ?>"
          aria-describedby="<?= hasErr($erreurs, 'email') ? errId('email') : '' ?>"
          class="<?= hasErr($erreurs, 'email') ? 'field-error' : '' ?>"
        >
        <?php if (hasErr($erreurs, 'email')): ?>
          <div id="<?= errId('email') ?>" role="alert"><?= $erreurs['email'] ?></div>
        <?php endif; ?>
      </div>

      <div class="field">
        <label for="message">Message : <span aria-hidden="true">*</span></label>
        <textarea
          id="message"
          name="message"
          minlength="10"
          maxlength="3000"
          required
          aria-invalid="<?= hasErr($erreurs, 'message') ? 'true' : 'false' ?>"
          aria-describedby="<?= hasErr($erreurs, 'message') ? errId('message') : '' ?>"
          class="<?= hasErr($erreurs, 'message') ? 'field-error' : '' ?>"
        ><?= old($old, 'message') ?></textarea>
        <?php if (hasErr($erreurs, 'message')): ?>
          <div id="<?= errId('message') ?>" role="alert"><?= $erreurs['message'] ?></div>
        <?php endif; ?>
      </div>

    </fieldset>

    <button type="submit">Envoyer</button>

    <?= $formMessage ?>

    <p class="hint"><span aria-hidden="true">*</span> Champs obligatoires</p>
  </form>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>