<?php
/* Team tab (included by views/layout/app.php; shares its variables) */
echo "<p class='hint'><b>Manager:</b> full control (sprints, releases, team, delete). <b>Developer / Tester:</b> add and update items and move cards.</p>";
if ($mgr && $pendingList) {
  echo "<div class='glass pendcard'><div class='card-h'>Waiting for a role (" . count($pendingList) . ")</div><p class='sub' style='margin:0 0 10px'>These people entered your team code. They cannot open the project until you choose their role.</p><table><tr><th>Name</th><th>Email</th><th>Choose role</th><th></th></tr>";
  foreach ($pendingList as $m) echo "<tr><td>" . h($m['name']) . "</td><td>" . h($m['email']) . "</td>
    <td><form method='post' class='inline'><input type='hidden' name='a' value='set_role'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='uid' value='{$m['id']}'><select name='role' required><option value=''>Choose a role…</option>" . opts($RL) . "</select> <button class='sm'>Approve</button></form></td>
    <td><form method='post' class='inline'><input type='hidden' name='a' value='remove_member'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='uid' value='{$m['id']}'><button class='sm gray'>Decline</button></form></td></tr>";
  echo "</table></div>";
}
if ($mgr) {
  $code = h($proj['join_code']);
  echo "<div class='glass codecard'><div class='between'><div><label>Team join code</label><div class='code'>$code</div><small>Share this code. People who enter it on Your Projects wait for your approval until you choose their role.</small></div>
  <div class='acts'><button type='button' class='gray' onclick=\"navigator.clipboard.writeText('$code');this.textContent='Copied!'\">Copy code</button>
  <form method='post' class='inline' onsubmit=\"return confirm('Generate a new code? The old code will stop working.')\"><input type='hidden' name='a' value='regen_code'><input type='hidden' name='pid' value='$pid'><button class='gray'>New code</button></form></div></div></div>";
}
echo "<div class='glass'><table><tr><th>Name</th><th>Email</th><th>Role</th>" . ($mgr ? "<th></th>" : '') . "</tr>";
foreach ($members as $m) {
  $rc = $mgr ? "<form method='post' class='inline'><input type='hidden' name='a' value='set_role'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='uid' value='{$m['id']}'><select name='role' onchange='this.form.submit()'>" . opts($RL, $m['role']) . "</select></form>" : $RL[$m['role']];
  $rm = $mgr ? "<td>" . ($m['id'] != me()['id'] ? "<form method='post' class='inline' onsubmit=\"return confirm('Remove this person from the team?')\"><input type='hidden' name='a' value='remove_member'><input type='hidden' name='pid' value='$pid'><input type='hidden' name='uid' value='{$m['id']}'><button class='sm gray'>Remove</button></form>" : '') . "</td>" : '';
  echo "<tr><td>" . h($m['name']) . "</td><td>" . h($m['email']) . "</td><td>$rc</td>$rm</tr>";
}
echo "</table></div>";
if ($mgr) echo "<form class='glass qa' method='post'><input type='hidden' name='a' value='add_member'><input type='hidden' name='pid' value='$pid'><input type='email' name='email' style='flex:1;min-width:220px;width:auto' placeholder='Registered email of the new member' required> <select name='role'>" . opts($RL, 'developer') . "</select> <button>" . ic('plus', 15) . "Add Member</button></form>";

$mgrN = count(array_filter($members, fn($x) => $x['role'] == 'manager'));
$alone = count($members) <= 1;
$lt = $alone ? "You are the last person in this team. Leaving will permanently delete the team and all of its items, sprints, and releases."
    : ($mgr && $mgrN <= 1 ? "You are the only Manager, so make someone else a Manager first." : "You can only be in one team at a time. Leave this one to join another team with a code.");
$lc = $alone ? "Leave and permanently delete this team? This cannot be undone." : "Leave this team? You will lose access until you join a team again with a code.";
echo "<form method='post' class='glass between' onsubmit=\"return confirm('$lc')\"><div><b>" . ($alone ? "Leave and delete this team" : "Leave this team") . "</b><p class='sub'>$lt</p></div><input type='hidden' name='a' value='leave_team'><input type='hidden' name='pid' value='$pid'><button class='red'>" . ($alone ? "Leave &amp; delete team" : "Leave team") . "</button></form>";
