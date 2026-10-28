<?php
/* Register, login and the 3-step password reset. Each returns an error string or null. */

function auth_register() {
  $email = trim((string)($_POST['email'] ?? ''));
  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strtolower(substr($email, -10)) !== '@gmail.com') {
    return 'Please enter a valid Gmail address ending in @gmail.com.';
  }

  try {
    q("INSERT INTO users(name,email,password) VALUES(?,?,?)", [trim($_POST['name'] ?? ''), $email, password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT)]);
    $newId = (int)db()->lastInsertId();
  } catch (Exception $e) {
    return 'That email is already registered.';
  }
  $rawCode = trim((string)($_POST['team_code'] ?? ''));
  if ($rawCode !== '') {
    $c = code_norm($rawCode);
    $row = $c !== '' ? q("SELECT id FROM projects WHERE join_code=?", [$c])->fetch() : false;
    if ($row) {
      q("INSERT IGNORE INTO project_members VALUES(?,?,'pending')", [$row['id'], $newId]);
      logact($row['id'], 'asked to join the team (waiting for a role)', $newId);
    } else {
      flash_err('Account created, but that team code was not valid. You can join a team from Your Projects after you log in.');
    }
  }
  go('index.php?registered=1');
  return null;
}

function auth_login() {
  $email = trim((string)($_POST['email'] ?? ''));
  $pass  = (string)($_POST['password'] ?? '');

  if ($wait = login_wait($email)) {
    return 'Too many failed attempts. Please try again in ' . login_wait_text($wait) . '.';
  }

  $u = q("SELECT * FROM users WHERE email=?", [$email])->fetch();
  /* unknown emails still run a hash check so the response time does not reveal which emails exist */
  static $dummy = null;
  $ok = $u ? password_verify($pass, $u['password'])
           : (password_verify($pass, $dummy ??= password_hash('pmits-dummy', PASSWORD_DEFAULT)) && false);

  if ($ok) {
    login_clear($email);
    if (password_needs_rehash($u['password'], PASSWORD_DEFAULT)) {
      q("UPDATE users SET password=? WHERE id=?", [password_hash($pass, PASSWORD_DEFAULT), $u['id']]);
    }
    session_regenerate_id(true);
    $_SESSION['u'] = ['id' => $u['id'], 'name' => $u['name']];
    remember_forget();                                   // drop any older remembered login of this browser
    if (!empty($_POST['remember'])) remember_issue((int)$u['id']);
    go('index.php');
  }

  login_fail($email);
  return 'Invalid email or password.';
}

/* Step 1: user enters email -> a 6-digit code is emailed */
function auth_forgot() {
  $email = strtolower(trim($_POST['email'] ?? ''));
  $u = q("SELECT id FROM users WHERE email=?", [$email])->fetch();
  $_SESSION['reset_email'] = $email;
  unset($_SESSION['password_reset']);
  $_SESSION['reset_attempts'] = 0;
  $failed = false;
  $debug = (bool)config('mail.debug', false);
  $ttl = (int)config('app.security.reset_ttl_minutes', 10);
  if ($u) {
    $code = (string)random_int(100000, 999999);
    q("DELETE FROM password_resets WHERE user_id=?", [$u['id']]);
    q("INSERT INTO password_resets(user_id,token_hash,expires_at) VALUES(?,?,DATE_ADD(NOW(), INTERVAL $ttl MINUTE))", [$u['id'], hash('sha256', $code)]);
    try {
      sendResetCode($email, $code);
    } catch (Throwable $e) {
      $failed = true;
      error_log('PMITS reset email error: ' . $e->getMessage());
      q("DELETE FROM password_resets WHERE user_id=?", [$u['id']]);
      if ($debug) $_SESSION['reset_error'] = 'Email failed: ' . $e->getMessage();
    }
  }
  if (!($failed && $debug)) {
    $_SESSION['reset_notice'] = 'If that email is registered, a 6-digit code has been sent. Check your inbox and spam folder.';
  }
  go('index.php?p=reset');
  return null;
}

/* Step 2: user pastes the code from the email */
function auth_verify_reset_code() {
  $email = strtolower(trim($_POST['email'] ?? ''));
  $code = preg_replace('/\D/', '', (string)($_POST['code'] ?? ''));
  $attempts = (int)($_SESSION['reset_attempts'] ?? 0);
  $u = q("SELECT id FROM users WHERE email=?", [$email])->fetch();
  $row = $u ? q("SELECT token_hash FROM password_resets WHERE user_id=? AND expires_at>NOW()", [$u['id']])->fetch() : false;
  if ($attempts >= (int)config('app.security.reset_max_attempts', 5)) {
    return 'Too many incorrect attempts. Request a new reset code.';
  }
  if ($u && $row && strlen($code) === 6 && hash_equals($row['token_hash'], hash('sha256', $code))) {
    $_SESSION['password_reset'] = ['user_id' => (int)$u['id'], 'expires' => time() + 60 * (int)config('app.security.reset_ttl_minutes', 10)];
    unset($_SESSION['reset_attempts']);
    q("DELETE FROM password_resets WHERE user_id=?", [$u['id']]);
    go('index.php?p=reset&step=password');
  }
  $_SESSION['reset_attempts'] = $attempts + 1;
  return 'The reset code is invalid or expired. Check the code or request a new one.';
}

/* Step 3: code confirmed -> set the new password */
function auth_reset_password() {
  $reset = $_SESSION['password_reset'] ?? null;
  if (!is_array($reset) || empty($reset['user_id']) || (int)($reset['expires'] ?? 0) < time()) {
    unset($_SESSION['password_reset']);
    return 'Your code confirmation has expired. Request a new reset code.';
  }
  if (strlen($_POST['password'] ?? '') < 6) return 'Password must be at least 6 characters.';
  if (($_POST['password'] ?? '') !== ($_POST['password2'] ?? '')) return 'Passwords do not match.';
  q("UPDATE users SET password=? WHERE id=?", [password_hash($_POST['password'], PASSWORD_DEFAULT), (int)$reset['user_id']]);
  q("DELETE FROM password_resets WHERE user_id=?", [(int)$reset['user_id']]);
  remember_forget_user((int)$reset['user_id']);          // a new password logs out every remembered browser
  unset($_SESSION['password_reset'], $_SESSION['reset_email'], $_SESSION['reset_attempts']);
  go('index.php?reset=1');
  return null;
}
