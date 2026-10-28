<?php
/* ---------- team join codes ---------- */
function code_norm($s) { $c = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$s)); return strlen($c) === 8 ? substr($c, 0, 4) . '-' . substr($c, 4) : ''; }
function code_new() {
  $al = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';   // no I, O, 0, 1 (look-alikes)
  do {
    $c = ''; for ($i = 0; $i < 8; $i++) $c .= $al[random_int(0, strlen($al) - 1)];
    $c = substr($c, 0, 4) . '-' . substr($c, 4);
  } while (q("SELECT 1 FROM projects WHERE join_code=?", [$c])->fetchColumn());
  return $c;
}
