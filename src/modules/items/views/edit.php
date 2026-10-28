<?php
/* Edit item screen (included by views/layout/app.php; shares its variables) */
$iid = (int)($_GET['item'] ?? 0);
$it = $iid ? q("SELECT * FROM items WHERE id=? AND project_id=?", [$iid, $pid])->fetch() : false;
if (!$it) {
  echo "<div class='note err'>Item not found.</div>";
} else {
  $g = fn($k, $d = '') => h($it[$k] ?? $d);
  echo "<form class='glass' method='post'><div class='card-h'>Edit item #$iid</div><input type='hidden' name='a' value='save_item'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='iid' value='$iid'>
  <div class='f'><label>Title</label><input name='title' value='" . $g('title') . "' required></div>
  <div class='f'><label>Description (details, acceptance criteria, steps to reproduce a bug)</label><textarea name='description' rows='4'>" . $g('description') . "</textarea></div>
  <div class='fgrid'>
    <div class='f'><label>Type</label><select name='type'>" . opts($TY, $it['type']) . "</select></div>
    <div class='f'><label>Priority</label><select name='priority'>" . opts($PR, $it['priority']) . "</select></div>
    <div class='f'><label>Status</label><select name='status'>" . opts($ST, $it['status']) . "</select></div>
    <div class='f'><label>Assigned to</label><select name='assignee_id'>" . opts($mopts, $it['assignee_id'] ?? '') . "</select></div>
    <div class='f'><label>Sprint</label><select name='sprint_id'>" . opts($sopts, $it['sprint_id'] ?? '') . "</select></div>
    <div class='f'><label>Release</label><select name='release_id'>" . opts($ropts, $it['release_id'] ?? '') . "</select></div>
  </div>
  <button>Save</button> &nbsp; <a href='index.php?p=project&id=$pid&t=items'>Cancel</a></form>";
  if ($mgr) echo "<form method='post' onsubmit=\"return confirm('Delete this item?')\"><input type='hidden' name='a' value='delete_item'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='iid' value='$iid'><button class='red'>Delete item</button></form>";
}
