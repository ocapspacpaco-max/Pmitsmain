  <form class="acard" method="post" action="index.php?p=register">
    <h3>Register</h3>
    <input type="hidden" name="a" value="register">
    <input name="name" placeholder="Full name" required>
    <input name="email" type="email" placeholder="name@gmail.com" title="Use a valid Gmail address ending in @gmail.com" required>
    <input name="password" type="password" placeholder="Password" required minlength="6">
    <input name="team_code" placeholder="Team code (optional)" maxlength="9" autocomplete="off">
    <button>Register</button>
  </form>
  <p class="alink">Already have an account? <a href="index.php">Login</a></p>
