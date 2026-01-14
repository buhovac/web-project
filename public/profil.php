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
  header('Location: connexion.php');
  exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT uti_pseudo, uti_email FROM t_utilisateur_uti WHERE uti_id = :id LIMIT 1");
$stmt->execute([':id' => $utiId]);
$user = $stmt->fetch();

if (!$user) {
  deconnecter_utilisateur();
  header('Location: connexion.php');
  exit;
}

require_once __DIR__ . '/../views/templates/header.php';
?>

<h1>Profil</h1>

<p><strong>Pseudo:</strong> <?= htmlspecialchars($user['uti_pseudo'], ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['uti_email'], ENT_QUOTES, 'UTF-8') ?></p>

<form method="post">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
  <button type="submit" name="logout" value="1">Déconnexion</button>
</form>

<?php require_once __DIR__ . '/../views/templates/footer.php'; ?>
