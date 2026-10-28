<?php
/* Releases (managers only). */
function releases_create($pid, $mgr) {
  if (trim($_POST['version'] ?? '') !== '') {
    q("INSERT INTO releases(project_id,version,release_date,notes) VALUES(?,?,?,?)", [$pid, trim($_POST['version']), ($_POST['release_date'] ?? '') ?: null, $_POST['notes'] ?? '']);
    logact($pid, 'created release ' . trim($_POST['version']));
  }
  go(project_url($pid, 'releases'));
  return null;
}
