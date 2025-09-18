<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesajlaşma - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .chat-container {
            height: calc(100vh - 120px);
        }
        .messages-area {
            height: calc(100% - 80px);
        }
        .dealer-item:hover {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        .dealer-item.active {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
        }
        .message-bubble {
            max-width: 70%;
            word-wrap: break-word;
        }
        .message-bubble.sent {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .message-bubble.received {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 12px 16px;
            background: #f3f4f6;
            border-radius: 18px;
            margin: 8px 0;
        }
        .typing-dot {
            width: 8px;
            height: 8px;
            background: #9ca3af;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        .online-indicator {
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid white;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
        }
        .unread-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .search-highlight {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }
        .mobile-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .mobile-sidebar.open {
            transform: translateX(0);
        }
        @media (max-width: 768px) {
            .chat-container {
                height: calc(100vh - 80px);
            }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="md:hidden mr-3 p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Mesajlaşma</h1>
                        <p class="text-sm text-gray-500 hidden sm:block">Bayilerle iletişim</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center text-sm text-gray-500">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span id="onlineCount">12</span> Çevrimiçi
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

    <!-- Main Chat Container -->
    <div class="max-w-7xl mx-auto flex h-full">
        <!-- Sidebar - Dealers List -->
        <div id="sidebar" class="mobile-sidebar fixed md:relative inset-y-0 left-0 z-40 w-80 bg-white border-r border-gray-200 md:translate-x-0">
            <div class="flex flex-col h-full">
                <!-- Search -->
                <div class="p-4 border-b border-gray-200">
                    <div class="relative">
                        <input type="text" id="searchDealers" placeholder="Bayi ara..." onkeyup="searchDealers()" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Dealers List -->
                <div class="flex-1 overflow-y-auto" id="dealersList">
                    <!-- Dealers will be populated here -->
                </div>
            </div>
        </div>

        <!-- Overlay for mobile -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden hidden" onclick="toggleSidebar()"></div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-white chat-container">
            <!-- Chat Header -->
            <div id="chatHeader" class="hidden p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                <span id="selectedDealerInitial">?</span>
                            </div>
                            <div class="online-indicator" id="selectedDealerStatus"></div>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-gray-900" id="selectedDealerName">Bayi Seçin</h3>
                            <p class="text-sm text-gray-500" id="selectedDealerInfo">Mesajlaşmaya başlamak için bir bayi seçin</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="clearChat()" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100" title="Sohbeti Temizle">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="messagesArea" class="flex-1 overflow-y-auto p-4 messages-area">
                <!-- Welcome Message -->
                <div id="welcomeMessage" class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Mesajlaşmaya Başlayın</h3>
                        <p class="text-gray-500 max-w-md">Sol taraftan bir bayi seçerek mesajlaşmaya başlayabilirsiniz. Tüm mesajlarınız güvenli şekilde saklanır.</p>
                    </div>
                </div>

                <!-- Messages will be populated here -->
                <div id="messagesList" class="hidden space-y-4"></div>
            </div>

            <!-- Message Input -->
            <div id="messageInput" class="hidden p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-end space-x-3">
                    <div class="flex-1">
                        <textarea id="messageText" placeholder="Mesajınızı yazın..." rows="1" onkeydown="handleKeyDown(event)" oninput="autoResize(this)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none max-h-32"></textarea>
                    </div>
                    <button onclick="sendMessage()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span class="hidden sm:inline">Gönder</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedDealer = null;
        let dealers = [];
        let messages = {};
        let typingTimeouts = {};

        // Sample dealers data
        const sampleDealers = [
            { id: 1, name: "Teknoloji Dünyası", city: "İstanbul", isOnline: true, lastSeen: new Date(), unreadCount: 3 },
            { id: 2, name: "Dijital Çözümler", city: "Ankara", isOnline: true, lastSeen: new Date(), unreadCount: 0 },
            { id: 3, name: "Mega Elektronik", city: "İzmir", isOnline: false, lastSeen: new Date(Date.now() - 300000), unreadCount: 1 },
            { id: 4, name: "Akıllı Sistemler", city: "Bursa", isOnline: true, lastSeen: new Date(), unreadCount: 0 },
            { id: 5, name: "Bilişim Merkezi", city: "Antalya", isOnline: false, lastSeen: new Date(Date.now() - 1800000), unreadCount: 2 },
            { id: 6, name: "Yenilikçi Teknoloji", city: "Adana", isOnline: true, lastSeen: new Date(), unreadCount: 0 },
            { id: 7, name: "Gelişim Bilgisayar", city: "Konya", isOnline: false, lastSeen: new Date(Date.now() - 3600000), unreadCount: 0 },
            { id: 8, name: "Modern Elektronik", city: "Gaziantep", isOnline: true, lastSeen: new Date(), unreadCount: 1 }
        ];

        // Sample messages
        const sampleMessages = {
            1: [
                { id: 1, text: "Merhaba, yeni ürün kataloğu ne zaman gelecek?", sender: "dealer", timestamp: new Date(Date.now() - 3600000) },
                { id: 2, text: "Merhaba! Katalog bu hafta içinde hazır olacak. Size hemen göndereceğim.", sender: "admin", timestamp: new Date(Date.now() - 3500000) },
                { id: 3, text: "Teşekkürler. Ayrıca stok durumu hakkında bilgi alabilir miyim?", sender: "dealer", timestamp: new Date(Date.now() - 1800000) }
            ],
            3: [
                { id: 1, text: "Sipariş durumu nasıl kontrol edebilirim?", sender: "dealer", timestamp: new Date(Date.now() - 7200000) }
            ],
            5: [
                { id: 1, text: "Kampanya detayları için bilgi istiyorum", sender: "dealer", timestamp: new Date(Date.now() - 5400000) },
                { id: 2, text: "Tabii ki! Size özel kampanya bilgilerini hazırlayıp göndereceğim.", sender: "admin", timestamp: new Date(Date.now() - 5000000) }
            ],
            8: [
                { id: 1, text: "Teknik destek için kimle iletişime geçmeliyim?", sender: "dealer", timestamp: new Date(Date.now() - 900000) }
            ]
        };

        // Initialize
        function initializeChat() {
            dealers = [...sampleDealers];
            messages = { ...sampleMessages };
            renderDealers();
            updateOnlineCount();
        }

        // Render dealers list
        function renderDealers() {
            const container = document.getElementById('dealersList');
            const searchTerm = document.getElementById('searchDealers').value.toLowerCase();
            
            const filteredDealers = dealers.filter(dealer => 
                dealer.name.toLowerCase().includes(searchTerm) ||
                dealer.city.toLowerCase().includes(searchTerm)
            );

            container.innerHTML = filteredDealers.map(dealer => {
                const lastMessage = messages[dealer.id] ? messages[dealer.id][messages[dealer.id].length - 1] : null;
                const isActive = selectedDealer && selectedDealer.id === dealer.id;
                
                return `
                    <div class="dealer-item p-4 border-b border-gray-100 cursor-pointer transition-all ${isActive ? 'active' : ''}" onclick="selectDealer(${dealer.id})">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    ${dealer.name.charAt(0)}
                                </div>
                                ${dealer.isOnline ? '<div class="online-indicator"></div>' : ''}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 truncate">${dealer.name}</h4>
                                    ${dealer.unreadCount > 0 ? `<span class="unread-badge text-xs text-white px-2 py-1 rounded-full">${dealer.unreadCount}</span>` : ''}
                                </div>
                                <p class="text-sm text-gray-500 truncate">${dealer.city}</p>
                                ${lastMessage ? `
                                    <p class="text-xs text-gray-400 truncate mt-1">
                                        ${lastMessage.sender === 'admin' ? 'Siz: ' : ''}${lastMessage.text}
                                    </p>
                                ` : ''}
                                <p class="text-xs text-gray-400 mt-1">
                                    ${dealer.isOnline ? 'Çevrimiçi' : `Son görülme: ${formatTime(dealer.lastSeen)}`}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Select dealer
        function selectDealer(dealerId) {
            selectedDealer = dealers.find(d => d.id === dealerId);
            if (!selectedDealer) return;

            // Mark messages as read
            selectedDealer.unreadCount = 0;
            
            // Update UI
            document.getElementById('chatHeader').classList.remove('hidden');
            document.getElementById('messageInput').classList.remove('hidden');
            document.getElementById('welcomeMessage').classList.add('hidden');
            document.getElementById('messagesList').classList.remove('hidden');
            
            // Update header
            document.getElementById('selectedDealerInitial').textContent = selectedDealer.name.charAt(0);
            document.getElementById('selectedDealerName').textContent = selectedDealer.name;
            document.getElementById('selectedDealerInfo').textContent = `${selectedDealer.city} • ${selectedDealer.isOnline ? 'Çevrimiçi' : 'Çevrimdışı'}`;
            document.getElementById('selectedDealerStatus').style.display = selectedDealer.isOnline ? 'block' : 'none';
            
            // Load messages
            loadMessages();
            renderDealers();
            
            // Close sidebar on mobile
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        // Load messages
        function loadMessages() {
            const container = document.getElementById('messagesList');
            const dealerMessages = messages[selectedDealer.id] || [];
            
            container.innerHTML = dealerMessages.map(message => `
                <div class="flex ${message.sender === 'admin' ? 'justify-end' : 'justify-start'} fade-in">
                    <div class="message-bubble ${message.sender === 'admin' ? 'sent text-white' : 'received text-gray-900'} px-4 py-2 rounded-lg">
                        <p class="text-sm">${message.text}</p>
                        <p class="text-xs ${message.sender === 'admin' ? 'text-blue-100' : 'text-gray-500'} mt-1">
                            ${formatTime(message.timestamp)}
                        </p>
                    </div>
                </div>
            `).join('');
            
            scrollToBottom();
        }

        // Send message
        function sendMessage() {
            if (!selectedDealer) return;
            
            const messageText = document.getElementById('messageText');
            const text = messageText.value.trim();
            
            if (!text) return;
            
            // Create message
            const message = {
                id: Date.now(),
                text: text,
                sender: 'admin',
                timestamp: new Date()
            };
            
            // Add to messages
            if (!messages[selectedDealer.id]) {
                messages[selectedDealer.id] = [];
            }
            messages[selectedDealer.id].push(message);
            
            // Clear input
            messageText.value = '';
            messageText.style.height = 'auto';
            
            // Update UI
            loadMessages();
            renderDealers();
            
            // Simulate dealer typing and response
            simulateDealerResponse();
        }

        // Simulate dealer response
        function simulateDealerResponse() {
            if (!selectedDealer) return;
            
            // Show typing indicator
            showTypingIndicator();
            
            setTimeout(() => {
                hideTypingIndicator();
                
                const responses = [
                    "Teşekkürler, bilgi için.",
                    "Anladım, takip edeceğim.",
                    "Çok teşekkürler!",
                    "Bilgilendirme için sağolun.",
                    "Tamam, beklemedeyim."
                ];
                
                const response = {
                    id: Date.now(),
                    text: responses[Math.floor(Math.random() * responses.length)],
                    sender: 'dealer',
                    timestamp: new Date()
                };
                
                messages[selectedDealer.id].push(response);
                loadMessages();
                renderDealers();
            }, 2000 + Math.random() * 2000);
        }

        // Show typing indicator
        function showTypingIndicator() {
            const container = document.getElementById('messagesList');
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typingIndicator';
            typingDiv.className = 'flex justify-start fade-in';
            typingDiv.innerHTML = `
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            `;
            container.appendChild(typingDiv);
            scrollToBottom();
        }

        // Hide typing indicator
        function hideTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        // Handle keyboard events
        function handleKeyDown(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }

        // Auto resize textarea
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
        }

        // Search dealers
        function searchDealers() {
            renderDealers();
        }

        // Clear chat
        function clearChat() {
            if (!selectedDealer) return;
            
            if (confirm('Bu sohbetteki tüm mesajları silmek istediğinizden emin misiniz?')) {
                messages[selectedDealer.id] = [];
                loadMessages();
                renderDealers();
                showMessage('Sohbet temizlendi', 'success');
            }
        }

        // Toggle sidebar (mobile)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        // Update online count
        function updateOnlineCount() {
            const onlineCount = dealers.filter(d => d.isOnline).length;
            document.getElementById('onlineCount').textContent = onlineCount;
        }

        // Scroll to bottom
        function scrollToBottom() {
            const container = document.getElementById('messagesList');
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }

        // Format time
        function formatTime(date) {
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Şimdi';
            if (diff < 3600000) return Math.floor(diff / 60000) + ' dk önce';
            if (diff < 86400000) return Math.floor(diff / 3600000) + ' sa önce';
            
            return date.toLocaleDateString('tr-TR', { 
                day: 'numeric', 
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            });
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

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('overlay').classList.add('hidden');
            }
        });

        // Initialize when page loads
        window.addEventListener('load', initializeChat);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'98075ce7924bd342',t:'MTc1ODA5OTA5OC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
