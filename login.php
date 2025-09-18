<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/api.php';
require_once __DIR__ . '/config/auth.php';

/* Logout isteği varsa */
if (isset($_GET['logout'])) {
    unset($_SESSION['auth'], $_SESSION['user'], $_SESSION['pending_mfa']);
    redirect(url_base('login.php'));
}

/* Zaten girişliyse dashboard'a gönder */
if (authed()) redirect(url_base('index.php?p=dashboard'));

$error = null;

/* POST: Login denemesi */
if (is_post()) {
    require_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "E-posta ve şifre zorunludur.";
    } else {
        // Backend: POST /api/auth/login/user
        $res = api_request('POST', API_AUTH . '/login/user', [
            'json' => ['email' => $email, 'password' => $password]
        ]);

        if (!$res['ok']) {
            $error = $res['data']['error'] ?? 'Giriş başarısız.';
        } else {
            // MFA zorunlu mu?
            if (($res['data']['status'] ?? '') === 'MFA_REQUIRED') {
                $_SESSION['pending_mfa'] = [
                    'session_id' => $res['data']['mfa']['session_id'] ?? null,
                    'method'     => $res['data']['mfa']['method'] ?? 'email',
                    'expires_at' => $res['data']['mfa']['expires_at'] ?? null,
                    'scope'      => $res['data']['auth']['scope'] ?? 'user',
                    'email'      => $res['data']['auth']['email'] ?? $email,
                    'user_id'    => $res['data']['auth']['user_id'] ?? null,
                    'partner_id' => $res['data']['auth']['partner_id'] ?? null,
                ];
                flash_set('info', 'E-postanıza gelen doğrulama kodunu giriniz.');
                redirect(url_base('mfa.php'));
            }

            // MFA yoksa: tokenları al ve içeri al
            $accessToken  = $res['data']['accessToken']  ?? null;
            $refreshToken = $res['data']['refreshToken'] ?? null;

            if (!$accessToken || !$refreshToken) {
                $error = "Token alınamadı.";
            } else {
                $_SESSION['auth'] = [
                    'accessToken'  => $accessToken,
                    'refreshToken' => $refreshToken,
                ];
                // Backend girişte user/role dönüyorsa burayı gerçek veriye göre set edebilirsin
                $_SESSION['user'] = [
                    'email' => $email,
                    'role'  => 'admin',
                ];
                redirect(url_base('index.php?p=dashboard'));
            }
        }
    }
}

$flash = flash_get('info');
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Bayi Yönetim Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 50%, #581c87 100%);
        }

        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
        }

        .error-message {
            animation: shake .5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0)
            }

            25% {
                transform: translateX(-5px)
            }

            75% {
                transform: translateX(5px)
            }
        }

        /* Scrollbar'ı gizle (scroll davranışı devam eder) */
        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        html,
        body {
            overscroll-behavior: none;
        }
    </style>
</head>

<body class="min-h-screen gradient-bg flex items-center justify-center p-3 sm:p-4">
    <div class="w-full max-w-sm sm:max-w-md">

        <!-- Başlık -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Admin Panel</h1>
            <p class="text-blue-100 text-sm sm:text-base">Bayi Yönetim Sistemi</p>
        </div>

        <!-- Kart -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl card-shadow">
            <div class="text-center mb-6">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Yönetici Girişi</h2>
                <p class="text-gray-600 text-sm sm:text-base">Lütfen giriş bilgilerinizi girin</p>
            </div>

            <?php if (!empty($flash)): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                    <?= h($flash) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm error-message">
                    <?= h($error) ?>
                </div>
            <?php endif; ?>

            <!-- Gerçek POST hedefi: kendi sayfası -->
            <form id="adminLoginForm" method="post" action="<?= h(url_base('login.php')) ?>" class="space-y-4 sm:space-y-5">
                <input type="hidden" name="_csrf" value="<?= h($_SESSION['csrf_token']) ?>">

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">E-posta Adresi</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" required
                            class="w-full px-3 py-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all pl-10 sm:pl-12 text-base sm:text-lg"
                            placeholder="admin@example.com" autocomplete="username">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-3 sm:left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Şifre</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-3 py-3 sm:px-4 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all pl-10 sm:pl-12 pr-10 sm:pr-12 text-base sm:text-lg"
                            placeholder="••••••••" autocomplete="current-password">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-3 sm:left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <button type="button" onclick="togglePassword()" class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors" aria-label="Şifreyi göster/gizle">
                            <svg id="eyeIcon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 block text-gray-700 font-medium">Beni hatırla</span>
                    </label>
                    <a href="#" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Şifremi unuttum</a>
                </div>

                <button type="submit" id="loginBtn" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all font-semibold text-base sm:text-lg shadow-lg">
                    <span id="loginBtnText">Admin Girişi</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
            } else {
                input.type = 'password';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        document.getElementById('adminLoginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const txt = document.getElementById('loginBtnText');
            btn.disabled = true;
            txt.textContent = 'Giriş yapılıyor...';
            setTimeout(() => {
                try {
                    btn.disabled = false;
                    txt.textContent = 'Admin Girişi';
                } catch (e) {}
            }, 6000);
        });

        window.addEventListener('load', () => {
            const em = document.getElementById('email');
            if (em) em.focus();
        });
    </script>
</body>

</html>