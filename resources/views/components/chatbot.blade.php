<div x-data="chatbot()" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button 
        @click="toggleChat()" 
        class="bg-[#0d5c2f] hover:bg-[#0a4a26] text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110 group"
        :class="{ 'rotate-45': isOpen }"
        title="Ask me anything about our services"
    >
        <i class="fas fa-comments text-xl group-hover:scale-110 transition-transform duration-200"></i>
    </button>

    <!-- Chat Window -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute bottom-16 right-0 w-96 h-[500px] bg-white rounded-lg shadow-xl border border-gray-200 flex flex-col"
    >
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 text-white p-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Parish Assistant</h3>
                        <p class="text-xs text-white/80" x-text="isTyping ? 'Typing...' : 'Online'"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- FAQ Toggle Button -->
                    <button 
                        @click="toggleFaqList()" 
                        class="text-white/80 hover:text-white transition-colors p-1 rounded hover:bg-white/10"
                        title="Browse all questions"
                    >
                        <i class="fas fa-list text-sm"></i>
                    </button>
                    <button @click="toggleChat()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- FAQ List Panel -->
        <div 
            x-show="showFaqList" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="flex-1 flex flex-col bg-gray-50 overflow-hidden"
        >
            <div class="p-4 h-full flex flex-col">
                <div class="flex items-center justify-between mb-3 flex-shrink-0">
                    <h4 class="text-sm font-medium text-gray-700">Frequently Asked Questions</h4>
                    <button 
                        @click="showFaqList = false" 
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                
                <!-- Search FAQ -->
                <div class="mb-3 flex-shrink-0">
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="faqSearch" 
                            placeholder="Search questions..."
                            class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                        >
                        <i class="fas fa-search absolute right-2 top-2.5 text-gray-400 text-xs"></i>
                    </div>
                </div>
                
                <!-- All FAQ Questions - Scrollable -->
                <div class="flex-1 overflow-y-auto space-y-1 min-h-0">
                    <template x-for="faq in filteredFaqs" :key="faq.id">
                        <button 
                            @click="sendFaqQuestion(faq.question)"
                            class="w-full text-left px-3 py-2 text-xs text-gray-600 hover:text-[#0d5c2f] hover:bg-white rounded transition-colors border border-transparent hover:border-[#0d5c2f]/20"
                        >
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-question-circle text-[#0d5c2f] mt-0.5 flex-shrink-0"></i>
                                <span class="text-left leading-relaxed" x-text="faq.question"></span>
                            </div>
                        </button>
                    </template>
                    
                    <!-- No Results Message -->
                    <div x-show="faqSearch && filteredFaqs.length === 0" class="text-center py-4 text-gray-500 text-xs">
                        <i class="fas fa-search text-gray-400 mb-2"></i>
                        <p>No questions found matching your search.</p>
                    </div>
                    
                    <!-- Loading State -->
                    <div x-show="isLoadingFaqs" class="text-center py-4 text-gray-500 text-xs">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        <p class="mt-2">Loading questions...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->
        <div x-show="!showFaqList" class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="messagesContainer">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="bg-gray-100 rounded-lg p-3 max-w-xs">
                    <p class="text-sm text-gray-800">
                        Hello! I'm your parish assistant. How can I help you today? You can ask me about:
                    </p>
                    <ul class="text-xs text-gray-600 mt-2 space-y-1">
                        <li>• Service bookings and requirements</li>
                        <li>• Parish schedules and activities</li>
                        <li>• Contact information</li>
                        <li>• General inquiries</li>
                    </ul>
                </div>
            </div>

            <!-- Quick Suggestions -->
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="space-y-2">
                    <p class="text-xs text-gray-500">Quick questions:</p>
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="sendMessage('How do I book a service?')"
                            class="bg-[#0d5c2f] text-white text-xs px-3 py-1.5 rounded-full hover:bg-[#0a4a26] transition-colors"
                        >
                            Book a service
                        </button>
                        <button 
                            @click="sendMessage('What are your office hours?')"
                            class="bg-[#0d5c2f] text-white text-xs px-3 py-1.5 rounded-full hover:bg-[#0a4a26] transition-colors"
                        >
                            Office hours
                        </button>
                        <button 
                            @click="sendMessage('How much does a service cost?')"
                            class="bg-[#0d5c2f] text-white text-xs px-3 py-1.5 rounded-full hover:bg-[#0a4a26] transition-colors"
                        >
                            Service costs
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dynamic Messages -->
            <template x-for="message in messages" :key="message.id">
                <div class="flex items-start space-x-3" :class="message.type === 'user' ? 'justify-end' : ''">
                    <!-- Bot Avatar -->
                    <div 
                        x-show="message.type === 'bot'"
                        class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center flex-shrink-0"
                    >
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    
                    <!-- Message Content -->
                    <div 
                        class="rounded-lg p-3 max-w-xs"
                        :class="message.type === 'user' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-800'"
                    >
                        <p class="text-sm" x-show="!message.message_is_html" x-text="message.text"></p>
                        <p class="text-sm" x-show="message.message_is_html" x-html="message.text"></p>
                        
                        <!-- FAQ Results -->
                        <template x-if="message.results && message.results.length > 0">
                            <div class="mt-3 space-y-2">
                                <template x-for="result in message.results" :key="result.id">
                                    <div class="bg-white/10 rounded p-2">
                                        <h4 class="font-semibold text-xs mb-1" x-text="result.question"></h4>
                                        <p class="text-xs opacity-90" x-text="result.answer"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Suggestions -->
                        <template x-if="message.suggestions && message.suggestions.length > 0">
                            <div class="mt-3">
                                <p class="text-xs mb-2 opacity-80">Try asking about:</p>
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="suggestion in message.suggestions" :key="suggestion">
                                        <button 
                                            @click="sendMessage(suggestion)"
                                            class="bg-white/20 text-xs px-2 py-1 rounded hover:bg-white/30 transition-colors"
                                            x-text="suggestion"
                                        ></button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- User Avatar -->
                    <div 
                        x-show="message.type === 'user'"
                        class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0"
                    >
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="bg-gray-100 rounded-lg p-3">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function chatbot() {
    return {
        isOpen: false,
        userInput: '',
        messages: [],
        isTyping: false,
        messageId: 0,
        showFaqList: false,
        faqSearch: '',
        allFaqs: [],
        isLoadingFaqs: false,

        // Computed properties
        get filteredFaqs() {
            if (!this.faqSearch.trim()) return this.allFaqs;
            
            const searchTerm = this.faqSearch.toLowerCase();
            return this.allFaqs.filter(faq => 
                faq.question.toLowerCase().includes(searchTerm)
            );
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                    this.loadFaqData();
                });
            }
        },

        async loadFaqData() {
            this.isLoadingFaqs = true;
            
            try {
                const response = await fetch('/faq/all');
                const data = await response.json();
                
                if (data.success && data.faqs) {
                    this.allFaqs = data.faqs;
                } else {
                    // Fallback to default questions if API fails
                    this.allFaqs = [
                        { id: 1, question: 'How do I book a service?', answer: 'You can book a service through our website or by contacting the parish office.' },
                        { id: 2, question: 'What are your office hours?', answer: 'Our office is open Monday to Friday, 9 AM to 5 PM.' },
                        { id: 3, question: 'How much does a service cost?', answer: 'Service costs vary. Please contact us for current pricing information.' },
                        { id: 4, question: 'Where is the parish located?', answer: 'We are located at B. Morcilla St., Pateros, Metro Manila.' },
                        { id: 5, question: 'What services do you offer?', answer: 'We offer various sacramental services including Baptism, Confirmation, Marriage, and more.' },
                        { id: 6, question: 'How can I contact the parish?', answer: 'You can reach us by phone at 0917-366-4359 or email at diocesansaintmartha@gmail.com.' },
                        { id: 7, question: 'What are the mass schedules?', answer: 'Please check our website or contact the office for current mass schedules.' },
                        { id: 8, question: 'Do you offer online services?', answer: 'Yes, we offer online booking for services and other digital services.' },
                        { id: 9, question: 'How do I become a parishioner?', answer: 'Contact the parish office to register as a parishioner.' },
                        { id: 10, question: 'What documents do I need for services?', answer: 'Required documents vary by service type. Please contact us for specific requirements.' }
                    ];
                }
            } catch (error) {
                console.error('Error loading FAQ data:', error);
                // Use default questions if API fails
                this.allFaqs = [
                    { id: 1, question: 'How do I book a service?', answer: 'You can book a service through our website or by contacting the parish office.' },
                    { id: 2, question: 'What are your office hours?', answer: 'Our office is open Monday to Friday, 9 AM to 5 PM.' },
                    { id: 3, question: 'How much does a service cost?', answer: 'Service costs vary. Please contact us for current pricing information.' },
                    { id: 4, question: 'Where is the parish located?', answer: 'We are located at B. Morcilla St., Pateros, Metro Manila.' },
                    { id: 5, question: 'What services do you offer?', answer: 'We offer various sacramental services including Baptism, Confirmation, Marriage, and more.' },
                    { id: 6, question: 'How can I contact the parish?', answer: 'You can reach us by phone at 0917-366-4359 or email at diocesansaintmartha@gmail.com.' },
                    { id: 7, question: 'What are the mass schedules?', answer: 'Please check our website or contact the office for current mass schedules.' },
                    { id: 8, question: 'Do you offer online services?', answer: 'Yes, we offer online booking for services and other digital services.' },
                    { id: 9, question: 'How do I become a parishioner?', answer: 'Contact the parish office to register as a parishioner.' },
                    { id: 10, question: 'What documents do I need for services?', answer: 'Required documents vary by service type. Please contact us for specific requirements.' }
                ];
            } finally {
                this.isLoadingFaqs = false;
            }
        },

        toggleFaqList() {
            this.showFaqList = !this.showFaqList;
            if (this.showFaqList) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },

        async sendMessage(text = null) {
            const message = text || this.userInput.trim();
            if (!message) return;

            // Add user message
            this.addMessage(message, 'user');
            this.userInput = '';

            // Show typing indicator
            this.isTyping = true;

            try {
                // Send to backend
                const response = await fetch('/faq/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ query: message })
                });

                const data = await response.json();

                // Simulate typing delay
                await new Promise(resolve => setTimeout(resolve, 1000));

                // Add bot response
                this.addMessage(data.message || 'I understand your question. Let me help you with that.', 'bot', data.results, data.suggestions, data.message_is_html);

            } catch (error) {
                console.error('Error sending message:', error);
                this.addMessage('Sorry, I\'m having trouble connecting right now. Please try again later.', 'bot');
            } finally {
                this.isTyping = false;
                this.scrollToBottom();
            }
        },

        sendFaqQuestion(question) {
            this.sendMessage(question);
            this.showFaqList = false;
            this.faqSearch = '';
        },

        addMessage(text, type, results = null, suggestions = null, message_is_html = false) {
            this.messages.push({
                id: ++this.messageId,
                text,
                type,
                results,
                suggestions,
                message_is_html,
                timestamp: new Date()
            });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                container.scrollTop = container.scrollHeight;
            });
        }
    }
}
</script> 