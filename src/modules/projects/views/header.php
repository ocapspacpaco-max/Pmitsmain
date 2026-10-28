<?php
/* Project title bar + tab caption */
$cap = $CAP[$t];
echo "<div class='phead'><span class='ib big'>" . ic('layers', 22) . "</span><div><h1>" . h($proj['name']) . "</h1><p class='sub'>" . h($proj['description']) . "</p></div><span class='pill'>" . ic('user', 13) . " " . h($RL[$role]) . "</span></div>";
echo "<div class='trow'><h2>" . h($cap[0]) . "</h2><small>" . h($cap[1]) . "</small></div>";
