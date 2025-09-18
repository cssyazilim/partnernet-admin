<?php
if (session_status() === PHP_SESSION_NONE) session_start();

/* Proje URL kökü — kökten çalışıyoruz (admin/ klasörü yok) */
if (!defined('BASE')) define('BASE', '/partnernet-admin/');

/* Backend API kökleri */
if (!defined('API_AUTH')) define('API_AUTH', 'http://34.44.194.247:3001/api/auth');
if (!defined('API_CORE')) define('API_CORE', 'http://34.44.194.247:3001/api'); // ör: /quotes, /dashboard

/* CSRF token */
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

/* Küçük flash helper: login/MFA mesajları için */
$_SESSION['_flash'] = $_SESSION['_flash'] ?? [];
