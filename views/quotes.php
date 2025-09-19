<?php
/* ====== BAŞLANGIÇ / KONFİG ====== */
if (session_status() === PHP_SESSION_NONE) session_start();

/* ROL YARDIMCISI */
if (!function_exists('has_role')) {
    function has_role(array $roles)
    {
        $r = $_SESSION['user']['role'] ?? null;
        return $r && in_array($r, $roles, true);
    }
}

/* ---- API kökü ---- */
if (!defined('API_CORE')) define('API_CORE', 'http://34.44.194.247:3001/api');

/* HTTP yardımcıları */
function http_get_json_bearer(string $base, string $path, string $token): array
{
    $url = rtrim($base, '/') . '/' . ltrim($path, '/');
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'Authorization: ' . 'Bearer ' . $token,
        ],
        CURLOPT_TIMEOUT        => 20,
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($raw === false)         throw new RuntimeException('Sunucuya ulaşılamadı: ' . $err);
    $json = json_decode($raw, true);
    if (!is_array($json))       throw new RuntimeException('Geçersiz yanıt: ' . $raw);
    if ($code < 200 || $code >= 300) {
        $msg = $json['message'] ?? $json['error'] ?? ('HTTP ' . $code);
        throw new RuntimeException($msg);
    }
    return $json;
}

/* ---- TOKEN ---- */
$accessToken = $_GET['token']
    ?? ($_SESSION['auth']['accessToken'] ?? ($_SESSION['admin_accessToken'] ?? ($_SESSION['partner_accessToken'] ?? '')));

$isAdmin = has_role(['admin', 'super_admin', 'merkez']);

$apiError    = null;
$quotes      = [];
$categories  = [];

/* ---- TÜM TEKLİFLERİ ÇEK ---- */
if ($accessToken) {
    try {
        $resp = http_get_json_bearer(API_CORE, 'quotes?limit=9999', $accessToken);

        if (isset($resp['items']) && is_array($resp['items'])) {
            $quotes      = $resp['items'];
            $categories  = $resp['categories']   ?? [];
        } elseif (is_array($resp)) {
            $quotes = $resp;
        }
    } catch (Throwable $e) {
        $apiError = $e->getMessage();
        $quotes   = [];
    }
} else {
    $apiError = 'Token bulunamadı (oturum açın).';
}

/* ---- HTML escape ---- */
function e($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tekliflerim - Bayi Yönetim Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -1px rgba(0, 0, 0, .06)
        }

        .modal-open {
            overflow: hidden
        }

        .table-mini td,
        .table-mini th {
            padding: .5rem;
            border-bottom: 1px solid #eee;
            font-size: .875rem;
        }

        .chip {
            display: inline-block;
            padding: .15rem .5rem;
            border-radius: .5rem;
            font-size: .75rem;
        }

        /* Toast (sağ-alt) */
        #toast-root {
            position: fixed;
            right: 16px;
            bottom: 16px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            min-width: 260px;
            max-width: 380px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-left-width: 6px;
            border-radius: .5rem;
            padding: .75rem .9rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -2px rgba(0, 0, 0, .05);
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            opacity: 0;
            transform: translateY(10px);
            transition: .2s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-success {
            border-left-color: #16a34a;
        }

        .toast-error {
            border-left-color: #dc2626;
        }

        .toast-info {
            border-left-color: #2563eb;
        }

        .toast-title {
            font-weight: 600;
            color: #111827;
            margin-bottom: .2rem;
        }

        .toast-text {
            color: #374151;
            font-size: .875rem;
            line-height: 1.3;
        }

        .toast-close {
            margin-left: auto;
            color: #6b7280;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm border-b border-gray-200 px-4 md:px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">Tekliflerim</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="createNewQuote()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Yeni Teklif
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-4 md:p-6">
        <!-- KPI'lar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
            <div class="bg-white p-4 md:p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Bekleyen Teklifler</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900" id="pendingCount">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Onaylanan</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900" id="approvedCount">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Reddedilen</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900" id="rejectedCount">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-lg card-shadow">
                <div class="flex items-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Başarı Oranı</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900" id="successRate">0%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste + filtre -->
        <div class="bg-white rounded-lg card-shadow mb-6">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Teklif Listesi</h2>
                        <p class="text-sm text-gray-600 mt-1" id="totalText">Toplam 0 teklif</p>
                        <?php if ($apiError): ?>
                            <p class="text-sm text-red-600 mt-1">API Hatası: <?= e($apiError) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col md:flex-row gap-3">
                        <div class="relative">
                            <input type="text" id="searchQuotes" placeholder="Teklif ara..." class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="filterQuotes()">
                            <option value="">Tüm Durumlar</option>
                            <option value="beklemede">Beklemede</option>
                            <option value="onaylandi">Onaylandı</option>
                            <option value="reddedildi">Reddedildi</option>
                            <option value="suresi-doldu">Süresi Doldu</option>
                        </select>
                        <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="filterQuotes()">
                            <option value="">Tüm Kategoriler</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartlar -->
        <div id="quotes-container" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6 mb-3"></div>

        <!-- Sayfalama -->
        <div id="pagination" class="flex items-center justify-between mt-2">
            <div class="text-sm text-gray-600" id="pageInfo"></div>
            <div class="flex items-center gap-2" id="pageButtons"></div>
        </div>

        <!-- Detay / Edit Modal -->
        <div id="quote-detail-modal" class="hidden fixed inset-0 z-50">
            <div id="modal-backdrop" class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="relative mx-auto my-8 w-[95%] max-w-5xl max-h-[90vh] overflow-y-auto">
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="modal-title" class="text-lg font-semibold text-gray-900">Teklif Detayları</h3>
                        <button onclick="closeQuoteDetail()" class="text-gray-400 hover:text-gray-600" aria-label="Kapat">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div id="quote-detail-content" class="p-6"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast kökü -->
    <div id="toast-root" aria-live="polite" aria-atomic="true"></div>

    <script>
        /* ==== PHP’den gelen veri ==== */
        const API_CORE_CONST = <?= json_encode(API_CORE) ?>;
        const ACCESS_TOKEN = <?= json_encode($accessToken ?? '') ?>;
        const IS_ADMIN = <?= json_encode($isAdmin) ?>;
        const apiErrPHP = <?= json_encode($apiError ?? null, JSON_UNESCAPED_UNICODE) ?>;
        const quotesRaw = <?= json_encode($quotes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        const CATEGORIES = <?= json_encode($categories ?? []) ?>;

        /* ---- API BASE & FETCH ---- */
        function buildApiBase() {
            try {
                const u = new URL(API_CORE_CONST, window.location.href);
                return u.href.replace(/\/$/, '');
            } catch {
                return (String(API_CORE_CONST || '')).replace(/\/$/, '') || '/api';
            }
        }
        const API_BASE = buildApiBase();

        async function apiFetch(path, options = {}) {
            const url1 = `${API_BASE}/${path.replace(/^\//,'')}`;
            try {
                return await fetch(url1, {
                    ...options,
                    mode: 'cors'
                });
            } catch (e) {
                const url2 = `/api/${path.replace(/^\//,'')}`;
                if (url2 === url1) throw e;
                return await fetch(url2, {
                    ...options,
                    mode: 'cors'
                });
            }
        }

        /* ---- Toast helper ---- */
        function showToast(text, type = 'success', title = null, timeoutMs = 3500) {
            const root = document.getElementById('toast-root');
            if (!root) return;
            const box = document.createElement('div');
            box.className = `toast toast-${type}`;
            box.innerHTML = `
                <div>
                    ${title ? `<div class="toast-title">${title}</div>` : ``}
                    <div class="toast-text">${(text||'').toString()}</div>
                </div>
                <button class="toast-close" aria-label="Kapat">✕</button>
            `;
            root.appendChild(box);
            requestAnimationFrame(() => box.classList.add('show'));

            const close = () => {
                box.classList.remove('show');
                setTimeout(() => box.remove(), 200);
            };
            box.querySelector('.toast-close').addEventListener('click', close);
            setTimeout(close, timeoutMs);
        }

        /* ---- helpers ---- */
        const statusBackendToTr = (st) => {
            const s = String(st || '').toLowerCase();
            if (s === 'approved') return 'onaylandi';
            if (s === 'rejected') return 'reddedildi';
            if (s === 'expired') return 'suresi-doldu';
            return 'beklemede';
        };
        const statusTrToBackend = (tr) => {
            const s = String(tr || '').toLowerCase();
            if (s === 'onaylandi') return 'approved';
            if (s === 'reddedildi') return 'rejected';
            if (s === 'suresi-doldu') return 'expired';
            return 'submitted';
        };

        function normalizeQuote(q, idx) {
            const raw = String(q.status ?? '').toLowerCase();
            const trSet = ['beklemede', 'onaylandi', 'reddedildi', 'suresi-doldu'];
            const status = trSet.includes(raw) ? raw : statusBackendToTr(raw);
            return {
                id: q.id ?? String(idx + 1),
                title: q.title || q.reference_no || 'Teklif',
                customer: (q.customer_name || q.customer_title || q.customer || '-'),
                contact: q.contact_name || (q.customer_type || ''),
                category: q.category || '',
                status,
                amount: q.total_amount ?? q.amount ?? null,
                deadline: q.validity_date || q.deadline || '',
                createdDate: q.created_at || '',
                description: q.notes || q.description || '',
                currency: q.currency || 'TRY',
                // << burada önce products, yoksa items
                products: Array.isArray(q.products) ? q.products : (Array.isArray(q.items) ? q.items : []),
                partner_id: q.partner_id || null,
                customer_id: q.customer_id || null,
                reject_reason: q.reject_reason ?? null
            };
        }

        function numberTL(v) {
            if (v == null) return '-';
            const n = Number(v);
            if (Number.isNaN(n)) return '-';
            return '₺' + n.toLocaleString('tr-TR');
        }

        function formatDate(s) {
            if (!s) return '-';
            const d = new Date(s);
            if (isNaN(d)) return '-';
            return d.toLocaleDateString('tr-TR', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function escapeHtml(str) {
            return (String(str || '')).replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [m]));
        }
        const byId = (id) => document.getElementById(id);

        /* ---- normalize → state ---- */
        let quotesData = Array.isArray(quotesRaw) ? quotesRaw.map(normalizeQuote) : [];
        let filteredQuotes = quotesData.slice();

        /* ==== Client-side sayfalama ==== */
        let PAGE_SIZE = 10;
        let currentPage = 1;

        /* ---- kategori filtresi doldur ---- */
        function fillCategoryFilter() {
            const sel = byId('categoryFilter');
            if (!sel) return;
            if (Array.isArray(CATEGORIES) && CATEGORIES.length) {
                const first = sel.querySelector('option[value=""]') || new Option('Tüm Kategoriler', '');
                sel.innerHTML = '';
                sel.appendChild(first);
                CATEGORIES.forEach(cat => {
                    if (cat == null) return;
                    const v = String(cat);
                    sel.appendChild(new Option(v, v));
                });
            }
        }

        /* ---- kart ---- */
        function createQuoteCard(quote) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg card-shadow hover:shadow-lg transition-shadow';
            const statusColors = {
                'beklemede': 'bg-yellow-100 text-yellow-800',
                'onaylandi': 'bg-green-100 text-green-800',
                'reddedildi': 'bg-red-100 text-red-800',
                'suresi-doldu': 'bg-gray-100 text-gray-800'
            };
            const statusTexts = {
                'beklemede': 'Beklemede',
                'onaylandi': 'Onaylandı',
                'reddedildi': 'Reddedildi',
                'suresi-doldu': 'Süresi Doldu'
            };
            const adminActions = IS_ADMIN ? `
                <div class="flex gap-2 mt-3">
                    <button onclick="quickSetStatus('${quote.id}','approved')" class="px-3 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">Onayla</button>
                    <button onclick="rejectQuotePrompt('${quote.id}')" class="px-3 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Reddet</button>
                </div>` : '';

            card.innerHTML = `
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">${escapeHtml(quote.title||'-')}</h3>
                            <p class="text-sm text-gray-600">${escapeHtml(quote.customer||'-')}</p>
                            ${quote.contact ? `<p class="text-xs text-gray-500">${escapeHtml(quote.contact)}</p>` : ''}
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${statusColors[quote.status]||'bg-gray-100 text-gray-800'}">${statusTexts[quote.status]||'Beklemede'}</span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center"><span class="text-sm text-gray-600">Tutar:</span><span class="font-semibold text-gray-900">${numberTL(quote.amount)}</span></div>
                        <div class="flex justify-between items-center"><span class="text-sm text-gray-600">Son Geçerlilik:</span><span class="text-sm text-gray-900">${formatDate(quote.deadline)}</span></div>
                        <div class="flex justify-between items-center"><span class="text-sm text-gray-600">Oluşturma:</span><span class="text-sm text-gray-900">${formatDate(quote.createdDate)}</span></div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex space-x-2">
                            <button onclick="viewQuoteDetail('${quote.id}')" class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Detay</button>
                            <button onclick="openEdit('${quote.id}')" class="flex-1 px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">Düzenle</button>
                        </div>
                        ${adminActions}
                    </div>
                </div>`;
            return card;
        }

        /* ---- liste + sayfa render ---- */
        function renderQuotes() {
            const container = byId('quotes-container');
            container.innerHTML = '';

            const total = filteredQuotes.length;
            if (!total) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">Aradığınız kriterlere uygun teklif bulunamadı</p>
                    </div>`;
                byId('totalText').textContent = 'Toplam 0 teklif';
                byId('pageInfo').textContent = 'Toplam 0 teklif';
                byId('pageButtons').innerHTML = '';
                return;
            }

            const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
            if (currentPage > totalPages) currentPage = totalPages;

            const startIdx = (currentPage - 1) * PAGE_SIZE;
            const endIdx = Math.min(total, startIdx + PAGE_SIZE);
            const pageItems = filteredQuotes.slice(startIdx, endIdx);

            byId('totalText').textContent = `Toplam ${total} teklif — ${startIdx+1}-${endIdx} arası`;
            byId('pageInfo').textContent = `Toplam ${total} teklif — ${startIdx+1}-${endIdx} arası`;

            pageItems.forEach(q => container.appendChild(createQuoteCard(q)));
        }

        /* ---- KPI (local veriden) ---- */
        function recomputeCountsFromLocal() {
            const pending = quotesData.filter(q => q.status === 'beklemede').length;
            const approved = quotesData.filter(q => q.status === 'onaylandi').length;
            const rejected = quotesData.filter(q => q.status === 'reddedildi').length;
            const expired = quotesData.filter(q => q.status === 'suresi-doldu').length;
            const total = pending + approved + rejected + expired;

            byId('pendingCount').textContent = pending;
            byId('approvedCount').textContent = approved;
            byId('rejectedCount').textContent = rejected;
            byId('successRate').textContent = total ? Math.round((approved / total) * 100) + '%' : '0%';
        }

        /* ---- Arama/filtre ---- */
        function setupSearch() {
            byId('searchQuotes').addEventListener('input', e => filterQuotes(e.target.value.toLowerCase()));
        }

        function filterQuotes(searchTerm = '') {
            const statusFilter = byId('statusFilter').value;
            const categoryFilter = byId('categoryFilter').value;

            filteredQuotes = quotesData.filter(q => {
                const matchesSearch = !searchTerm ||
                    (q.title || '').toLowerCase().includes(searchTerm) ||
                    (q.customer || '').toLowerCase().includes(searchTerm) ||
                    (q.contact || '').toLowerCase().includes(searchTerm);
                const matchesStatus = !statusFilter || q.status === statusFilter;
                const matchesCategory = !categoryFilter || (q.category || '') === categoryFilter;
                return matchesSearch && matchesStatus && matchesCategory;
            });

            currentPage = 1;
            renderPagination();
            renderQuotes();
        }

        /* ---- client-side sayfalama butonları ---- */
        function renderPagination() {
            const total = filteredQuotes.length;
            const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
            if (currentPage > totalPages) currentPage = totalPages;

            const btns = byId('pageButtons');
            btns.innerHTML = '';

            const makeBtn = (label, targetPage, disabled = false, current = false) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'px-3 py-1 rounded border text-sm ' +
                    (current ? 'bg-blue-600 text-white border-blue-600' :
                        disabled ? 'bg-gray-100 text-gray-400 border-gray-200' :
                        'bg-white text-gray-700 border-gray-300 hover:bg-gray-50');
                b.textContent = label;
                if (!disabled && !current) {
                    b.addEventListener('click', () => {
                        currentPage = targetPage;
                        renderPagination();
                        renderQuotes();
                    });
                }
                return b;
            };

            btns.appendChild(makeBtn('Önceki', Math.max(1, currentPage - 1), currentPage <= 1));
            const span = 2;
            const from = Math.max(1, currentPage - span);
            const to = Math.min(totalPages, currentPage + span);

            if (from > 1) btns.appendChild(makeBtn('1', 1));
            if (from > 2) {
                const s = document.createElement('span');
                s.className = 'px-2 text-gray-400';
                s.textContent = '…';
                btns.appendChild(s);
            }
            for (let p = from; p <= to; p++) btns.appendChild(makeBtn(String(p), p, false, p === currentPage));
            if (to < totalPages - 1) {
                const s = document.createElement('span');
                s.className = 'px-2 text-gray-400';
                s.textContent = '…';
                btns.appendChild(s);
            }
            if (to < totalPages) btns.appendChild(makeBtn(String(totalPages), totalPages));
            btns.appendChild(makeBtn('Sonraki', Math.min(totalPages, currentPage + 1), currentPage >= totalPages));
        }

        /* ---- modal ---- */
        let currentModalId = null;

        function openModal() {
            byId('quote-detail-modal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeQuoteDetail() {
            byId('quote-detail-modal').classList.add('hidden');
            document.body.classList.remove('modal-open');
            currentModalId = null;
        }
        byId('modal-backdrop').addEventListener('click', closeQuoteDetail);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeQuoteDetail();
        });

        /* ==== Detay / Edit ==== */
        function viewQuoteDetail(id) {
            const q = quotesData.find(x => String(x.id) === String(id));
            if (!q) return;
            currentModalId = q.id;

            const itemsRows = (q.products || []).map((it, i) => `
                <tr>
                    <td>${i+1}</td>
                    <td><div class="text-xs text-gray-500">${escapeHtml(it.code||'')}</div><div class="font-medium">${escapeHtml(it.name||'')}</div></td>
                    <td class="text-right">${Number(it.qty||0)}</td>
                    <td class="text-right">${numberTL(it.unit_price||0)}</td>
                    <td class="text-right">${numberTL(it.discount||0)}</td>
                    <td class="text-right">${numberTL(it.line_total||0)}</td>
                </tr>`).join('');

            byId('modal-title').textContent = 'Teklif Detayları';
            byId('quote-detail-content').innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Proje Bilgileri</h4>
                            <div class="space-y-3">
                                <div><span class="text-sm font-medium text-gray-600">Proje Adı:</span><p class="text-gray-900">${escapeHtml(q.title||'-')}</p></div>
                                ${q.description ? `<div><span class="text-sm font-medium text-gray-600">Açıklama:</span><p class="text-gray-900">${escapeHtml(q.description)}</p></div>` : ''}
                                ${q.category    ? `<div><span class="text-sm font-medium text-gray-600">Kategori:</span><p class="text-gray-900">${escapeHtml(q.category)}</p></div>` : ''}
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Müşteri Bilgileri</h4>
                            <div class="space-y-3">
                                <div><span class="text-sm font-medium text-gray-600">Şirket:</span><p class="text-gray-900">${escapeHtml(q.customer||'-')}</p></div>
                                ${q.contact ? `<div><span class="text-sm font-medium text-gray-600">İletişim Kişisi:</span><p class="text-gray-900">${escapeHtml(q.contact)}</p></div>` : ''}
                                <div><span class="text-sm font-medium text-gray-600">Toplam Tutar:</span><p class="text-xl font-bold text-gray-900">${numberTL(q.amount)}</p></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Kalemler</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-mini">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Ürün</th>
                                        <th class="text-right">Adet</th>
                                        <th class="text-right">Birim Fiyatı</th>
                                        <th class="text-right">İskonto</th>
                                        <th class="text-right">Tutar</th>
                                    </tr>
                                </thead>
                                <tbody>${itemsRows || `<tr><td colspan="6" class="text-gray-500">Kalem bulunamadı</td></tr>`}</tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg"><span class="text-sm font-medium text-gray-600">Oluşturma Tarihi:</span><p class="text-gray-900">${formatDate(q.createdDate)}</p></div>
                        <div class="p-4 bg-gray-50 rounded-lg"><span class="text-sm font-medium text-gray-600">Son Geçerlilik:</span><p class="text-gray-900">${formatDate(q.deadline)}</p></div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-gray-200">
                        <button onclick="openEdit('${q.id}')" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Düzenle</button>
                        <button onclick="downloadQuotePDF('${q.id}')" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">PDF İndir</button>
                    </div>
                </div>`;
            openModal();
        }

        function itemRow(it = {}, i = 0) {
            return `
                <tr data-row="${i}">
                    <td><input class="w-full border rounded px-2 py-1" value="${escapeHtml(it.product_id||'')}" placeholder="product_id"></td>
                    <td><input type="number" step="1" min="0" class="w-24 border rounded px-2 py-1 text-right" value="${Number(it.qty||0)}"></td>
                    <td><input type="number" step="0.01" class="w-28 border rounded px-2 py-1 text-right" value="${Number(it.unit_price||0)}"></td>
                    <td><input type="number" step="0.01" class="w-24 border rounded px-2 py-1 text-right" value="${Number(it.discount||0)}"></td>
                    <td><input class="w-full border rounded px-2 py-1" value="${escapeHtml(it.meta_params||'')}" placeholder='{"color":"blue"} (ops)'></td>
                    <td><button type="button" class="px-2 py-1 text-sm bg-red-600 text-white rounded" onclick="removeItemRow(this)">Sil</button></td>
                </tr>`;
        }

        function openEdit(id) {
            const q = quotesData.find(x => String(x.id) === String(id));
            if (!q) return;
            byId('modal-title').textContent = 'Teklif Düzenle';

            const itemsHtml = (q.products || []).map((it, i) => itemRow(it, i)).join('');

            byId('quote-detail-content').innerHTML = `
                <form id="edit-form" class="space-y-6" onsubmit="event.preventDefault(); saveQuote('${q.id}');">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Proje Adı (title)</label>
                            <input id="f-title" type="text" value="${escapeHtml(q.title||'')}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Para Birimi (currency)</label>
                            <select id="f-currency" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                ${['TRY','USD','EUR'].map(c=>`<option value="${c}" ${c===(q.currency||'TRY')?'selected':''}>${c}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Son Geçerlilik (validity_date)</label>
                            <input id="f-deadline" type="date" value="${(q.deadline ? new Date(q.deadline).toISOString().slice(0,10) : '')}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        ${IS_ADMIN ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Durum (status)</label>
                            <select id="f-status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">(değiştirme)</option>
                                <option value="submitted"  ${q.status==='beklemede'  ? 'selected':''}>submitted</option>
                                <option value="approved"   ${q.status==='onaylandi'  ? 'selected':''}>approved</option>
                                <option value="rejected"   ${q.status==='reddedildi' ? 'selected':''}>rejected</option>
                                <option value="expired"    ${q.status==='suresi-doldu'? 'selected':''}>expired</option>
                            </select>
                        </div>` : ``}
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama (notes)</label>
                        <textarea id="f-notes" rows="4" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">${escapeHtml(q.description||'')}</textarea>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-900">Kalemler (items)</h4>
                            <button type="button" onclick="addItemRow()" class="px-3 py-1 text-sm bg-gray-800 text-white rounded">Kalem Ekle</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-mini" id="items-table">
                                <thead><tr><th>product_id</th><th>qty</th><th>unit_price</th><th>discount</th><th>meta_params (JSON string)</th><th></th></tr></thead>
                                <tbody id="items-tbody">${itemsHtml || itemRow({},0)}</tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reddetme Gerekçesi (reject_reason)</label>
                            <input id="f-reject-reason" type="text" value="${escapeHtml(q.reject_reason||'')}" class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-2 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Kaydet</button>
                        <button type="button" onclick="viewQuoteDetail('${q.id}')" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">Vazgeç</button>
                    </div>
                </form>`;
            openModal();
        }

        function addItemRow() {
            byId('items-tbody').insertAdjacentHTML('beforeend', itemRow({}, Date.now()));
        }

        function removeItemRow(btn) {
            const tr = btn.closest('tr');
            if (tr) tr.remove();
        }

        /* ---- KAYDET: PUT /api/quotes/admin/:id ---- */
        async function saveQuote(id) {
            if (!ACCESS_TOKEN) {
                showToast('Token yok (giriş yapın).', 'error', 'Hata');
                return;
            }

            const title = byId('f-title')?.value.trim();
            const currency = byId('f-currency')?.value;
            const dead = byId('f-deadline')?.value;
            const notes = byId('f-notes')?.value.trim();
            const statusEl = byId('f-status');
            const rejectIn = byId('f-reject-reason')?.value.trim();

            // items to array
            const items = [];
            document.querySelectorAll('#items-tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length < 5) return;
                const product_id = tds[0].querySelector('input')?.value.trim();
                const qty = Number(tds[1].querySelector('input')?.value || 0);
                const unit_price = Number(tds[2].querySelector('input')?.value || 0);
                const discount = Number(tds[3].querySelector('input')?.value || 0);
                const meta_raw = tds[4].querySelector('input')?.value || '';
                if (!product_id) return;
                const row = {
                    product_id,
                    qty,
                    unit_price,
                    discount
                };
                if (meta_raw) row.meta_params = meta_raw; // BE string bekliyor
                items.push(row);
            });

            const bodyObj = {};
            if (title) bodyObj.title = title;
            if (currency) bodyObj.currency = currency;
            if (dead) bodyObj.validity_date = dead;
            if (notes !== undefined) bodyObj.notes = notes || null;
            if (IS_ADMIN && statusEl && statusEl.value) bodyObj.status = statusEl.value;
            if (rejectIn !== undefined && rejectIn !== '') bodyObj.reject_reason = rejectIn;
            if (items.length) bodyObj.items = items;

            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + ACCESS_TOKEN
            };

            try {
                const res = await apiFetch(`quotes/admin/${encodeURIComponent(id)}`, {
                    method: 'PUT',
                    headers,
                    body: JSON.stringify(bodyObj)
                });

                const text = await res.text();
                let data = null;
                if (text) {
                    try {
                        data = JSON.parse(text);
                    } catch {}
                }
                if (!res.ok) {
                    const msg = (data && (data.error || data.message)) ? (data.error || data.message) : (text || `HTTP ${res.status}`);
                    throw new Error(msg);
                }

                // UI update (local state)
                const i = quotesData.findIndex(q => String(q.id) === String(id));
                if (i >= 0) {
                    if (bodyObj.title) quotesData[i].title = bodyObj.title;
                    if (bodyObj.currency) quotesData[i].currency = bodyObj.currency;
                    if (bodyObj.validity_date) quotesData[i].deadline = bodyObj.validity_date;
                    if (bodyObj.notes !== undefined) quotesData[i].description = bodyObj.notes || '';
                    if (bodyObj.status) quotesData[i].status = statusBackendToTr(bodyObj.status);
                    if (bodyObj.reject_reason !== undefined) quotesData[i].reject_reason = bodyObj.reject_reason || null;
                    if (bodyObj.items) quotesData[i].products = bodyObj.items.slice();
                }
                const term = byId('searchQuotes')?.value?.toLowerCase() || '';
                filterQuotes(term);
                recomputeCountsFromLocal();
                renderPagination();
                closeQuoteDetail();
                showToast('Teklif başarıyla kaydedildi.', 'success', 'Kaydedildi');
            } catch (e) {
                showToast('Kaydetme hatası: ' + e.message + ' (API_BASE: ' + API_BASE + ')', 'error', 'Hata');
            }
        }

        /* ---- Hızlı durum setleri (PUT /api/quotes/admin/:id) ---- */
        async function quickSetStatus(id, backendStatus) {
            if (!ACCESS_TOKEN) {
                showToast('Token yok (giriş yapın).', 'error', 'Hata');
                return;
            }
            try {
                const res = await apiFetch(`quotes/admin/${encodeURIComponent(id)}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + ACCESS_TOKEN
                    },
                    body: JSON.stringify({
                        status: backendStatus
                    })
                });
                const t = await res.text();
                let data = null;
                if (t) {
                    try {
                        data = JSON.parse(t);
                    } catch {}
                }
                if (!res.ok) throw new Error((data && (data.error || data.message)) || t || 'status update failed');

                const i = quotesData.findIndex(q => String(q.id) === String(id));
                if (i >= 0) quotesData[i].status = statusBackendToTr(backendStatus);

                const term = byId('searchQuotes')?.value?.toLowerCase() || '';
                filterQuotes(term);
                recomputeCountsFromLocal();
                renderPagination();
                showToast('Teklif durumu güncellendi.', 'success', 'Güncellendi');
            } catch (e) {
                showToast('Durum güncelleme hatası: ' + e.message, 'error', 'Hata');
            }
        }

        function rejectQuotePrompt(id) {
            const reason = prompt('Reddetme gerekçesi:', '');
            if (reason === null) return;
            quickReject(id, reason.trim());
        }
        async function quickReject(id, reason) {
            if (!ACCESS_TOKEN) {
                showToast('Token yok (giriş yapın).', 'error', 'Hata');
                return;
            }
            try {
                const res = await apiFetch(`quotes/admin/${encodeURIComponent(id)}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + ACCESS_TOKEN
                    },
                    body: JSON.stringify({
                        status: 'rejected',
                        reject_reason: reason || null
                    })
                });
                const t = await res.text();
                let data = null;
                if (t) {
                    try {
                        data = JSON.parse(t);
                    } catch {}
                }
                if (!res.ok) throw new Error((data && (data.error || data.message)) || t || 'reject failed');

                const i = quotesData.findIndex(q => String(q.id) === String(id));
                if (i >= 0) {
                    quotesData[i].status = 'reddedildi';
                    quotesData[i].reject_reason = reason || null;
                }

                const term = byId('searchQuotes')?.value?.toLowerCase() || '';
                filterQuotes(term);
                recomputeCountsFromLocal();
                renderPagination();
                showToast('Teklif reddedildi.', 'info', 'Bilgi');
            } catch (e) {
                showToast('Reddetme hatası: ' + e.message, 'error', 'Hata');
            }
        }

        /* ---- diğer stubs ---- */
        function createNewQuote() {
            showToast('Yeni teklif oluşturma sayfasına yönlendirme burada yapılacak.', 'info', 'Bilgi');
        }

        function downloadQuotePDF(id) {
            showToast('PDF indirme: ' + id, 'info', 'Bilgi');
        }

        /* ---- init ---- */
        function init() {
            quotesData = Array.isArray(quotesRaw) ? quotesRaw.map(normalizeQuote) : [];
            filteredQuotes = quotesData.slice();
            fillCategoryFilter();
            setupSearch();
            recomputeCountsFromLocal();
            renderPagination();
            renderQuotes();
            if (apiErrPHP) console.warn('API Hatası:', apiErrPHP);
        }
        window.addEventListener('load', init);
    </script>
</body>

</html>