<?php
/* Writes one line to the project's activity feed. $uid defaults to the logged-in user. */
function logact($pid, $msg, $uid = null) {
  q("INSERT INTO activity_log(project_id,user_id,action) VALUES(?,?,?)", [$pid, $uid ?? me()['id'], $msg]);
}
