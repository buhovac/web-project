<?php
function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;

  $pdo = new PDO(
    'mysql:host=db;dbname=bdd_projet_web;charset=utf8mb4',
    'db',
    'db',
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
  return $pdo;
}
