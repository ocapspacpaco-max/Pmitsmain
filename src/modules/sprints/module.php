<?php
/* Module: sprints */
require_once __DIR__ . '/actions.php';

route_action('new_sprint',   'sprints_create',    'project', true);
route_action('sprint_state', 'sprints_set_state', 'project', true);

route_view('tab:sprints', __DIR__ . '/views/sprints.php');
