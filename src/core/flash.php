<?php
/* One-shot messages that survive a redirect. */
function flash_ok($m)  { $_SESSION['flash_ok'] = $m; }
function flash_err($m) { $_SESSION['flash_err'] = $m; }

/* Called once per request: returns [$err, $msg] and clears what was stored. */
function flash_load() {
  $err = $msg = '';
  if (!empty($_SESSION['reset_notice'])) { $msg = $_SESSION['reset_notice']; unset($_SESSION['reset_notice']); }
  if (!empty($_SESSION['reset_error'])) { $err = $_SESSION['reset_error']; unset($_SESSION['reset_error']); }
  if (!empty($_SESSION['flash_ok'])) { $msg = $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); }
  if (!empty($_SESSION['flash_err'])) { $err = $_SESSION['flash_err']; unset($_SESSION['flash_err']); }
  if (isset($_GET['registered'])) $msg = 'Account created! You can log in now.';
  if (isset($_GET['reset'])) $msg = 'Password updated! You can log in now.';
  if (isset($_GET['added'])) $msg = 'Member added to the team.';
  if (($_GET['err'] ?? '') == 'nouser') $err = 'No registered user with that email. Ask them to register first.';
  return [$err, $msg];
}
