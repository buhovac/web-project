<?php
require_once __DIR__ . '/../src/session.php';
start_secure_session();

require_once __DIR__ . '/../src/gestionAuthentification.php';
require_once __DIR__ . '/../src/csrf.php';
require_once __DIR__ . '/../config/database.php';

rediriger_si_non_connecte('connexion.php');

$pdo = db();
$utiId = (int)($_SESSION['utilisateurId'] ?? 0);

// Logout submit (POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    die('CSRF token invalide.');
  }

  deconnecter_utilisateur();
  header('Location: /connexion');
  exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT uti_pseudo, uti_email FROM t_utilisateur_uti WHERE uti_id = :id LIMIT 1");
$stmt->execute([':id' => $utiId]);
$user = $stmt->fetch();

if (!$user) {
  deconnecter_utilisateur();
  header('Location: /connexion');
  exit;
}

require_once __DIR__ . '/../views/templates/header.php';
?>

<section class="card" aria-labelledby="profile-heading">
  <div class="profile-header">
    <h1 id="profile-heading">Mon Profil</h1>
    <div class="badge-active">Actif</div>
  </div>

  <div class="profile-info">
    <p class="profile-info-item"><strong class="profile-label">Pseudo:</strong> <span class="profile-value"><?= htmlspecialchars($user['uti_pseudo'], ENT_QUOTES, 'UTF-8') ?></span></p>
    <p class="profile-info-item"><strong class="profile-label">Email:</strong> <span class="profile-value"><?= htmlspecialchars($user['uti_email'], ENT_QUOTES, 'UTF-8') ?></span></p>
  </div>

  <form method="post" class="form-inline">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit" name="logout" value="1" class="button-logout">Déconnexion</button>
  </form>
</section>

<?php require_once __DIR__ . '/../views/templates/footer.php'; ?>
