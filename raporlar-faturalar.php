<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporlar ve Fatura Oluşturma - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-area, .print-area * {
                visibility: visible;
            }
            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
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
        
        /* Modal styles */
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        /* Invoice styles */
        .invoice-header {
            border-bottom: 3px solid #3b82f6;
        }
        .invoice-table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        .invoice-table td {
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Raporlar ve Faturalar</h1>
                        <p class="text-sm text-gray-500 hidden sm:block">Analiz ve fatura yönetimi</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
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
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Toplam Satış</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalSales">₺125,430</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Toplam Fatura</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalInvoices">247</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Bekleyen Ödeme</p>
                        <p class="text-2xl font-semibold text-gray-900" id="pendingPayments">₺18,750</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg card-shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Aktif Müşteri</p>
                        <p class="text-2xl font-semibold text-gray-900" id="activeCustomers">156</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Reports Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Report Filters -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Rapor Filtreleri
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="reportType" class="block text-sm font-medium text-gray-700 mb-2">
                                Rapor Tipi
                            </label>
                            <select id="reportType" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="sales">Satış Raporu</option>
                                <option value="customers">Müşteri Raporu</option>
                                <option value="products">Ürün Raporu</option>
                                <option value="invoices">Fatura Raporu</option>
                                <option value="payments">Ödeme Raporu</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="dateRange" class="block text-sm font-medium text-gray-700 mb-2">
                                Tarih Aralığı
                            </label>
                            <select id="dateRange" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="today">Bugün</option>
                                <option value="week">Bu Hafta</option>
                                <option value="month" selected>Bu Ay</option>
                                <option value="quarter">Bu Çeyrek</option>
                                <option value="year">Bu Yıl</option>
                                <option value="custom">Özel Tarih</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button onclick="generateReport()" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Rapor Oluştur
                            </button>
                        </div>
                    </div>
                    
                    <div id="customDateRange" class="hidden grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">
                                Başlangıç Tarihi
                            </label>
                            <input type="date" id="startDate" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">
                                Bitiş Tarihi
                            </label>
                            <input type="date" id="endDate" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Satış Analizi
                        </h2>
                        <div class="flex gap-2 mt-4 sm:mt-0">
                            <button onclick="changeChartType('line')" class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                Çizgi
                            </button>
                            <button onclick="changeChartType('bar')" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                Sütun
                            </button>
                        </div>
                    </div>
                    
                    <div class="h-80">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span id="reportTableTitle">Satış Raporu</span>
                            </h2>
                            <button onclick="exportReport()" class="mt-4 sm:mt-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Excel'e Aktar
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full" id="reportTable">
                            <thead class="bg-gray-50">
                                <tr id="reportTableHeader">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="reportTableBody">
                                <!-- Report data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invoice Generation Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg card-shadow p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Fatura Oluştur
                    </h2>
                    
                    <form id="invoiceForm" class="space-y-4">
                        <!-- Customer Selection -->
                        <div>
                            <label for="invoiceCustomer" class="block text-sm font-medium text-gray-700 mb-2">
                                Müşteri <span class="text-red-500">*</span>
                            </label>
                            <select id="invoiceCustomer" required class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Müşteri seçiniz</option>
                                <option value="1">ABC Teknoloji Ltd. Şti.</option>
                                <option value="2">Ahmet Yılmaz</option>
                                <option value="3">XYZ İnşaat A.Ş.</option>
                                <option value="4">Mehmet Demir</option>
                                <option value="5">DEF Ticaret Ltd.</option>
                            </select>
                        </div>

                        <!-- Invoice Type -->
                        <div>
                            <label for="invoiceType" class="block text-sm font-medium text-gray-700 mb-2">
                                Fatura Tipi
                            </label>
                            <select id="invoiceType" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="sales">Satış Faturası</option>
                                <option value="service">Hizmet Faturası</option>
                                <option value="proforma">Proforma Fatura</option>
                            </select>
                        </div>

                        <!-- Invoice Date -->
                        <div>
                            <label for="invoiceDate" class="block text-sm font-medium text-gray-700 mb-2">
                                Fatura Tarihi
                            </label>
                            <input type="date" id="invoiceDate" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="dueDate" class="block text-sm font-medium text-gray-700 mb-2">
                                Vade Tarihi
                            </label>
                            <input type="date" id="dueDate" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>

                        <!-- Products Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ürünler
                            </label>
                            <div id="invoiceProducts" class="space-y-2 mb-3">
                                <div class="invoice-product-item flex gap-2">
                                    <select class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none product-select">
                                        <option value="">Ürün seçiniz</option>
                                        <option value="1" data-price="150.00">Laptop Çantası</option>
                                        <option value="2" data-price="75.50">Wireless Mouse</option>
                                        <option value="3" data-price="299.99">Mekanik Klavye</option>
                                        <option value="4" data-price="45.00">USB Kablo</option>
                                    </select>
                                    <input type="number" placeholder="Adet" min="1" value="1" class="w-20 px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none quantity-input">
                                    <button type="button" onclick="removeInvoiceProduct(this)" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addInvoiceProduct()" class="w-full px-3 py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-lg hover:border-blue-500 hover:text-blue-500 transition-colors text-sm">
                                + Ürün Ekle
                            </button>
                        </div>

                        <!-- Total -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                                <span>Ara Toplam:</span>
                                <span id="subtotal">₺0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                                <span>KDV (%20):</span>
                                <span id="taxAmount">₺0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-semibold text-gray-900 pt-2 border-t border-gray-200">
                                <span>Toplam:</span>
                                <span id="totalAmount">₺0.00</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="invoiceNotes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notlar
                            </label>
                            <textarea id="invoiceNotes" rows="3" class="w-full px-3 py-2.5 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none" placeholder="Fatura notları (opsiyonel)"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-4">
                            <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Fatura Oluştur
                            </button>
                            <button type="button" onclick="previewInvoice()" class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Önizleme
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white rounded-lg card-shadow p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Son Faturalar
                    </h3>
                    
                    <div class="space-y-3" id="recentInvoices">
                        <!-- Recent invoices will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Invoice Preview Modal -->
    <div id="invoiceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 modal-backdrop z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg modal-content w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center no-print">
                <h3 class="text-lg font-semibold text-gray-900">Fatura Önizleme</h3>
                <div class="flex gap-2">
                    <button onclick="printInvoice()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Yazdır
                    </button>
                    <button onclick="closeInvoiceModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Kapat
                    </button>
                </div>
            </div>
            
            <div id="invoicePreview" class="print-area p-6">
                <!-- Invoice content will be generated here -->
            </div>
        </div>
    </div>

    <script>
        let salesChart;
        let invoiceCounter = 1001;
        let recentInvoicesData = [];

        // Initialize page
        function initializePage() {
            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('invoiceDate').value = today;
            
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            document.getElementById('dueDate').value = dueDate.toISOString().split('T')[0];
            
            // Initialize chart
            initializeSalesChart();
            
            // Generate initial report
            generateReport();
            
            // Load recent invoices
            loadRecentInvoices();
            
            // Setup event listeners
            setupEventListeners();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Date range change
            document.getElementById('dateRange').addEventListener('change', function() {
                const customRange = document.getElementById('customDateRange');
                if (this.value === 'custom') {
                    customRange.classList.remove('hidden');
                } else {
                    customRange.classList.add('hidden');
                }
            });

            // Invoice form submission
            document.getElementById('invoiceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                createInvoice();
            });

            // Product and quantity change listeners
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
                    calculateInvoiceTotal();
                }
            });
        }

        // Initialize sales chart
        function initializeSalesChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
                    datasets: [{
                        label: 'Satış (₺)',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₺' + value.toLocaleString('tr-TR');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Change chart type
        function changeChartType(type) {
            salesChart.config.type = type;
            salesChart.update();
            
            // Update button styles
            document.querySelectorAll('[onclick^="changeChartType"]').forEach(btn => {
                btn.className = 'px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors';
            });
            event.target.className = 'px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors';
        }

        // Generate report
        function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const dateRange = document.getElementById('dateRange').value;
            
            // Update table title
            const titles = {
                'sales': 'Satış Raporu',
                'customers': 'Müşteri Raporu',
                'products': 'Ürün Raporu',
                'invoices': 'Fatura Raporu',
                'payments': 'Ödeme Raporu'
            };
            
            document.getElementById('reportTableTitle').textContent = titles[reportType];
            
            // Generate sample data based on report type
            const sampleData = generateSampleReportData(reportType);
            populateReportTable(sampleData, reportType);
            
            showMessage('Rapor başarıyla oluşturuldu', 'success');
        }

        // Generate sample report data
        function generateSampleReportData(reportType) {
            const data = [];
            const customers = ['ABC Teknoloji Ltd.', 'Ahmet Yılmaz', 'XYZ İnşaat A.Ş.', 'Mehmet Demir', 'DEF Ticaret Ltd.'];
            const products = ['Laptop Çantası', 'Wireless Mouse', 'Mekanik Klavye', 'USB Kablo', 'Monitör'];
            const statuses = ['Tamamlandı', 'Beklemede', 'İptal', 'Kısmi'];
            
            for (let i = 0; i < 15; i++) {
                const date = new Date();
                date.setDate(date.getDate() - Math.floor(Math.random() * 30));
                
                data.push({
                    date: date.toLocaleDateString('tr-TR'),
                    customer: customers[Math.floor(Math.random() * customers.length)],
                    product: products[Math.floor(Math.random() * products.length)],
                    quantity: Math.floor(Math.random() * 10) + 1,
                    amount: (Math.random() * 1000 + 100).toFixed(2),
                    status: statuses[Math.floor(Math.random() * statuses.length)]
                });
            }
            
            return data;
        }

        // Populate report table
        function populateReportTable(data, reportType) {
            const tbody = document.getElementById('reportTableBody');
            
            tbody.innerHTML = data.map(row => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.customer}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.product}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.quantity}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">₺${row.amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(row.status)}">
                            ${row.status}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        // Get status color
        function getStatusColor(status) {
            const colors = {
                'Tamamlandı': 'bg-green-100 text-green-800',
                'Beklemede': 'bg-yellow-100 text-yellow-800',
                'İptal': 'bg-red-100 text-red-800',
                'Kısmi': 'bg-blue-100 text-blue-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        }

        // Export report
        function exportReport() {
            const reportType = document.getElementById('reportType').value;
            const table = document.getElementById('reportTable');
            
            // Convert table to CSV
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = [];
                const cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    row.push('"' + cols[j].textContent.trim() + '"');
                }
                csv.push(row.join(','));
            }
            
            // Download CSV
            const csvContent = '\ufeff' + csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${reportType}_raporu_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
            
            showMessage('Rapor Excel formatında dışa aktarıldı', 'success');
        }

        // Add invoice product
        function addInvoiceProduct() {
            const container = document.getElementById('invoiceProducts');
            const newProduct = document.createElement('div');
            newProduct.className = 'invoice-product-item flex gap-2';
            newProduct.innerHTML = `
                <select class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none product-select">
                    <option value="">Ürün seçiniz</option>
                    <option value="1" data-price="150.00">Laptop Çantası</option>
                    <option value="2" data-price="75.50">Wireless Mouse</option>
                    <option value="3" data-price="299.99">Mekanik Klavye</option>
                    <option value="4" data-price="45.00">USB Kablo</option>
                </select>
                <input type="number" placeholder="Adet" min="1" value="1" class="w-20 px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none quantity-input">
                <button type="button" onclick="removeInvoiceProduct(this)" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newProduct);
        }

        // Remove invoice product
        function removeInvoiceProduct(button) {
            const productItems = document.querySelectorAll('.invoice-product-item');
            if (productItems.length > 1) {
                button.closest('.invoice-product-item').remove();
                calculateInvoiceTotal();
            }
        }

        // Calculate invoice total
        function calculateInvoiceTotal() {
            let subtotal = 0;
            
            document.querySelectorAll('.invoice-product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                
                if (select.value && quantityInput.value) {
                    const price = parseFloat(select.selectedOptions[0].dataset.price || 0);
                    const quantity = parseInt(quantityInput.value || 0);
                    subtotal += price * quantity;
                }
            });
            
            const taxRate = 0.20; // 20% KDV
            const taxAmount = subtotal * taxRate;
            const total = subtotal + taxAmount;
            
            document.getElementById('subtotal').textContent = '₺' + subtotal.toFixed(2);
            document.getElementById('taxAmount').textContent = '₺' + taxAmount.toFixed(2);
            document.getElementById('totalAmount').textContent = '₺' + total.toFixed(2);
        }

        // Create invoice
        function createInvoice() {
            const formData = new FormData(document.getElementById('invoiceForm'));
            
            // Validate form
            if (!formData.get('invoiceCustomer')) {
                showMessage('Lütfen müşteri seçiniz', 'error');
                return;
            }
            
            // Collect products
            const products = [];
            document.querySelectorAll('.invoice-product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                
                if (select.value && quantityInput.value) {
                    products.push({
                        id: select.value,
                        name: select.selectedOptions[0].textContent,
                        price: parseFloat(select.selectedOptions[0].dataset.price),
                        quantity: parseInt(quantityInput.value)
                    });
                }
            });
            
            if (products.length === 0) {
                showMessage('Lütfen en az bir ürün ekleyiniz', 'error');
                return;
            }
            
            // Create invoice object
            const invoice = {
                id: invoiceCounter++,
                number: `INV-${new Date().getFullYear()}-${String(invoiceCounter).padStart(4, '0')}`,
                customer: document.getElementById('invoiceCustomer').selectedOptions[0].textContent,
                type: document.getElementById('invoiceType').value,
                date: document.getElementById('invoiceDate').value,
                dueDate: document.getElementById('dueDate').value,
                products: products,
                notes: document.getElementById('invoiceNotes').value,
                subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('₺', '')),
                tax: parseFloat(document.getElementById('taxAmount').textContent.replace('₺', '')),
                total: parseFloat(document.getElementById('totalAmount').textContent.replace('₺', '')),
                status: 'Oluşturuldu',
                createdAt: new Date().toLocaleString('tr-TR')
            };
            
            // Add to recent invoices
            recentInvoicesData.unshift(invoice);
            if (recentInvoicesData.length > 10) {
                recentInvoicesData.pop();
            }
            
            // Update UI
            loadRecentInvoices();
            document.getElementById('invoiceForm').reset();
            
            // Reset dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('invoiceDate').value = today;
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            document.getElementById('dueDate').value = dueDate.toISOString().split('T')[0];
            
            calculateInvoiceTotal();
            
            showMessage(`Fatura ${invoice.number} başarıyla oluşturuldu!`, 'success');
        }

        // Preview invoice
        function previewInvoice() {
            const customer = document.getElementById('invoiceCustomer').selectedOptions[0]?.textContent;
            if (!customer) {
                showMessage('Lütfen müşteri seçiniz', 'error');
                return;
            }
            
            // Collect products for preview
            const products = [];
            document.querySelectorAll('.invoice-product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                
                if (select.value && quantityInput.value) {
                    products.push({
                        name: select.selectedOptions[0].textContent,
                        price: parseFloat(select.selectedOptions[0].dataset.price),
                        quantity: parseInt(quantityInput.value)
                    });
                }
            });
            
            if (products.length === 0) {
                showMessage('Lütfen en az bir ürün ekleyiniz', 'error');
                return;
            }
            
            generateInvoicePreview({
                number: `INV-${new Date().getFullYear()}-${String(invoiceCounter + 1).padStart(4, '0')}`,
                customer: customer,
                date: document.getElementById('invoiceDate').value,
                dueDate: document.getElementById('dueDate').value,
                products: products,
                notes: document.getElementById('invoiceNotes').value,
                subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('₺', '')),
                tax: parseFloat(document.getElementById('taxAmount').textContent.replace('₺', '')),
                total: parseFloat(document.getElementById('totalAmount').textContent.replace('₺', ''))
            });
            
            document.getElementById('invoiceModal').classList.remove('hidden');
        }

        // Generate invoice preview
        function generateInvoicePreview(invoice) {
            const preview = document.getElementById('invoicePreview');
            
            preview.innerHTML = `
                <div class="max-w-4xl mx-auto bg-white">
                    <!-- Invoice Header -->
                    <div class="invoice-header pb-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">FATURA</h1>
                                <p class="text-lg text-gray-600">Fatura No: ${invoice.number}</p>
                            </div>
                            <div class="text-right">
                                <h2 class="text-xl font-semibold text-blue-600 mb-2">ŞİRKET ADI</h2>
                                <p class="text-gray-600">Adres Bilgisi</p>
                                <p class="text-gray-600">Telefon: +90 XXX XXX XX XX</p>
                                <p class="text-gray-600">Email: info@sirket.com</p>
                                <p class="text-gray-600">VKN: 1234567890</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer and Date Info -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Fatura Edilen:</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-medium text-gray-900">${invoice.customer}</p>
                                <p class="text-gray-600">Müşteri Adresi</p>
                                <p class="text-gray-600">Şehir, Ülke</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Fatura Bilgileri:</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600"><span class="font-medium">Fatura Tarihi:</span> ${new Date(invoice.date).toLocaleDateString('tr-TR')}</p>
                                <p class="text-gray-600"><span class="font-medium">Vade Tarihi:</span> ${new Date(invoice.dueDate).toLocaleDateString('tr-TR')}</p>
                                <p class="text-gray-600"><span class="font-medium">Ödeme Şekli:</span> Havale/EFT</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Table -->
                    <div class="mb-8">
                        <table class="w-full invoice-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Ürün/Hizmet</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-900">Miktar</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Birim Fiyat</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${invoice.products.map(product => `
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">${product.name}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-center">${product.quantity}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">₺${product.price.toFixed(2)}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">₺${(product.price * product.quantity).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Totals -->
                    <div class="flex justify-end mb-8">
                        <div class="w-80">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Ara Toplam:</span>
                                    <span class="font-medium">₺${invoice.subtotal.toFixed(2)}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">KDV (%20):</span>
                                    <span class="font-medium">₺${invoice.tax.toFixed(2)}</span>
                                </div>
                                <div class="border-t border-gray-300 pt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Genel Toplam:</span>
                                        <span class="text-lg font-bold text-blue-600">₺${invoice.total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    ${invoice.notes ? `
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notlar:</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">${invoice.notes}</p>
                            </div>
                        </div>
                    ` : ''}
                    
                    <!-- Footer -->
                    <div class="border-t border-gray-300 pt-6 text-center text-sm text-gray-500">
                        <p>Bu fatura elektronik ortamda oluşturulmuş olup, yasal geçerliliği bulunmaktadır.</p>
                        <p class="mt-2">Teşekkür ederiz!</p>
                    </div>
                </div>
            `;
        }

        // Print invoice
        function printInvoice() {
            window.print();
        }

        // Close invoice modal
        function closeInvoiceModal() {
            document.getElementById('invoiceModal').classList.add('hidden');
        }

        // Load recent invoices
        function loadRecentInvoices() {
            const container = document.getElementById('recentInvoices');
            
            if (recentInvoicesData.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm">Henüz fatura oluşturulmadı</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = recentInvoicesData.slice(0, 5).map(invoice => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">${invoice.number}</p>
                        <p class="text-xs text-gray-500">${invoice.customer}</p>
                        <p class="text-xs text-gray-500">${invoice.createdAt}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-green-600 text-sm">₺${invoice.total.toFixed(2)}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            ${invoice.status}
                        </span>
                    </div>
                </div>
            `).join('');
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
            showMessage('Ana sayfaya yönlendirilecek', 'info');
        }

        // Initialize when page loads
        window.addEventListener('load', initializePage);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9806ec7a0792b8c8',t:'MTc1ODA5NDQ5NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
