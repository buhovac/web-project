<?php
declare(strict_types=1);

require_once __DIR__ . '/session.php';

function rate_limit_post(string $key, int $maxRequests = 10, int $windowSeconds = 10): bool
{
  start_secure_session();

  $tsKey = "rl_ts_$key";
  $ctKey = "rl_ct_$key";

  $now = time();

  if (!isset($_SESSION[$tsKey], $_SESSION[$ctKey])) {
    $_SESSION[$tsKey] = $now;
    $_SESSION[$ctKey] = 1;
    return true;
  }

  $delta = $now - (int)$_SESSION[$tsKey];

  if ($delta > $windowSeconds) {
    $_SESSION[$tsKey] = $now;
    $_SESSION[$ctKey] = 1;
    return true;
  }

  $_SESSION[$ctKey] = (int)$_SESSION[$ctKey] + 1;

  return (int)$_SESSION[$ctKey] <= $maxRequests;
}
