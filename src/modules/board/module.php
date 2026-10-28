<?php
/* Module: Kanban board */
require_once __DIR__ . '/actions.php';

route_action('move', 'board_move');

route_view('tab:board', __DIR__ . '/views/board.php');
