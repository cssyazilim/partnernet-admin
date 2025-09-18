<?php
require_once __DIR__ . '/helpers.php';

/* ------------------ Yardımcılar ------------------ */

/**
 * Auth ve kullanıcıyla ilgili session verilerini temizler.
 * Gerekirse tüm session'ı bitirir ve yeni bir ID üretir.
 */
function clear_auth_session(): void
{
    // Uygulamaya özel anahtarları temizle
    unset($_SESSION['auth'], $_SESSION['user']);

    // Flash gibi diğer verileri korumak istemiyorsan komple boşalt:
    // session_unset();

    // Cookie tabanlı "remember me" benzeri tokenlar varsa sil
    foreach (['remember', 'accessToken', 'refreshToken'] as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        }
    }

    // Session güvenlik için ID yenile
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

function authed(): bool
{
    return !empty($_SESSION['auth']['accessToken']) && !empty($_SESSION['user']);
}

function current_user(): array
{
    return $_SESSION['user'] ?? [];
}

function has_role(array $roles): bool
{
    $u = current_user();
    $role = strtolower($u['role'] ?? '');
    $roles = array_map('strtolower', $roles);
    return in_array($role, $roles, true);
}

/* ------------------ Giriş kontrolü ------------------ */

function require_login_or_redirect(): void
{
    if (authed()) return;

    // 1) Session'ı temizle
    clear_auth_session();

    // 2) Login URL (next paramıyla geri dönüş)
    $next = $_SERVER['REQUEST_URI'] ?? url_admin('');
    $loginUrl = url_admin('login.php') . '?next=' . rawurlencode($next);

    // 3) AJAX ise JSON
    if (
        isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok'       => false,
            'error'    => 'unauthorized',
            'redirect' => $loginUrl
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 4) Normal istek: doğrudan yönlendir
    redirect($loginUrl, 302);

    // Eğer "kısacık gözüksün" istersen üst satır yerine şu bloğu kullan:
    /*
    http_response_code(401);
    header('Refresh: 2; url=' . $loginUrl);
    echo '<!DOCTYPE html><meta charset="utf-8"><title>401</title>
      <div style="max-width:560px;margin:64px auto;padding:24px;border:1px solid #fecaca;background:#fee2e2;color:#991b1b;border-radius:12px;text-align:center;font-family:sans-serif">
        <h2 style="font-size:24px;margin:0 0 8px">401</h2>
        <p style="margin:0 0 16px">Bu sayfaya erişim yetkiniz yok. Giriş sayfasına yönlendiriliyorsunuz…</p>
        <a href="'.h($loginUrl).'" style="display:inline-block;padding:8px 12px;background:#dc2626;color:#fff;border-radius:8px;text-decoration:none">Hemen giriş yap</a>
      </div>
      <script>setTimeout(function(){ location.href='.json_encode($loginUrl).'; }, 2000);</script>';
    exit;
    */
}

/* ------------------ Rol kontrolü ------------------ */

function enforce_roles_or_401(array $roles): void
{
    if (has_role($roles)) return;

    // 1) Yetkisiz → session'ı temizle
    clear_auth_session();

    // 2) Login’e yönlendir (next ile)
    $next = $_SERVER['REQUEST_URI'] ?? url_admin('');
    $loginUrl = url_admin('login.php') . '?next=' . rawurlencode($next);

    // AJAX isteği ise JSON
    if (
        isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok'       => false,
            'error'    => 'forbidden',
            'redirect' => $loginUrl
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Normal istek: doğrudan 302
    redirect($loginUrl, 302);

    // “Biraz görünsün” istersen yukarıdaki redirect yerine şu alternatif bloğu kullanabilirsin:
    /*
    http_response_code(401);
    header('Refresh: 2; url=' . $loginUrl);
    echo '<!DOCTYPE html><meta charset="utf-8"><title>401</title>
      <div style="max-width:560px;margin:64px auto;padding:24px;border:1px solid #fecaca;background:#fee2e2;color:#991b1b;border-radius:12px;text-align:center;font-family:sans-serif">
        <h2 style="font-size:24px;margin:0 0 8px">401</h2>
        <p style="margin:0 0 16px">Bu sayfaya erişim yetkiniz yok. Giriş sayfasına yönlendiriliyorsunuz…</p>
        <a href="'.h($loginUrl).'" style="display:inline-block;padding:8px 12px;background:#dc2626;color:#fff;border-radius:8px;text-decoration:none">Hemen giriş yap</a>
      </div>
      <script>setTimeout(function(){ location.href='.json_encode($loginUrl).'; }, 2000);</script>';
    exit;
    */
}
