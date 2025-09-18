<?php
// /admin/notifications.php
// Bu sayfa sadece HTML+JS render eder. JSON istekleri /partnernet-admin/api/notifications.php üzerinden gider.
$EMBED = isset($_GET['embed']);
?>
<?php if (!$EMBED): ?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Bildirimler - Bayi Yönetim Sistemi</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .card-shadow {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -1px rgba(0, 0, 0, .06)
            }

            .success-message {
                animation: slideIn .3s ease-out
            }

            @keyframes slideIn {
                from {
                    transform: translateY(-10px);
                    opacity: 0
                }

                to {
                    transform: translateY(0);
                    opacity: 1
                }
            }

            .notification-card {
                transition: all .2s ease
            }

            .notification-card:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 25px -5px rgba(0, 0, 0, .1), 0 4px 6px -2px rgba(0, 0, 0, .05)
            }

            .notification-unread {
                border-left: 4px solid #3b82f6;
                background: linear-gradient(90deg, rgba(59, 130, 246, .05) 0%, #fff 10%)
            }

            .notification-read {
                opacity: .9
            }

            .pulse-dot {
                animation: pulse 2s infinite
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1
                }

                50% {
                    opacity: .5
                }
            }

            .tab-active {
                border-bottom: 2px solid #3b82f6;
                color: #3b82f6
            }

            .priority-high {
                border-left-color: #dc2626;
                background: linear-gradient(90deg, rgba(220, 38, 38, .05) 0%, #fff 10%)
            }

            .priority-medium {
                border-left-color: #f59e0b;
                background: linear-gradient(90deg, rgba(245, 158, 11, .05) 0%, #fff 10%)
            }

            .priority-low {
                border-left-color: #10b981;
                background: linear-gradient(90deg, rgba(16, 185, 129, .05) 0%, #fff 10%)
            }

            @media (max-width:640px) {
                .mobile-scroll {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch
                }
            }
        </style>
    </head>

    <body class="bg-gray-50 min-h-screen">
    <?php endif; ?>

    <section id="notif-page" class="min-h-screen" data-api="/partnernet-admin/api/notifications.php">
        <!-- Üst Bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 px-4 md:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button type="button" onclick="history.back()" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="flex items-center">
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Bildirimler</h1>
                        <span id="unread-count" class="ml-3 px-2 py-1 bg-red-500 text-white text-xs rounded-full hidden">0</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2 md:space-x-4">
                    <button id="btn-read-all" class="px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center text-sm md:text-base">
                        <svg class="w-4 h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="hidden sm:inline">Tümünü Okundu İşaretle</span>
                        <span class="sm:hidden">Tümü Okundu</span>
                    </button>
                    <button onclick="openNotificationSettings()" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Ana İçerik -->
        <main class="max-w-4xl mx-auto p-4 md:p-6">
            <!-- Özet Kartları -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6">
                <div class="bg-white p-3 md:p-6 rounded-lg card-shadow">
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h10V9H4v2zM4 7h12V5H4v2z" />
                            </svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Toplam</p>
                            <p id="stat-total" class="text-lg md:text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                    <div class="mt-2 md:mt-4">
                        <span class="text-blue-600 text-xs md:text-sm font-medium">Tüm bildirimler</span>
                    </div>
                </div>

                <div class="bg-white p-3 md:p-6 rounded-lg card-shadow">
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full pulse-dot"></div>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Okunmamış</p>
                            <p id="stat-unread" class="text-lg md:text-2xl font-bold text-red-600">0</p>
                        </div>
                    </div>
                    <div class="mt-2 md:mt-4">
                        <span class="text-red-600 text-xs md:text-sm font-medium">Yeni bildirimler</span>
                    </div>
                </div>

                <div class="bg-white p-3 md:p-6 rounded-lg card-shadow">
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Önemli</p>
                            <p id="stat-important" class="text-lg md:text-2xl font-bold text-yellow-600">0</p>
                        </div>
                    </div>
                    <div class="mt-2 md:mt-4">
                        <span class="text-yellow-600 text-xs md:text-sm font-medium">Yüksek öncelik</span>
                    </div>
                </div>

                <div class="bg-white p-3 md:p-6 rounded-lg card-shadow">
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Bu Hafta</p>
                            <p id="stat-week" class="text-lg md:text-2xl font-bold text-green-600">0</p>
                        </div>
                    </div>
                    <div class="mt-2 md:mt-4">
                        <span class="text-green-600 text-xs md:text-sm font-medium">Yeni bildirimler</span>
                    </div>
                </div>
            </div>

            <!-- Filtreler -->
            <div class="bg-white rounded-lg card-shadow mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-4 md:px-6" aria-label="Tabs">
                        <button onclick="switchTab('all')" id="tab-all" class="py-4 px-1 border-b-2 font-medium text-sm tab-active">Tümü</button>
                        <button onclick="switchTab('unread')" id="tab-unread" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Okunmamış</button>
                        <button onclick="switchTab('important')" id="tab-important" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Önemli</button>
                        <button onclick="switchTab('system')" id="tab-system" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Sistem</button>
                    </nav>
                </div>

                <div class="p-4 md:p-6 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900" id="section-title">Tüm Bildirimler</h2>
                            <p class="text-sm text-gray-600 mt-1" id="section-description">Sistem bildirimleri ve güncellemeler</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative">
                                <input type="text" id="searchNotifications" placeholder="Bildirim ara..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <select id="typeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Tüm Türler</option>
                                <option value="system">Sistem</option>
                                <option value="payment">Ödeme</option>
                                <option value="project">Proje</option>
                                <option value="commission">Komisyon</option>
                                <option value="update">Güncelleme</option>
                                <option value="quote">Teklif</option>
                                <option value="order">Sipariş</option>
                                <option value="announcement">Duyuru</option>
                                <option value="invoice_due">Fatura</option>
                            </select>
                            <select id="priorityFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Tüm Öncelikler</option>
                                <option value="high">Yüksek</option>
                                <option value="medium">Orta</option>
                                <option value="low">Düşük</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bildirimler Listesi -->
            <div id="notifications-container" class="space-y-3 md:space-y-4 mb-6"></div>

            <!-- Sayfalama -->
            <div class="mt-4 flex items-center justify-center gap-2">
                <button id="pg-prev" class="px-3 py-1.5 rounded-md border text-sm disabled:opacity-40">Önceki</button>
                <span id="pg-info" class="text-sm text-gray-600">Sayfa 1 / 1</span>
                <button id="pg-next" class="px-3 py-1.5 rounded-md border text-sm">Sonraki</button>
            </div>

            <!-- Bildirim Detay Modal -->
            <div id="notification-detail-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Bildirim Detayları</h3>
                            <button onclick="closeNotificationDetail()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div id="notification-detail-content" class="p-4 md:p-6"></div>
                </div>
            </div>

            <!-- Bildirim Ayarları Modal -->
            <div id="notification-settings-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-md w-full">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Bildirim Ayarları</h3>
                            <button onclick="closeNotificationSettings()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 md:p-6">
                        <!-- örnek ayarlar -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">E-posta Bildirimleri</p>
                                    <p class="text-sm text-gray-600">Önemli güncellemeler için e-posta al</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">SMS Bildirimleri</p>
                                    <p class="text-sm text-gray-600">Acil durumlar için SMS al</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-6 mt-6 border-t border-gray-200">
                            <button onclick="closeNotificationSettings()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">İptal</button>
                            <button onclick="saveNotificationSettings()" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Kaydet</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script>
        // --------- CONFIG ----------
        const pageEl = document.getElementById('notif-page');
        const API_BASE = pageEl?.dataset?.api || '/partnernet-admin/api/notifications.php';

        // UI el'leri
        const unreadBadge = document.getElementById('unread-count');
        const statTotal = document.getElementById('stat-total');
        const statUnread = document.getElementById('stat-unread');
        const statImportant = document.getElementById('stat-important');
        const statWeek = document.getElementById('stat-week');

        const searchInput = document.getElementById('searchNotifications');
        const typeFilter = document.getElementById('typeFilter');
        const prioFilter = document.getElementById('priorityFilter');

        const listWrap = document.getElementById('notifications-container');

        const btnPrev = document.getElementById('pg-prev');
        const btnNext = document.getElementById('pg-next');
        const pgInfo = document.getElementById('pg-info');
        const btnReadAll = document.getElementById('btn-read-all');

        // Sekme state
        let currentTab = 'all';

        // Data state
        let page = 1;
        const limit = 20;
        let total = 0;
        let rows = []; // son fetch edilen ham veri

        document.addEventListener('DOMContentLoaded', () => {
            bindUI();
            fetchPage();
        });

        function bindUI() {
            // Tabs
            window.switchTab = (tab) => {
                document.querySelectorAll('[id^="tab-"]').forEach(el => {
                    el.classList.remove('tab-active');
                    el.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });
                document.getElementById(`tab-${tab}`).classList.add('tab-active');
                document.getElementById(`tab-${tab}`).classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

                const titles = {
                    all: 'Tüm Bildirimler',
                    unread: 'Okunmamış Bildirimler',
                    important: 'Önemli Bildirimler',
                    system: 'Sistem Bildirimleri'
                };
                const descs = {
                    all: 'Sistem bildirimleri ve güncellemeler',
                    unread: 'Henüz okunmamış bildirimler',
                    important: 'Yüksek öncelikli bildirimler',
                    system: 'Sistem bakım ve güncelleme bildirimleri'
                };
                document.getElementById('section-title').textContent = titles[tab];
                document.getElementById('section-description').textContent = descs[tab];

                currentTab = tab;
                renderList(); // client-side filtre
            };

            // Search & filters
            searchInput.addEventListener('input', debounce(renderList, 250));
            typeFilter.addEventListener('change', renderList);
            prioFilter.addEventListener('change', renderList);

            // Pagination
            btnPrev.addEventListener('click', () => {
                if (page > 1) {
                    page--;
                    fetchPage();
                }
            });
            btnNext.addEventListener('click', () => {
                const max = Math.max(1, Math.ceil(total / limit));
                if (page < max) {
                    page++;
                    fetchPage();
                }
            });

            // Mark all
            btnReadAll.addEventListener('click', markAllAsRead);
        }

        async function fetchPage() {
            try {
                const qs = new URLSearchParams({
                    path: 'list',
                    limit: String(limit),
                    offset: String((page - 1) * limit),
                    include_payload: 'true',
                    // admin için kendi user bildirimleri dahil et
                    for_self: 'true'
                });

                // “system” sekmesi backend tür filtrelemesiyle de daraltılabilir
                if (currentTab === 'system') qs.set('type', 'system');

                // (Arama & tip filtrelerini backend’e de göndermek istersen aç)
                const s = searchInput.value.trim();
                if (s) qs.set('search', s);
                if (typeFilter.value) qs.set('type', typeFilter.value);

                const res = await fetch(`${API_BASE}?${qs.toString()}`, {
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error(await res.text());
                const json = await res.json();

                rows = Array.isArray(json?.data) ? json.data : [];
                total = Number(json?.pagination?.total ?? rows.length);

                renderList();
                updateStats();

                const max = Math.max(1, Math.ceil(total / limit));
                pgInfo.textContent = `Sayfa ${page} / ${max}`;
                btnPrev.disabled = page <= 1;
                btnNext.disabled = page >= max;
            } catch (e) {
                console.error('fetch list error', e);
                listWrap.innerHTML = `<div class="text-center text-sm text-gray-500 py-6">Bildirimler yüklenemedi.</div>`;
            }
        }

        // -------- Render & helpers ----------
        function renderList() {
            let list = rows.slice();

            // Öncelik türet (backend’den gelmiyor → type’a göre bir mantık)
            list = list.map(n => ({
                ...n,
                _priority: derivePriority(n)
            }));

            // Sekme filtreleri (client side)
            if (currentTab === 'unread') {
                list = list.filter(n => !isRead(n));
            } else if (currentTab === 'important') {
                list = list.filter(n => n._priority === 'high');
            } else if (currentTab === 'system') {
                list = list.filter(n => (n.type || '').toLowerCase() === 'system');
            }

            // Arama
            const q = searchInput.value.trim().toLowerCase();
            if (q) {
                list = list.filter(n => (n.message || '').toLowerCase().includes(q));
            }

            // Tip & öncelik filtre
            if (typeFilter.value) {
                list = list.filter(n => (n.type || '').toLowerCase() === typeFilter.value);
            }
            if (prioFilter.value) {
                list = list.filter(n => n._priority === prioFilter.value);
            }

            // Render
            if (!list.length) {
                listWrap.innerHTML = `
        <div class="text-center py-12">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h10V9H4v2zM4 7h12V5H4v2z"/>
          </svg>
          <p class="text-gray-500 text-lg">Aradığınız kriterlere uygun bildirim bulunamadı</p>
        </div>`;
                updateUnreadBadge(list);
                return;
            }

            listWrap.innerHTML = list.map(n => cardHTML(n)).join('');
            updateUnreadBadge(list);
        }

        function cardHTML(n) {
            const title = deriveTitle(n);
            const typeTxt = getTypeText(n.type);
            const prio = n._priority || 'low';
            const prioCls = prio === 'high' ? 'text-red-600' : (prio === 'medium' ? 'text-yellow-600' : 'text-green-600');
            const prioText = prio === 'high' ? 'Yüksek' : (prio === 'medium' ? 'Orta' : 'Düşük');
            const isread = isRead(n);

            const base = 'bg-white rounded-lg card-shadow notification-card cursor-pointer';
            const readCls = isread ? 'notification-read' : 'notification-unread';
            const prioSide = prio === 'high' ? 'priority-high' : (prio === 'medium' ? 'priority-medium' : 'priority-low');

            return `
      <div class="${base} ${readCls} ${prioSide}" onclick="viewNotificationDetail('${escapeHTML(n.id)}')">
        <div class="p-4 md:p-6">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
              <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-lg">
                ${typeIcon(n.type)}
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-4 mb-2">
                <div class="flex-1">
                  <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">
                    ${escapeHTML(title)}
                    ${!isread ? '<span class="w-2 h-2 bg-blue-500 rounded-full inline-block ml-2"></span>' : ''}
                  </h3>
                  <p class="text-sm text-gray-600 line-clamp-2">${escapeHTML(n.message || '')}</p>
                </div>
                <div class="flex-shrink-0 text-right">
                  <span class="text-xs ${prioCls} font-medium">${prioText}</span>
                  <p class="text-xs text-gray-500 mt-1">${fmtWhen(n.created_at)}</p>
                </div>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">${typeTxt}</span>
                </div>
                <div class="flex items-center gap-2">
                  <button onclick="event.stopPropagation(); markOneAsRead('${escapeJS(n.id)}', ${n.partner_id===null?'true':'false'})" class="text-gray-600 hover:text-gray-900 text-xs font-medium">
                    ${isread ? 'Okundu' : 'Okundu İşaretle'}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>`;
        }

        // -------- Actions ----------
        async function markOneAsRead(id, isBroadcast) {
            try {
                const body = isBroadcast ? {
                    global: true
                } : {};
                const res = await fetch(`${API_BASE}?path=read&id=${encodeURIComponent(id)}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(body),
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error(await res.text());

                // optimistic: rows üzerinde güncelle
                rows = rows.map(n => n.id === id ? ({
                    ...n,
                    read_at: n.read_at || new Date().toISOString()
                }) : n);
                renderList();
                showSuccess('Bildirim okundu olarak işaretlendi');
                // server ile sync
                fetchPage();
            } catch (e) {
                console.error('mark one read error', e);
                alert('Okundu işaretlenemedi.');
            }
        }

        async function markAllAsRead() {
            try {
                btnReadAll.disabled = true;
                btnReadAll.textContent = 'İşaretleniyor…';

                // Admin yayını da kapsasın diye global:true
                const body = {
                    global: true
                };
                const res = await fetch(`${API_BASE}?path=read-all`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(body),
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error(await res.text());

                // optimistic: eldeki sayfada tümünü okundu yap
                const nowIso = new Date().toISOString();
                rows = rows.map(n => ({
                    ...n,
                    read_at: n.read_at || nowIso
                }));
                renderList();
                showSuccess('Tüm bildirimler okundu olarak işaretlendi');

                // server ile eşitle & sayaçları güncelle
                fetchPage();
            } catch (e) {
                console.error('mark all read error', e);
                alert('Tümünü okundu işaretleme başarısız.');
            } finally {
                btnReadAll.disabled = false;
                btnReadAll.textContent = 'Tümünü Okundu İşaretle';
            }
        }

        // -------- Stats & helpers ----------
        function updateStats() {
            statTotal.textContent = String(total);

            const unreadCnt = rows.filter(n => !isRead(n)).length;
            statUnread.textContent = String(unreadCnt);

            const importantCnt = rows.filter(n => (derivePriority(n) === 'high')).length;
            statImportant.textContent = String(importantCnt);

            // Bu hafta
            const weekAgo = Date.now() - 7 * 24 * 60 * 60 * 1000;
            const weekCnt = rows.filter(n => (new Date(n.created_at).getTime() >= weekAgo)).length;
            statWeek.textContent = String(weekCnt);
        }

        function updateUnreadBadge(list) {
            // liste verilmişse ona göre, yoksa rows'a göre
            const base = Array.isArray(list) ? list : rows;
            const cnt = base.filter(n => !isRead(n)).length;
            if (cnt > 0) {
                unreadBadge.textContent = String(cnt);
                unreadBadge.classList.remove('hidden');
            } else {
                unreadBadge.classList.add('hidden');
            }
        }

        function isRead(n) {
            // partner görünümünde API is_read verebilir; yoksa read_at’e bak
            if (typeof n.is_read === 'boolean') return n.is_read;
            return !!n.read_at;
        }

        function deriveTitle(n) {
            // Basit başlık türetimi: type’a göre
            const t = (n.type || '').toLowerCase();
            if (t === 'payment') return 'Ödeme Bildirimi';
            if (t === 'commission') return 'Komisyon Bildirimi';
            if (t === 'project') return 'Proje Güncellemesi';
            if (t === 'quote') return 'Teklif Bildirimi';
            if (t === 'order') return 'Sipariş Bildirimi';
            if (t === 'invoice_due') return 'Fatura Hatırlatma';
            if (t === 'announcement') return 'Duyuru';
            if (t === 'system') return 'Sistem Bildirimi';
            return 'Bildirim';
        }

        function derivePriority(n) {
            const t = (n.type || '').toLowerCase();
            // Örnek mantık: fatura & payment önemli, teklif/sipariş orta, duyuru düşük
            if (t === 'invoice_due') return 'high';
            if (t === 'payment') return 'medium';
            if (t === 'quote' || t === 'order' || t === 'project' || t === 'commission') return 'medium';
            if (t === 'system' || t === 'announcement' || t === 'update') return 'low';
            return 'low';
        }

        function getTypeText(type) {
            const map = {
                system: 'Sistem',
                payment: 'Ödeme',
                project: 'Proje',
                commission: 'Komisyon',
                update: 'Güncelleme',
                quote: 'Teklif',
                order: 'Sipariş',
                announcement: 'Duyuru',
                invoice_due: 'Fatura'
            };
            return map[(type || '').toLowerCase()] || (type || '');
        }

        function typeIcon(type) {
            const t = (type || '').toLowerCase();
            if (t === 'system') return '⚙️';
            if (t === 'payment') return '💳';
            if (t === 'project') return '📋';
            if (t === 'commission') return '💰';
            if (t === 'update') return '🔄';
            if (t === 'quote') return '📝';
            if (t === 'order') return '📦';
            if (t === 'announcement') return '📢';
            if (t === 'invoice_due') return '🧾';
            return '🔔';
        }

        function fmtWhen(v) {
            const d = new Date(v);
            const now = new Date();
            const diffH = Math.floor((now - d) / 36e5);
            if (Number.isNaN(d.getTime())) return '';
            if (diffH < 1) return 'Az önce';
            if (diffH < 24) return `${diffH} saat önce`;
            if (diffH < 48) return 'Dün';
            return d.toLocaleDateString('tr-TR', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function openNotificationSettings() {
            document.getElementById('notification-settings-modal').classList.remove('hidden');
        }

        function closeNotificationSettings() {
            document.getElementById('notification-settings-modal').classList.add('hidden');
        }

        function saveNotificationSettings() {
            showSuccess('Bildirim ayarları kaydedildi');
            closeNotificationSettings();
        }

        function closeNotificationDetail() {
            document.getElementById('notification-detail-modal').classList.add('hidden');
        }

        // Basit detay (mesajı ve payload’ı göster)
        function viewNotificationDetail(id) {
            const n = rows.find(x => x.id === id);
            if (!n) return;
            if (!isRead(n)) { // optimistik okundu yap
                n.read_at = n.read_at || new Date().toISOString();
                renderList();
            }
            const c = document.getElementById('notification-detail-content');
            const payloadPretty = n.payload ? `<pre class="text-xs bg-gray-50 p-3 rounded overflow-auto">${escapeHTML(JSON.stringify(n.payload,null,2))}</pre>` : '';
            c.innerHTML = `
      <div class="space-y-6">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xl">${typeIcon(n.type)}</div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900 mb-2">${escapeHTML(deriveTitle(n))}</h3>
            <div class="flex items-center gap-4 text-sm text-gray-600">
              <span>${escapeHTML(getTypeText(n.type))}</span><span>•</span>
              <span>${fmtWhen(n.created_at)}</span><span>•</span>
              <span class="${derivePriority(n)==='high'?'text-red-600':derivePriority(n)==='medium'?'text-yellow-600':'text-green-600'} font-medium">
                ${derivePriority(n)==='high'?'Yüksek':derivePriority(n)==='medium'?'Orta':'Düşük'} Öncelik
              </span>
            </div>
          </div>
        </div>
        <div class="prose max-w-none"><p class="text-gray-700 leading-relaxed">${escapeHTML(n.message || '')}</p></div>
        ${payloadPretty}
        <div class="flex justify-end pt-6 border-t border-gray-200">
          <button onclick="closeNotificationDetail()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Kapat</button>
        </div>
      </div>`;
            document.getElementById('notification-detail-modal').classList.remove('hidden');
        }

        function showSuccess(msg) {
            const el = document.createElement('div');
            el.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg success-message z-50';
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2500);
        }

        function escapeHTML(s) {
            return String(s).replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [m]))
        }

        function escapeJS(s) {
            return String(s).replace(/['"\\]/g, '\\$&')
        }

        function debounce(fn, ms) {
            let t;
            return (...a) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...a), ms)
            }
        }
    </script>

    <?php if (!$EMBED): ?>
    </body>

    </html>
<?php endif; ?>