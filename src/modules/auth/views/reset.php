<?php
    $resetAuth = $_SESSION['password_reset'] ?? null;
    $resetVerified = is_array($resetAuth) && !empty($resetAuth['user_id']) && (int)($resetAuth['expires'] ?? 0) >= time();
    if (!$resetVerified && is_array($resetAuth)) { unset($_SESSION['password_reset']); }
    $resetEmail = (string)($_SESSION['reset_email'] ?? '');
    if ($resetVerified): ?>
  <form class="acard" method="post" action="index.php?p=reset">
    <h3>New Password</h3>
    <small style="text-align:center">Code confirmed. Choose a new password for your account.</small>
    <input type="hidden" name="a" value="reset_password">
    <input name="password" type="password" placeholder="New password" required minlength="6" autocomplete="new-password">
    <input name="password2" type="password" placeholder="Confirm new password" required minlength="6" autocomplete="new-password">
    <button>Update Password</button>
  </form>
    <?php elseif ($resetEmail !== ''): ?>
  <form class="acard" method="post" action="index.php?p=reset">
    <h3>Confirm Reset Code</h3>
    <small style="text-align:center">Paste the 6-digit code sent to your email. It expires in <?= (int)config('security.reset_ttl_minutes', 10) ?> minutes.</small>
    <input type="hidden" name="a" value="verify_reset_code">
    <input type="hidden" name="email" value="<?= h($resetEmail) ?>">
    <input name="code" type="text" inputmode="numeric" autocomplete="one-time-code" placeholder="6-digit reset code" required minlength="6" maxlength="6" pattern="[0-9]{6}">
    <button>Confirm Code</button>
  </form>
  <p class="alink"><a href="index.php?p=forgot">Request a new code</a></p>
    <?php else: ?>
  <div class="note err">Request a reset code before entering it.</div>
  <p class="alink"><a href="index.php?p=forgot">Request a reset code</a></p>
    <?php endif; ?>
