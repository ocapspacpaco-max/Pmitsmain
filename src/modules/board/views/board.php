<?php
/* Kanban board tab (included by views/layout/app.php; shares its variables) */
$sf = $_GET['sprint'] ?? ''; if (!is_string($sf)) $sf = '';
$ex = ''; $ep = [];
if ($sf === 'none') $ex = ' AND i.sprint_id IS NULL';
elseif (ctype_digit($sf)) { $ex = ' AND i.sprint_id=?'; $ep = [(int)$sf]; }
else $sf = '';
$bopts = ['' => 'All items', 'none' => 'Backlog (no sprint)']; foreach ($sprints as $s) $bopts[$s['id']] = $s['name'];
$keys = array_keys($ST);
echo "<p class='hint'>Each card is one piece of work. Use the arrows to move it: To Do → In Progress → In Review → Done.</p>";
echo "<form method='get' class='showf'><input type='hidden' name='p' value='project'><input type='hidden' name='id' value='$pid'><input type='hidden' name='t' value='board'><span>Show:</span><select name='sprint' onchange='this.form.submit()'>" . opts($bopts, $sf) . "</select></form><div class='kan'>";
foreach ($ST as $k => $v) {
  $rows = q("SELECT i.*, u.name an FROM items i LEFT JOIN users u ON u.id=i.assignee_id WHERE i.project_id=? AND i.status=?$ex ORDER BY FIELD(i.priority,'critical','high','medium','low'), i.id", array_merge([$pid, $k], $ep))->fetchAll();
  $i = array_search($k, $keys); $pv = $keys[$i - 1] ?? null; $nx = $keys[$i + 1] ?? null;
  echo "<div class='col'><h4>$v (" . count($rows) . ")</h4>";
  foreach ($rows as $r) {
    echo "<div class='kc'>" . tag($r['type'], $TY[$r['type']]) . " " . tag($r['priority'], $PR[$r['priority']]) . "<a href='index.php?p=project&id=$pid&t=item&item={$r['id']}'>#{$r['id']} " . h($r['title']) . "</a><small>" . h($r['an'] ?: 'Unassigned') . "</small>
    <form method='post' class='mv'><input type='hidden' name='a' value='move'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='iid' value='{$r['id']}'><input type='hidden' name='sprint' value='" . h($sf) . "'>"
    . ($pv ? "<button class='gray' name='status' value='$pv' title='Move to {$ST[$pv]}'>" . ic('arrow-left', 14) . "</button>" : '')
    . ($nx ? "<button class='gray' name='status' value='$nx' title='Move to {$ST[$nx]}'>" . ic('arrow-right', 14) . "</button>" : '') . "</form></div>";
  }
  if (!$rows) echo "<small>No items</small>";
  echo "</div>";
}
echo "</div>";
