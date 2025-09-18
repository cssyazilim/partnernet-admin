<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/api.php';

if (!empty($_SESSION['auth']['accessToken'])) redirect(url_base('index.php?p=dashboard'));

$pending = $_SESSION['pending_mfa'] ?? null;
if (!$pending || empty($pending['session_id'])) {
    flash_set('info', 'Oturum bulunamadı. Lütfen tekrar giriş yapınız.');
    redirect(url_base('login.php'));
}

$error = null;
$info  = flash_get('info');

if (is_post() && isset($_POST['verify'])) {
    require_csrf();
    $code = trim($_POST['code'] ?? '');
    if (!$code) {
        $error = "Kod zorunludur.";
    } else {
        $res = api_request('POST', API_AUTH . '/mfa/verify', [
            'json' => ['session_id' => $pending['session_id'], 'code' => $code]
        ]);
        if (!$res['ok']) {
            $error = $res['data']['error'] ?? 'Doğrulama başarısız.';
        } else {
            $accessToken  = $res['data']['accessToken']  ?? null;
            $refreshToken = $res['data']['refreshToken'] ?? null;
            if (!$accessToken || !$refreshToken) {
                $error = "Token alınamadı.";
            } else {
                $_SESSION['auth'] = [
                    'accessToken'  => $accessToken,
                    'refreshToken' => $refreshToken,
                ];
                $_SESSION['user'] = [
                    'email' => $pending['email'] ?? 'unknown@user',
                    'role'  => 'admin', // backend'ten role dönüyorsa set et
                ];
                unset($_SESSION['pending_mfa']);
                redirect(url_base('index.php?p=dashboard'));
            }
        }
    }
}

if (is_post() && isset($_POST['resend'])) {
    require_csrf();
    $res = api_request('POST', API_AUTH . '/mfa/resend', [
        'json' => ['session_id' => $pending['session_id']]
    ]);
    if (!$res['ok']) {
        $error = $res['data']['error'] ?? 'Kod tekrar gönderilemedi.';
    } else {
        $info = $res['data']['message'] ?? 'Kod tekrar gönderildi.';
        if (!empty($res['data']['mfa']['expires_at'])) {
            $_SESSION['pending_mfa']['expires_at'] = $res['data']['mfa']['expires_at'];
        }
    }
}
?>
<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <title>MFA Doğrulama</title>
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
            margin: 0;
            background: #0f172a;
            color: #e2e8f0;
            display: grid;
            place-items: center;
            height: 100vh
        }

        .card {
            background: #111827;
            padding: 24px;
            border-radius: 14px;
            min-width: 320px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35)
        }

        input,
        button {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #0b1220;
            color: #e2e8f0
        }

        button {
            cursor: pointer;
            border: 0;
            background: #22c55e;
            color: #031;
            font-weight: 700
        }

        .mt {
            margin-top: 10px
        }

        .err {
            color: #fecaca;
            margin-bottom: 8px
        }

        .info {
            color: #a7f3d0;
            margin-bottom: 8px
        }

        .row {
            display: flex;
            gap: 8px
        }

        .row form {
            flex: 1
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>MFA Doğrulama</h2>
        <div style="opacity:.9;font-size:.9em">E-posta: <?= h($pending['email'] ?? '-') ?></div>
        <?php if ($info): ?><div class="info"><?= h($info) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="err"><?= h($error) ?></div><?php endif; ?>

        <!-- OTP verify -->
        <form method="post" class="mt">
            <input type="hidden" name="_csrf" value="<?= h($_SESSION['csrf_token']) ?>">
            <input class="mt" type="text" name="code" placeholder="6 haneli kod" required>
            <button class="mt" type="submit" name="verify" value="1">Doğrula</button>
        </form>

        <!-- Resend -->
        <form method="post" class="mt">
            <input type="hidden" name="_csrf" value="<?= h($_SESSION['csrf_token']) ?>">
            <button type="submit" name="resend" value="1">Kodu Tekrar Gönder</button>
        </form>

        <div class="mt" style="font-size:.9em;opacity:.8">
            <a href="<?= h(url_admin('login.php?logout=1')) ?>" style="color:#93c5fd">Hesabı değiştir</a>
        </div>
    </div>
</body>

</html>