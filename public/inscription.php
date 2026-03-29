<?php
require_once __DIR__ . '/../src/session.php';
start_secure_session();

require_once __DIR__ . DIRECTORY_SEPARATOR . '../src/form_manager.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../config/database.php';
require_once __DIR__ . '/../src/gestionAuthentification.php';
require_once __DIR__ . '/../src/csrf.php';
require_once __DIR__ . '/../src/rate_limit.php';

// Ako je korisnik već logiran, nema smisla pokazivati registraciju
rediriger_si_connecte('profil.php');

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

$erreurs = [];
$formMessage = '';
$old = [];
$registrationOk = false;
?>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php'; ?>

<section data-aos="fade-up">
    <h1>Inscription</h1>

    <form id="inscription-form" method="post" novalidate aria-labelledby="form-heading">
        <h2 id="form-heading">Veuillez remplir pour vous inscrire</h2>

        <fieldset>
            <legend>Inscription</legend>

            <div class="field">
                <label for="inscription_pseudo">Inscription Pseudo : <span aria-hidden="true">*</span></label>
                <input
                        type="text"
                        id="inscription_pseudo"
                        name="inscription_pseudo"
                        minlength="2"
                        maxlength="255"
                        required
                        value="<?= old($old, 'inscription_pseudo') ?>"
                        aria-invalid="<?= hasErr($erreurs, 'inscription_pseudo') ? 'true' : 'false' ?>"
                        aria-describedby="<?= hasErr($erreurs, 'inscription_pseudo') ? errId('inscription_pseudo') : '' ?>"
                        class="<?= hasErr($erreurs, 'inscription_pseudo') ? 'field-error' : '' ?>"
                >
                <?php if (hasErr($erreurs, 'inscription_pseudo')): ?>
                    <div id="<?= errId('inscription_pseudo') ?>" role="alert"><?= $erreurs['inscription_pseudo'] ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="inscription_email">Email : <span aria-hidden="true">*</span></label>
                <input
                        type="email"
                        id="inscription_email"
                        name="inscription_email"
                        required
                        value="<?= old($old, 'inscription_email') ?>"
                        aria-invalid="<?= hasErr($erreurs, 'inscription_email') ? 'true' : 'false' ?>"
                        aria-describedby="<?= hasErr($erreurs, 'inscription_email') ? errId('inscription_email') : '' ?>"
                        class="<?= hasErr($erreurs, 'inscription_email') ? 'field-error' : '' ?>"
                >
                <?php if (hasErr($erreurs, 'inscription_email')): ?>
                    <div id="<?= errId('inscription_email') ?>" role="alert"><?= $erreurs['inscription_email'] ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="inscription_motDePasse">Mot de passe : <span aria-hidden="true">*</span></label>
                <input
                        type="password"
                        id="inscription_motDePasse"
                        name="inscription_motDePasse"
                        minlength="8"
                        maxlength="72"
                        required
                        autocomplete="new-password"
                        aria-invalid="<?= hasErr($erreurs, 'inscription_motDePasse') ? 'true' : 'false' ?>"
                        aria-describedby="<?= hasErr($erreurs, 'inscription_motDePasse') ? errId('inscription_motDePasse') : '' ?>"
                        class="<?= hasErr($erreurs, 'inscription_motDePasse') ? 'field-error' : '' ?>"
                >
                <?php if (hasErr($erreurs, 'inscription_motDePasse')): ?>
                    <div id="<?= errId('inscription_motDePasse') ?>" role="alert"><?= $erreurs['inscription_motDePasse'] ?></div>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="inscription_motDePasse_confirmation">
                    Mot de passe (confirmation) : <span aria-hidden="true">*</span>
                </label>
                <input
                        type="password"
                        id="inscription_motDePasse_confirmation"
                        name="inscription_motDePasse_confirmation"
                        minlength="8"
                        maxlength="72"
                        required
                        autocomplete="new-password"
                        aria-invalid="<?= hasErr($erreurs, 'inscription_motDePasse_confirmation') ? 'true' : 'false' ?>"
                        aria-describedby="<?= hasErr($erreurs, 'inscription_motDePasse_confirmation') ? errId('inscription_motDePasse_confirmation') : '' ?>"
                        class="<?= hasErr($erreurs, 'inscription_motDePasse_confirmation') ? 'field-error' : '' ?>"
                >
                <?php if (hasErr($erreurs, 'inscription_motDePasse_confirmation')): ?>
                    <div id="<?= errId('inscription_motDePasse_confirmation') ?>" role="alert">
                        <?= $erreurs['inscription_motDePasse_confirmation'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        </fieldset>

        <button type="submit">Envoyer</button>

        <div id="form-response" aria-live="polite"></div>

        <?= $formMessage ?>

        <p class="hint"><span aria-hidden="true">*</span> Champs obligatoires</p>
    </form>

    <a href="/connexion">Connexion</a>
</section>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>
