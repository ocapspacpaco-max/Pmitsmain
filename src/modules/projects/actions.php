<?php
/* Create a project, join one with a team code, leave one. */

function projects_create() {
  q("INSERT INTO projects(name,description,owner_id,join_code) VALUES(?,?,?,?)", [$_POST['name'] ?? '', $_POST['description'] ?? '', me()['id'], code_new()]);
  $id = (int)db()->lastInsertId();
  q("INSERT INTO project_members VALUES(?,?,'manager')", [$id, me()['id']]);
  logact($id, 'created the project');
  go(project_url($id));
  return null;
}

function projects_leave() {
  $lp = (int)($_POST['pid'] ?? 0);
  $cur = raw_role($lp);
  if ($cur) {
    $mc = (int)q("SELECT COUNT(*) FROM project_members WHERE project_id=? AND role='manager'", [$lp])->fetchColumn();
    $others = (int)q("SELECT COUNT(*) FROM project_members WHERE project_id=? AND user_id<>? AND role<>'pending'", [$lp, me()['id']])->fetchColumn();
    if ($cur != 'pending' && $others == 0) {
      /* last person in the team: leaving deletes the empty team and all of its data */
      $pdo = db();
      try {
        $pdo->beginTransaction();
        foreach (['items', 'sprints', 'releases', 'activity_log', 'project_members'] as $tb) q("DELETE FROM $tb WHERE project_id=?", [$lp]);
        q("DELETE FROM projects WHERE id=?", [$lp]);
        $pdo->commit();
      } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        flash_err('Could not delete the team: ' . $e->getMessage());
        go(project_url($lp, 'team'));
      }
      flash_ok('You were the last member, so the team was deleted. You can create a new team or join another one now.');
      go('index.php');
    }
    if ($cur == 'manager' && $mc <= 1) {
      flash_err('You are the only Manager of this team. Make someone else a Manager before you leave.');
      go(project_url($lp, 'team'));
    }
    q("DELETE FROM project_members WHERE project_id=? AND user_id=?", [$lp, me()['id']]);
    q("UPDATE items SET assignee_id=NULL WHERE project_id=? AND assignee_id=?", [$lp, me()['id']]);
    logact($lp, $cur == 'pending' ? 'cancelled a join request' : 'left the team');
    flash_ok($cur == 'pending' ? 'Join request cancelled.' : 'You left the team. You can join another team with a code now.');
  }
  go('index.php');
  return null;
}

function projects_join() {
  $c = code_norm($_POST['code'] ?? '');
  if (($_SESSION['join_lock'] ?? 0) > time()) {
    flash_err('Too many wrong codes. Please wait a few minutes and try again.');
    go('index.php');
  }
  if ((int)q("SELECT COUNT(*) FROM project_members WHERE user_id=?", [me()['id']])->fetchColumn() > 0) {
    flash_err('You are already in a team. Leave it first before joining a new team.');
    go('index.php');
  }
  $row = $c !== '' ? q("SELECT id,name FROM projects WHERE join_code=?", [$c])->fetch() : false;
  if (!$row) {
    $n = (int)($_SESSION['join_fail'] ?? 0) + 1;
    $_SESSION['join_fail'] = $n;
    if ($n >= (int)config('app.security.join_max_fails', 5)) {
      $_SESSION['join_lock'] = time() + (int)config('app.security.join_lock_seconds', 600);
      $_SESSION['join_fail'] = 0;
    }
    flash_err('That team code is not valid. Check it and try again.');
    go('index.php');
  }
  unset($_SESSION['join_fail']);
  $cur = raw_role($row['id']);
  if ($cur === 'pending') {
    flash_ok('Your request to join ' . $row['name'] . ' is still waiting for the manager to choose your role.');
  } elseif ($cur) {
    flash_ok('You are already a member of ' . $row['name'] . '.');
    go(project_url((int)$row['id']));
  } else {
    q("INSERT IGNORE INTO project_members VALUES(?,?,'pending')", [$row['id'], me()['id']]);
    logact($row['id'], 'asked to join the team (waiting for a role)');
    flash_ok('Request sent! You can open ' . $row['name'] . ' as soon as the manager chooses your role.');
  }
  go('index.php');
  return null;
}
