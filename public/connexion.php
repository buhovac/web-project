<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php'; ?>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../src/form_manager.php'; ?>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../config/database.php'; ?>

<?php
$lang = 'fr';
$messages = getMessages($lang);

$reglesDesChamps = [
  'connexion_pseudo' => [
    'requis' => true,
    'longueurMin' => 2,
    'longueurMax' => 255,
    'messages' => [
      'required' => 'required',
      'length'   => 'length_range',
    ],
  ],
  'connexion_motDePasse' => [
    'requis' => true,
    'longueurMin' => 8,
    'longueurMax' => 72,
    'messages' => [
      'required' => 'required',
      'length'   => 'length_range',
    ],
  ],
];

$erreurs = [];
$formMessage = '';
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old = $_POST;

  $erreurs = verifierValiditeChamps($reglesDesChamps, $_POST, $messages);

  // DB: login check
  if (empty($erreurs)) {
    $pdo = db();

    $pseudo = trim($_POST['connexion_pseudo'] ?? '');
    $pwd    = $_POST['connexion_motDePasse'] ?? '';

    $stmt = $pdo->prepare("
      SELECT uti_id, uti_pseudo, uti_motdepasse, uti_compte_active
      FROM t_utilisateur_uti
      WHERE uti_pseudo = :p
      LIMIT 1
    ");
    $stmt->execute([':p' => $pseudo]);
    $user = $stmt->fetch();

    if (!$user) {
      $formMessage = renderStatus($messages, 'login_ko');
    } elseif ((int)$user['uti_compte_active'] !== 1) {
      $formMessage = renderStatus($messages, 'login_inactive');
    } elseif (!password_verify($pwd, (string)$user['uti_motdepasse'])) {
      $formMessage = renderStatus($messages, 'login_ko');
    } else {
      session_start();
      session_regenerate_id(true);

      $_SESSION['uti_id'] = (int)$user['uti_id'];
      $_SESSION['uti_pseudo'] = (string)$user['uti_pseudo'];

      $formMessage = renderStatus($messages, 'login_ok');

    }
  }

  if ($formMessage === '') {
    $formMessage = empty($erreurs)
      ? renderStatus($messages, 'form_ok')
      : renderStatus($messages, 'form_ko');
  }
}
?>

<h1>Connexion</h1>

<form method="post" novalidate aria-labelledby="form-heading">
  <h2 id="form-heading">Veuillez remplir pour vous connecter</h2>

  <fieldset>
    <legend>Connexion</legend>

    <div class="field">
      <label for="connexion_pseudo">Connexion Pseudo : <span aria-hidden="true">*</span></label>
      <input
        type="text"
        id="connexion_pseudo"
        name="connexion_pseudo"
        minlength="2"
        maxlength="255"
        required
        value="<?= old($old, 'connexion_pseudo') ?>"
        aria-invalid="<?= hasErr($erreurs, 'connexion_pseudo') ? 'true' : 'false' ?>"
        aria-describedby="<?= hasErr($erreurs, 'connexion_pseudo') ? errId('connexion_pseudo') : '' ?>"
        class="<?= hasErr($erreurs, 'connexion_pseudo') ? 'field-error' : '' ?>"
      >
      <?php if (hasErr($erreurs, 'connexion_pseudo')): ?>
        <div id="<?= errId('connexion_pseudo') ?>" role="alert"><?= $erreurs['connexion_pseudo'] ?></div>
      <?php endif; ?>
    </div>

    <div class="field">
      <label for="connexion_motDePasse">Mot de passe : <span aria-hidden="true">*</span></label>
      <input
        type="password"
        id="connexion_motDePasse"
        name="connexion_motDePasse"
        minlength="8"
        maxlength="72"
        required
        autocomplete="current-password"
        aria-invalid="<?= hasErr($erreurs, 'connexion_motDePasse') ? 'true' : 'false' ?>"
        aria-describedby="<?= hasErr($erreurs, 'connexion_motDePasse') ? errId('connexion_motDePasse') : '' ?>"
        class="<?= hasErr($erreurs, 'connexion_motDePasse') ? 'field-error' : '' ?>"
      >
      <?php if (hasErr($erreurs, 'connexion_motDePasse')): ?>
        <div id="<?= errId('connexion_motDePasse') ?>" role="alert"><?= $erreurs['connexion_motDePasse'] ?></div>
      <?php endif; ?>
    </div>

  </fieldset>

  <button type="submit">Envoyer</button>

  <?= $formMessage ?>

  <p class="hint"><span aria-hidden="true">*</span> Champs obligatoires</p>
</form>

<a href="inscription.php">Inscription</a>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>
