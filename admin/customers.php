<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteriler - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell text-lg"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img class="h-8 w-8 rounded-full bg-blue-500" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="Admin">
                        <span class="text-sm font-medium text-gray-700">Admin</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-sm min-h-screen hidden lg:block">
            <nav class="mt-8">
                <div class="px-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-store mr-3"></i>
                                Bayiler
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg">
                                <i class="fas fa-users mr-3"></i>
                                Müşteriler
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-chart-bar mr-3"></i>
                                Raporlar
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-cog mr-3"></i>
                                Ayarlar
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Müşteriler</h2>
                        <p class="mt-1 text-sm text-gray-600">Bayiler altındaki müşterileri yönetin</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Yeni Müşteri
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bayi Seçin</label>
                        <select id="dealerFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tüm Bayiler</option>
                            <option value="bayi1">Ankara Merkez Bayi</option>
                            <option value="bayi2">İstanbul Anadolu Bayi</option>
                            <option value="bayi3">İzmir Konak Bayi</option>
                            <option value="bayi4">Bursa Nilüfer Bayi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tüm Durumlar</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Pasif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Müşteri adı veya telefon..." class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dealers and Customers List -->
            <div class="space-y-6" id="dealersCustomersList">
                <!-- Dealer sections will be populated by JavaScript -->
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    <span id="paginationInfo">1-10 arası gösteriliyor, toplam 45 müşteri</span>
                </div>
                <div class="flex space-x-2">
                    <button id="prevPage" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Önceki
                    </button>
                    <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm">1</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">2</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">3</button>
                    <button id="nextPage" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                        Sonraki
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="lg:hidden fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden">
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50 transform -translate-x-full transition-transform duration-300" id="mobileSidebar">
            <div class="p-4">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Menü</h3>
                    <button id="closeMobileMenu" class="text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav>
                    <ul class="space-y-2">
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-store mr-3"></i>Bayiler</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg"><i class="fas fa-users mr-3"></i>Müşteriler</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-chart-bar mr-3"></i>Raporlar</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-cog mr-3"></i>Ayarlar</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Sample dealers and customers data
        const dealers = [
            {
                id: "bayi1",
                name: "Ankara Merkez Bayi",
                location: "Ankara",
                phone: "0312 123 4567",
                email: "ankara@bayi.com",
                status: "active",
                customers: [
                    {
                        id: 1,
                        name: "Ahmet Yılmaz",
                        phone: "0532 123 4567",
                        email: "ahmet@email.com",
                        registerDate: "15.03.2024",
                        status: "active",
                        totalOrders: 12,
                        lastOrderDate: "20.03.2024"
                    },
                    {
                        id: 5,
                        name: "Ali Çelik",
                        phone: "0542 777 8899",
                        email: "ali@email.com",
                        registerDate: "05.03.2024",
                        status: "active",
                        totalOrders: 8,
                        lastOrderDate: "18.03.2024"
                    },
                    {
                        id: 8,
                        name: "Zeynep Arslan",
                        phone: "0533 555 6677",
                        email: "zeynep@email.com",
                        registerDate: "02.03.2024",
                        status: "inactive",
                        totalOrders: 3,
                        lastOrderDate: "10.03.2024"
                    }
                ]
            },
            {
                id: "bayi2",
                name: "İstanbul Anadolu Bayi",
                location: "İstanbul",
                phone: "0216 987 6543",
                email: "istanbul@bayi.com",
                status: "active",
                customers: [
                    {
                        id: 2,
                        name: "Fatma Kaya",
                        phone: "0541 987 6543",
                        email: "fatma@email.com",
                        registerDate: "12.03.2024",
                        status: "active",
                        totalOrders: 15,
                        lastOrderDate: "22.03.2024"
                    },
                    {
                        id: 6,
                        name: "Murat Şahin",
                        phone: "0544 333 4455",
                        email: "murat@email.com",
                        registerDate: "01.03.2024",
                        status: "active",
                        totalOrders: 20,
                        lastOrderDate: "21.03.2024"
                    }
                ]
            },
            {
                id: "bayi3",
                name: "İzmir Konak Bayi",
                location: "İzmir",
                phone: "0232 111 2233",
                email: "izmir@bayi.com",
                status: "active",
                customers: [
                    {
                        id: 3,
                        name: "Mehmet Demir",
                        phone: "0555 111 2233",
                        email: "mehmet@email.com",
                        registerDate: "10.03.2024",
                        status: "inactive",
                        totalOrders: 5,
                        lastOrderDate: "15.03.2024"
                    },
                    {
                        id: 7,
                        name: "Elif Yıldız",
                        phone: "0536 888 9900",
                        email: "elif@email.com",
                        registerDate: "28.02.2024",
                        status: "active",
                        totalOrders: 18,
                        lastOrderDate: "19.03.2024"
                    }
                ]
            },
            {
                id: "bayi4",
                name: "Bursa Nilüfer Bayi",
                location: "Bursa",
                phone: "0224 444 5566",
                email: "bursa@bayi.com",
                status: "active",
                customers: [
                    {
                        id: 4,
                        name: "Ayşe Öztürk",
                        phone: "0533 444 5566",
                        email: "ayse@email.com",
                        registerDate: "08.03.2024",
                        status: "active",
                        totalOrders: 10,
                        lastOrderDate: "17.03.2024"
                    }
                ]
            }
        ];

        let filteredDealers = [...dealers];

        function renderDealersAndCustomers() {
            const container = document.getElementById('dealersCustomersList');
            container.innerHTML = '';

            filteredDealers.forEach(dealer => {
                const dealerSection = document.createElement('div');
                dealerSection.className = 'bg-white rounded-lg shadow-sm overflow-hidden';
                
                dealerSection.innerHTML = `
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                    ${dealer.name.charAt(0)}
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">${dealer.name}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>${dealer.location}</span>
                                        <span><i class="fas fa-phone mr-1"></i>${dealer.phone}</span>
                                        <span><i class="fas fa-users mr-1"></i>${dealer.customers.length} Müşteri</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 text-xs font-medium rounded-full ${
                                    dealer.status === 'active' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800'
                                }">
                                    ${dealer.status === 'active' ? 'Aktif' : 'Pasif'}
                                </span>
                                <button class="text-gray-400 hover:text-gray-600" onclick="toggleDealer('${dealer.id}')">
                                    <i class="fas fa-chevron-down transition-transform duration-200" id="chevron-${dealer.id}"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="customers-section" id="customers-${dealer.id}" style="display: block;">
                        ${dealer.customers.length > 0 ? `
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Müşteri</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İletişim</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sipariş Bilgisi</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        ${dealer.customers.map(customer => `
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-medium">
                                                            ${customer.name.charAt(0)}
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">${customer.name}</div>
                                                            <div class="text-sm text-gray-500">${customer.email}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">${customer.phone}</div>
                                                    <div class="text-sm text-gray-500">${customer.email}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${customer.registerDate}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">${customer.totalOrders} Sipariş</div>
                                                    <div class="text-sm text-gray-500">Son: ${customer.lastOrderDate}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full ${
                                                        customer.status === 'active' 
                                                            ? 'bg-green-100 text-green-800' 
                                                            : 'bg-red-100 text-red-800'
                                                    }">
                                                        ${customer.status === 'active' ? 'Aktif' : 'Pasif'}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="text-blue-600 hover:text-blue-900" onclick="viewCustomer(${customer.id})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="text-green-600 hover:text-green-900" onclick="editCustomer(${customer.id})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="text-red-600 hover:text-red-900" onclick="deleteCustomer(${customer.id}, '${dealer.id}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        ` : `
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p>Bu bayinin henüz müşterisi bulunmuyor.</p>
                            </div>
                        `}
                    </div>
                `;
                
                container.appendChild(dealerSection);
            });
        }

        function toggleDealer(dealerId) {
            const customersSection = document.getElementById(`customers-${dealerId}`);
            const chevron = document.getElementById(`chevron-${dealerId}`);
            
            if (customersSection.style.display === 'none') {
                customersSection.style.display = 'block';
                chevron.style.transform = 'rotate(0deg)';
            } else {
                customersSection.style.display = 'none';
                chevron.style.transform = 'rotate(-90deg)';
            }
        }

        function filterDealersAndCustomers() {
            const dealerFilter = document.getElementById('dealerFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const searchInput = document.getElementById('searchInput').value.toLowerCase();

            filteredDealers = dealers.filter(dealer => {
                // Filter by dealer
                const matchesDealer = !dealerFilter || dealer.id === dealerFilter;
                
                // Filter customers within dealer
                let filteredCustomers = dealer.customers.filter(customer => {
                    const matchesStatus = !statusFilter || customer.status === statusFilter;
                    const matchesSearch = !searchInput || 
                        customer.name.toLowerCase().includes(searchInput) ||
                        customer.phone.includes(searchInput) ||
                        customer.email.toLowerCase().includes(searchInput);
                    
                    return matchesStatus && matchesSearch;
                });

                // If dealer matches filter, show it with filtered customers
                if (matchesDealer) {
                    return {
                        ...dealer,
                        customers: filteredCustomers
                    };
                }
                
                // If dealer doesn't match but has matching customers, show it
                if (filteredCustomers.length > 0) {
                    return {
                        ...dealer,
                        customers: filteredCustomers
                    };
                }
                
                return false;
            }).filter(Boolean);

            renderDealersAndCustomers();
            updatePaginationInfo();
        }

        function updatePaginationInfo() {
            const totalCustomers = filteredDealers.reduce((sum, dealer) => sum + dealer.customers.length, 0);
            const totalDealers = filteredDealers.length;
            const info = document.getElementById('paginationInfo');
            info.textContent = `${totalDealers} bayi, toplam ${totalCustomers} müşteri gösteriliyor`;
        }

        function viewCustomer(id) {
            let customer = null;
            let dealerName = '';
            
            dealers.forEach(dealer => {
                const found = dealer.customers.find(c => c.id === id);
                if (found) {
                    customer = found;
                    dealerName = dealer.name;
                }
            });
            
            if (customer) {
                alert(`${customer.name} müşterisinin detayları görüntüleniyor.\nBayi: ${dealerName}\nToplam Sipariş: ${customer.totalOrders}`);
            }
        }

        function editCustomer(id) {
            let customer = null;
            let dealerName = '';
            
            dealers.forEach(dealer => {
                const found = dealer.customers.find(c => c.id === id);
                if (found) {
                    customer = found;
                    dealerName = dealer.name;
                }
            });
            
            if (customer) {
                alert(`${customer.name} müşterisi düzenleniyor.\nBayi: ${dealerName}`);
            }
        }

        function deleteCustomer(customerId, dealerId) {
            const dealer = dealers.find(d => d.id === dealerId);
            const customer = dealer.customers.find(c => c.id === customerId);
            
            if (confirm(`${customer.name} müşterisini ${dealer.name} bayisinden silmek istediğinizden emin misiniz?`)) {
                const customerIndex = dealer.customers.findIndex(c => c.id === customerId);
                dealer.customers.splice(customerIndex, 1);
                filterDealersAndCustomers();
                alert('Müşteri başarıyla silindi.');
            }
        }

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        function openMobileMenu() {
            mobileOverlay.classList.remove('hidden');
            setTimeout(() => {
                mobileSidebar.classList.remove('-translate-x-full');
            }, 10);
        }

        function closeMobileMenuFunc() {
            mobileSidebar.classList.add('-translate-x-full');
            setTimeout(() => {
                mobileOverlay.classList.add('hidden');
            }, 300);
        }

        mobileMenuBtn.addEventListener('click', openMobileMenu);
        closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
        mobileOverlay.addEventListener('click', (e) => {
            if (e.target === mobileOverlay) {
                closeMobileMenuFunc();
            }
        });

        // Event listeners for filters
        document.getElementById('dealerFilter').addEventListener('change', filterDealersAndCustomers);
        document.getElementById('statusFilter').addEventListener('change', filterDealersAndCustomers);
        document.getElementById('searchInput').addEventListener('input', filterDealersAndCustomers);

        // Initialize
        renderDealersAndCustomers();
        updatePaginationInfo();
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'981798acd7322b1b',t:'MTc1ODI2OTMxOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
