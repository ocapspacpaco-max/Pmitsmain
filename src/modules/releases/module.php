<?php
/* Module: releases */
require_once __DIR__ . '/actions.php';

route_action('new_release', 'releases_create', 'project', true);

route_view('tab:releases', __DIR__ . '/views/releases.php');
