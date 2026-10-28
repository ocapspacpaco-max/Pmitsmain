<?php
/* Sends the password-reset code. All settings come from config/mail.php (+ mail.local.php). */

/* Load PHPMailer: Composer (vendor/) or manual copy (PHPMailer/src/) */
function load_phpmailer() {
  if (class_exists('\\PHPMailer\\PHPMailer\\PHPMailer', false)) return;
  $autoload = BASE_PATH . '/vendor/autoload.php';
  if (is_file($autoload)) {
    require_once $autoload;
  } elseif (is_file(BASE_PATH . '/PHPMailer/src/PHPMailer.php')) {
    require_once BASE_PATH . '/PHPMailer/src/Exception.php';
    require_once BASE_PATH . '/PHPMailer/src/PHPMailer.php';
    require_once BASE_PATH . '/PHPMailer/src/SMTP.php';
  }
}

function sendResetCode($to, $code) {
  load_phpmailer();
  if (!class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
    throw new RuntimeException('PHPMailer is missing. Put it in PHPMailer/src/ or run "composer require phpmailer/phpmailer".');
  }
  $c = config('mail', []);
  $smtpUser = trim((string)($c['user'] ?? ''));
  $smtpPass = (string)($c['pass'] ?? '');
  if ($smtpUser === '' || $smtpPass === '' || $smtpUser === 'your.real.email@gmail.com') {
    throw new RuntimeException('SMTP username/password are not configured. Edit config/mail.local.php.');
  }
  $host   = trim((string)($c['host'] ?? 'smtp.gmail.com'));
  $secure = strtolower(trim((string)($c['secure'] ?? 'tls')));
  $port   = (int)($c['port'] ?? 0);
  if (!$port) $port = $secure === 'ssl' ? 465 : 587;
  $from = trim((string)($c['from'] ?? ''));
  if ($from === '') $from = $smtpUser;
  $from = str_replace(["\r", "\n"], '', $from);
  $ttl = (int)config('app.security.reset_ttl_minutes', 10);

  $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = $host;
  $mail->SMTPAuth = true;
  $mail->Username = $smtpUser;
  $mail->Password = str_replace(' ', '', $smtpPass); // App Passwords are shown with spaces
  $mail->Port = $port;
  $mail->Timeout = 20;
  $mail->CharSet = 'UTF-8';
  $mail->SMTPSecure = $secure === 'ssl'
    ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
    : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
  if (!empty($c['local_ssl_fix'])) {
    $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
  }
  $mail->setFrom($from, (string)($c['from_name'] ?? 'PMITS Password Reset'));
  $mail->addAddress($to);
  $mail->Subject = 'PMITS password reset code';
  $mail->Body = "Your PMITS password reset code is: {$code}\n\nEnter this code on the password reset page. It expires in {$ttl} minutes. If you did not request this, you can ignore this message.";
  $mail->isHTML(false);
  $mail->send();
}
