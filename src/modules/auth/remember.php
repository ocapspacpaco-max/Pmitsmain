<?php
/* "Remember me": a long-lived login cookie made of selector:validator.
   Only a SHA-256 hash of the validator is stored, so a leaked table cannot be used to log in. */
const REMEMBER_COOKIE = 'pmits_remember';

function remember_cookie_opts($expires) {
  $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/') . '/';
  return [
    'expires'  => $expires,
    'path'     => $dir,
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
  ];
}

/* create a token for this user and send the cookie */
function remember_issue($userId) {
  $days = max(1, (int)config('app.security.remember_days', 30));
  $sel = bin2hex(random_bytes(9));      // 18 chars
  $val = bin2hex(random_bytes(32));
  q("DELETE FROM remember_tokens WHERE expires_at < NOW()");
  q("INSERT INTO remember_tokens(selector,token_hash,user_id,expires_at) VALUES(?,?,?,DATE_ADD(NOW(), INTERVAL $days DAY))",
    [$sel, hash('sha256', $val), (int)$userId]);
  setcookie(REMEMBER_COOKIE, "$sel:$val", remember_cookie_opts(time() + $days * 86400));
}

/* drop this browser's token (logout, or logging in without "Remember me") */
function remember_forget() {
  $c = $_COOKIE[REMEMBER_COOKIE] ?? '';
  if (is_string($c) && strpos($c, ':') !== false) {
    q("DELETE FROM remember_tokens WHERE selector=?", [explode(':', $c, 2)[0]]);
  }
  if (isset($_COOKIE[REMEMBER_COOKIE])) {
    setcookie(REMEMBER_COOKIE, '', remember_cookie_opts(time() - 3600));
    unset($_COOKIE[REMEMBER_COOKIE]);
  }
}

/* drop every remembered browser of a user (used after a password reset) */
function remember_forget_user($userId) { q("DELETE FROM remember_tokens WHERE user_id=?", [(int)$userId]); }

/* runs on every request: no session but a valid cookie => log the user back in and rotate the token */
function auth_remember_restore() {
  $c = $_COOKIE[REMEMBER_COOKIE] ?? '';
  if (me() || !is_string($c) || $c === '') return;
  $parts = explode(':', $c, 2);
  $row = count($parts) === 2
    ? q("SELECT t.user_id, t.token_hash, u.name FROM remember_tokens t JOIN users u ON u.id=t.user_id
         WHERE t.selector=? AND t.expires_at > NOW()", [$parts[0]])->fetch()
    : false;
  if ($row && hash_equals($row['token_hash'], hash('sha256', $parts[1]))) {
    session_regenerate_id(true);
    $_SESSION['u'] = ['id' => (int)$row['user_id'], 'name' => $row['name']];
    q("DELETE FROM remember_tokens WHERE selector=?", [$parts[0]]);
    remember_issue((int)$row['user_id']);
  } else {
    remember_forget();
  }
}
