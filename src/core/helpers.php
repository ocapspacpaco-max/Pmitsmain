<?php
/* Small helpers used across every module. */
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES); }
function go($u) { header("Location: $u"); exit; }
/* index.php?p=project&id=7&t=board */
function project_url($pid, $tab = '') { return 'index.php?p=project&id=' . (int)$pid . ($tab !== '' ? '&t=' . $tab : ''); }
function opts($arr, $sel = '') { $o = ''; foreach ($arr as $k => $v) $o .= '<option value="' . h($k) . '"' . ($k == $sel ? ' selected' : '') . '>' . h($v) . '</option>'; return $o; }
function pct($d, $t) { return $t ? (int)round($d / $t * 100) : 0; }
function bar($d, $t) { $p = pct($d, $t); return "<div class='bar'><i style='width:$p%'></i></div><small class='bt'>$d of $t done ($p%)</small>"; }
function tag($c, $t) { return "<span class='tag $c'>" . h($t) . "</span>"; }
function inproj($table, $id, $pid) { return ($id !== '' && $id !== null && q("SELECT 1 FROM $table WHERE id=? AND project_id=?", [$id, $pid])->fetchColumn()) ? (int)$id : null; }
