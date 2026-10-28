<?php
/* Backlog items: create/update, assign to a sprint or release, delete. Handlers get ($pid, $isManager). */

function items_save($pid, $mgr) {
  $TY = config('app.types'); $PR = config('app.priorities'); $ST = config('app.statuses');
  $iid = (int)($_POST['iid'] ?? 0);
  $title = trim($_POST['title'] ?? '');
  if ($title === '') go(project_url($pid, 'items'));
  $type = array_key_exists($_POST['type'] ?? '', $TY) ? $_POST['type'] : 'task';
  $pri = array_key_exists($_POST['priority'] ?? '', $PR) ? $_POST['priority'] : 'medium';
  $st = array_key_exists($_POST['status'] ?? '', $ST) ? $_POST['status'] : 'todo';
  $as = $_POST['assignee_id'] ?? '';
  $as = ($as !== '' && q("SELECT 1 FROM project_members WHERE project_id=? AND user_id=? AND role<>'pending'", [$pid, $as])->fetchColumn()) ? (int)$as : null;
  $sp = inproj('sprints', $_POST['sprint_id'] ?? '', $pid);
  $rel = inproj('releases', $_POST['release_id'] ?? '', $pid);
  $f = [$type, $title, $_POST['description'] ?? '', $pri, $st, $as, $sp, $rel];
  if ($iid) {
    q("UPDATE items SET type=?,title=?,description=?,priority=?,status=?,assignee_id=?,sprint_id=?,release_id=? WHERE id=? AND project_id=?", array_merge($f, [$iid, $pid]));
    logact($pid, "updated {$TY[$type]} #$iid: $title");
  } else {
    q("INSERT INTO items(type,title,description,priority,status,assignee_id,sprint_id,release_id,points,project_id,reporter_id) VALUES(?,?,?,?,?,?,?,?,0,?,?)", array_merge($f, [$pid, me()['id']]));
    logact($pid, "created {$TY[$type]}: $title");
  }
  go(project_url($pid, 'items'));
  return null;
}

function items_assign($pid, $mgr) {
  $iid = (int)($_POST['iid'] ?? 0);
  $field = ($_POST['field'] ?? '') == 'release' ? 'release' : 'sprint';
  $val = inproj($field == 'release' ? 'releases' : 'sprints', $_POST['val'] ?? '', $pid);
  if ($iid) {
    q("UPDATE items SET {$field}_id=? WHERE id=? AND project_id=?", [$val, $iid, $pid]);
    logact($pid, $val ? "added item #$iid to $field #$val" : "removed item #$iid from its $field");
  }
  go(project_url($pid, $field == 'release' ? 'releases' : 'sprints'));
  return null;
}

function items_delete($pid, $mgr) {
  $iid = (int)($_POST['iid'] ?? 0);
  q("DELETE FROM items WHERE id=? AND project_id=?", [$iid, $pid]);
  logact($pid, "deleted item #" . $iid);
  go(project_url($pid, 'items'));
  return null;
}
