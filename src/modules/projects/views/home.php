<?php
/* Your Projects page */
  $ps = q("SELECT p.*, m.role, (SELECT COUNT(*) FROM items i WHERE i.project_id=p.id) total, (SELECT COUNT(*) FROM items i WHERE i.project_id=p.id AND i.status='done') done FROM projects p JOIN project_members m ON m.project_id=p.id WHERE m.user_id=? ORDER BY p.id DESC", [me()['id']])->fetchAll();
  $n = count($ps); ?>
  <div class="ptitle">
    <div><h1>Projects</h1><p class="sub">A clear space for your next idea. Create, organize, and track.</p></div>
    <span class="pill"><?= $n ?> project<?= $n == 1 ? '' : 's' ?></span>
  </div>
  <div class="two">
    <div class="stack">
    <form class="glass newp" method="post">
      <div class="ch"><span class="ib"><?= ic('plus', 16) ?></span><h3>New Project</h3></div>
      <input type="hidden" name="a" value="new_project">
      <div class="f"><label>Project Name</label><input name="name" placeholder="Project Name" required></div>
      <div class="f"><label>Description</label><textarea name="description" rows="5" placeholder="Description"></textarea></div>
      <div class="frow"><small>Make it happen.</small><button><?= ic('plus', 15) ?>Create Project</button></div>
    </form>
    <?php if (!$ps): /* only people who are not in a team can join one */ ?>
    <form class="glass joinp" method="post">
      <div class="ch"><span class="ib"><?= ic('users', 16) ?></span><h3>Join a Team</h3></div>
      <input type="hidden" name="a" value="join_project">
      <div class="f"><label>Team code</label><input name="code" placeholder="e.g. K7M2-9QXP" required maxlength="9" autocomplete="off"></div>
      <div class="frow"><small>Ask your manager for the code.</small><button><?= ic('arrow-right', 15) ?>Join Team</button></div>
    </form>
    <?php endif; ?>
    </div>
    <div class="glass">
      <div class="ch between"><h3>Your Projects</h3><?= ic('folder', 16) ?></div>
      <?php foreach ($ps as $r): $pp = pct($r['done'], $r['total']); $pend = $r['role'] == 'pending'; ?>
      <<?= $pend ? 'div' : 'a' ?> class="tile<?= $pend ? ' pending' : '' ?>"<?= $pend ? '' : ' href="index.php?p=project&id=' . (int)$r['id'] . '"' ?>>
        <div class="between"><span class="ib"><?= ic('layers', 16) ?></span><?= $pend ? '' : ic('arrow-up-right', 15) ?></div>
        <h4><?= h($r['name']) ?></h4>
        <?php if (trim((string)$r['description']) !== ''): ?><p class="sub"><?= h($r['description']) ?></p><?php endif; ?>
        <?php if ($pend): ?>
        <div class="role"><?= ic('user', 13) ?>Waiting for the manager to choose your role</div>
        <form method="post" class="inline" style="margin-top:6px"><input type="hidden" name="a" value="leave_team"><input type="hidden" name="pid" value="<?= (int)$r['id'] ?>"><button class="sm gray">Cancel request</button></form>
        <?php else: ?>
        <div class="role"><?= ic('user', 13) ?><?= h($RL[$r['role']] ?? $r['role']) ?></div>
        <div class="prow"><span>Overall Progress</span><span><?= (int)$r['done'] ?> / <?= (int)$r['total'] ?> (<?= $pp ?>%)</span></div>
        <div class="bar"><i style="width:<?= $pp ?>%"></i></div>
        <div class="open"><span>Open project</span><?= ic('arrow-right', 15) ?></div>
        <?php endif; ?>
      </<?= $pend ? 'div' : 'a' ?>>
      <?php endforeach;
      if (!$ps) echo "<p class='sub'>No projects yet. Create your first one on the left.</p>"; ?>
    </div>
  </div>
  <div class="glass strip">
    <?= ic('layers', 18) ?>
    <div><b>From the big picture to the smallest issue.</b><small>Open <?= $ps ? h($ps[0]['name']) : 'a project' ?> to view your dashboard, board, backlog, sprints, and team.</small></div>
  </div>
