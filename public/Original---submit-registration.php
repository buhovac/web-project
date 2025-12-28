<?php
function nettoyerEntreeUtilisateur(array $entreesUtilisateur, string $nomChamp): string
{
  return trim($entreesUtilisateur[$nomChamp] ?? '');
}

function estRempli(string $entreeUtilisateur): bool
{
  return $entreeUtilisateur != '';
}

function respecteLongueurMinEtMax(string $entreeUtilisateur, int $longueurMin, int $longueurMax): bool
{
  return mb_strlen($entreeUtilisateur) >= $longueurMin
    && mb_strlen($entreeUtilisateur) <= $longueurMax;
}

// Vérifier si le formulaire a été soumis avec la méthode "POST" :
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
  $erreurs = [];

  $nom = nettoyerEntreeUtilisateur($_POST, 'nom');
  $prenom = nettoyerEntreeUtilisateur($_POST, 'prenom');
  $message = nettoyerEntreeUtilisateur($_POST, 'message');

  /* Validation des champs REQUIS */

  // Vérifier que le champ REQUIS "nom" a bien été rempli.
  if (!estRempli($nom))
  {
    $erreurs['nom'] = "<p>Le nom est requis!</p>";
  }
  elseif (!respecteLongueurMinEtMax($nom, 2, 255))
  {
    $erreurs['nom'] = "<p>Le nom doit contenir entre 2 et 255 caractères!</p>";
  }

  // Vérifier que le champ REQUIS "message" a bien été rempli.
  if (!estRempli($message))
  {
    $erreurs['message'] = "<p>Le message est requis!</p>";
  }
  elseif (!respecteLongueurMinEtMax($message, 10, 3000))
  {
    $erreurs['message'] = "<p>Le message doit contenir entre 10 et 3000 caractères!</p>";
  }

  /* Validation des champs FACULTATIFS */

  // Vérifier que le champ FACULTATIF "prenom" a bien été rempli avant de réaliser les tests de validation.
  if (estRempli($prenom))
  {
    if (!respecteLongueurMinEtMax($nom, 2, 255))
    {
      $erreurs['prenom'] = "<p>Le prénom doit contenir entre 2 et 255 caractères!</p>";
    }
  }

  // Si le tableau des erreurs est vide, définir un message de confirmation de validation :
  if (empty($erreurs))
  {
    $formMessage = "<p>Formulaire envoyé avec succès!</p>";
  }
}
