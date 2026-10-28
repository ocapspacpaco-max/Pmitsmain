<?php
/* Releases tab (included by views/layout/app.php; shares its variables) */
echo "<p class='hint'>A release is a version you deliver (like v1.0.0). Add the items that go into it and watch it reach 100%.</p>";
if ($mgr) echo "<form class='glass' method='post'><div class='card-h'>New Release</div><input type='hidden' name='a' value='new_release'><input type='hidden' name='pid' value='$pid'>
<div class='fgrid'><div class='f'><label>Version</label><input name='version' placeholder='v1.0.0' required></div><div class='f'><label>Release date</label><input type='date' name='release_date'></div></div>
<div class='f'><label>Release notes</label><textarea name='notes' rows='2' placeholder='What is new in this version?'></textarea></div><button>Create Release</button></form>";
$free = q("SELECT id,title FROM items WHERE project_id=? AND release_id IS NULL ORDER BY id DESC", [$pid])->fetchAll();
$fo = ['' => '— choose an item to add —']; foreach ($free as $x) $fo[$x['id']] = "#{$x['id']} " . $x['title'];
foreach ($rels as $r) {
  $its = q("SELECT id,title,type,status FROM items WHERE release_id=? AND project_id=? ORDER BY id", [$r['id'], $pid])->fetchAll();
  $c = count($its); $d = count(array_filter($its, fn($x) => $x['status'] == 'done'));
  echo "<div class='glass'><div class='card-h'>" . h($r['version']) . " <small>" . h($r['release_date']) . "</small> " . ($c && $d == $c ? tag('s-done', 'Ready to release') : tag('s-todo', 'In progress')) . "</div>";
  if (trim((string)$r['notes']) !== '') echo "<p class='sub' style='margin:0 0 10px'>" . nl2br(h($r['notes'])) . "</p>";
  echo bar($d, $c);
  if ($its) {
    echo "<table style='margin-top:10px'><tr><th>#</th><th>Title</th><th>Type</th><th>Status</th><th></th></tr>";
    foreach ($its as $x) echo "<tr><td>{$x['id']}</td><td><a href='index.php?p=project&id=$pid&t=item&item={$x['id']}'>" . h($x['title']) . "</a></td><td>" . $TY[$x['type']] . "</td><td>" . tag('s-' . $x['status'], $ST[$x['status']]) . "</td>
      <td><form method='post' class='inline'><input type='hidden' name='a' value='assign'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='iid' value='{$x['id']}'><input type='hidden' name='field' value='release'><input type='hidden' name='val' value=''><button class='sm gray'>Remove</button></form></td></tr>";
    echo "</table>";
  } else echo "<p class='sub' style='margin-top:10px'>No items in this release yet.</p>";
  echo "<p style='margin-top:12px'><form method='post' class='inline'><input type='hidden' name='a' value='assign'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='field' value='release'><input type='hidden' name='val' value='{$r['id']}'><select name='iid'>" . opts($fo) . "</select> <button class='sm'>Add to release</button></form></p></div>";
}
if (!$rels) echo "<p class='sub'>No releases yet." . ($mgr ? " Create one above." : " Ask a Manager to create one.") . "</p>";
