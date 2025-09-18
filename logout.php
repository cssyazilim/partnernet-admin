<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/api.php';

$refresh = $_SESSION['auth']['refreshToken'] ?? null;
if ($refresh) {
    api_request('POST', API_AUTH . '/logout', [
        'json' => ['refreshToken' => $refresh]
    ]);
}

// Session temizle
unset($_SESSION['auth'], $_SESSION['user'], $_SESSION['pending_mfa']);
redirect(url_base('login.php'));
