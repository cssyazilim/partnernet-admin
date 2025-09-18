
<?php
$EMBED = isset($_GET['embed']);
if (!$EMBED): ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="page-title" content="Ürün Ekle"> <!-- index.php başlık fallback -->
  <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-50 p-6">
<?php endif; ?>

<!-- SAYFA İÇERİĞİNİZ -->
<div class="bg-white rounded-lg card-shadow p-6">
  <h3 class="text-lg font-semibold text-gray-900 mb-4">Ürün Ekle</h3>
  <p class="text-gray-600">… içerik …</p>
</div>

<?php if (!$EMBED): ?>
</body>
</html>
<?php endif; ?>

<!-- admin/dashboard.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <main class="p-6">

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Toplam Müşteri</p>
            <p class="text-2xl font-bold text-gray-900">127</p>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-green-600 text-sm font-medium">+12%</span>
          <span class="text-gray-600 text-sm">Bu ay</span>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Aylık Ciro</p>
            <p class="text-2xl font-bold text-gray-900">₺245K</p>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-green-600 text-sm font-medium">+8%</span>
          <span class="text-gray-600 text-sm">Geçen aya göre</span>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Bekleyen Teklifler</p>
            <p class="text-2xl font-bold text-gray-900">8</p>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-orange-600 text-sm font-medium">3 Yeni</span>
          <span class="text-gray-600 text-sm">Bu hafta</span>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Aktif Siparişler</p>
            <p class="text-2xl font-bold text-gray-900">15</p>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-purple-600 text-sm font-medium">₺180K</span>
          <span class="text-gray-600 text-sm">Toplam değer</span>
        </div>
      </div>
    </div>

    <!-- Son Aktiviteler ve Hızlı İşlemler -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Son Aktiviteler -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Son Aktiviteler</h3>
        <div class="space-y-4">
          <div class="flex items-start">
            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">Yeni müşteri eklendi</p>
              <p class="text-xs text-gray-600">ABC Teknoloji Ltd. Şti. sisteme kaydedildi</p>
              <p class="text-xs text-gray-400 mt-1">2 saat önce</p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">Teklif onaylandı</p>
              <p class="text-xs text-gray-600">XYZ A.Ş. için hazırlanan teklif onaylandı</p>
              <p class="text-xs text-gray-400 mt-1">4 saat önce</p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3"></div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">Ödeme alındı</p>
              <p class="text-xs text-gray-600">DEF Şirketi'nden 25.000₺ ödeme alındı</p>
              <p class="text-xs text-gray-400 mt-1">1 gün önce</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Hızlı İşlemler -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hızlı İşlemler</h3>
        <div class="grid grid-cols-2 gap-4">
          <button onclick="parent.showModule('customers')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <p class="text-sm font-medium text-gray-900">Müşteri Ekle</p>
          </button>

          <button onclick="parent.showModule('request-quote')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-900">Teklif Talep Et</p>
          </button>

          <button onclick="parent.showModule('orders')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-900">Siparişler</p>
          </button>

          <button onclick="parent.showModule('invoices')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <svg class="w-8 h-8 text-orange-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-900">Faturalar</p>
          </button>
        </div>
      </div>
    </div>

  </main>
</body>
</html>
