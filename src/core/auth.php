<?php
/* Who is logged in, and what role they have in a project. */
function me() { return $_SESSION['u'] ?? null; }
function raw_role($pid) { return q("SELECT role FROM project_members WHERE project_id=? AND user_id=?", [$pid, me()['id']])->fetchColumn(); }
/* people who joined with a code stay "pending" (no access) until a manager picks their role */
function member($pid) { $r = raw_role($pid); return $r === 'pending' ? false : $r; }
