<?php
/* Front controller: boot, run any POST action, work out the page, draw it. */
require __DIR__ . '/src/bootstrap.php';

[$err, $msg] = flash_load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $postError = dispatch_post();
  if ($postError) $err = $postError;
}

require SRC_PATH . '/context.php';

include VIEWS_PATH . '/layout/head.php';
include VIEWS_PATH . '/layout/' . (me() ? 'app' : 'auth') . '.php';
?>
</body></html>
