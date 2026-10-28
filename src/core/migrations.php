<?php
/* Auto-migrations: safe to run on every request, each step does nothing once applied. */
function run_migrations() {
  /* adds the join_code column once and gives every existing project a code */
  try { db()->query("SELECT join_code FROM projects LIMIT 1"); }
  catch (Throwable $e) { db()->exec("ALTER TABLE projects ADD COLUMN join_code VARCHAR(12) NULL, ADD UNIQUE KEY uq_join_code (join_code)"); }
  foreach (q("SELECT id FROM projects WHERE join_code IS NULL OR join_code=''")->fetchAll(PDO::FETCH_COLUMN) as $x) {
    q("UPDATE projects SET join_code=? WHERE id=?", [code_new(), $x]);
  }

  /* role column becomes VARCHAR so it can hold "pending" */
  $rc = q("SHOW COLUMNS FROM project_members LIKE 'role'")->fetch();
  if ($rc && stripos((string)$rc['Type'], 'enum') === 0) {
    db()->exec("ALTER TABLE project_members MODIFY role VARCHAR(20) NOT NULL DEFAULT 'developer'");
  }

  /* login security tables ("Remember me" tokens + failed-login log), created once per session */
  if (empty($_SESSION['_mig_login'])) {
    db()->exec("CREATE TABLE IF NOT EXISTS remember_tokens (
      id INT AUTO_INCREMENT PRIMARY KEY,
      selector CHAR(18) NOT NULL,
      token_hash CHAR(64) NOT NULL,
      user_id INT NOT NULL,
      expires_at DATETIME NOT NULL,
      UNIQUE KEY uq_selector (selector),
      KEY idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS login_attempts (
      id INT AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(190) NOT NULL,
      ip VARCHAR(45) NOT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      KEY idx_email_ip (email, ip, created_at),
      KEY idx_ip (ip, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $_SESSION['_mig_login'] = 1;
  }
}
