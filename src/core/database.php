<?php
/* One shared PDO connection, built from config/database.php. */
function db() {
  static $pdo = null;
  if ($pdo === null) {
    $c = config('database');
    if (!$c) die('Missing config/database.php');
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $c['host'], $c['port'], $c['name'], $c['charset']);
    $pdo = new PDO($dsn, $c['user'], $c['pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  }
  return $pdo;
}

/* prepared query: q("SELECT ... WHERE id=?", [$id])->fetch() */
function q($sql, $a = []) { $s = db()->prepare($sql); $s->execute($a); return $s; }
