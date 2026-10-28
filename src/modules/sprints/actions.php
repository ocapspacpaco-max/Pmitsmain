<?php
/* Sprints (managers only). */
function sprints_create($pid, $mgr) {
  if (trim($_POST['name'] ?? '') !== '') {
    q("INSERT INTO sprints(project_id,name,goal,start_date,end_date) VALUES(?,?,?,?,?)", [$pid, trim($_POST['name']), $_POST['goal'] ?? '', ($_POST['start_date'] ?? '') ?: null, ($_POST['end_date'] ?? '') ?: null]);
    logact($pid, 'created sprint ' . trim($_POST['name']));
  }
  go(project_url($pid, 'sprints'));
  return null;
}

function sprints_set_state($pid, $mgr) {
  $s = in_array($_POST['status'] ?? '', ['planned', 'active', 'completed']) ? $_POST['status'] : 'planned';
  $sid = (int)($_POST['sid'] ?? 0);
  q("UPDATE sprints SET status=? WHERE id=? AND project_id=?", [$s, $sid, $pid]);
  logact($pid, "set sprint #$sid to $s");
  go(project_url($pid, 'sprints'));
  return null;
}
