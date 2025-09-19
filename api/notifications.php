<?php
// STANDALONE endpoint — herhangi bir layout’a include ETMEYİN.
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/config.php'; // API_CORE
require_once __DIR__ . '/../config/api.php';    // api_request(method, url, ['query'=>[], 'json'=>[]])

// Caching kapat (özellikle read-all sonrası eski response dönmesin)
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (empty($_SESSION['auth']['accessToken'])) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path   = $_GET['path'] ?? '';   // list | one | read | read-all
$id     = $_GET['id']   ?? '';

try {
    $base = rtrim(API_CORE, '/') . '/notifications';

    /* ===== LIST (limit/offset -> page/page_size dönüştürmesi backend’de var) ===== */
    if ($method === 'GET' && ($path === '' || $path === 'list')) {
        $q = [
            'unread_only'     => $_GET['unread_only']    ?? null,
            'type'            => $_GET['type']           ?? null,
            'since'           => $_GET['since']          ?? null,
            'before'          => $_GET['before']         ?? null,
            'search'          => $_GET['search']         ?? null,
            'include_payload' => $_GET['include_payload'] ?? null,
            'partner_id'      => $_GET['partner_id']     ?? null,
            'sort_by'         => $_GET['sort_by']        ?? null,
            'sort_dir'        => $_GET['sort_dir']       ?? null,
            'limit'           => $_GET['limit']          ?? null,
            'offset'          => $_GET['offset']         ?? null,
            // cache buster
            '_t'              => time(),
        ];
        $q = array_filter($q, fn($v) => $v !== null && $v !== '');

        $res = api_request('GET', $base, ['query' => $q]);
        http_response_code($res['status'] ?? 500);
        echo $res['raw'] ?? json_encode(['error' => 'proxy_error']);
        exit;
    }

    /* ===== ONE ===== */
    if ($method === 'GET' && $path === 'one' && $id) {
        $url = $base . '/' . rawurlencode($id);
        $res = api_request('GET', $url, ['query' => ['_t' => time()]]);
        http_response_code($res['status'] ?? 500);
        echo $res['raw'] ?? json_encode(['error' => 'proxy_error']);
        exit;
    }

    /* ===== READ (single) =====
     Admin+broadcast (partner_id=null) için backend {global:true} ya da partner_id ister.
  */
    if ($method === 'POST' && $path === 'read' && $id) {
        // frontend’den gelen body varsa al; yoksa boş {}
        $body = json_decode(file_get_contents('php://input') ?: '{}', true);
        if (!is_array($body)) $body = [];
        $url = $base . '/' . rawurlencode($id) . '/read';
        $res = api_request('PUT', $url, ['json' => $body]);
        http_response_code($res['status'] ?? 500);
        echo $res['raw'] ?? json_encode(['error' => 'proxy_error']);
        exit;
    }

    /* ===== READ-ALL =====
     Body: { since?: 'YYYY-MM-DD', partner_id?: uuid, global?: true }
     Admin için global:true → broadcast’leri topluca okundu.
  */
    if ($method === 'POST' && $path === 'read-all') {
        $body = json_decode(file_get_contents('php://input') ?: '{}', true);
        if (!is_array($body)) $body = [];
        $url = $base . '/read-all';
        $res = api_request('PUT', $url, ['json' => $body]);
        http_response_code($res['status'] ?? 500);
        echo $res['raw'] ?? json_encode(['error' => 'proxy_error']);
        exit;
    }

    http_response_code(404);
    echo json_encode(['error' => 'not_found']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'proxy_error', 'detail' => $e->getMessage()]);
}
