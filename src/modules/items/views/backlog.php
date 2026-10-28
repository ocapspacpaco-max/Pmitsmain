<?php
/* Backlog & Issues tab (included by views/layout/app.php; shares its variables) */
$ty = $_GET['type'] ?? ''; if (!is_string($ty) || !array_key_exists($ty, $TY)) $ty = '';
$cnt = q("SELECT type, COUNT(*) FROM items WHERE project_id=? GROUP BY type", [$pid])->fetchAll(PDO::FETCH_KEY_PAIR);
echo "<p class='hint'>Add an item below, then click its title to assign it to someone or to a sprint.</p>";
echo "<form class='glass qa' method='post'><input type='hidden' name='a' value='save_item'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='iid' value='0'>
<input name='title' placeholder='What needs to be done? (e.g. As a student, I want to log in)' required>
<select name='type' title='Type'>" . opts($TY, $ty ?: 'task') . "</select>
<select name='priority' title='Priority'>" . opts($PR, 'medium') . "</select>
<button>" . ic('plus', 15) . "Add</button></form>";
echo "<div class='pills'><a class='" . ($ty == '' ? 'on' : '') . "' href='index.php?p=project&id=$pid&t=items'>All (" . array_sum($cnt) . ")</a>";
foreach ($TYP as $k => $v) echo "<a class='" . ($ty == $k ? 'on' : '') . "' href='index.php?p=project&id=$pid&t=items&type=$k'>$v (" . (int)($cnt[$k] ?? 0) . ")</a>";
echo "</div>";
$rows = q("SELECT i.*, u.name an, s.name sn FROM items i LEFT JOIN users u ON u.id=i.assignee_id LEFT JOIN sprints s ON s.id=i.sprint_id WHERE i.project_id=?" . ($ty ? " AND i.type=?" : '') . " ORDER BY i.id DESC", $ty ? [$pid, $ty] : [$pid])->fetchAll();
echo "<div class='glass' style='overflow-x:auto'><table><tr><th>#</th><th>Title</th><th>Type</th><th>Priority</th><th>Status</th><th>Assigned to</th><th>Sprint</th></tr>";
foreach ($rows as $r) echo "<tr><td>{$r['id']}</td><td><a href='index.php?p=project&id=$pid&t=item&item={$r['id']}'>" . h($r['title']) . "</a></td><td>" . $TY[$r['type']] . "</td><td>" . tag($r['priority'], $PR[$r['priority']]) . "</td><td>" . tag('s-' . $r['status'], $ST[$r['status']]) . "</td><td>" . h($r['an']) . "</td><td>" . h($r['sn']) . "</td></tr>";
echo "</table>" . (!$rows ? "<p class='sub' style='margin-top:10px'>No items yet. Add one above.</p>" : '') . "</div>";
