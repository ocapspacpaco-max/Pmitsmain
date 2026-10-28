<?php
/**
 * Outgoing mail (password-reset codes). No secrets here.
 * Put the real account in config/mail.local.php (git-ignored), or use PMITS_SMTP_* environment variables.
 */
return [
  'host'   => env('PMITS_SMTP_HOST', 'smtp.gmail.com'),
  'port'   => (int)env('PMITS_SMTP_PORT', 587),       // 587 with 'tls', 465 with 'ssl'
  'secure' => env('PMITS_SMTP_SECURE', 'tls'),         // 'tls' or 'ssl'
  'user'   => env('PMITS_SMTP_USER', ''),              // the Gmail address that SENDS the codes
  'pass'   => env('PMITS_SMTP_PASS', ''),              // a Gmail App Password (16 chars), NOT the normal password
  'from'   => env('PMITS_MAIL_FROM', ''),              // blank = same as 'user'
  'from_name' => 'PMITS Password Reset',

  'debug'         => env_bool('PMITS_SMTP_DEBUG', false),          // true = show the real email error on screen
  'local_ssl_fix' => env_bool('PMITS_SMTP_LOCAL_SSL_FIX', false),  // fixes "certificate verify failed" on XAMPP; keep false in production
];
