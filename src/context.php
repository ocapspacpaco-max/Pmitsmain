<?php
/*
 * Per-request state the views read. Included from index.php, so these are plain globals.
 * Runs after POST actions and before any HTML is sent.
 */

/* lookup lists (see config/app.php) */
$ST  = config('app.statuses');       $STI = config('app.status_icons');
$TY  = config('app.types');          $TYP = config('app.types_plural');   $TYI = config('app.type_icons');
$PR  = config('app.priorities');     $PRI = config('app.priority_icons');
$RL  = config('app.roles');
$TABS = config('app.tabs');          $CAP = config('app.captions');

/* which page, which project */
$p = $_GET['p'] ?? 'home'; if (!is_string($p)) $p = 'home';
if ($p == 'logout') { remember_forget(); $_SESSION = []; session_destroy(); go('index.php'); }
if ($p == 'register' && me()) go('index.php');
if (in_array($p, ['forgot', 'reset']) && me()) go('index.php');

$t = $_GET['t'] ?? 'overview'; if (!is_string($t) || !isset($CAP[$t])) $t = 'overview';
$pid = 0; $role = false; $pendingList = [];
if (me() && $p == 'project') {
  $pid = (int)($_GET['id'] ?? 0);
  $role = $pid ? member($pid) : false;
  if ($role) {
    $mgr = $role == 'manager';
    $proj = q("SELECT * FROM projects WHERE id=?", [$pid])->fetch();
    $members = q("SELECT u.id,u.name,u.email,m.role FROM project_members m JOIN users u ON u.id=m.user_id WHERE m.project_id=?", [$pid])->fetchAll();
    $pendingList = array_values(array_filter($members, fn($x) => $x['role'] == 'pending'));
    $members = array_values(array_filter($members, fn($x) => $x['role'] != 'pending'));
    $mopts = ['' => '— Nobody —']; foreach ($members as $m) $mopts[$m['id']] = $m['name'];
    $sprints = q("SELECT * FROM sprints WHERE project_id=? ORDER BY id DESC", [$pid])->fetchAll();
    $sopts = ['' => '— None (backlog) —']; foreach ($sprints as $s) $sopts[$s['id']] = $s['name'];
    $rels = q("SELECT * FROM releases WHERE project_id=? ORDER BY id DESC", [$pid])->fetchAll();
    $ropts = ['' => '— None —']; foreach ($rels as $r) $ropts[$r['id']] = $r['version'];
  }
}
