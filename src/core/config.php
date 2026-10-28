<?php
/* Reads config/<file>.php, then lets config/<file>.local.php override it. */

/* environment variable, or $default when it is unset/empty */
function env($key, $default = null) {
  $v = getenv($key);
  return ($v !== false && $v !== '') ? $v : $default;
}
function env_bool($key, $default = false) {
  $v = env($key);
  return $v === null ? (bool)$default : filter_var($v, FILTER_VALIDATE_BOOLEAN);
}

/* config('mail.host'), config('app.security.join_max_fails', 5), config('database') */
function config($key, $default = null) {
  static $loaded = [];
  $parts = explode('.', $key);
  $file = array_shift($parts);
  if (!isset($loaded[$file])) {
    $base  = BASE_PATH . "/config/$file.php";
    $local = BASE_PATH . "/config/$file.local.php";
    $data = is_file($base) ? require $base : [];
    if (!is_array($data)) $data = [];
    if (is_file($local)) {
      $over = require $local;
      if (is_array($over)) $data = array_replace_recursive($data, $over);
    }
    $loaded[$file] = $data;
  }
  $v = $loaded[$file];
  foreach ($parts as $seg) {
    if (!is_array($v) || !array_key_exists($seg, $v)) return $default;
    $v = $v[$seg];
  }
  return $v;
}
