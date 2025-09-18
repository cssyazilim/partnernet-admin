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


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayi Performans - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .hover-scale {
            transition: transform 0.2s ease;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .rank-badge {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }
        .rank-badge.gold {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }
        .rank-badge.silver {
            background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
        }
        .rank-badge.bronze {
            background: linear-gradient(135deg, #d97706 0%, #92400e 100%);
        }
        .performance-bar {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            transition: width 0.8s ease-out;
        }
        .dealer-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .dealer-card:hover {
            border-left-color: #3b82f6;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .top-performer {
            border-left-color: #10b981;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        .loading-skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Bayi Performans</h1>
                        <p class="text-sm text-gray-500 hidden sm:block">Sipariş performansına göre sıralama</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="refreshData()" class="text-gray-500 hover:text-gray-700 transition-colors p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="goBack()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Performance Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Toplam Bayi</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalDealers">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Aktif Bayi</p>
                        <p class="text-2xl font-semibold text-gray-900" id="activeDealers">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Toplam Sipariş</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalOrders">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ortalama Sipariş</p>
                        <p class="text-2xl font-semibold text-gray-900" id="avgOrders">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Sorting -->
        <div class="bg-white rounded-lg card-shadow p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div>
                        <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-2">
                            Sıralama
                        </label>
                        <select id="sortBy" onchange="sortDealers()" class="px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="orders-desc">Sipariş Sayısı (Yüksek → Düşük)</option>
                            <option value="orders-asc">Sipariş Sayısı (Düşük → Yüksek)</option>
                            <option value="name-asc">İsim (A → Z)</option>
                            <option value="name-desc">İsim (Z → A)</option>
                            <option value="city-asc">Şehir (A → Z)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-2">
                            Durum
                        </label>
                        <select id="filterStatus" onchange="filterDealers()" class="px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="all">Tümü</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Pasif</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="text" id="searchInput" placeholder="Bayi ara..." onkeyup="searchDealers()" class="px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none w-full sm:w-64">
                    <button onclick="clearSearch()" class="px-3 py-2 text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dealers List -->
        <div class="space-y-4" id="dealersList">
            <!-- Loading skeleton -->
            <div id="loadingSkeleton" class="space-y-4">
                <div class="bg-white rounded-lg card-shadow p-6 loading-skeleton h-24"></div>
                <div class="bg-white rounded-lg card-shadow p-6 loading-skeleton h-24"></div>
                <div class="bg-white rounded-lg card-shadow p-6 loading-skeleton h-24"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Bayi bulunamadı</h3>
                <p class="text-gray-500">Arama kriterlerinize uygun bayi bulunmuyor.</p>
            </div>
        </div>
    </main>

    <script>
        let dealersData = [];
        let filteredDealers = [];

        // Sample dealer data
        const sampleDealers = [
            { id: 1, name: "Teknoloji Dünyası", city: "İstanbul", region: "Marmara", orders: 245, status: "active", phone: "+90 212 555 0101", email: "info@teknolojidunyasi.com", joinDate: "2022-01-15", lastOrder: "2024-01-10" },
            { id: 2, name: "Dijital Çözümler", city: "Ankara", region: "İç Anadolu", orders: 189, status: "active", phone: "+90 312 555 0202", email: "iletisim@dijitalcozumler.com", joinDate: "2022-03-20", lastOrder: "2024-01-08" },
            { id: 3, name: "Mega Elektronik", city: "İzmir", region: "Ege", orders: 167, status: "active", phone: "+90 232 555 0303", email: "satis@megaelektronik.com", joinDate: "2021-11-10", lastOrder: "2024-01-09" },
            { id: 4, name: "Akıllı Sistemler", city: "Bursa", region: "Marmara", orders: 134, status: "active", phone: "+90 224 555 0404", email: "info@akillisistemler.com", joinDate: "2022-05-08", lastOrder: "2024-01-07" },
            { id: 5, name: "Bilişim Merkezi", city: "Antalya", region: "Akdeniz", orders: 128, status: "active", phone: "+90 242 555 0505", email: "destek@bilisimmerkezi.com", joinDate: "2022-02-14", lastOrder: "2024-01-06" },
            { id: 6, name: "Yenilikçi Teknoloji", city: "Adana", region: "Akdeniz", orders: 112, status: "active", phone: "+90 322 555 0606", email: "info@yenilikci.com", joinDate: "2022-07-22", lastOrder: "2024-01-05" },
            { id: 7, name: "Gelişim Bilgisayar", city: "Konya", region: "İç Anadolu", orders: 98, status: "active", phone: "+90 332 555 0707", email: "satis@gelisimbilgisayar.com", joinDate: "2022-04-18", lastOrder: "2024-01-04" },
            { id: 8, name: "Modern Elektronik", city: "Gaziantep", region: "Güneydoğu Anadolu", orders: 87, status: "active", phone: "+90 342 555 0808", email: "info@modernelektronik.com", joinDate: "2022-06-30", lastOrder: "2024-01-03" },
            { id: 9, name: "Teknoloji Noktası", city: "Kayseri", region: "İç Anadolu", orders: 76, status: "inactive", phone: "+90 352 555 0909", email: "iletisim@teknolojinoktasi.com", joinDate: "2021-12-05", lastOrder: "2023-12-15" },
            { id: 10, name: "Dijital Dünya", city: "Trabzon", region: "Karadeniz", orders: 65, status: "active", phone: "+90 462 555 1010", email: "info@dijitaldunya.com", joinDate: "2022-08-12", lastOrder: "2024-01-02" },
            { id: 11, name: "Bilgisayar Merkezi", city: "Eskişehir", region: "İç Anadolu", orders: 54, status: "active", phone: "+90 222 555 1111", email: "satis@bilgisayarmerkezi.com", joinDate: "2022-09-25", lastOrder: "2024-01-01" },
            { id: 12, name: "Teknoloji Uzmanı", city: "Denizli", region: "Ege", orders: 43, status: "inactive", phone: "+90 258 555 1212", email: "info@teknolojiuzmani.com", joinDate: "2022-10-14", lastOrder: "2023-11-20" },
            { id: 13, name: "Akıllı Çözümler", city: "Samsun", region: "Karadeniz", orders: 38, status: "active", phone: "+90 362 555 1313", email: "destek@akillicozumler.com", joinDate: "2022-11-08", lastOrder: "2023-12-28" },
            { id: 14, name: "Bilişim Dünyası", city: "Malatya", region: "Doğu Anadolu", orders: 29, status: "active", phone: "+90 422 555 1414", email: "info@bilisimdunyasi.com", joinDate: "2023-01-20", lastOrder: "2023-12-25" },
            { id: 15, name: "Teknoloji Merkezi", city: "Van", region: "Doğu Anadolu", orders: 21, status: "inactive", phone: "+90 432 555 1515", email: "iletisim@teknolojimerkezi.com", joinDate: "2023-02-15", lastOrder: "2023-10-30" }
        ];

        // Initialize page
        function initializePage() {
            dealersData = [...sampleDealers];
            filteredDealers = [...dealersData];
            
            setTimeout(() => {
                document.getElementById('loadingSkeleton').style.display = 'none';
                updateSummaryStats();
                renderDealers();
            }, 1000);
        }

        // Update summary statistics
        function updateSummaryStats() {
            const totalDealers = dealersData.length;
            const activeDealers = dealersData.filter(d => d.status === 'active').length;
            const totalOrders = dealersData.reduce((sum, d) => sum + d.orders, 0);
            const avgOrders = Math.round(totalOrders / totalDealers);

            document.getElementById('totalDealers').textContent = totalDealers;
            document.getElementById('activeDealers').textContent = activeDealers;
            document.getElementById('totalOrders').textContent = totalOrders.toLocaleString('tr-TR');
            document.getElementById('avgOrders').textContent = avgOrders;
        }

        // Render dealers list
        function renderDealers() {
            const container = document.getElementById('dealersList');
            const emptyState = document.getElementById('emptyState');
            
            if (filteredDealers.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }
            
            emptyState.classList.add('hidden');
            
            const maxOrders = Math.max(...filteredDealers.map(d => d.orders));
            
            container.innerHTML = filteredDealers.map((dealer, index) => {
                const performancePercentage = (dealer.orders / maxOrders) * 100;
                const isTopPerformer = index < 3;
                const rankBadge = index === 0 ? 'gold' : index === 1 ? 'silver' : index === 2 ? 'bronze' : '';
                
                return `
                    <div class="dealer-card bg-white rounded-lg card-shadow p-6 fade-in ${isTopPerformer ? 'top-performer' : ''}" style="animation-delay: ${index * 0.1}s">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <!-- Dealer Info -->
                            <div class="flex items-center space-x-4 flex-1">
                                ${isTopPerformer ? `
                                    <div class="rank-badge ${rankBadge} w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        ${index + 1}
                                    </div>
                                ` : `
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-medium text-sm">
                                        ${index + 1}
                                    </div>
                                `}
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">${dealer.name}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${dealer.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${dealer.status === 'active' ? 'Aktif' : 'Pasif'}
                                        </span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            ${dealer.city}, ${dealer.region}
                                        </div>
                                        <div class="hidden sm:block">•</div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            ${dealer.phone}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Performance Stats -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                                <!-- Orders Count -->
                                <div class="text-center sm:text-right">
                                    <div class="text-2xl font-bold text-blue-600">${dealer.orders}</div>
                                    <div class="text-sm text-gray-500">Sipariş</div>
                                </div>
                                
                                <!-- Performance Bar -->
                                <div class="w-full sm:w-32">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-500">Performans</span>
                                        <span class="text-xs font-medium text-gray-700">${Math.round(performancePercentage)}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="performance-bar h-2 rounded-full" style="width: ${performancePercentage}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Last Order -->
                                <div class="text-center sm:text-right min-w-0">
                                    <div class="text-sm font-medium text-gray-900">Son Sipariş</div>
                                    <div class="text-xs text-gray-500">${new Date(dealer.lastOrder).toLocaleDateString('tr-TR')}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Info (Mobile) -->
                        <div class="mt-4 pt-4 border-t border-gray-100 sm:hidden">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Email:</span>
                                    <div class="font-medium text-gray-900 truncate">${dealer.email}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Katılım:</span>
                                    <div class="font-medium text-gray-900">${new Date(dealer.joinDate).toLocaleDateString('tr-TR')}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Sort dealers
        function sortDealers() {
            const sortBy = document.getElementById('sortBy').value;
            
            filteredDealers.sort((a, b) => {
                switch (sortBy) {
                    case 'orders-desc':
                        return b.orders - a.orders;
                    case 'orders-asc':
                        return a.orders - b.orders;
                    case 'name-asc':
                        return a.name.localeCompare(b.name, 'tr');
                    case 'name-desc':
                        return b.name.localeCompare(a.name, 'tr');
                    case 'city-asc':
                        return a.city.localeCompare(b.city, 'tr');
                    default:
                        return b.orders - a.orders;
                }
            });
            
            renderDealers();
        }

        // Filter dealers
        function filterDealers() {
            const status = document.getElementById('filterStatus').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            filteredDealers = dealersData.filter(dealer => {
                const matchesStatus = status === 'all' || dealer.status === status;
                const matchesSearch = searchTerm === '' || 
                    dealer.name.toLowerCase().includes(searchTerm) ||
                    dealer.city.toLowerCase().includes(searchTerm) ||
                    dealer.region.toLowerCase().includes(searchTerm);
                
                return matchesStatus && matchesSearch;
            });
            
            sortDealers();
        }

        // Search dealers
        function searchDealers() {
            filterDealers();
        }

        // Clear search
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            filterDealers();
        }

        // Refresh data
        function refreshData() {
            document.getElementById('loadingSkeleton').style.display = 'block';
            document.getElementById('dealersList').innerHTML = '';
            
            setTimeout(() => {
                document.getElementById('loadingSkeleton').style.display = 'none';
                renderDealers();
                showMessage('Veriler güncellendi', 'success');
            }, 1000);
        }

        // Utility functions
        function showMessage(message, type) {
            const existingMessages = document.querySelectorAll('.message-toast');
            existingMessages.forEach(msg => msg.remove());

            const messageDiv = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            messageDiv.className = `message-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 fade-in ${bgColor} text-white`;
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        function goBack() {
            showMessage('Ana sayfaya yönlendirilecek', 'info');
        }

        // Initialize when page loads
        window.addEventListener('load', initializePage);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9807373634e4d617',t:'MTc1ODA5NzU1NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
