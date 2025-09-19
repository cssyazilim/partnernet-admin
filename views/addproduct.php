<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
   

    <div class="flex">
       

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Ürünler</h2>
                        <p class="mt-1 text-sm text-gray-600">Ürünleri listeleyin ve yeni ürün ekleyin</p>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Ürün Ekleme Formu -->
                <div id="productForm" class="bg-white rounded-lg shadow-sm">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50" onclick="toggleCard('productFormContent', 'productFormChevron')">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                                Yeni Ürün Ekle
                            </h3>
                            <i id="productFormChevron" class="fas fa-chevron-down transition-transform duration-200"></i>
                        </div>
                    </div>
                    <div id="productFormContent" class="block">
                        <div class="p-6">
                        <form id="addProductForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ürün Adı *</label>
                                    <input type="text" id="productName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ürün adını girin">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                                    <select id="productCategory" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Kategori seçin</option>
                                        <option value="elektronik">Elektronik</option>
                                        <option value="giyim">Giyim</option>
                                        <option value="ev-yasam">Ev & Yaşam</option>
                                        <option value="spor">Spor</option>
                                        <option value="kitap">Kitap</option>
                                        <option value="oyuncak">Oyuncak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fiyat (₺) *</label>
                                    <input type="number" id="productPrice" required min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Miktarı *</label>
                                    <input type="number" id="productStock" required min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Marka</label>
                                    <input type="text" id="productBrand" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Marka adı">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                    <input type="text" id="productSku" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ürün kodu">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                                <textarea id="productDescription" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ürün açıklaması..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="productStatus" value="active" checked class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="productStatus" value="inactive" class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Pasif</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <button type="button" id="clearForm" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Temizle
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                    Ürün Ekle
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

                <!-- Ürün Listesi -->
                <div id="productList" class="bg-white rounded-lg shadow-sm">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50" onclick="toggleCard('productListContent', 'productListChevron')">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list mr-2 text-blue-600"></i>
                                Ürün Listesi
                            </h3>
                            <i id="productListChevron" class="fas fa-chevron-down transition-transform duration-200"></i>
                        </div>
                    </div>
                    <div id="productListContent" class="block">
                        <!-- Arama Kutusu -->
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                            <div class="relative">
                                <input type="text" id="searchProducts" placeholder="Ürün ara..." class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    
                        <!-- Filters -->
                        <div class="px-4 sm:px-6 py-4 bg-gray-50 border-b">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <select id="categoryFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Tüm Kategoriler</option>
                                        <option value="elektronik">Elektronik</option>
                                        <option value="giyim">Giyim</option>
                                        <option value="ev-yasam">Ev & Yaşam</option>
                                        <option value="spor">Spor</option>
                                        <option value="kitap">Kitap</option>
                                        <option value="oyuncak">Oyuncak</option>
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

                        <div class="p-4 sm:p-6">
                            <div id="productsContainer" class="space-y-3 max-h-80 overflow-y-auto">
                            <!-- Ürünler buraya yüklenecek -->
                        </div>
                            
                            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between border-t pt-4 space-y-3 sm:space-y-0">
                                <div class="text-xs sm:text-sm text-gray-700">
                                    <span id="productCount">0 ürün gösteriliyor</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button id="prevProducts" class="px-2 sm:px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Önceki
                                    </button>
                                    <button id="nextProducts" class="px-2 sm:px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Sonraki
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-users mr-3"></i>Müşteriler</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg"><i class="fas fa-box mr-3"></i>Ürünler</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-chart-bar mr-3"></i>Raporlar</a></li>
                        <li><a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"><i class="fas fa-cog mr-3"></i>Ayarlar</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-eye mr-2 text-blue-600"></i>
                    Ürün Detayları
                </h3>
                <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="viewProductContent" class="space-y-4">
                    <!-- Product details will be loaded here -->
                </div>
            </div>
            <div class="flex justify-end p-6 border-t bg-gray-50">
                <button onclick="closeModal('viewModal')" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Kapat
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-green-600"></i>
                    Ürün Düzenle
                </h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="editProductForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ürün Adı *</label>
                            <input type="text" id="editProductName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                            <select id="editProductCategory" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Kategori seçin</option>
                                <option value="elektronik">Elektronik</option>
                                <option value="giyim">Giyim</option>
                                <option value="ev-yasam">Ev & Yaşam</option>
                                <option value="spor">Spor</option>
                                <option value="kitap">Kitap</option>
                                <option value="oyuncak">Oyuncak</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fiyat (₺) *</label>
                            <input type="number" id="editProductPrice" required min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stok Miktarı *</label>
                            <input type="number" id="editProductStock" required min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Marka</label>
                            <input type="text" id="editProductBrand" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                            <input type="text" id="editProductSku" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea id="editProductDescription" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="editProductStatus" value="active" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="editProductStatus" value="inactive" class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Pasif</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t bg-gray-50">
                <button onclick="closeModal('editModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    İptal
                </button>
                <button onclick="saveEditProduct()" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                    Kaydet
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                    Ürün Sil
                </h3>
                <button onclick="closeModal('deleteModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Bu ürünü silmek istediğinizden emin misiniz?</p>
                <div id="deleteProductInfo" class="bg-gray-50 p-4 rounded-lg">
                    <!-- Product info will be loaded here -->
                </div>
                <p class="text-sm text-red-600 mt-4">
                    <i class="fas fa-warning mr-1"></i>
                    Bu işlem geri alınamaz!
                </p>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t bg-gray-50">
                <button onclick="closeModal('deleteModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    İptal
                </button>
                <button onclick="confirmDeleteProduct()" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                    Sil
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sample products data
        let products = [
            {
                id: 1,
                name: "iPhone 15 Pro",
                category: "elektronik",
                price: 45999.99,
                stock: 25,
                brand: "Apple",
                sku: "IPH15PRO128",
                description: "128GB Doğal Titanyum iPhone 15 Pro",
                status: "active",
                createdDate: "15.03.2024"
            },
            {
                id: 2,
                name: "Samsung Galaxy S24",
                category: "elektronik",
                price: 32999.99,
                stock: 18,
                brand: "Samsung",
                sku: "SGS24256",
                description: "256GB Phantom Black Galaxy S24",
                status: "active",
                createdDate: "12.03.2024"
            },
            {
                id: 3,
                name: "Nike Air Max 270",
                category: "spor",
                price: 2499.99,
                stock: 0,
                brand: "Nike",
                sku: "NAM270BLK42",
                description: "Siyah renk, 42 numara spor ayakkabı",
                status: "inactive",
                createdDate: "10.03.2024"
            },
            {
                id: 4,
                name: "MacBook Air M3",
                category: "elektronik",
                price: 54999.99,
                stock: 12,
                brand: "Apple",
                sku: "MBA13M3512",
                description: "13 inç, M3 çip, 512GB SSD",
                status: "active",
                createdDate: "08.03.2024"
            },
            {
                id: 5,
                name: "Levi's 501 Jean",
                category: "giyim",
                price: 899.99,
                stock: 45,
                brand: "Levi's",
                sku: "LV501BL32",
                description: "Klasik mavi jean, 32 beden",
                status: "active",
                createdDate: "05.03.2024"
            }
        ];

        let filteredProducts = [...products];
        let currentPage = 1;
        const productsPerPage = 5;

        function renderProducts() {
            const container = document.getElementById('productsContainer');
            const startIndex = (currentPage - 1) * productsPerPage;
            const endIndex = startIndex + productsPerPage;
            const currentProducts = filteredProducts.slice(startIndex, endIndex);

            container.innerHTML = '';

            if (currentProducts.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-3xl mb-2"></i>
                        <p>Ürün bulunamadı</p>
                    </div>
                `;
                return;
            }

            currentProducts.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow';
                
                productCard.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-900 truncate pr-2">${product.name}</h4>
                                <span class="px-2 py-1 text-xs font-medium rounded-full flex-shrink-0 ${
                                    product.status === 'active' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800'
                                }">
                                    ${product.status === 'active' ? 'Aktif' : 'Pasif'}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-1 text-xs text-gray-600">
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Kategori</span>
                                    <span class="font-medium">${getCategoryName(product.category)}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Fiyat</span>
                                    <span class="font-medium text-green-600">₺${product.price.toLocaleString('tr-TR', {minimumFractionDigits: 2})}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Stok</span>
                                    <span class="font-medium ${product.stock > 0 ? 'text-green-600' : 'text-red-600'}">${product.stock}</span>
                                </div>
                                ${product.brand ? `
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Marka</span>
                                    <span class="font-medium">${product.brand}</span>
                                </div>
                                ` : ''}
                                ${product.sku ? `
                                <div class="flex flex-col">
                                    <span class="text-gray-500">SKU</span>
                                    <span class="font-medium">${product.sku}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="flex flex-col space-y-1 ml-3">
                            <button class="text-blue-600 hover:text-blue-900 p-1" onclick="viewProduct(${product.id})" title="Görüntüle">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-900 p-1" onclick="editProduct(${product.id})" title="Düzenle">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900 p-1" onclick="deleteProduct(${product.id})" title="Sil">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                container.appendChild(productCard);
            });

            updateProductCount();
            updatePagination();
        }

        function getCategoryName(category) {
            const categories = {
                'elektronik': 'Elektronik',
                'giyim': 'Giyim',
                'ev-yasam': 'Ev & Yaşam',
                'spor': 'Spor',
                'kitap': 'Kitap',
                'oyuncak': 'Oyuncak'
            };
            return categories[category] || category;
        }

        function filterProducts() {
            const searchTerm = document.getElementById('searchProducts').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            filteredProducts = products.filter(product => {
                const matchesSearch = !searchTerm || 
                    product.name.toLowerCase().includes(searchTerm) ||
                    product.brand?.toLowerCase().includes(searchTerm) ||
                    product.sku?.toLowerCase().includes(searchTerm);
                
                const matchesCategory = !categoryFilter || product.category === categoryFilter;
                const matchesStatus = !statusFilter || product.status === statusFilter;

                return matchesSearch && matchesCategory && matchesStatus;
            });

            currentPage = 1;
            renderProducts();
        }

        function updateProductCount() {
            const count = document.getElementById('productCount');
            const total = filteredProducts.length;
            const startIndex = (currentPage - 1) * productsPerPage + 1;
            const endIndex = Math.min(currentPage * productsPerPage, total);
            
            if (total === 0) {
                count.textContent = '0 ürün gösteriliyor';
            } else {
                count.textContent = `${startIndex}-${endIndex} arası gösteriliyor, toplam ${total} ürün`;
            }
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
            const prevBtn = document.getElementById('prevProducts');
            const nextBtn = document.getElementById('nextProducts');

            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        }

        function addProduct(event) {
            event.preventDefault();
            
            const formData = {
                id: Date.now(),
                name: document.getElementById('productName').value,
                category: document.getElementById('productCategory').value,
                price: parseFloat(document.getElementById('productPrice').value),
                stock: parseInt(document.getElementById('productStock').value),
                brand: document.getElementById('productBrand').value || null,
                sku: document.getElementById('productSku').value || null,
                description: document.getElementById('productDescription').value || null,
                status: document.querySelector('input[name="productStatus"]:checked').value,
                createdDate: new Date().toLocaleDateString('tr-TR')
            };

            products.unshift(formData);
            filterProducts();
            clearForm();
            
            showNotification('Ürün başarıyla eklendi!', 'success');
        }

        function clearForm() {
            document.getElementById('addProductForm').reset();
            document.querySelector('input[name="productStatus"][value="active"]').checked = true;
        }

        let currentEditId = null;
        let currentDeleteId = null;

        function viewProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                const content = document.getElementById('viewProductContent');
                content.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Ürün Adı</label>
                                <p class="text-lg font-semibold text-gray-900">${product.name}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                                <p class="text-gray-900">${getCategoryName(product.category)}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Fiyat</label>
                                <p class="text-xl font-bold text-green-600">₺${product.price.toLocaleString('tr-TR', {minimumFractionDigits: 2})}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Stok Durumu</label>
                                <p class="text-gray-900">
                                    <span class="font-semibold ${product.stock > 0 ? 'text-green-600' : 'text-red-600'}">${product.stock}</span> adet
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            ${product.brand ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Marka</label>
                                <p class="text-gray-900">${product.brand}</p>
                            </div>
                            ` : ''}
                            ${product.sku ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">SKU</label>
                                <p class="text-gray-900 font-mono">${product.sku}</p>
                            </div>
                            ` : ''}
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Durum</label>
                                <span class="px-3 py-1 text-sm font-medium rounded-full ${
                                    product.status === 'active' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800'
                                }">
                                    ${product.status === 'active' ? 'Aktif' : 'Pasif'}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Oluşturulma Tarihi</label>
                                <p class="text-gray-900">${product.createdDate}</p>
                            </div>
                        </div>
                    </div>
                    ${product.description ? `
                    <div class="mt-6 pt-6 border-t">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Açıklama</label>
                        <p class="text-gray-900 leading-relaxed">${product.description}</p>
                    </div>
                    ` : ''}
                `;
                openModal('viewModal');
            }
        }

        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                currentEditId = id;
                
                // Modal form alanlarını doldur
                document.getElementById('editProductName').value = product.name;
                document.getElementById('editProductCategory').value = product.category;
                document.getElementById('editProductPrice').value = product.price;
                document.getElementById('editProductStock').value = product.stock;
                document.getElementById('editProductBrand').value = product.brand || '';
                document.getElementById('editProductSku').value = product.sku || '';
                document.getElementById('editProductDescription').value = product.description || '';
                document.querySelector(`input[name="editProductStatus"][value="${product.status}"]`).checked = true;
                
                openModal('editModal');
            }
        }

        function deleteProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                currentDeleteId = id;
                
                const deleteInfo = document.getElementById('deleteProductInfo');
                deleteInfo.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-box text-2xl text-gray-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">${product.name}</h4>
                            <p class="text-sm text-gray-600">${getCategoryName(product.category)} • ₺${product.price.toLocaleString('tr-TR', {minimumFractionDigits: 2})}</p>
                            ${product.brand ? `<p class="text-sm text-gray-600">${product.brand}</p>` : ''}
                        </div>
                    </div>
                `;
                
                openModal('deleteModal');
            }
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset current IDs
            if (modalId === 'editModal') {
                currentEditId = null;
            } else if (modalId === 'deleteModal') {
                currentDeleteId = null;
            }
        }

        function saveEditProduct() {
            if (!currentEditId) return;
            
            const productIndex = products.findIndex(p => p.id === currentEditId);
            if (productIndex !== -1) {
                products[productIndex] = {
                    ...products[productIndex],
                    name: document.getElementById('editProductName').value,
                    category: document.getElementById('editProductCategory').value,
                    price: parseFloat(document.getElementById('editProductPrice').value),
                    stock: parseInt(document.getElementById('editProductStock').value),
                    brand: document.getElementById('editProductBrand').value || null,
                    sku: document.getElementById('editProductSku').value || null,
                    description: document.getElementById('editProductDescription').value || null,
                    status: document.querySelector('input[name="editProductStatus"]:checked').value
                };
                
                filterProducts();
                closeModal('editModal');
                
                // Success notification
                showNotification('Ürün başarıyla güncellendi!', 'success');
            }
        }

        function confirmDeleteProduct() {
            if (!currentDeleteId) return;
            
            const index = products.findIndex(p => p.id === currentDeleteId);
            if (index !== -1) {
                products.splice(index, 1);
                filterProducts();
                closeModal('deleteModal');
                
                // Success notification
                showNotification('Ürün başarıyla silindi!', 'success');
            }
        }

        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Toggle card functionality
        function toggleCard(contentId, chevronId) {
            const content = document.getElementById(contentId);
            const chevron = document.getElementById(chevronId);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                content.classList.add('hidden');
                chevron.style.transform = 'rotate(-90deg)';
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



        // Event listeners
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const editId = this.dataset.editId;
            if (editId) {
                // Güncelleme işlemi
                const productIndex = products.findIndex(p => p.id === parseInt(editId));
                if (productIndex !== -1) {
                    products[productIndex] = {
                        ...products[productIndex],
                        name: document.getElementById('productName').value,
                        category: document.getElementById('productCategory').value,
                        price: parseFloat(document.getElementById('productPrice').value),
                        stock: parseInt(document.getElementById('productStock').value),
                        brand: document.getElementById('productBrand').value || null,
                        sku: document.getElementById('productSku').value || null,
                        description: document.getElementById('productDescription').value || null,
                        status: document.querySelector('input[name="productStatus"]:checked').value
                    };
                    
                    filterProducts();
                    clearForm();
                    
                    // Formu normal moda döndür
                    delete this.dataset.editId;
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.textContent = 'Ürün Ekle';
                    submitBtn.className = 'px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700';
                    
                    showNotification('Ürün başarıyla güncellendi!', 'success');
                }
            } else {
                // Yeni ekleme işlemi
                addProduct(e);
            }
        });

        document.getElementById('clearForm').addEventListener('click', function() {
            clearForm();
            // Eğer düzenleme modundaysa normal moda döndür
            const form = document.getElementById('addProductForm');
            if (form.dataset.editId) {
                delete form.dataset.editId;
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Ürün Ekle';
                submitBtn.className = 'px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700';
            }
        });

        document.getElementById('searchProducts').addEventListener('input', filterProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('statusFilter').addEventListener('change', filterProducts);

        document.getElementById('prevProducts').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderProducts();
            }
        });

        document.getElementById('nextProducts').addEventListener('click', () => {
            const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderProducts();
            }
        });

        mobileMenuBtn.addEventListener('click', openMobileMenu);
        closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
        mobileOverlay.addEventListener('click', (e) => {
            if (e.target === mobileOverlay) {
                closeMobileMenuFunc();
            }
        });

        // Close modals when clicking outside
        document.getElementById('viewModal').addEventListener('click', (e) => {
            if (e.target.id === 'viewModal') {
                closeModal('viewModal');
            }
        });

        document.getElementById('editModal').addEventListener('click', (e) => {
            if (e.target.id === 'editModal') {
                closeModal('editModal');
            }
        });

        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') {
                closeModal('deleteModal');
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!document.getElementById('viewModal').classList.contains('hidden')) {
                    closeModal('viewModal');
                } else if (!document.getElementById('editModal').classList.contains('hidden')) {
                    closeModal('editModal');
                } else if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                    closeModal('deleteModal');
                }
            }
        });

        // Initialize
        renderProducts();
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98190b5422b9d5fc',t:'MTc1ODI4NDUwMS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
