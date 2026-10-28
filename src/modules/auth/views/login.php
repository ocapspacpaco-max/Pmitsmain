<?php /* Login card: logo, welcome line, notices and the form all live inside one glass card. */ ?>
  <div class="acard login">
    <div class="logo"><?= logo() ?></div>
    <p class="welcome">Hi, Welcome to <?= h(config('app.name', 'PMITS')) ?>!</p>
    <?php if ($err) echo "<div class='note err' role='alert'>" . h($err) . "</div>"; ?>
    <?php if ($msg) echo "<div class='note ok' role='status'>" . h($msg) . "</div>"; ?>

    <form class="lform" id="loginForm" method="post" action="index.php">
      <input type="hidden" name="a" value="login">
      <input name="email" type="email" placeholder="Email" required autocomplete="username" maxlength="190"
             value="<?= h($_POST['email'] ?? '') ?>" <?= empty($_POST['email']) ? 'autofocus' : '' ?>>
      <div class="pw">
        <input name="password" type="password" placeholder="Password" required autocomplete="current-password"
               <?= empty($_POST['email']) ? '' : 'autofocus' ?>>
        <button type="button" class="eye" aria-label="Show password" aria-pressed="false">
          <svg class="on" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
          <svg class="off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.9 17.9A10.4 10.4 0 0 1 12 19c-6.4 0-10-7-10-7a17.6 17.6 0 0 1 4.1-4.9M9.9 5.2A9.8 9.8 0 0 1 12 5c6.4 0 10 7 10 7a17.7 17.7 0 0 1-2.2 3.2M1 1l22 22"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
        </button>
      </div>
      <div class="lrow">
        <label class="chk"><input type="checkbox" name="remember" value="1"<?= !empty($_POST['remember']) ? ' checked' : '' ?>><span>Remember me</span></label>
        <a href="index.php?p=forgot">Forgot password?</a>
      </div>
      <button class="lbtn" type="submit">Login</button>
    </form>

    <p class="alink">Don't have an account? <a href="index.php?p=register">Register</a></p>
  </div>
  <script>
  (function () {
    var f = document.getElementById('loginForm'); if (!f) return;
    var pw = f.elements.password, eye = f.querySelector('.eye'), btn = f.querySelector('.lbtn');
    eye.addEventListener('click', function () {
      var show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      eye.classList.toggle('on', show);
      eye.setAttribute('aria-pressed', show);
      eye.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      pw.focus();
    });
    f.addEventListener('submit', function () {
      setTimeout(function () { btn.disabled = true; btn.textContent = 'Logging in…'; }, 0);
    });
    window.addEventListener('pageshow', function () { btn.disabled = false; btn.textContent = 'Login'; });
  })();
  </script>
