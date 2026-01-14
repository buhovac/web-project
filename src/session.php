<?php
declare(strict_types=1);

/**
 * Session hardening (PP06)
 * Call this BEFORE session_start() and BEFORE any output.
 */

function start_secure_session(): void
{
  // Ako je session već aktivna, ne smiješ mijenjati ini/cookie params.
  if (session_status() === PHP_SESSION_ACTIVE) {
    return;
  }

  ini_set('session.use_strict_mode', '1');
  ini_set('session.use_only_cookies', '1');
  ini_set('session.use_trans_sid', '0');

  $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

  $secure = $isHttps ? true : false;

  session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
  ]);

  session_start();
}
