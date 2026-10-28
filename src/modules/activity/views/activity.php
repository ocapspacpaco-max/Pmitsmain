<?php
/* Activity tab (included by views/layout/app.php; shares its variables) */
$rows = q("SELECT a.created_at, a.action, u.name FROM activity_log a JOIN users u ON u.id=a.user_id WHERE a.project_id=? ORDER BY a.id DESC LIMIT 100", [$pid])->fetchAll();
echo "<div class='glass' style='overflow-x:auto'><table><tr><th>When</th><th>Who</th><th>What happened</th></tr>";
foreach ($rows as $r) echo "<tr><td>" . h($r['created_at']) . "</td><td>" . h($r['name']) . "</td><td>" . h($r['action']) . "</td></tr>";
echo "</table>" . (!$rows ? "<p class='sub' style='margin-top:10px'>No activity yet.</p>" : '') . "</div>";
