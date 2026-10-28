<?php
/* Team members, roles and the join code (managers only). */

function team_regen_code($pid, $mgr) {
  q("UPDATE projects SET join_code=? WHERE id=?", [code_new(), $pid]);
  logact($pid, 'generated a new team code');
  go(project_url($pid, 'team'));
  return null;
}

function team_set_role($pid, $mgr) {
  $RL = config('app.roles');
  $uid = (int)($_POST['uid'] ?? 0);
  $nr = $_POST['role'] ?? '';
  if (!array_key_exists($nr, $RL)) go(project_url($pid, 'team'));
  $cur = q("SELECT role FROM project_members WHERE project_id=? AND user_id=?", [$pid, $uid])->fetchColumn();
  $mc = (int)q("SELECT COUNT(*) FROM project_members WHERE project_id=? AND role='manager'", [$pid])->fetchColumn();
  if ($cur && !($cur == 'manager' && $nr != 'manager' && $mc <= 1)) {
    q("UPDATE project_members SET role=? WHERE project_id=? AND user_id=?", [$nr, $pid, $uid]);
    logact($pid, $cur == 'pending' ? "approved a join request as " . $RL[$nr] : "changed a member's role to " . $RL[$nr]);
  } elseif ($cur) {
    flash_err('A project needs at least one Manager.');
  }
  go(project_url($pid, 'team'));
  return null;
}

function team_remove_member($pid, $mgr) {
  $uid = (int)($_POST['uid'] ?? 0);
  $cur = q("SELECT role FROM project_members WHERE project_id=? AND user_id=?", [$pid, $uid])->fetchColumn();
  $mc = (int)q("SELECT COUNT(*) FROM project_members WHERE project_id=? AND role='manager'", [$pid])->fetchColumn();
  if ($uid == me()['id']) {
    flash_err('You cannot remove yourself.');
  } elseif ($cur && !($cur == 'manager' && $mc <= 1)) {
    q("DELETE FROM project_members WHERE project_id=? AND user_id=?", [$pid, $uid]);
    q("UPDATE items SET assignee_id=NULL WHERE project_id=? AND assignee_id=?", [$pid, $uid]);
    logact($pid, $cur == 'pending' ? 'declined a join request' : 'removed a member from the team');
  } elseif ($cur) {
    flash_err('A project needs at least one Manager.');
  }
  go(project_url($pid, 'team'));
  return null;
}

function team_add_member($pid, $mgr) {
  $RL = config('app.roles');
  $email = trim($_POST['email'] ?? '');
  $u = q("SELECT id FROM users WHERE email=?", [$email])->fetchColumn();
  if ($u) {
    $role = $_POST['role'] ?? '';
    q("INSERT IGNORE INTO project_members VALUES(?,?,?)", [$pid, $u, array_key_exists($role, $RL) ? $role : 'developer']);
    logact($pid, 'added ' . $email . ' to the team');
  }
  go(project_url($pid, 'team') . ($u ? '&added=1' : '&err=nouser'));
  return null;
}
