<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekleme - Admin Panel</title>
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table-responsive table {
                min-width: 700px;
            }
        }

        /* Product item animation */
        .product-item {
            transition: all 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Form validation styles */
        .input-error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .input-success {
            border-color: #10b981;
            background-color: #f0fdf4;
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Ürün Ekleme</h1>
                        <p class="text-sm text-gray-500 hidden sm:block">Kalem kalem ürün girişi</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center text-sm text-gray-500">
                        <span id="productCount" class="font-medium text-blue-600">0</span>
                        <span class="ml-1">ürün eklendi</span>
                    </div>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Entry Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg card-shadow p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Yeni Ürün Ekle
                    </h2>

                    <form id="productForm" class="space-y-4">
                        <!-- Product Code -->
                        <div>
                            <label for="productCode" class="block text-sm font-medium text-gray-700 mb-2">
                                Ürün Kodu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="productCode" name="productCode" required
                                class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="Örn: PRD001">
                        </div>

                        <!-- Product Name -->
                        <div>
                            <label for="productName" class="block text-sm font-medium text-gray-700 mb-2">
                                Ürün Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="productName" name="productName" required
                                class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="Ürün adını giriniz">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="category" name="category" required
                                class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                <option value="">Kategori seçiniz</option>
                                <option value="elektronik">Elektronik</option>
                                <option value="giyim">Giyim</option>
                                <option value="ev-yasam">Ev & Yaşam</option>
                                <option value="spor">Spor</option>
                                <option value="kitap">Kitap</option>
                                <option value="oyuncak">Oyuncak</option>
                                <option value="kozmetik">Kozmetik</option>
                                <option value="diger">Diğer</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="buyPrice" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alış Fiyatı <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="buyPrice" name="buyPrice" required min="0" step="0.01"
                                    class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label for="sellPrice" class="block text-sm font-medium text-gray-700 mb-2">
                                    Satış Fiyatı <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="sellPrice" name="sellPrice" required min="0" step="0.01"
                                    class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok Miktarı <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stock" name="stock" required min="0"
                                    class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                    placeholder="0">
                            </div>
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Birim
                                </label>
                                <select id="unit" name="unit"
                                    class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                    <option value="adet">Adet</option>
                                    <option value="kg">Kg</option>
                                    <option value="lt">Litre</option>
                                    <option value="m">Metre</option>
                                    <option value="m2">m²</option>
                                    <option value="paket">Paket</option>
                                    <option value="kutu">Kutu</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Açıklama
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none transition-colors"
                                placeholder="Ürün açıklaması (opsiyonel)"></textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="isActive" name="isActive" checked
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Ürün aktif</span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="submit" id="addProductBtn"
                                class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span id="addBtnText">Ürün Ekle</span>
                            </button>
                            <button type="button" onclick="clearForm()"
                                class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Temizle
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg card-shadow">
                    <!-- List Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Eklenen Ürünler
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">Toplam <span id="totalProducts">0</span> ürün</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="exportProducts()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Dışa Aktar
                                </button>
                                <button onclick="saveAllProducts()" id="saveAllBtn"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                    </svg>
                                    <span id="saveAllText">Tümünü Kaydet</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="table-responsive custom-scrollbar" style="max-height: 600px; overflow-y: auto;">
                        <div id="productsContainer" class="p-6">
                            <div id="emptyState" class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz ürün eklenmedi</h3>
                                <p class="text-gray-500">Sol taraftaki formu kullanarak ürün eklemeye başlayın</p>
                            </div>

                            <div id="productsList" class="hidden space-y-4">
                                <!-- Products will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let products = [];
        let productIdCounter = 1;

        // Form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (validateForm()) {
                addProduct();
            }
        });

        // Validate form
        function validateForm() {
            clearValidationErrors();
            let isValid = true;

            const requiredFields = ['productCode', 'productName', 'category', 'buyPrice', 'sellPrice', 'stock'];

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const value = field.value.trim();

                if (!value) {
                    showFieldError(fieldId, 'Bu alan zorunludur');
                    isValid = false;
                } else {
                    showFieldSuccess(fieldId);
                }
            });

            // Check if product code already exists
            const productCode = document.getElementById('productCode').value.trim();
            if (productCode && products.some(p => p.code === productCode)) {
                showFieldError('productCode', 'Bu ürün kodu zaten kullanılıyor');
                isValid = false;
            }

            // Validate prices
            const buyPrice = parseFloat(document.getElementById('buyPrice').value);
            const sellPrice = parseFloat(document.getElementById('sellPrice').value);

            if (buyPrice < 0) {
                showFieldError('buyPrice', 'Alış fiyatı negatif olamaz');
                isValid = false;
            }

            if (sellPrice < 0) {
                showFieldError('sellPrice', 'Satış fiyatı negatif olamaz');
                isValid = false;
            }

            if (sellPrice < buyPrice) {
                showFieldError('sellPrice', 'Satış fiyatı alış fiyatından düşük olamaz');
                isValid = false;
            }

            return isValid;
        }

        // Add product to list
        function addProduct() {
            const formData = new FormData(document.getElementById('productForm'));

            const product = {
                id: productIdCounter++,
                code: formData.get('productCode').trim(),
                name: formData.get('productName').trim(),
                category: formData.get('category'),
                buyPrice: parseFloat(formData.get('buyPrice')),
                sellPrice: parseFloat(formData.get('sellPrice')),
                stock: parseInt(formData.get('stock')),
                unit: formData.get('unit'),
                description: formData.get('description').trim(),
                isActive: formData.get('isActive') === 'on',
                addedAt: new Date().toLocaleString('tr-TR')
            };

            products.push(product);
            renderProducts();
            updateProductCount();
            clearForm();
            showMessage('Ürün başarıyla eklendi!', 'success');

            // Auto-generate next product code
            generateNextProductCode();
        }

        // Render products list
        function renderProducts() {
            const emptyState = document.getElementById('emptyState');
            const productsList = document.getElementById('productsList');

            if (products.length === 0) {
                emptyState.classList.remove('hidden');
                productsList.classList.add('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            productsList.classList.remove('hidden');

            productsList.innerHTML = products.map(product => `
                <div class="product-item bg-gray-50 rounded-lg p-4 border border-gray-200" data-id="${product.id}">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                                <h3 class="font-semibold text-gray-900">${product.name}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${product.code}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ${getCategoryName(product.category)}
                                    </span>
                                    ${product.isActive ? 
                                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>' : 
                                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Pasif</span>'
                                    }
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Alış:</span>
                                    <span class="font-medium text-gray-900">${product.buyPrice.toFixed(2)} ₺</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Satış:</span>
                                    <span class="font-medium text-green-600">${product.sellPrice.toFixed(2)} ₺</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Stok:</span>
                                    <span class="font-medium text-gray-900">${product.stock} ${product.unit}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Kar:</span>
                                    <span class="font-medium text-blue-600">${(product.sellPrice - product.buyPrice).toFixed(2)} ₺</span>
                                </div>
                            </div>
                            
                            ${product.description ? `
                                <p class="text-sm text-gray-600 mt-2">${product.description}</p>
                            ` : ''}
                            
                            <p class="text-xs text-gray-400 mt-2">Eklenme: ${product.addedAt}</p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button onclick="editProduct(${product.id})" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                    title="Düzenle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="removeProduct(${product.id})" 
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                    title="Sil">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Edit product
        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (!product) return;

            // Fill form with product data
            document.getElementById('productCode').value = product.code;
            document.getElementById('productName').value = product.name;
            document.getElementById('category').value = product.category;
            document.getElementById('buyPrice').value = product.buyPrice;
            document.getElementById('sellPrice').value = product.sellPrice;
            document.getElementById('stock').value = product.stock;
            document.getElementById('unit').value = product.unit;
            document.getElementById('description').value = product.description;
            document.getElementById('isActive').checked = product.isActive;

            // Remove product from list
            removeProduct(id);

            // Scroll to form
            document.getElementById('productForm').scrollIntoView({
                behavior: 'smooth'
            });

            showMessage('Ürün düzenleme için forma yüklendi', 'info');
        }

        // Remove product
        function removeProduct(id) {
            if (confirm('Bu ürünü silmek istediğinizden emin misiniz?')) {
                products = products.filter(p => p.id !== id);
                renderProducts();
                updateProductCount();
                showMessage('Ürün silindi', 'success');
            }
        }

        // Clear form
        function clearForm() {
            document.getElementById('productForm').reset();
            document.getElementById('isActive').checked = true;
            clearValidationErrors();
        }

        // Generate next product code
        function generateNextProductCode() {
            const lastProduct = products[products.length - 1];
            if (lastProduct) {
                const lastCode = lastProduct.code;
                const match = lastCode.match(/(\D*)(\d+)$/);
                if (match) {
                    const prefix = match[1];
                    const number = parseInt(match[2]) + 1;
                    const nextCode = prefix + number.toString().padStart(match[2].length, '0');
                    document.getElementById('productCode').value = nextCode;
                }
            }
        }

        // Update product count
        function updateProductCount() {
            const count = products.length;
            document.getElementById('productCount').textContent = count;
            document.getElementById('totalProducts').textContent = count;
        }

        // Get category name
        function getCategoryName(category) {
            const categories = {
                'elektronik': 'Elektronik',
                'giyim': 'Giyim',
                'ev-yasam': 'Ev & Yaşam',
                'spor': 'Spor',
                'kitap': 'Kitap',
                'oyuncak': 'Oyuncak',
                'kozmetik': 'Kozmetik',
                'diger': 'Diğer'
            };
            return categories[category] || category;
        }

        // Export products
        function exportProducts() {
            if (products.length === 0) {
                showMessage('Dışa aktarılacak ürün bulunamadı', 'error');
                return;
            }

            const csvContent = [
                ['Ürün Kodu', 'Ürün Adı', 'Kategori', 'Alış Fiyatı', 'Satış Fiyatı', 'Stok', 'Birim', 'Açıklama', 'Durum', 'Eklenme Tarihi'],
                ...products.map(p => [
                    p.code,
                    p.name,
                    getCategoryName(p.category),
                    p.buyPrice,
                    p.sellPrice,
                    p.stock,
                    p.unit,
                    p.description,
                    p.isActive ? 'Aktif' : 'Pasif',
                    p.addedAt
                ])
            ].map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');

            const blob = new Blob(['\ufeff' + csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `urunler_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();

            showMessage('Ürünler CSV formatında dışa aktarıldı', 'success');
        }

        // Save all products
        async function saveAllProducts() {
            if (products.length === 0) {
                showMessage('Kaydedilecek ürün bulunamadı', 'error');
                return;
            }

            const saveBtn = document.getElementById('saveAllBtn');
            const saveText = document.getElementById('saveAllText');

            saveBtn.disabled = true;
            saveText.textContent = 'Kaydediliyor...';

            try {
                // Simulate API call
                console.log('Saving products:', products);
                await new Promise(resolve => setTimeout(resolve, 2000));

                showMessage(`${products.length} ürün başarıyla kaydedildi!`, 'success');

                // Clear products after successful save
                setTimeout(() => {
                    if (confirm('Ürünler başarıyla kaydedildi. Listeyi temizlemek ister misiniz?')) {
                        products = [];
                        renderProducts();
                        updateProductCount();
                    }
                }, 1500);

            } catch (error) {
                console.error('Save error:', error);
                showMessage('Ürünler kaydedilirken hata oluştu', 'error');
            } finally {
                saveBtn.disabled = false;
                saveText.textContent = 'Tümünü Kaydet';
            }
        }

        // Validation helpers
        function clearValidationErrors() {
            document.querySelectorAll('.input-error, .input-success').forEach(el => {
                el.classList.remove('input-error', 'input-success');
            });
            document.querySelectorAll('.error-message').forEach(el => el.remove());
        }

        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('input-error');

            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        function showFieldSuccess(fieldId) {
            const field = document.getElementById(fieldId);
            field.classList.add('input-success');
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
            }, 5000);
        }

        function goBack() {
            if (products.length > 0) {
                if (confirm('Kaydedilmemiş ürünler var. Çıkmak istediğinizden emin misiniz?')) {
                    showMessage('Ana sayfaya yönlendirilecek', 'info');
                }
            } else {
                showMessage('Ana sayfaya yönlendirilecek', 'info');
            }
        }

        // Initialize page
        function initializePage() {
            updateProductCount();
            document.getElementById('productCode').value = 'PRD001';
        }

        // Initialize when page loads
        window.addEventListener('load', initializePage);
    </script>
    <script>
        (function() {
            function c() {
                var b = a.contentDocument || a.contentWindow.document;
                if (b) {
                    var d = b.createElement('script');
                    d.innerHTML = "window.__CF$cv$params={r:'9806c4103564355c',t:'MTc1ODA5MjgzOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
                    b.getElementsByTagName('head')[0].appendChild(d)
                }
            }
            if (document.body) {
                var a = document.createElement('iframe');
                a.height = 1;
                a.width = 1;
                a.style.position = 'absolute';
                a.style.top = 0;
                a.style.left = 0;
                a.style.border = 'none';
                a.style.visibility = 'hidden';
                document.body.appendChild(a);
                if ('loading' !== document.readyState) c();
                else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c);
                else {
                    var e = document.onreadystatechange || function() {};
                    document.onreadystatechange = function(b) {
                        e(b);
                        'loading' !== document.readyState && (document.onreadystatechange = e, c())
                    }
                }
            }
        })();
    </script>
</body>

</html>