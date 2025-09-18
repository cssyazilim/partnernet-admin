<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

/**
 * Genel API wrapper
 * - Authorization header'a session'daki accessToken'ı takar (varsa)
 * - 401 gelirse bir KERE refresh dener (refreshToken ile)
 */
function api_request(string $method, string $url, array $opts = [])
{
    $headers = ['Accept: application/json', 'Content-Type: application/json'];

    $access = $_SESSION['auth']['accessToken'] ?? null;
    if ($access) $headers[] = 'Authorization: Bearer ' . $access;

    $u = $url;
    if (!empty($opts['query'])) {
        $qs = http_build_query($opts['query']);
        $u = $url . (str_contains($url, '?') ? '&' : '?') . $qs;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $u,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15,
    ]);

    if (!empty($opts['json'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($opts['json'], JSON_UNESCAPED_UNICODE));
    }

    $raw = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($err) return ['ok' => false, 'status' => 0, 'error' => $err];

    // 401 ⇒ refresh dene
    if ($http === 401 && !empty($_SESSION['auth']['refreshToken'])) {
        if (api_try_refresh()) {
            // aynı isteği 1 kez yeniden gönder
            return api_request($method, $url, $opts);
        }
    }

    $data = json_decode($raw, true);
    return ['ok' => $http >= 200 && $http < 300, 'status' => $http, 'data' => $data, 'raw' => $raw];
}

/**
 * Refresh akışı (backend'in beklediği camelCase: { refreshToken } → { accessToken })
 */
function api_try_refresh(): bool
{
    $refresh = $_SESSION['auth']['refreshToken'] ?? null;
    if (!$refresh) return false;

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => API_AUTH . '/refresh',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode(['refreshToken' => $refresh], JSON_UNESCAPED_UNICODE),
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
    ]);
    $raw = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http === 200) {
        $data = json_decode($raw, true);
        $newAccess = $data['accessToken'] ?? null;
        if ($newAccess) {
            $_SESSION['auth']['accessToken'] = $newAccess;
            return true;
        }
    }
    // refresh başarısız → oturumu temizle
    unset($_SESSION['auth'], $_SESSION['user'], $_SESSION['pending_mfa']);
    return false;
}
