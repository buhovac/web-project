<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php'; ?>
  <h1>Inscription Utilisateur</h1>
<?php
// Vérifier si le formulaire a été soumis avec la méthode "POST" :
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
  $erreurs = [];

  $nom = trim($_POST['lastName'] ?? '');
  $prenom = trim($_POST['firstName'] ?? '');
  $message = trim($_POST['message'] ?? '');

  /* Validation des champs REQUIS */

  // Vérifier que le champ REQUIS "nom" a bien été rempli.
  if ($nom == '')
  {
    $erreurs['lastName'] = "<p>Le nom est requis!</p>";
  }
  elseif (mb_strlen($nom) < 2 || mb_strlen($nom) > 255)
  {
    $erreurs['lastName'] = "<p>Le nom doit contenir entre 2 et 255 caractères!</p>";
  }

  // Vérifier que le champ REQUIS "message" a bien été rempli.
  if ($message == '')
  {
    $erreurs['message'] = "<p>Le message est requis!</p>";
  }
  elseif (mb_strlen($message) < 10 || mb_strlen($message) > 3000)
  {
    $erreurs['message'] = "<p>Le message doit contenir entre 10 et 3000 caractères!</p>";
  }

  /* Validation des champs FACULTATIFS */

  // Réaliser les tests de validation uniquement si le champ FACULTATIF "prenom" a été rempli :
  if ($prenom != '')
  {
    if (mb_strlen($nom) < 2 || mb_strlen($nom) > 255)
    {
      $erreurs['firstName'] = "<p>Le prénom doit contenir entre 2 et 255 caractères!</p>";
    }
  }

  // Si le tableau des erreurs est vide, définir un message de confirmation de validation :
  if (empty($erreurs))
  {
    $formMessage = "<p>Formulaire envoyé avec succès!</p>";
  }
}
?>
  <form aria-labelledby="form-heading" method="POST" novalidate>
    <h2 id="form-heading">Veuillez remplir vos informations</h2>

    <fieldset>
      <legend>Informations personnelles</legend>

      <div>
        <label for="firstName">Prénom : <span aria-hidden="true">*</span></label>
        <input type="text" id="firstName" name="firstName" minlength="2" maxlength="255" required>
        <?=$erreurs['firstName'] ?? ''?>
      </div>

      <div>
        <label for="lastName">Nom : <span aria-hidden="true">*</span></label>
        <input type="text" id="lastName" name="lastName" minlength="2" maxlength="255" required>
        <?=$erreurs['lastName'] ?? ''?>
      </div>

      <div>
        <label for="message">Message : <span aria-hidden="true">*</span></label>
        <textarea id="message" name="message"  minlength="10" maxlength="3000" required></textarea>
        <?=$erreurs['message'] ?? ''?>
      </div>

    </fieldset>

    <div>
      <button type="submit">S'inscrire</button>
      <?=$formMessage ?? ''?>
      <button type="reset">Réinitialiser</button>
    </div>

    <p><span aria-hidden="true">*</span> Champs obligatoires</p>
  </form>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>