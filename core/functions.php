<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (BASE_URL !== '') {
  $currentPath = str_replace(BASE_URL, '', $currentPath);
}
$currentPath = ltrim($currentPath, '/');
?>
