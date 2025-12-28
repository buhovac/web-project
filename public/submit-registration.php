<?php
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
  $nom = trim($_POST['lastName'] ?? '');
  $prenom = trim($_POST['firstName'] ?? '');

  // Vérifier que le champ REQUIS "nom" a bien été rempli.
  if ($nom == '')
  {
    echo 'Le nom est requis!';
  }
  elseif (mb_strlen($nom) < 2 || mb_strlen($nom) > 255)
  {
    echo 'Le nom doit contenir entre 2 et 255 caractères!';
  }
  else
  {
    echo $nom; // Exemple d'affichage : Focan
  }

  // Réaliser les tests de validation uniquement si le champ FACULTATIF "prenom" a été rempli :
  if ($prenom != '')
  {
    if (mb_strlen($prenom) < 2 || mb_strlen($prenom) > 255)
    {
      echo 'Le prenom doit contenir entre 2 et 255 caractères!';
    }
    else
    {
      echo $prenom; // Exemple d'affichage : Claudy
    }
  }
}