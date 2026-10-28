<?php
/* Slows down password guessing. Failed logins are logged per email + IP address;
   after login_max_fails misses the login is paused for login_lock_seconds. */

function login_ip() { return substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45); }
function login_key($email) { return substr(strtolower(trim((string)$email)), 0, 190); }

/* seconds the visitor must still wait (0 = allowed to try) */
function login_wait($email) {
  $win = max(1, (int)config('app.security.login_lock_seconds', 300));
  $max = max(1, (int)config('app.security.login_max_fails', 5));
  $ip = login_ip(); $key = login_key($email);
  $base = "SELECT COUNT(*) c, TIMESTAMPDIFF(SECOND, MIN(created_at), NOW()) age FROM login_attempts
           WHERE created_at > DATE_SUB(NOW(), INTERVAL $win SECOND)";
  $wait = 0;
  foreach ([
    [$base . " AND ip=? AND email=?", [$ip, $key], $max],        // one account from one address
    [$base . " AND ip=?",             [$ip],       $max * 4],    // one address trying many accounts
  ] as [$sql, $args, $limit]) {
    $r = q($sql, $args)->fetch();
    if ((int)$r['c'] >= $limit) $wait = max($wait, $win - (int)$r['age']);
  }
  return max(0, $wait);
}
function login_fail($email) {
  q("DELETE FROM login_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
  q("INSERT INTO login_attempts(email,ip) VALUES(?,?)", [login_key($email), login_ip()]);
}
function login_clear($email) { q("DELETE FROM login_attempts WHERE ip=? AND email=?", [login_ip(), login_key($email)]); }
function login_wait_text($s) { return $s < 60 ? max(1, (int)$s) . ' seconds' : (int)ceil($s / 60) . ' minute' . ($s > 60 ? 's' : ''); }
