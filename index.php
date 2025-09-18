<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/api.php';
require_once __DIR__ . '/config/auth.php';

require_login_or_redirect();

$routes = [
    'dashboard' => ['file' => __DIR__ . '/views/dashboard.php', 'roles' => ['admin', 'super_admin', 'merkez', 'partner']],
    'quotes'    => ['file' => __DIR__ . '/views/quotes.php',    'roles' => ['admin', 'super_admin', 'merkez', 'partner']],
    'orders'    => ['file' => __DIR__ . '/views/orders.php',    'roles' => ['admin', 'super_admin', 'merkez', 'partner']],
    'customers' => ['file' => __DIR__ . '/views/customers.php', 'roles' => ['admin', 'super_admin', 'merkez', 'partner']],
    'system'    => ['file' => __DIR__ . '/views/system.php',    'roles' => ['admin', 'super_admin', 'merkez']],
    'notifications'    => ['file' => __DIR__ . '/views/notifications.php',    'roles' => ['admin', 'super_admin', 'merkez']],
];

$page = trim($_GET['p'] ?? 'dashboard');

if (!isset($routes[$page])) {
    $__view_file = __DIR__ . '/views/not_found.php';
} else {
    $meta = $routes[$page];
    enforce_roles_or_401($meta['roles']);
    $__view_file = $meta['file'];
}

include __DIR__ . '/views/_layout.php';
