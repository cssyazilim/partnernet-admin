<?php
// NOTE: Bu dosya kökten çalışan kurgu içindir (admin/ klasörü yok).
// BASE sabiti config.php içinde '/partnernet-admin/' gibi tanımlı olmalı.

if (!function_exists('h')) {
    function h($v)
    {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url_base')) {
    function url_base(string $path = '')
    {
        return rtrim(BASE, '/') . '/' . ltrim($path, '/');
    }
}

/**
 * Geri uyumluluk için bırakılmıştır.
 * Eski kodlarda url_admin(...) çağrısı varsa kırılmasın diye
 * doğrudan url_base(...) ile aynı davranır.
 * İstersen projede tüm kullanım yerlerini url_base(...) yapıp
 * burayı silebilirsin.
 */
if (!function_exists('url_admin')) {
    function url_admin(string $path = '')
    {
        return url_base($path);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('is_post')) {
    function is_post(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }
}

if (!function_exists('require_csrf')) {
    function require_csrf()
    {
        if (!is_post()) return;
        $t = $_POST['_csrf'] ?? '';
        if (!$t || $t !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(419);
            echo "CSRF token doğrulanamadı.";
            exit;
        }
    }
}

/* -------- Basit flash mesaj yardımcıları -------- */
if (!function_exists('flash_set')) {
    function flash_set(string $key, string $msg)
    {
        $_SESSION['_flash'][$key] = $msg;
    }
}

if (!function_exists('flash_get')) {
    function flash_get(string $key): ?string
    {
        $m = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $m;
    }
}
