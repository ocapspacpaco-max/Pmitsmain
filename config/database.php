<?php
/**
 * Database connection. XAMPP defaults are below.
 * Override per machine in config/database.local.php (git-ignored) or with PMITS_DB_* environment variables.
 */
return [
  'host'    => env('PMITS_DB_HOST', 'localhost'),
  'port'    => (int)env('PMITS_DB_PORT', 3306),
  'name'    => env('PMITS_DB_NAME', 'pmtracker'),
  'user'    => env('PMITS_DB_USER', 'root'),
  'pass'    => env('PMITS_DB_PASS', ''),
  'charset' => 'utf8mb4',
];
