<?php
/* Module: items (Backlog & Issues tab, edit screen) */
require_once __DIR__ . '/actions.php';

route_action('save_item',   'items_save');
route_action('assign',      'items_assign');
route_action('delete_item', 'items_delete', 'project', true);   // managers only

route_view('tab:items', __DIR__ . '/views/backlog.php');
route_view('tab:item',  __DIR__ . '/views/edit.php');
