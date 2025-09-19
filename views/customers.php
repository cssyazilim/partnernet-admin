<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayi ve Müşteri Yönetimi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
   

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Bayi ve Müşteri Listesi</h2>
                    <p class="mt-1 text-sm text-gray-600">Bayileri ve müşterilerini yönetin</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <button onclick="openAddDealerModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                        <i class="fas fa-store mr-2"></i>
                        Yeni Bayi
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" id="searchInput" placeholder="Bayi veya müşteri ara..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select id="cityFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tüm Şehirler</option>
                        <option value="istanbul">İstanbul</option>
                        <option value="ankara">Ankara</option>
                        <option value="izmir">İzmir</option>
                        <option value="bursa">Bursa</option>
                        <option value="antalya">Antalya</option>
                    </select>
                </div>
                <div>
                    <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Pasif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Dealers and Customers List -->
        <div class="space-y-6" id="dealersContainer">
            <!-- Bayiler ve müşterileri buraya yüklenecek -->
        </div>
    </div>

    <!-- Add/Edit Dealer Modal -->
    <div id="dealerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 id="dealerModalTitle" class="text-xl font-semibold text-gray-900">Yeni Bayi Ekle</h3>
                <button onclick="closeDealerModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="dealerForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bayi Adı *</label>
                            <input type="text" id="dealerName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yetkili Kişi *</label>
                            <input type="text" id="dealerContact" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-posta *</label>
                            <input type="email" id="dealerEmail" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefon *</label>
                            <input type="tel" id="dealerPhone" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Şehir</label>
                            <select id="dealerCity" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Şehir seçin</option>
                                <option value="istanbul">İstanbul</option>
                                <option value="ankara">Ankara</option>
                                <option value="izmir">İzmir</option>
                                <option value="bursa">Bursa</option>
                                <option value="antalya">Antalya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bayi Tipi</label>
                            <select id="dealerType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="yetkili">Yetkili Bayi</option>
                                <option value="distributer">Distribütör</option>
                                <option value="perakende">Perakende</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                        <textarea id="dealerAddress" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="dealerStatus" value="active" checked class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="dealerStatus" value="inactive" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Pasif</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t bg-gray-50">
                <button onclick="closeDealerModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    İptal
                </button>
                <button onclick="saveDealer()" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                    Kaydet
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Customer Modal -->
    <div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 id="customerModalTitle" class="text-xl font-semibold text-gray-900">Yeni Müşteri Ekle</h3>
                <button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="customerForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ad *</label>
                            <input type="text" id="customerFirstName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Soyad *</label>
                            <input type="text" id="customerLastName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-posta *</label>
                            <input type="email" id="customerEmail" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefon *</label>
                            <input type="tel" id="customerPhone" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Şehir</label>
                            <select id="customerCity" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Şehir seçin</option>
                                <option value="istanbul">İstanbul</option>
                                <option value="ankara">Ankara</option>
                                <option value="izmir">İzmir</option>
                                <option value="bursa">Bursa</option>
                                <option value="antalya">Antalya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri Tipi</label>
                            <select id="customerType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="bireysel">Bireysel</option>
                                <option value="kurumsal">Kurumsal</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                        <textarea id="customerAddress" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="customerStatus" value="active" checked class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="customerStatus" value="inactive" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Pasif</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t bg-gray-50">
                <button onclick="closeCustomerModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    İptal
                </button>
                <button onclick="saveCustomer()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Kaydet
                </button>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 id="viewModalTitle" class="text-xl font-semibold text-gray-900">Detaylar</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="viewContent">
                    <!-- İçerik buraya yüklenecek -->
                </div>
            </div>
            <div class="flex justify-end p-6 border-t bg-gray-50">
                <button onclick="closeViewModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Kapat
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-900 text-red-600">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="deleteModalTitle">Sil</span>
                </h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p id="deleteModalText" class="text-gray-700 mb-4">Bu öğeyi silmek istediğinizden emin misiniz?</p>
                <div id="deleteInfo" class="bg-gray-50 p-4 rounded-lg">
                    <!-- Silinecek öğe bilgileri -->
                </div>
                <p class="text-sm text-red-600 mt-4">
                    <i class="fas fa-warning mr-1"></i>
                    Bu işlem geri alınamaz!
                </p>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t bg-gray-50">
                <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    İptal
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                    Sil
                </button>
            </div>
        </div>
    </div>

    <script>
        // Örnek veri yapısı
        let dealers = [
            {
                id: 1,
                name: "Teknoloji Mağazası A.Ş.",
                contact: "Ahmet Yılmaz",
                email: "ahmet@teknoloji.com",
                phone: "0212 555 01 01",
                city: "istanbul",
                type: "yetkili",
                address: "Levent, İstanbul",
                status: "active",
                createdDate: "01.03.2024",
                customers: [
                    {
                        id: 101,
                        firstName: "Mehmet",
                        lastName: "Kaya",
                        email: "mehmet.kaya@email.com",
                        phone: "0532 111 22 33",
                        city: "istanbul",
                        type: "bireysel",
                        address: "Kadıköy, İstanbul",
                        status: "active",
                        createdDate: "15.03.2024"
                    },
                    {
                        id: 102,
                        firstName: "Ayşe",
                        lastName: "Demir",
                        email: "ayse.demir@email.com",
                        phone: "0533 444 55 66",
                        city: "istanbul",
                        type: "kurumsal",
                        address: "Şişli, İstanbul",
                        status: "active",
                        createdDate: "18.03.2024"
                    }
                ]
            },
            {
                id: 2,
                name: "Elektronik Dünyası Ltd.",
                contact: "Fatma Özkan",
                email: "fatma@elektronik.com",
                phone: "0312 777 88 99",
                city: "ankara",
                type: "distributer",
                address: "Kızılay, Ankara",
                status: "active",
                createdDate: "05.03.2024",
                customers: [
                    {
                        id: 201,
                        firstName: "Ali",
                        lastName: "Şahin",
                        email: "ali.sahin@email.com",
                        phone: "0534 777 88 99",
                        city: "ankara",
                        type: "bireysel",
                        address: "Çankaya, Ankara",
                        status: "active",
                        createdDate: "20.03.2024"
                    }
                ]
            },
            {
                id: 3,
                name: "Dijital Çözümler",
                contact: "Hasan Yıldız",
                email: "hasan@dijital.com",
                phone: "0232 333 44 55",
                city: "izmir",
                type: "perakende",
                address: "Konak, İzmir",
                status: "inactive",
                createdDate: "10.03.2024",
                customers: []
            }
        ];

        let editingDealerId = null;
        let editingCustomerId = null;
        let currentDealerId = null;
        let deleteType = null;
        let deleteId = null;
        let deleteDealerId = null;

        // Yardımcı fonksiyonlar
        function getCityName(city) {
            const cities = {
                'istanbul': 'İstanbul',
                'ankara': 'Ankara',
                'izmir': 'İzmir',
                'bursa': 'Bursa',
                'antalya': 'Antalya'
            };
            return cities[city] || city;
        }

        function getAvatarColor(name) {
            const colors = [
                'from-blue-500 to-blue-600',
                'from-green-500 to-green-600',
                'from-purple-500 to-purple-600',
                'from-red-500 to-red-600',
                'from-yellow-500 to-yellow-600',
                'from-indigo-500 to-indigo-600'
            ];
            const index = name.charCodeAt(0) % colors.length;
            return colors[index];
        }

        function getDealerTypeText(type) {
            const types = {
                'yetkili': 'Yetkili Bayi',
                'distributer': 'Distribütör',
                'perakende': 'Perakende'
            };
            return types[type] || type;
        }

        function getDealerTypeColor(type) {
            const colors = {
                'yetkili': 'bg-green-100 text-green-800',
                'distributer': 'bg-blue-100 text-blue-800',
                'perakende': 'bg-purple-100 text-purple-800'
            };
            return colors[type] || 'bg-gray-100 text-gray-800';
        }

        // Bayileri ve müşterileri render et
        function renderDealers() {
            const container = document.getElementById('dealersContainer');
            const search = document.getElementById('searchInput').value.toLowerCase();
            const city = document.getElementById('cityFilter').value;
            const status = document.getElementById('statusFilter').value;

            // Filtreleme
            const filteredDealers = dealers.filter(dealer => {
                const matchesSearch = !search || 
                    dealer.name.toLowerCase().includes(search) ||
                    dealer.contact.toLowerCase().includes(search) ||
                    dealer.email.toLowerCase().includes(search) ||
                    dealer.customers.some(customer => 
                        customer.firstName.toLowerCase().includes(search) ||
                        customer.lastName.toLowerCase().includes(search) ||
                        customer.email.toLowerCase().includes(search)
                    );
                
                const matchesCity = !city || dealer.city === city;
                const matchesStatus = !status || dealer.status === status;

                return matchesSearch && matchesCity && matchesStatus;
            });

            container.innerHTML = '';

            if (filteredDealers.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-store text-4xl mb-4"></i>
                        <p class="text-lg">Bayi bulunamadı</p>
                        <p class="text-sm">Yeni bayi eklemek için yukarıdaki butonu kullanın</p>
                    </div>
                `;
                return;
            }

            filteredDealers.forEach(dealer => {
                const dealerCard = document.createElement('div');
                dealerCard.className = 'bg-white rounded-lg shadow-sm border border-gray-200';
                
                dealerCard.innerHTML = `
                    <!-- Bayi Kartı -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br ${getAvatarColor(dealer.name)} rounded-lg flex items-center justify-center text-white font-bold">
                                    <i class="fas fa-store text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">${dealer.name}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                        <span><i class="fas fa-user mr-1"></i>${dealer.contact}</span>
                                        <span><i class="fas fa-envelope mr-1"></i>${dealer.email}</span>
                                        <span><i class="fas fa-phone mr-1"></i>${dealer.phone}</span>
                                    </div>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>${getCityName(dealer.city)}</span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full ${getDealerTypeColor(dealer.type)}">
                                            ${getDealerTypeText(dealer.type)}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full ${
                                            dealer.status === 'active' 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        }">
                                            ${dealer.status === 'active' ? 'Aktif' : 'Pasif'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="addCustomerToDealer(${dealer.id})" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Müşteri Ekle">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                <button onclick="viewDealer(${dealer.id})" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editDealer(${dealer.id})" class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded-lg transition-colors" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteDealer(${dealer.id})" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="toggleCustomers(${dealer.id})" class="text-gray-600 hover:text-gray-900 hover:bg-gray-50 p-2 rounded-lg transition-colors" title="Müşterileri Göster/Gizle">
                                    <i id="toggle-${dealer.id}" class="fas fa-chevron-down transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Müşteriler Bölümü -->
                    <div id="customers-${dealer.id}" class="hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <div class="flex items-center justify-between">
                                <h4 class="text-md font-medium text-gray-900">
                                    <i class="fas fa-users mr-2"></i>
                                    Müşteriler (${dealer.customers.length})
                                </h4>
                            </div>
                        </div>
                        <div class="p-6">
                            ${dealer.customers.length === 0 ? `
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-users text-2xl mb-2"></i>
                                    <p>Bu bayinin henüz müşterisi yok</p>
                                    <button onclick="addCustomerToDealer(${dealer.id})" class="mt-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-plus mr-1"></i>İlk müşteriyi ekle
                                    </button>
                                </div>
                            ` : `
                                <div class="space-y-3">
                                    ${dealer.customers.map(customer => `
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br ${getAvatarColor(customer.firstName)} rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    ${customer.firstName.charAt(0)}${customer.lastName.charAt(0)}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <h5 class="text-sm font-semibold text-gray-900">${customer.firstName} ${customer.lastName}</h5>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full ${
                                                            customer.status === 'active' 
                                                                ? 'bg-green-100 text-green-800' 
                                                                : 'bg-red-100 text-red-800'
                                                        }">
                                                            ${customer.status === 'active' ? 'Aktif' : 'Pasif'}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-600 space-y-1">
                                                        <div class="flex items-center justify-between">
                                                            <span>${customer.email}</span>
                                                            <span>${customer.phone}</span>
                                                        </div>
                                                        <div class="flex items-center justify-between">
                                                            <span>${getCityName(customer.city)} • ${customer.type === 'bireysel' ? 'Bireysel' : 'Kurumsal'}</span>
                                                            <span>${customer.createdDate}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-1">
                                                    <button onclick="viewCustomer(${dealer.id}, ${customer.id})" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Görüntüle">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </button>
                                                    <button onclick="editCustomer(${dealer.id}, ${customer.id})" class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded-lg transition-colors" title="Düzenle">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </button>
                                                    <button onclick="deleteCustomer(${dealer.id}, ${customer.id})" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Sil">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            `}
                        </div>
                    </div>
                `;
                
                container.appendChild(dealerCard);
            });
        }

        // Müşteri listesini aç/kapat
        function toggleCustomers(dealerId) {
            const customersDiv = document.getElementById(`customers-${dealerId}`);
            const toggleIcon = document.getElementById(`toggle-${dealerId}`);
            
            if (customersDiv.classList.contains('hidden')) {
                customersDiv.classList.remove('hidden');
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                customersDiv.classList.add('hidden');
                toggleIcon.style.transform = 'rotate(0deg)';
            }
        }

        // Bayi Modal İşlemleri
        function openAddDealerModal() {
            editingDealerId = null;
            document.getElementById('dealerModalTitle').textContent = 'Yeni Bayi Ekle';
            document.getElementById('dealerForm').reset();
            document.querySelector('input[name="dealerStatus"][value="active"]').checked = true;
            document.getElementById('dealerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDealerModal() {
            document.getElementById('dealerModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            editingDealerId = null;
        }

        function saveDealer() {
            const form = document.getElementById('dealerForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const dealerData = {
                name: document.getElementById('dealerName').value,
                contact: document.getElementById('dealerContact').value,
                email: document.getElementById('dealerEmail').value,
                phone: document.getElementById('dealerPhone').value,
                city: document.getElementById('dealerCity').value,
                type: document.getElementById('dealerType').value,
                address: document.getElementById('dealerAddress').value,
                status: document.querySelector('input[name="dealerStatus"]:checked').value,
                createdDate: new Date().toLocaleDateString('tr-TR')
            };

            if (editingDealerId) {
                const index = dealers.findIndex(d => d.id === editingDealerId);
                dealers[index] = { ...dealers[index], ...dealerData };
                showNotification('Bayi başarıyla güncellendi!', 'success');
            } else {
                dealerData.id = Date.now();
                dealerData.customers = [];
                dealers.unshift(dealerData);
                showNotification('Bayi başarıyla eklendi!', 'success');
            }

            renderDealers();
            closeDealerModal();
        }

        // Müşteri Modal İşlemleri
        function addCustomerToDealer(dealerId) {
            currentDealerId = dealerId;
            editingCustomerId = null;
            document.getElementById('customerModalTitle').textContent = 'Yeni Müşteri Ekle';
            document.getElementById('customerForm').reset();
            document.querySelector('input[name="customerStatus"][value="active"]').checked = true;
            document.getElementById('customerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            editingCustomerId = null;
            currentDealerId = null;
        }

        function saveCustomer() {
            const form = document.getElementById('customerForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const customerData = {
                firstName: document.getElementById('customerFirstName').value,
                lastName: document.getElementById('customerLastName').value,
                email: document.getElementById('customerEmail').value,
                phone: document.getElementById('customerPhone').value,
                city: document.getElementById('customerCity').value,
                type: document.getElementById('customerType').value,
                address: document.getElementById('customerAddress').value,
                status: document.querySelector('input[name="customerStatus"]:checked').value,
                createdDate: new Date().toLocaleDateString('tr-TR')
            };

            const dealerIndex = dealers.findIndex(d => d.id === currentDealerId);
            
            if (editingCustomerId) {
                const customerIndex = dealers[dealerIndex].customers.findIndex(c => c.id === editingCustomerId);
                dealers[dealerIndex].customers[customerIndex] = { ...dealers[dealerIndex].customers[customerIndex], ...customerData };
                showNotification('Müşteri başarıyla güncellendi!', 'success');
            } else {
                customerData.id = Date.now();
                dealers[dealerIndex].customers.unshift(customerData);
                showNotification('Müşteri başarıyla eklendi!', 'success');
            }

            renderDealers();
            closeCustomerModal();
        }

        // Görüntüleme İşlemleri
        function viewDealer(id) {
            const dealer = dealers.find(d => d.id === id);
            if (!dealer) return;

            document.getElementById('viewModalTitle').textContent = 'Bayi Detayları';
            const content = document.getElementById('viewContent');
            content.innerHTML = `
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br ${getAvatarColor(dealer.name)} rounded-lg flex items-center justify-center text-white font-bold text-xl">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">${dealer.name}</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="px-3 py-1 text-sm font-medium rounded-full ${getDealerTypeColor(dealer.type)}">
                                    ${getDealerTypeText(dealer.type)}
                                </span>
                                <span class="px-3 py-1 text-sm font-medium rounded-full ${
                                    dealer.status === 'active' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800'
                                }">
                                    ${dealer.status === 'active' ? 'Aktif' : 'Pasif'}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Yetkili Kişi</label>
                                <p class="text-gray-900">${dealer.contact}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">E-posta</label>
                                <p class="text-gray-900">${dealer.email}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Telefon</label>
                                <p class="text-gray-900">${dealer.phone}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Şehir</label>
                                <p class="text-gray-900">${getCityName(dealer.city)}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kayıt Tarihi</label>
                                <p class="text-gray-900">${dealer.createdDate}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Müşteri Sayısı</label>
                                <p class="text-gray-900">${dealer.customers.length} müşteri</p>
                            </div>
                        </div>
                    </div>
                    
                    ${dealer.address ? `
                    <div class="pt-6 border-t">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Adres</label>
                        <p class="text-gray-900 leading-relaxed">${dealer.address}</p>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function viewCustomer(dealerId, customerId) {
            const dealer = dealers.find(d => d.id === dealerId);
            const customer = dealer.customers.find(c => c.id === customerId);
            if (!customer) return;

            document.getElementById('viewModalTitle').textContent = 'Müşteri Detayları';
            const content = document.getElementById('viewContent');
            content.innerHTML = `
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br ${getAvatarColor(customer.firstName)} rounded-full flex items-center justify-center text-white font-bold text-xl">
                            ${customer.firstName.charAt(0)}${customer.lastName.charAt(0)}
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">${customer.firstName} ${customer.lastName}</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="px-3 py-1 text-sm font-medium rounded-full ${
                                    customer.type === 'bireysel' 
                                        ? 'bg-blue-100 text-blue-800' 
                                        : 'bg-purple-100 text-purple-800'
                                }">
                                    ${customer.type === 'bireysel' ? 'Bireysel' : 'Kurumsal'}
                                </span>
                                <span class="px-3 py-1 text-sm font-medium rounded-full ${
                                    customer.status === 'active' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800'
                                }">
                                    ${customer.status === 'active' ? 'Aktif' : 'Pasif'}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-store text-blue-600"></i>
                            <span class="text-sm font-medium text-blue-900">Bağlı Bayi: ${dealer.name}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">E-posta</label>
                                <p class="text-gray-900">${customer.email}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Telefon</label>
                                <p class="text-gray-900">${customer.phone}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Şehir</label>
                                <p class="text-gray-900">${getCityName(customer.city)}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kayıt Tarihi</label>
                                <p class="text-gray-900">${customer.createdDate}</p>
                            </div>
                        </div>
                    </div>
                    
                    ${customer.address ? `
                    <div class="pt-6 border-t">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Adres</label>
                        <p class="text-gray-900 leading-relaxed">${customer.address}</p>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('viewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Düzenleme İşlemleri
        function editDealer(id) {
            const dealer = dealers.find(d => d.id === id);
            if (!dealer) return;

            editingDealerId = id;
            document.getElementById('dealerModalTitle').textContent = 'Bayi Düzenle';
            
            document.getElementById('dealerName').value = dealer.name;
            document.getElementById('dealerContact').value = dealer.contact;
            document.getElementById('dealerEmail').value = dealer.email;
            document.getElementById('dealerPhone').value = dealer.phone;
            document.getElementById('dealerCity').value = dealer.city;
            document.getElementById('dealerType').value = dealer.type;
            document.getElementById('dealerAddress').value = dealer.address || '';
            document.querySelector(`input[name="dealerStatus"][value="${dealer.status}"]`).checked = true;

            document.getElementById('dealerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function editCustomer(dealerId, customerId) {
            const dealer = dealers.find(d => d.id === dealerId);
            const customer = dealer.customers.find(c => c.id === customerId);
            if (!customer) return;

            currentDealerId = dealerId;
            editingCustomerId = customerId;
            document.getElementById('customerModalTitle').textContent = 'Müşteri Düzenle';
            
            document.getElementById('customerFirstName').value = customer.firstName;
            document.getElementById('customerLastName').value = customer.lastName;
            document.getElementById('customerEmail').value = customer.email;
            document.getElementById('customerPhone').value = customer.phone;
            document.getElementById('customerCity').value = customer.city;
            document.getElementById('customerType').value = customer.type;
            document.getElementById('customerAddress').value = customer.address || '';
            document.querySelector(`input[name="customerStatus"][value="${customer.status}"]`).checked = true;

            document.getElementById('customerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Silme İşlemleri
        function deleteDealer(id) {
            const dealer = dealers.find(d => d.id === id);
            if (!dealer) return;

            deleteType = 'dealer';
            deleteId = id;
            
            document.getElementById('deleteModalTitle').textContent = 'Bayi Sil';
            document.getElementById('deleteModalText').textContent = 'Bu bayiyi ve tüm müşterilerini silmek istediğinizden emin misiniz?';
            
            const deleteInfo = document.getElementById('deleteInfo');
            deleteInfo.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br ${getAvatarColor(dealer.name)} rounded-lg flex items-center justify-center text-white font-semibold">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">${dealer.name}</h4>
                        <p class="text-sm text-gray-600">${dealer.contact}</p>
                        <p class="text-sm text-gray-600">${dealer.customers.length} müşteri</p>
                    </div>
                </div>
            `;

            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function deleteCustomer(dealerId, customerId) {
            const dealer = dealers.find(d => d.id === dealerId);
            const customer = dealer.customers.find(c => c.id === customerId);
            if (!customer) return;

            deleteType = 'customer';
            deleteId = customerId;
            deleteDealerId = dealerId;
            
            document.getElementById('deleteModalTitle').textContent = 'Müşteri Sil';
            document.getElementById('deleteModalText').textContent = 'Bu müşteriyi silmek istediğinizden emin misiniz?';
            
            const deleteInfo = document.getElementById('deleteInfo');
            deleteInfo.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br ${getAvatarColor(customer.firstName)} rounded-full flex items-center justify-center text-white font-semibold">
                        ${customer.firstName.charAt(0)}${customer.lastName.charAt(0)}
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">${customer.firstName} ${customer.lastName}</h4>
                        <p class="text-sm text-gray-600">${customer.email}</p>
                        <p class="text-sm text-gray-600">Bayi: ${dealer.name}</p>
                    </div>
                </div>
            `;

            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function confirmDelete() {
            if (deleteType === 'dealer') {
                const index = dealers.findIndex(d => d.id === deleteId);
                if (index !== -1) {
                    dealers.splice(index, 1);
                    showNotification('Bayi ve tüm müşterileri başarıyla silindi!', 'success');
                }
            } else if (deleteType === 'customer') {
                const dealerIndex = dealers.findIndex(d => d.id === deleteDealerId);
                const customerIndex = dealers[dealerIndex].customers.findIndex(c => c.id === deleteId);
                if (customerIndex !== -1) {
                    dealers[dealerIndex].customers.splice(customerIndex, 1);
                    showNotification('Müşteri başarıyla silindi!', 'success');
                }
            }

            renderDealers();
            closeDeleteModal();
        }

        // Modal Kapatma İşlemleri
        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            deleteType = null;
            deleteId = null;
            deleteDealerId = null;
        }

        // Bildirim göster
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Event Listeners
        document.getElementById('searchInput').addEventListener('input', renderDealers);
        document.getElementById('cityFilter').addEventListener('change', renderDealers);
        document.getElementById('statusFilter').addEventListener('change', renderDealers);

        // Modal dışına tıklayınca kapat
        document.getElementById('dealerModal').addEventListener('click', (e) => {
            if (e.target.id === 'dealerModal') closeDealerModal();
        });

        document.getElementById('customerModal').addEventListener('click', (e) => {
            if (e.target.id === 'customerModal') closeCustomerModal();
        });

        document.getElementById('viewModal').addEventListener('click', (e) => {
            if (e.target.id === 'viewModal') closeViewModal();
        });

        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') closeDeleteModal();
        });

        // Escape tuşu ile kapat
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!document.getElementById('dealerModal').classList.contains('hidden')) closeDealerModal();
                if (!document.getElementById('customerModal').classList.contains('hidden')) closeCustomerModal();
                if (!document.getElementById('viewModal').classList.contains('hidden')) closeViewModal();
                if (!document.getElementById('deleteModal').classList.contains('hidden')) closeDeleteModal();
            }
        });

        // Sayfa yüklendiğinde render et
        renderDealers();
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98192145a1d39cd2',t:'MTc1ODI4NTQwMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
