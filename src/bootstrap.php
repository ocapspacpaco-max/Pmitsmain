<?php
/* Starts the app: constants, session, core files, then every module. */
define('BASE_PATH', dirname(__DIR__));
define('SRC_PATH', __DIR__);
define('MODULES_PATH', SRC_PATH . '/modules');
define('VIEWS_PATH', BASE_PATH . '/views');

session_start();

(function () {
  foreach (['config', 'database', 'helpers', 'flash', 'auth', 'router', 'icons', 'migrations'] as $f) {
    require_once SRC_PATH . "/core/$f.php";
  }
  /* every folder in src/modules/ with a module.php is loaded and registers its own actions and views */
  foreach (glob(MODULES_PATH . '/*/module.php') as $m) require_once $m;

  run_migrations();
  auth_remember_restore();   /* bring back a "Remember me" login when the session has expired */
})();
