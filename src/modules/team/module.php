<?php
/* Module: team (Team tab, roles, add/remove members, join code) */
require_once __DIR__ . '/actions.php';

route_action('regen_code',    'team_regen_code',    'project', true);
route_action('set_role',      'team_set_role',      'project', true);
route_action('remove_member', 'team_remove_member', 'project', true);
route_action('add_member',    'team_add_member',    'project', true);

route_view('tab:team', __DIR__ . '/views/team.php');
