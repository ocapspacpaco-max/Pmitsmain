<?php
/* Module: projects (Your Projects page, new project, join/leave a team, team codes) */
require_once __DIR__ . '/join_code.php';
require_once __DIR__ . '/actions.php';

route_action('new_project',  'projects_create', 'user');
route_action('join_project', 'projects_join',   'user');
route_action('leave_team',   'projects_leave',  'user');

route_view('app:home',           __DIR__ . '/views/home.php');
route_view('app:project_header', __DIR__ . '/views/header.php');
