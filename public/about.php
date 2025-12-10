<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/header.php'; ?>
  <h1>Inscription Utilisateur</h1>

  <form aria-labelledby="form-heading" action="/submit-registration" method="POST">
    <h2 id="form-heading">Veuillez remplir vos informations</h2>

    <fieldset>
      <legend>Informations personnelles</legend>

      <div>
        <label for="firstName">Prénom : <span aria-hidden="true">*</span></label>
        <input type="text" id="firstName" name="firstName" required>
      </div>

      <div>
        <label for="lastName">Nom : <span aria-hidden="true">*</span></label>
        <input type="text" id="lastName" name="lastName" required>
      </div>

      <div>
        <label for="gender">Sexe :</label>
        <select id="gender" name="gender">
          <option value="">Sélectionner</option>
          <option value="male">Homme</option>
          <option value="female">Femme</option>
          <option value="other">Autre</option>
          <option value="prefer-not-say">Préfère ne pas dire</option>
        </select>
      </div>

      <div>
        <label for="birthYear">Année de naissance :</label>
        <input type="number" id="birthYear" name="birthYear" min="1900" max="2025" placeholder="YYYY">
      </div>
    </fieldset>

    <fieldset>
      <legend>Coordonnées</legend>

      <div>
        <label for="address">Adresse :</label>
        <input type="text" id="address" name="address">
      </div>

      <div>
        <label for="city">Ville :</label>
        <input type="text" id="city" name="city">
      </div>

      <div>
        <label for="country">Pays :</label>
        <input type="text" id="country" name="country">
      </div>

      <div>
        <label for="phone">Numéro de téléphone :</label>
        <input type="tel" id="phone" name="phone" placeholder="ex: +33 1 23 45 67 89">
      </div>

      <div>
        <label for="email">Email : <span aria-hidden="true">*</span></label>
        <input type="email" id="email" name="email" required>
      </div>
    </fieldset>

    <div>
      <button type="submit">S'inscrire</button>
      <button type="reset">Réinitialiser</button>
    </div>

    <p><span aria-hidden="true">*</span> Champs obligatoires</p>
  </form>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '../views/templates/footer.php'; ?>