<?php
/* Module: activity feed (log function + Activity tab) */
require_once __DIR__ . '/log.php';

route_view('tab:activity', __DIR__ . '/views/activity.php');
