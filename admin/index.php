<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Router Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
  .gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }
  .card-shadow {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }
  .hover-scale { transition: transform 0.2s ease; }
  .hover-scale:hover { transform: scale(1.02); }

  .fade-in { animation: fadeIn 0.6s ease-out; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* === SIDEBAR === */
  .sidebar {
    transition: all 0.3s ease;
    overflow: hidden;                 /* gizli kısımların tıklanmasını engelle */
  }
  .sidebar.collapsed { width: 80px; } /* mevcut JS ile uyumlu kalsın */

  /* Logo kaybolmasın */
  .sidebar .logo-container { padding: 16px 20px; }
  .sidebar.collapsed .logo-container {
    justify-content: center;
    padding: 12px;                    /* daralt: ikon kesilmesin */
  }
  .sidebar.collapsed .logo-container .w-10{
    margin: 0 auto;
    flex-shrink: 0;                   /* ikon küçülmesin */
  }

  /* Metin ve rozetleri DOM'dan kaldır → tıklanamaz */
  .menu-text { transition: opacity 0.3s ease; }
  .menu-badge { transition: opacity 0.3s ease; }

  .sidebar.collapsed .menu-text,
  .sidebar.collapsed .menu-badge{
    display: none !important;         /* opacity yerine display:none */
  }

  /* Kapalıyken linklerin hizası/padding'i */
  .sidebar.collapsed nav .menu-item{
    justify-content: center;
    padding-left: 16px;
    padding-right: 16px;
  }

  .stat-card { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); }

  .notification-dot { animation: pulse 2s infinite; }
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50%      { opacity: 0.5; }
  }

  .page-transition { transition: all 0.3s ease-in-out; }
  .page-enter { opacity: 0; transform: translateX(20px); }
  .page-enter-active { opacity: 1; transform: translateX(0); }
  .page-exit { opacity: 1; transform: translateX(0); }
  .page-exit-active { opacity: 0; transform: translateX(-20px); }

  .route-content { min-height: calc(100vh - 80px); }

  .breadcrumb { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }

  .loading-spinner {
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    width: 40px; height: 40px;
    animation: spin 1s linear infinite;
  }
  @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-lg z-50">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="logo-container flex items-center p-6 border-b cursor-pointer hover:bg-gray-50 transition-colors" onclick="toggleSidebar()">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="menu-text ml-3 text-xl font-bold text-gray-800 whitespace-nowrap">Admin Panel</span>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <!-- Dashboard -->
                <a href="#" onclick="navigateTo('dashboard')" class="menu-item flex items-center p-3 text-blue-600 bg-blue-50 rounded-lg" data-route="dashboard">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Dashboard</span>
                </a>

                <!-- Müşterilerim -->
                <a href="#" onclick="navigateTo('customers')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="customers">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Müşterilerim</span>
                    <span class="menu-badge ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full transition-opacity duration-300">12</span>
                </a>

                <!-- Teklif Talep Et -->
                <a href="#" onclick="navigateTo('quote-request')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="quote-request">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Teklif Talep Et</span>
                </a>

                <!-- Tekliflerim -->
                <a href="#" onclick="navigateTo('quotes')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="quotes">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Tekliflerim</span>
                    <span class="menu-badge ml-auto bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full transition-opacity duration-300">3</span>
                </a>

                <!-- Siparişlerim -->
                <a href="#" onclick="navigateTo('orders')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="orders">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Siparişlerim</span>
                    <span class="menu-badge ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full transition-opacity duration-300">7</span>
                </a>

                <!-- Hakedişlerim -->
                <a href="#" onclick="navigateTo('progress-payments')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="progress-payments">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Hakedişlerim</span>
                    <span class="menu-badge ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full transition-opacity duration-300">₺45K</span>
                </a>

                <!-- Faturalar / Cari -->
                <a href="#" onclick="navigateTo('invoices')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="invoices">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Faturalar / Cari</span>
                </a>

                <!-- Mesajlaşma -->
                <a href="#" onclick="navigateTo('messaging')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="messaging">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Mesajlaşma</span>
                </a>

                <!-- Bildirimler -->
                <a href="#" onclick="navigateTo('notifications')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="notifications">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-7a1 1 0 011-1h4a1 1 0 011 1v7h6M4 10l8-8 8 8"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Bildirimler</span>
                    <span class="menu-badge ml-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full notification-dot transition-opacity duration-300">5</span>
                </a>

                <!-- Ayarlar -->
                <a href="#" onclick="navigateTo('settings')" class="menu-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors" data-route="settings">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="menu-text transition-all duration-300 whitespace-nowrap">Ayarlar</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="menu-text ml-3">
                        <p class="text-sm font-medium text-gray-900">Admin User</p>
                        <p class="text-xs text-gray-500">admin@company.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="ml-64 transition-all duration-300">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b sticky top-0 z-40">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                   
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900" id="pageTitle">Dashboard</h1>
                        <nav class="breadcrumb text-sm text-gray-500" id="breadcrumb">
                            <span>Ana Sayfa</span> / <span id="currentPage">Dashboard</span>
                        </nav>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Ara..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <button onclick="toggleNotifications()" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-7a1 1 0 011-1h4a1 1 0 011 1v7h6M4 10l8-8 8 8"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">
                            <div class="p-4 border-b">
                                <h3 class="font-semibold text-gray-900">Bildirimler</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="p-4 border-b hover:bg-gray-50">
                                    <p class="text-sm font-medium text-gray-900">Yeni sipariş alındı</p>
                                    <p class="text-xs text-gray-500">2 dakika önce</p>
                                </div>
                                <div class="p-4 border-b hover:bg-gray-50">
                                    <p class="text-sm font-medium text-gray-900">Teklif onaylandı</p>
                                    <p class="text-xs text-gray-500">1 saat önce</p>
                                </div>
                                <div class="p-4 hover:bg-gray-50">
                                    <p class="text-sm font-medium text-gray-900">Yeni müşteri kaydı</p>
                                    <p class="text-xs text-gray-500">3 saat önce</p>
                                </div>
                            </div>
                            <div class="p-4 border-t">
                                <button onclick="navigateTo('notifications')" class="w-full text-center text-blue-600 hover:text-blue-800 text-sm">Tümünü Gör</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">A</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                            <div class="p-4 border-b">
                                <p class="font-medium text-gray-900">Admin User</p>
                                <p class="text-sm text-gray-500">admin@company.com</p>
                            </div>
                            <div class="py-2">
                                <a href="#" onclick="navigateTo('profile')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <a href="#" onclick="navigateTo('settings')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ayarlar</a>
                                <hr class="my-2">
                                <a href="#" onclick="logout()" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Çıkış Yap</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
            <div class="loading-spinner"></div>
        </div>

        <!-- Route Content Container -->
        <div id="routeContent" class="route-content page-transition">
            <!-- Dashboard Content (Default) -->
            <div id="dashboard-page" class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card p-6 rounded-xl card-shadow hover-scale fade-in">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Toplam Müşteri</p>
                                <p class="text-2xl font-bold text-gray-900">248</p>
                                <p class="text-xs text-green-600">+12% bu ay</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 rounded-xl card-shadow hover-scale fade-in" style="animation-delay: 0.1s">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Aylık Ciro</p>
                                <p class="text-2xl font-bold text-gray-900">₺2.4M</p>
                                <p class="text-xs text-green-600">+15% bu ay</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 rounded-xl card-shadow hover-scale fade-in" style="animation-delay: 0.2s">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Bekleyen Teklif</p>
                                <p class="text-2xl font-bold text-gray-900">12</p>
                                <p class="text-xs text-yellow-600">3 yeni</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 rounded-xl card-shadow hover-scale fade-in" style="animation-delay: 0.3s">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Aktif Sipariş</p>
                                <p class="text-2xl font-bold text-gray-900">47</p>
                                <p class="text-xs text-green-600">+8% bu ay</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities and Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Activities -->
                    <div class="bg-white p-6 rounded-xl card-shadow fade-in" style="animation-delay: 0.4s">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Son Aktiviteler</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Tümünü Gör</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Yeni sipariş onaylandı</p>
                                    <p class="text-xs text-gray-500">Teknoloji Dünyası - 2 dakika önce</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Yeni müşteri eklendi</p>
                                    <p class="text-xs text-gray-500">ABC Şirketi - 1 saat önce</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Teklif gönderildi</p>
                                    <p class="text-xs text-gray-500">XYZ Ltd. - 3 saat önce</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white p-6 rounded-xl card-shadow fade-in" style="animation-delay: 0.5s">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hızlı İşlemler</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button onclick="navigateTo('customers')" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors text-left">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Yeni Müşteri</p>
                                <p class="text-xs text-gray-500">Müşteri ekle</p>
                            </button>

                            <button onclick="navigateTo('quote-request')" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors text-left">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Teklif Oluştur</p>
                                <p class="text-xs text-gray-500">Yeni teklif</p>
                            </button>

                            <button onclick="navigateTo('orders')" class="p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors text-left">
                                <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Siparişler</p>
                                <p class="text-xs text-gray-500">Yönet</p>
                            </button>

                            <button onclick="navigateTo('invoices')" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors text-left">
                                <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Faturalar</p>
                                <p class="text-xs text-gray-500">Görüntüle</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Pages (Hidden by default) -->
            <div id="other-pages" class="hidden p-6">
                <div class="bg-white rounded-xl card-shadow p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2" id="pageContentTitle">Sayfa Geliştiriliyor</h3>
                    <p class="text-gray-600" id="pageContentDescription">Bu sayfa şu anda geliştirme aşamasındadır. Yakında kullanıma sunulacaktır.</p>
                    <button onclick="navigateTo('dashboard')" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Dashboard'a Dön
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Router System
        class Router {
            constructor() {
                this.routes = {
                    'dashboard': {
                        title: 'Dashboard',
                        breadcrumb: 'Dashboard',
                        content: 'dashboard-page'
                    },
                    'customers': {
                        title: 'Müşterilerim',
                        breadcrumb: 'Müşteriler',
                        content: 'other-pages',
                        description: 'Müşteri listesi ve detayları burada görüntülenecek.'
                    },
                    'quote-request': {
                        title: 'Teklif Talep Et',
                        breadcrumb: 'Teklif Talebi',
                        content: 'other-pages',
                        description: 'Yeni teklif oluşturma formu burada yer alacak.'
                    },
                    'quotes': {
                        title: 'Tekliflerim',
                        breadcrumb: 'Teklifler',
                        content: 'other-pages',
                        description: 'Gönderilen teklifler ve durumları burada listelenecek.'
                    },
                    'orders': {
                        title: 'Siparişlerim',
                        breadcrumb: 'Siparişler',
                        content: 'other-pages',
                        description: 'Aktif siparişler ve sipariş geçmişi burada görüntülenecek.'
                    },
                    'progress-payments': {
                        title: 'Hakedişlerim',
                        breadcrumb: 'Hakedişler',
                        content: 'other-pages',
                        description: 'Hakediş takibi ve ödeme durumları burada yer alacak.'
                    },
                    'invoices': {
                        title: 'Faturalar / Cari',
                        breadcrumb: 'Faturalar',
                        content: 'other-pages',
                        description: 'Fatura listesi ve cari hesap durumu burada görüntülenecek.'
                    },
                    'messaging': {
                        title: 'Mesajlaşma',
                        breadcrumb: 'Mesajlar',
                        content: 'other-pages',
                        description: 'Müşterilerle mesajlaşma sistemi burada yer alacak.'
                    },
                    'notifications': {
                        title: 'Bildirimler',
                        breadcrumb: 'Bildirimler',
                        content: 'other-pages',
                        description: 'Tüm bildirimler ve uyarılar burada listelenecek.'
                    },
                    'settings': {
                        title: 'Ayarlar',
                        breadcrumb: 'Ayarlar',
                        content: 'other-pages',
                        description: 'Sistem ayarları ve konfigürasyonlar burada yer alacak.'
                    },
                    'profile': {
                        title: 'Profil',
                        breadcrumb: 'Profil',
                        content: 'other-pages',
                        description: 'Kullanıcı profil bilgileri ve düzenleme seçenekleri.'
                    }
                };
                this.currentRoute = 'dashboard';
                this.init();
            }

            init() {
                // Handle browser back/forward buttons
                window.addEventListener('popstate', (e) => {
                    if (e.state && e.state.route) {
                        this.loadRoute(e.state.route, false);
                    }
                });

                // Check URL parameters on load
                const urlParams = new URLSearchParams(window.location.search);
                const pageParam = urlParams.get('page');
                const hash = window.location.hash.substring(1);
                
                // Map PHP pages to routes
                const phpToRoute = {
                    'dashboard.php': 'dashboard',
                    'musteriler.php': 'customers',
                    'teklif-talep.php': 'quote-request',
                    'teklifler.php': 'quotes',
                    'siparisler.php': 'orders',
                    'hakedisler.php': 'progress-payments',
                    'faturalar.php': 'invoices',
                    'mesajlasma.php': 'messaging',
                    'bildirimler.php': 'notifications',
                    'ayarlar.php': 'settings',
                    'profil.php': 'profile'
                };
                
                // Determine initial route
                let initialRoute = 'dashboard';
                if (hash && this.routes[hash]) {
                    initialRoute = hash;
                } else if (pageParam && phpToRoute[pageParam]) {
                    initialRoute = phpToRoute[pageParam];
                }
                
                this.currentRoute = initialRoute;
                
                // Set initial state with proper URL
                const url = new URL(window.location);
                const pageMapping = {
                    'dashboard': 'dashboard.php',
                    'customers': 'musteriler.php',
                    'quote-request': 'teklif-talep.php',
                    'quotes': 'teklifler.php',
                    'orders': 'siparisler.php',
                    'progress-payments': 'hakedisler.php',
                    'invoices': 'faturalar.php',
                    'messaging': 'mesajlasma.php',
                    'notifications': 'bildirimler.php',
                    'settings': 'ayarlar.php',
                    'profile': 'profil.php'
                };
                
                url.searchParams.set('page', pageMapping[initialRoute]);
                url.hash = initialRoute;
                
                history.replaceState({ route: initialRoute }, '', url.toString());
                
                // Load the initial route
                this.loadRoute(initialRoute, false);
            }

            navigate(route) {
                if (this.routes[route]) {
                    this.loadRoute(route, true);
                }
            }

            loadRoute(route, pushState = true) {
                // Show loading indicator
                this.showLoading();

                // Simulate loading delay for smooth transition
                setTimeout(() => {
                    const routeConfig = this.routes[route];
                    
                    // Update URL and browser history
                    if (pushState) {
                        // Get current URL and update the page parameter
                        const url = new URL(window.location);
                        const pageMapping = {
                            'dashboard': 'dashboard.php',
                            'customers': 'musteriler.php',
                            'quote-request': 'teklif-talep.php',
                            'quotes': 'teklifler.php',
                            'orders': 'siparisler.php',
                            'progress-payments': 'hakedisler.php',
                            'invoices': 'faturalar.php',
                            'messaging': 'mesajlasma.php',
                            'notifications': 'bildirimler.php',
                            'settings': 'ayarlar.php',
                            'profile': 'profil.php'
                        };
                        
                        url.searchParams.set('page', pageMapping[route] || 'dashboard.php');
                        url.hash = route;
                        
                        history.pushState({ route: route }, '', url.toString());
                    }

                    // Update page title and breadcrumb
                    document.getElementById('pageTitle').textContent = routeConfig.title;
                    document.getElementById('currentPage').textContent = routeConfig.breadcrumb;

                    // Update active menu item
                    this.updateActiveMenuItem(route);

                    // Show appropriate content
                    this.showContent(route, routeConfig);

                    // Update current route
                    this.currentRoute = route;

                    // Hide loading indicator
                    this.hideLoading();

                    // Close mobile dropdowns
                    this.closeDropdowns();
                }, 300);
            }

            showContent(route, routeConfig) {
                const dashboardPage = document.getElementById('dashboard-page');
                const otherPages = document.getElementById('other-pages');
                const pageContentTitle = document.getElementById('pageContentTitle');
                const pageContentDescription = document.getElementById('pageContentDescription');

                // Add exit animation
                const routeContent = document.getElementById('routeContent');
                routeContent.classList.add('page-exit');

                setTimeout(() => {
                    if (route === 'dashboard') {
                        dashboardPage.classList.remove('hidden');
                        otherPages.classList.add('hidden');
                    } else {
                        dashboardPage.classList.add('hidden');
                        otherPages.classList.remove('hidden');
                        pageContentTitle.textContent = routeConfig.title;
                        pageContentDescription.textContent = routeConfig.description || 'Bu sayfa şu anda geliştirme aşamasındadır.';
                    }

                    // Add enter animation
                    routeContent.classList.remove('page-exit');
                    routeContent.classList.add('page-enter');

                    setTimeout(() => {
                        routeContent.classList.remove('page-enter');
                    }, 300);
                }, 150);
            }

            updateActiveMenuItem(route) {
                // Remove active class from all menu items
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('text-blue-600', 'bg-blue-50');
                    item.classList.add('text-gray-700');
                });

                // Add active class to current menu item
                const activeMenuItem = document.querySelector(`[data-route="${route}"]`);
                if (activeMenuItem) {
                    activeMenuItem.classList.remove('text-gray-700');
                    activeMenuItem.classList.add('text-blue-600', 'bg-blue-50');
                }
            }

            showLoading() {
                document.getElementById('loadingIndicator').classList.remove('hidden');
            }

            hideLoading() {
                document.getElementById('loadingIndicator').classList.add('hidden');
            }

            closeDropdowns() {
                document.getElementById('notificationsDropdown').classList.add('hidden');
                document.getElementById('userDropdown').classList.add('hidden');
            }
        }

        // Initialize router
        const router = new Router();

        // Global navigation function
        function navigateTo(route) {
            router.navigate(route);
        }

        // Sidebar functionality
        let sidebarCollapsed = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebarCollapsed = !sidebarCollapsed;
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.style.marginLeft = '80px';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.style.marginLeft = '256px';
            }
        }

        // Dropdown functions
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            const userDropdown = document.getElementById('userDropdown');
            
            userDropdown.classList.add('hidden');
            dropdown.classList.toggle('hidden');
        }

        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            
            notificationsDropdown.classList.add('hidden');
            dropdown.classList.toggle('hidden');
        }

        function logout() {
            if (confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
                alert('Çıkış yapılıyor...');
                // Here you would typically redirect to login page
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const userDropdown = document.getElementById('userDropdown');
            
            if (!event.target.closest('.relative')) {
                notificationsDropdown.classList.add('hidden');
                userDropdown.classList.add('hidden');
            }
        });

        // Handle mobile responsiveness
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                
                if (sidebarCollapsed) {
                    mainContent.style.marginLeft = '80px';
                } else {
                    mainContent.style.marginLeft = '256px';
                }
            } else {
                document.getElementById('mainContent').style.marginLeft = '0';
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation delays to cards
            const cards = document.querySelectorAll('.hover-scale');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98093c6217e7f31d',t:'MTc1ODExODczOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
