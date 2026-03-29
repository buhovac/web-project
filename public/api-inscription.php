<?php
require_once __DIR__ . '/../src/session.php';
start_secure_session();

require_once __DIR__ . '/../src/form_manager.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/csrf.php';
require_once __DIR__ . '/../src/rate_limit.php';

header('Content-Type: application/json; charset=utf-8');

$lang = 'fr';
$messages = getMessages($lang);

$reglesDesChamps = [
    'inscription_pseudo' => [
        'requis' => true,
        'longueurMin' => 2,
        'longueurMax' => 255,
        'messages' => [
            'required' => 'required',
            'length'   => 'length_range',
        ],
    ],
    'inscription_email' => [
        'requis' => true,
        'type' => 'email',
        'messages' => [
            'required' => 'required',
            'email'    => 'email_invalid',
        ],
    ],
    'inscription_motDePasse' => [
        'requis' => true,
        'longueurMin' => 8,
        'longueurMax' => 72,
        'messages' => [
            'required' => 'required',
            'length'   => 'length_range',
        ],
    ],
    'inscription_motDePasse_confirmation' => [
        'requis' => true,
        'longueurMin' => 8,
        'longueurMax' => 72,
        'messages' => [
            'required' => 'required',
            'length'   => 'length_range',
        ],
    ],
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF token invalide.']);
    exit;
}

if (!rate_limit_post('inscription', 2, 10)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Trop de requêtes. Réessayez plus tard.']);
    exit;
}

$erreurs = verifierValiditeChamps($reglesDesChamps, $_POST, $messages);

$pwd  = $_POST['inscription_motDePasse'] ?? '';
$pwd2 = $_POST['inscription_motDePasse_confirmation'] ?? '';
if ($pwd !== '' && $pwd2 !== '' && $pwd !== $pwd2) {
    $erreurs['inscription_motDePasse_confirmation'] = renderMessage($messages, 'password_mismatch');
}

if (!empty($erreurs)) {
    echo json_encode([
        'success' => false,
        'errors' => $erreurs,
        'message' => 'Le formulaire n\'a pas été envoyé !'
    ]);
    exit;
}

$pdo = db();

$pseudo = trim($_POST['inscription_pseudo'] ?? '');
$email  = trim($_POST['inscription_email'] ?? '');
$pwd    = $_POST['inscription_motDePasse'] ?? '';

$stmt = $pdo->prepare("SELECT 1 FROM t_utilisateur_uti WHERE uti_pseudo = :p LIMIT 1");
$stmt->execute([':p' => $pseudo]);
if ($stmt->fetchColumn()) {
    $erreurs['inscription_pseudo'] = renderMessage($messages, 'pseudo_taken');
}

$stmt = $pdo->prepare("SELECT 1 FROM t_utilisateur_uti WHERE uti_email = :e LIMIT 1");
$stmt->execute([':e' => $email]);
if ($stmt->fetchColumn()) {
    $erreurs['inscription_email'] = renderMessage($messages, 'email_taken');
}

if (!empty($erreurs)) {
    echo json_encode([
        'success' => false,
        'errors' => $erreurs,
        'message' => 'Le formulaire n\'a pas été envoyé !'
    ]);
    exit;
}

$hash = password_hash($pwd, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("
        INSERT INTO t_utilisateur_uti
        (uti_pseudo, uti_email, uti_motdepasse, uti_compte_active, uti_code_activation)
        VALUES
        (:pseudo, :email, :hash, 1, NULL)
    ");

    $stmt->execute([
        ':pseudo' => $pseudo,
        ':email'  => $email,
        ':hash'   => $hash,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Inscription réussie. Vous pouvez maintenant vous connecter.'
    ]);
} catch (PDOException $ex) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de l\'inscription. Veuillez réessayer.'
    ]);
}