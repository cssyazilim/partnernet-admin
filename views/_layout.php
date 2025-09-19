<?php
// _layout.php — Kök router şablonu (index.php bu dosyayı include eder)

// Mevcut oturum bilgileri
$user     = $_SESSION['user'] ?? ['email' => 'unknown@user'];
$role     = strtolower($user['role'] ?? '');
$canAdmin = in_array($role, ['admin', 'super_admin', 'merkez'], true);
$current  = $_GET['p'] ?? 'dashboard';

// Menü etiketleri (isteğe göre)
$labels = [
    'dashboard'     => 'Dashboard',
    'customers'     => 'Müşterilerim',
    'quotes'        => 'Tekliflerim',
    'orders'        => 'Siparişlerim',
    'products'     => 'Ürünlerim',
    'invoices'      => 'Faturalar / Raporlar',
    'performance'   => 'Bayi Performans',
    'messages'      => 'Mesajlar',
    'notifications' => 'Bildirimler',
    'settings'      => 'Ayarlar',
    'system'        => 'Sistem',
    'profile'       => 'Profil',
    'not_found'     => 'Sayfa bulunamadı',
];
$pageLabel = $labels[$current] ?? $labels['not_found'];
$title     = $pageLabel . ' · Bayi Yönetim Sistemi';

// Aktif menü sınıfları
function nav_classes(string $id, string $current): string
{
    $base = 'menu-item flex items-center px-4 py-3 rounded-lg transition-colors';
    $active = 'bg-blue-50 text-blue-600';
    $passive = 'text-gray-700 hover:bg-blue-50 hover:text-blue-600';
    return $base . ' ' . ($id === $current ? $active : $passive);
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-transition {
            transition: transform .3s ease-in-out;
        }

        .content-shift {
            transition: margin-left .3s ease-in-out;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .7
            }
        }

        .mobile-overlay {
            transition: opacity .3s ease-in-out;
        }

        .fixed-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 40;
        }

        .fixed-topbar {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 30;
        }

        .main-content {
            padding-top: 4rem;
            min-height: 100vh;
        }

        @media (min-width:768px) {
            .main-content {
                margin-left: 16rem;
            }
        }

        @media (max-width:767px) {
            .sidebar-mobile-hidden {
                transform: translateX(-100%);
            }

            .sidebar-mobile-visible {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden mobile-overlay"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed-sidebar w-64 bg-white shadow-lg sidebar-transition sidebar-mobile-hidden md:sidebar-mobile-visible">
        <a href="<?= h(url_base('index.php?p=dashboard')) ?>" class="block cursor-pointer">
            <div class="h-16 p-6 border-b border-gray-200 flex items-center hover:bg-gray-50 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">AdminPanel</h1>
                        <p class="text-sm text-gray-500">v2.1.0</p>
                    </div>
                </div>
            </div>
        </a>


        <nav class="mt-6 px-4 pb-20 overflow-y-auto h-full">
            <div class="space-y-2">
                <a href="<?= h(url_base('index.php?p=dashboard')) ?>" class="<?= h(nav_classes('dashboard', $current)) ?>">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= h(url_base('index.php?p=customers')) ?>" class="<?= h(nav_classes('customers', $current)) ?>">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Müşterilerim</span>
                    <span class="ml-auto bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">12</span>
                </a>

                <a href="<?= h(url_base('index.php?p=quotes')) ?>" class="<?= h(nav_classes('quotes', $current)) ?>">
                    <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                    <span>Tekliflerim</span>
                    <span class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full">3</span>
                </a>

                <a href="<?= h(url_base('index.php?p=orders')) ?>" class="<?= h(nav_classes('orders', $current)) ?>">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    <span>Siparişlerim</span>
                    <span class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">7</span>
                </a>

                <a href="<?= h(url_base('index.php?p=products')) ?>" class="<?= h(nav_classes('products', $current)) ?>">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    <span>Ürunlerim</span>
                    <span class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">7</span>
                </a>

                <a href="<?= h(url_base('index.php?p=invoices')) ?>" class="<?= h(nav_classes('invoices', $current)) ?>">
                    <i class="fas fa-receipt w-5 h-5 mr-3"></i>
                    <span>Faturalar / Raporlar</span>
                </a>

                <a href="<?= h(url_base('index.php?p=performance')) ?>" class="<?= h(nav_classes('performance', $current)) ?>">
                    <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                    <span>Bayi Performans</span>
                    <span class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">A+</span>
                </a>

                <a href="<?= h(url_base('index.php?p=messages')) ?>" class="<?= h(nav_classes('messages', $current)) ?>">
                    <i class="fas fa-envelope w-5 h-5 mr-3"></i>
                    <span>Mesajlar</span>
                    <span class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">2</span>
                </a>

                <a href="<?= h(url_base('index.php?p=notifications')) ?>" class="<?= h(nav_classes('notifications', $current)) ?>">
                    <i class="fas fa-bell w-5 h-5 mr-3"></i>
                    <span>Bildirimler</span>
                    <span class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full notification-badge">5</span>
                </a>

                <div class="border-t border-gray-200 my-4"></div>

                <a href="<?= h(url_base('index.php?p=settings')) ?>" class="<?= h(nav_classes('settings', $current)) ?>">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span>Ayarlar</span>
                </a>

                <?php if ($canAdmin): ?>
                    <a href="<?= h(url_base('index.php?p=system')) ?>" class="<?= h(nav_classes('system', $current)) ?>">
                        <i class="fas fa-sliders-h w-5 h-5 mr-3"></i>
                        <span>Sistem</span>
                    </a>
                <?php endif; ?>

                <a href="<?= h(url_base('logout.php')) ?>" class="menu-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                    <span>Çıkış Yap</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Top Bar -->
    <div class="fixed-topbar left-0 md:left-64 right-0 h-16 bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between h-full px-4">
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-bars text-gray-600"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex flex-1 max-w-md mx-4">
                <div class="relative w-full">
                    <input type="text" placeholder="Ara..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Search -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-search text-gray-600"></i>
                </button>

                <!-- Notifications -->
                <div class="relative">
                    <button id="notificationBtn" class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                        <i class="fas fa-bell text-gray-600"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center notification-badge">3</span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">Bildirimler</h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <div class="p-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800">Yeni teklif talebi alındı</p>
                                        <p class="text-xs text-gray-500 mt-1">2 dakika önce</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800">Sipariş onaylandı</p>
                                        <p class="text-xs text-gray-500 mt-1">15 dakika önce</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 hover:bg-gray-50">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800">Hakediş ödemesi bekliyor</p>
                                        <p class="text-xs text-gray-500 mt-1">1 saat önce</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-t border-gray-200">
                            <a class="w-full block text-center text-sm text-blue-600 hover:text-blue-700" href="<?= h(url_base('index.php?p=notifications')) ?>">Tümünü Gör</a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="relative">
                    <button id="userMenuBtn" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium"><?= h(strtoupper(substr($user['email'] ?? 'A', 0, 1))) ?></span>
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700"><?= h($user['email'] ?? '') ?></span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="p-3 border-b border-gray-200">
                            <p class="font-medium text-gray-800"><?= h($user['email'] ?? '') ?></p>
                            <p class="text-sm text-gray-500"><?= h($role ?: '-') ?></p>
                        </div>
                        <div class="py-2">
                            <a href="<?= h(url_base('index.php?p=profile')) ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user w-4 h-4 mr-3"></i> Profil
                            </a>
                            <a href="<?= h(url_base('index.php?p=settings')) ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog w-4 h-4 mr-3"></i> Ayarlar
                            </a>
                            <a href="<?= h(url_base('help.php')) ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-question-circle w-4 h-4 mr-3"></i> Yardım
                            </a>
                            <div class="border-t border-gray-200 my-2"></div>
                            <a href="<?= h(url_base('logout.php')) ?>" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i> Çıkış Yap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="p-4 md:p-6">
            <!-- Başlık -->


            <!-- Route içeriği (View) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <?php
                // View dosyalarının kendi <html> üretmemesi için sinyal
                $EMBED = true;
                include $__view_file;
                ?>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        mobileMenuBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-mobile-hidden');
            sidebar.classList.toggle('sidebar-mobile-visible');
            mobileOverlay.classList.toggle('hidden');
        });
        mobileOverlay?.addEventListener('click', () => {
            sidebar.classList.add('sidebar-mobile-hidden');
            sidebar.classList.remove('sidebar-mobile-visible');
            mobileOverlay.classList.add('hidden');
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-mobile-hidden');
                sidebar.classList.add('sidebar-mobile-visible');
                mobileOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.classList.remove('sidebar-mobile-visible');
            }
        });

        // Dropdowns
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        notificationBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            userDropdown?.classList.add('hidden');
        });
        userMenuBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
            notificationDropdown?.classList.add('hidden');
        });
        document.addEventListener('click', () => {
            notificationDropdown?.classList.add('hidden');
            userDropdown?.classList.add('hidden');
        });
    </script>
</body>

</html>