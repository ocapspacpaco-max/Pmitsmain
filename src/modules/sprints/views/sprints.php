<?php
/* Sprints tab (included by views/layout/app.php; shares its variables) */
echo "<p class='hint'>A sprint is a short work period (1–4 weeks). Add items to a sprint, then track them on the Kanban Board.</p>";
if ($mgr) echo "<form class='glass' method='post'><div class='card-h'>New Sprint</div><input type='hidden' name='a' value='new_sprint'><input type='hidden' name='pid' value='$pid'>
<div class='fgrid'><div class='f'><label>Name</label><input name='name' placeholder='Sprint 1' required></div><div class='f'><label>Goal</label><input name='goal' placeholder='What should we finish?'></div>
<div class='f'><label>Start date</label><input type='date' name='start_date'></div><div class='f'><label>End date</label><input type='date' name='end_date'></div></div><button>Create Sprint</button></form>";
$free = q("SELECT id,title FROM items WHERE project_id=? AND sprint_id IS NULL AND status<>'done' ORDER BY id DESC", [$pid])->fetchAll();
$fo = ['' => '— choose an item to add —']; foreach ($free as $x) $fo[$x['id']] = "#{$x['id']} " . $x['title'];
foreach ($sprints as $s) {
  $its = q("SELECT id,title,type,priority,status FROM items WHERE sprint_id=? AND project_id=? ORDER BY FIELD(priority,'critical','high','medium','low'), id", [$s['id'], $pid])->fetchAll();
  $c = count($its); $n = array_count_values(array_column($its, 'status')); $d = $n['done'] ?? 0;
  $ob = count(array_filter($its, fn($x) => $x['type'] == 'bug' && $x['status'] != 'done'));
  $days = $s['end_date'] ? (int)ceil((strtotime($s['end_date']) - time()) / 86400) : null;
  echo "<div class='glass'><div class='card-h'>" . h($s['name']) . " " . tag('s-' . $s['status'], ucfirst($s['status'])) . "</div>";
  echo "<p class='sub' style='margin:0 0 12px'>" . h($s['goal']) . "<br>" . h($s['start_date']) . " → " . h($s['end_date']) . ($days !== null && $s['status'] == 'active' ? ($days >= 0 ? " · $days days left" : " · past end date") : '') . "</p>";
  echo "<b>Progress report</b>" . bar($d, $c) . "<p style='margin:6px 0 12px'><small>To Do: " . ($n['todo'] ?? 0) . " · In Progress: " . ($n['in_progress'] ?? 0) . " · In Review: " . ($n['review'] ?? 0) . " · Done: $d · Open bugs: $ob</small></p>";
  if ($its) {
    echo "<table><tr><th>#</th><th>Title</th><th>Type</th><th>Priority</th><th>Status</th><th></th></tr>";
    foreach ($its as $x) echo "<tr><td>{$x['id']}</td><td><a href='index.php?p=project&id=$pid&t=item&item={$x['id']}'>" . h($x['title']) . "</a></td><td>" . $TY[$x['type']] . "</td><td>" . tag($x['priority'], $PR[$x['priority']]) . "</td><td>" . tag('s-' . $x['status'], $ST[$x['status']]) . "</td>
      <td><form method='post' class='inline'><input type='hidden' name='a' value='assign'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='iid' value='{$x['id']}'><input type='hidden' name='field' value='sprint'><input type='hidden' name='val' value=''><button class='sm gray'>Remove</button></form></td></tr>";
    echo "</table>";
  } else echo "<p class='sub'>No items in this sprint yet.</p>";
  echo "<p style='margin-top:12px'><form method='post' class='inline'><input type='hidden' name='a' value='assign'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='field' value='sprint'><input type='hidden' name='val' value='{$s['id']}'><select name='iid'>" . opts($fo) . "</select> <button class='sm'>Add to sprint</button></form></p>";
  if ($mgr && $s['status'] != 'completed') {
    $nx = $s['status'] == 'planned' ? 'active' : 'completed';
    echo "<form method='post' class='inline'><input type='hidden' name='a' value='sprint_state'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='sid' value='{$s['id']}'><input type='hidden' name='status' value='$nx'><button style='margin-top:10px'>" . ($nx == 'active' ? 'Start Sprint' : 'Complete Sprint') . "</button></form>";
  }
  echo "</div>";
}
if (!$sprints) echo "<p class='sub'>No sprints yet." . ($mgr ? " Create one above." : " Ask a Manager to create one.") . "</p>";
