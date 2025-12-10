<?php
// Pretpostavljajući da je BASE_URL definirana u config/constants.php

// Definirajte ovu varijablu na početku vašeg index.php ili u nekom core fajlu:
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Ako koristite BASE_URL, uklonite je da biste dobili čistu rutu:
if (BASE_URL !== '') {
  $currentPath = str_replace(BASE_URL, '', $currentPath);
}

// Uklonite leading slash radi jednostavnije usporedbe
$currentPath = ltrim($currentPath, '/');
// Primjer rezultata: "views/user/profile.php" ili "index.php"
?>
