<div class="shell">
  <aside class="side">
    <div class="brand"><span class="bi"><?= ic('layers', 18) ?></span>Issue Tracker</div>
    <div class="lbl">Workspace</div>
    <a class="nv<?= $p != 'project' ? ' on' : '' ?>" href="index.php"><?= ic('folder', 16) ?>Your Projects</a>
    <?php if ($role): ?>
      <div class="lbl"><?= h($proj['name']) ?></div>
      <?php foreach ($TABS as $k => [$lab, $icn]): ?>
        <a class="nv<?= ($t == $k || ($t == 'item' && $k == 'items')) ? ' on' : '' ?>" href="index.php?p=project&id=<?= $pid ?>&t=<?= $k ?>"><?= ic($icn, 16) ?><?= h($lab) ?><?php if ($k == 'team' && $mgr && $pendingList): ?><span class="badge"><?= count($pendingList) ?></span><?php endif; ?></a>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="who">
      <span class="av"><?= h(strtoupper(mb_substr(me()['name'], 0, 1))) ?></span>
      <div><b><?= h(me()['name']) ?></b><small>Workspace</small></div>
      <a class="lo" href="index.php?p=logout" title="Logout"><?= ic('logout', 16) ?></a>
    </div>
  </aside>

  <section class="panel">
    <div class="top">
      <div class="crumbs"><?= ic('folder', 15) ?><a href="index.php">Projects</a><?php if ($role): ?><?= ic('chev', 14) ?><span><?= h($proj['name']) ?></span><?php endif; ?></div>
      <div>Hi, <?= h(me()['name']) ?></div>
    </div>
    <div class="body">
<?php
if ($err) echo "<div class='note err'>" . h($err) . "</div>";
if ($msg) echo "<div class='note ok'>" . h($msg) . "</div>";

if ($p == 'home') {
  include view_for('app:home');
} elseif ($p == 'project') {
  if (!$role) {
    echo "<div class='note err'>No access to this project.</div>";
  } else {
    include view_for('app:project_header');
    $tabView = view_for('tab:' . $t);
    if ($tabView) include $tabView;
  }
}
?>
    </div>
  </section>
</div>
