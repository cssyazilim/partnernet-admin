<?php
// admin/dashboard.php

/* ================= Helpers ================= */
if (!function_exists('h')) {
    function h($v)
    {
        return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
if (!function_exists('num')) {
    function num($v)
    {
        if ($v === null || $v === '') return '—';
        if (!is_numeric($v)) return h($v);
        return number_format((float)$v, 0, ',', '.');
    }
}
if (!function_exists('money')) {
    function money($amount, $currency = 'TRY')
    {
        if ($amount === null || $amount === '') return '—';
        $prefix = '';
        $suffix = '';
        switch ($currency) {
            case 'TRY':
                $prefix = '₺';
                break;
            case 'USD':
                $prefix = '$';
                break;
            case 'EUR':
                $suffix = ' EUR';
                break;
            default:
                $suffix = ' ' . h($currency);
        }
        return $prefix . number_format((float)$amount, 0, ',', '.') . $suffix;
    }
}
if (!function_exists('ago')) {
    function ago($v)
    {
        return $v ? h($v) : '—';
    }
}

/* ================= API ================= */
$res  = api_request('GET', API_CORE . '/dashboard/summary', []);
$data = $res['ok'] ? ($res['data'] ?? []) : [];

/* ================= Safe reads ================= */
// JSON örneğine göre kartlar "cards" altında
$cards = $data['cards'] ?? [];

// Toplam müşteri
$totalCustomers = $cards['total_customers'] ?? 0;

// Aylık ciro (string gelebilir, money() handle ediyor)
$monthlyRevenueAmount   = $cards['monthly_revenue'] ?? null;
$monthlyRevenueCurrency = 'TRY'; // JSON vermiyor; TRY varsayıyoruz
$monthlyRevenueChange   = null;  // JSON’da yok; trend gizlenecek

// Bekleyen teklifler
$pending = $cards['pending_quotes'] ?? [];
$pendingQuotesCount    = $pending['count'] ?? 0;
$pendingQuotesNewWeek  = $pending['new_this_week'] ?? 0;

// Aktif siparişler
$activeOrders          = $cards['active_orders'] ?? [];
$activeOrdersCount     = $activeOrders['count'] ?? 0;
$activeOrdersTotalValue = $activeOrders['total_amount'] ?? null; // dikkat: total_amount
$activeOrdersCurrency  = 'TRY'; // JSON vermiyor

// Aktiviteler
$activities            = $data['activities'] ?? [];

// Nokta rengi
function activityDotColor($type)
{
    switch ($type) {
        case 'customer_created':
            return 'bg-blue-500';
        case 'quote_approved':
            return 'bg-green-500';
        case 'payment_received':
            return 'bg-orange-500';
        case 'order_created':
            return 'bg-purple-500';
        case 'quote_submitted':
            return 'bg-amber-500';
        default:
            return 'bg-gray-400';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <main class="p-6">

        <!-- Hata/Geliştirici Paneli -->
        <?php if (!$res['ok']): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <div class="font-semibold mb-1">Veri çekilemedi (<?= h($res['status'] ?? '-') ?>)</div>
                <pre class="whitespace-pre-wrap text-red-900"><?= h($res['raw'] ?? '') ?></pre>
            </div>
        <?php endif; ?>

        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Toplam Müşteri -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Toplam Müşteri</p>
                        <p class="text-2xl font-bold text-gray-900"><?= num($totalCustomers) ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-gray-400 text-sm">Bu ay trend verisi yok</span>
                </div>
            </div>

            <!-- Aylık Ciro -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Aylık Ciro</p>
                        <p class="text-2xl font-bold text-gray-900"><?= money($monthlyRevenueAmount, $monthlyRevenueCurrency) ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-gray-400 text-sm">Trend verisi yok</span>
                </div>
            </div>

            <!-- Bekleyen Teklifler -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bekleyen Teklifler</p>
                        <p class="text-2xl font-bold text-gray-900"><?= num($pendingQuotesCount) ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-orange-600 text-sm font-medium"><?= num($pendingQuotesNewWeek) ?> Yeni</span>
                    <span class="text-gray-600 text-sm">Bu hafta</span>
                </div>
            </div>

            <!-- Aktif Siparişler -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Aktif Siparişler</p>
                        <p class="text-2xl font-bold text-gray-900"><?= num($activeOrdersCount) ?></p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-purple-600 text-sm font-medium"><?= money($activeOrdersTotalValue, $activeOrdersCurrency) ?></span>
                    <span class="text-gray-600 text-sm">Toplam değer</span>
                </div>
            </div>
        </div>

        <!-- Son Aktiviteler ve Hızlı İşlemler -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Son Aktiviteler -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Son Aktiviteler</h3>

                <?php if (empty($activities)): ?>
                    <div class="text-sm text-gray-500">Son aktivite bulunamadı.</div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($activities as $act):
                            $type  = $act['type'] ?? 'generic';
                            $title = $act['title'] ?? (
                                $type === 'customer_created' ? 'Yeni müşteri eklendi' : ($type === 'quote_approved' ? 'Teklif onaylandı' : ($type === 'payment_received' ? 'Ödeme alındı' : ($type === 'order_created' ? 'Yeni sipariş oluşturuldu' : ($type === 'quote_submitted' ? 'Teklif gönderildi' : 'Aktivite'))))
                            );
                            $desc = $act['detail'] ?? $act['description'] ?? $act['desc'] ?? '';
                            $time = $act['ago'] ?? $act['time_ago'] ?? $act['created_at_human'] ?? null;
                            $dot  = activityDotColor($type);
                        ?>
                            <div class="flex items-start">
                                <div class="w-2 h-2 <?= $dot ?> rounded-full mt-2 mr-3"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900"><?= h($title) ?></p>
                                    <?php if ($desc): ?>
                                        <p class="text-xs text-gray-600"><?= h($desc) ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-400 mt-1"><?= ago($time) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hızlı İşlemler -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Hızlı İşlemler</h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Hızlı İşlemler (düzeltilmiş) -->
                    <a href="<?= h(url_base('index.php?p=customers&action=new')) ?>"
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors block text-center">
                        <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-900">Müşteri Ekle</p>
                    </a>

                    <a href="<?= h(url_base('index.php?p=quotes&action=new')) ?>"
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors block text-center">
                        <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-900">Tekliflerim</p>
                    </a>

                    <a href="<?= h(url_base('index.php?p=orders')) ?>"
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors block text-center">
                        <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-900">Siparişler</p>
                    </a>

                    <a href="<?= h(url_base('index.php?p=invoices')) ?>"
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors block text-center">
                        <svg class="w-8 h-8 text-orange-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-900">Faturalar</p>
                    </a>

                </div>
            </div>
        </div>

        <!-- Debug JSON -->
        <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ham JSON</h3>
                <pre class="whitespace-pre-wrap text-sm text-gray-800"><?= h(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
            </div>
        <?php endif; ?>

    </main>
</body>

</html>