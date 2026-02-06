<?php
declare(strict_types=1);

function db(): PDO
{
  // Default values for DDEV
  $host = 'db';
  $name = 'db';      // prefer default db for DDEV
  $user = 'db';
  $pass = 'db';

  // Optional local override (not committed)
  $localConfig = __DIR__ . '/config.local.php';
  if (file_exists($localConfig)) {
    /** @var array{host:string,name:string,user:string,pass:string} $cfg */
    $cfg = require $localConfig;
    $host = $cfg['host'];
    $name = $cfg['name'];
    $user = $cfg['user'];
    $pass = $cfg['pass'];
  }

  $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

  return new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
}
