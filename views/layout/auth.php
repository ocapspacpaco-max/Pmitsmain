<?php $isLogin = !in_array($p, ['register', 'forgot', 'reset']); ?>
<div class="auth-frame">
<?php if ($isLogin): ?>
  <?php include view_for('auth:login'); /* the login card draws its own logo, welcome line and notices */ ?>
<?php else: ?>
  <div class="logo"><?= logo() ?></div>
  <?php if ($err) echo "<div class='note err'>" . h($err) . "</div>"; ?>
  <?php if ($msg) echo "<div class='note ok'>" . h($msg) . "</div>"; ?>
  <?php $authView = view_for('auth:' . $p) ?: view_for('auth:login'); include $authView; ?>
<?php endif; ?>
</div>
