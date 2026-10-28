<?php
/* Module: accounts (login, register, forgot/reset password) */
require_once __DIR__ . '/remember.php';
require_once __DIR__ . '/throttle.php';
require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/actions.php';

route_action('register',          'auth_register',          'public');
route_action('login',             'auth_login',             'public');
route_action('forgot',            'auth_forgot',            'public');
route_action('verify_reset_code', 'auth_verify_reset_code', 'public');
route_action('reset_password',    'auth_reset_password',    'public');

route_view('auth:login',    __DIR__ . '/views/login.php');
route_view('auth:register', __DIR__ . '/views/register.php');
route_view('auth:forgot',   __DIR__ . '/views/forgot.php');
route_view('auth:reset',    __DIR__ . '/views/reset.php');
