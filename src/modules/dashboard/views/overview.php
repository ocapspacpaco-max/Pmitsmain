<?php
/* Dashboard tab (included by views/layout/app.php; shares its variables) */
$sc = q("SELECT status, COUNT(*) FROM items WHERE project_id=? GROUP BY status", [$pid])->fetchAll(PDO::FETCH_KEY_PAIR);
$tc = q("SELECT type, COUNT(*) FROM items WHERE project_id=? GROUP BY type", [$pid])->fetchAll(PDO::FETCH_KEY_PAIR);
$pc = q("SELECT priority, COUNT(*) FROM items WHERE project_id=? AND status<>'done' GROUP BY priority", [$pid])->fetchAll(PDO::FETCH_KEY_PAIR);
$total = array_sum($sc); $done = (int)($sc['done'] ?? 0); $openN = array_sum($pc);
$ob = (int)q("SELECT COUNT(*) FROM items WHERE project_id=? AND type='bug' AND status<>'done'", [$pid])->fetchColumn();
$pr = pct($done, $total);

echo "<div class='stats'>";
foreach ($ST as $k => $v) echo "<div class='glass stat'><div class='sh'><span>$v</span>" . ic($STI[$k], 16) . "</div><b>" . (int)($sc[$k] ?? 0) . "</b></div>";
echo "<div class='glass stat'><div class='sh'><span>Open Bugs</span>" . ic('bug', 16) . "</div><b>$ob</b></div></div>";

echo "<div class='glass prog'><div class='ring' style='--p:$pr'><span>$pr%</span></div><div class='pg'><div class='between'><b>Overall Progress</b><small>$done / $total ($pr%)</small></div><div class='bar'><i style='width:$pr%'></i></div><small>" . ($total ? "$done of $total items are done." : "No issues yet. Progress will appear as work moves forward.") . "</small></div></div>";

echo "<div class='two2'><div class='glass'><div class='ch between'><h3>By Type</h3><small>$total items</small></div>";
foreach ($TY as $k => $v) { $c = (int)($tc[$k] ?? 0); $w = pct($c, $total); echo "<div class='mrow'>" . ic($TYI[$k], 15) . "<span>$v</span><div class='bar thin'><i style='width:$w%'></i></div><b>$c</b></div>"; }
echo "</div><div class='glass'><div class='ch between'><h3>Open Items by Priority</h3><small>$openN items</small></div>";
foreach ($PR as $k => $v) { $c = (int)($pc[$k] ?? 0); $w = pct($c, $openN); echo "<div class='mrow'>" . ic($PRI[$k], 15) . "<span>$v</span><div class='bar thin'><i style='width:$w%'></i></div><b>$c</b></div>"; }
echo "</div></div>";
echo "<div class='foot'>" . ic('info', 14) . "All metrics reflect the current project. " . ($total ? "$total item" . ($total == 1 ? '' : 's') . " in " . h($proj['name']) . "." : "No issues have been added to " . h($proj['name']) . ".") . "</div>";
