<?php
/* Kanban board: moving a card between columns. */
function board_move($pid, $mgr) {
  $ST = config('app.statuses');
  $iid = (int)($_POST['iid'] ?? 0);
  if (array_key_exists($_POST['status'] ?? '', $ST)) {
    q("UPDATE items SET status=? WHERE id=? AND project_id=?", [$_POST['status'], $iid, $pid]);
    logact($pid, "moved item #$iid to " . $ST[$_POST['status']]);
  }
  $sf = $_POST['sprint'] ?? '';
  go(project_url($pid, 'board') . ($sf !== '' ? '&sprint=' . urlencode($sf) : ''));
  return null;
}
