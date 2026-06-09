<style>
/* ========== FLOATING CHATBOX ========== */
.pmec-chat-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: 'Poppins', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

/* Chat Toggle Button */
.pmec-chat-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #03428E, #00AEEF);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 174, 239, 0.4);
    transition: all 0.3s ease;
    border: none;
    outline: none;
    position: relative;
    z-index: 10;
    animation: chatTogglePulse 2s infinite;
}

.pmec-chat-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 24px rgba(0, 174, 239, 0.5);
    animation: none;
}

@keyframes chatTogglePulse {
    0% { box-shadow: 0 0 0 0 rgba(0,174,239,0.5); }
    70% { box-shadow: 0 0 0 15px rgba(0,174,239,0); }
    100% { box-shadow: 0 0 0 0 rgba(0,174,239,0); }
}

/* Chat Window */
.pmec-chat-window {
    width: 400px;
    height: 520px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: absolute;
    bottom: 80px;
    right: 0;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    transform-origin: bottom right;
    transform: scale(0);
    opacity: 0;
    pointer-events: none;
}

.pmec-chat-window.open {
    transform: scale(1);
    opacity: 1;
    pointer-events: auto;
}

/* Header */
.pmec-chat-header {
    background: linear-gradient(135deg, #03428E, #0074D9);
    color: #fff;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.pmec-chat-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.pmec-chat-avatar {
    width: 36px;
    height: 36px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.pmec-chat-avatar img {
    width: 24px;
    height: 24px;
    object-fit: contain;
}

.pmec-chat-title h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.pmec-chat-title p {
    margin: 0;
    font-size: 11px;
    opacity: 0.8;
}

.pmec-chat-close {
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.pmec-chat-close:hover {
    background: rgba(255,255,255,0.3);
}

/* Body */
.pmec-chat-body {
    flex: 1;
    padding: 20px 16px;
    overflow-y: auto;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Messages */
.pmec-chat-msg {
    max-width: 85%;
    display: flex;
    flex-direction: column;
}

.pmec-chat-msg.bot {
    align-self: flex-start;
}

.pmec-chat-msg.user {
    align-self: flex-end;
}

.pmec-chat-bubble {
    padding: 12px 16px;
    font-size: 13.5px;
    line-height: 1.5;
    word-wrap: break-word;
}

.pmec-chat-msg.bot .pmec-chat-bubble {
    background: #fff;
    color: #333;
    border-radius: 2px 16px 16px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid #edf2f7;
}

.pmec-chat-msg.user .pmec-chat-bubble {
    background: #00AEEF;
    color: #fff;
    border-radius: 16px 2px 16px 16px;
}

/* Render markdown in bot message */
.pmec-chat-msg.bot .pmec-chat-bubble strong {
    font-weight: 600;
    color: #03428E;
}

.pmec-chat-msg.bot .pmec-chat-bubble ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

/* Image in message */
.pmec-chat-img-preview {
    max-width: 100%;
    border-radius: 8px;
    margin-top: 8px;
    display: block;
}

/* Footer / Input Area */
.pmec-chat-footer {
    padding: 12px 16px;
    background: #fff;
    border-top: 1px solid #edf2f7;
}

.pmec-chat-image-preview-container {
    display: none;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 1px dashed #e2e8f0;
    margin-bottom: 8px;
}

.pmec-chat-image-preview-container img {
    height: 40px;
    width: auto;
    border-radius: 4px;
    object-fit: cover;
}

.pmec-chat-remove-img {
    color: #ef4444;
    cursor: pointer;
    font-size: 16px;
}

.pmec-chat-input-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    background: #f1f5f9;
    border-radius: 20px;
    padding: 6px 12px;
}

.pmec-chat-input-wrapper input[type="text"] {
    flex: 1;
    border: none;
    background: transparent;
    padding: 6px 0;
    font-size: 14px;
    outline: none;
    font-family: 'Poppins', sans-serif;
}

.pmec-chat-attach-btn {
    color: #64748b;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    transition: color 0.2s;
}

.pmec-chat-attach-btn:hover {
    color: #03428E;
}

.pmec-chat-send-btn {
    background: #00AEEF;
    color: #fff;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 14px;
    flex-shrink: 0;
}

.pmec-chat-send-btn:hover {
    background: #03428E;
}

.pmec-chat-send-btn:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 6px 4px;
}

.typing-dot {
    width: 6px;
    height: 6px;
    background: #00AEEF;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

@media (max-width: 480px) {
    .pmec-chat-window {
        width: calc(100vw - 32px);
        height: calc(100vh - 120px);
        bottom: 76px;
        right: -8px;
    }
}
</style>

<div class="pmec-chat-widget">
    <!-- Chat Window -->
    <div class="pmec-chat-window" id="pmecChatWindow">
        <!-- Header -->
        <div class="pmec-chat-header">
            <div class="pmec-chat-header-info">
                <div class="pmec-chat-avatar">
                    <img src="/img/logo.webp" alt="Logo">
                </div>
                <div class="pmec-chat-title">
                    <h4>PhenikaaMec AI</h4>
                    <p>Trợ lý sức khỏe thông minh</p>
                </div>
            </div>
            <button class="pmec-chat-close" onclick="toggleChat()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="pmec-chat-body" id="pmecChatBody">
            <div class="pmec-chat-msg bot">
                <div class="pmec-chat-bubble">
                    Xin chào! Tôi là PhenikaaMec AI - trợ lý y tế chuyên về Da liễu. Tôi có thể giúp gì cho bạn?
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pmec-chat-footer">
            <form id="pmecChatForm" onsubmit="sendChatMessage(event)">
                <div class="pmec-chat-image-preview-container" id="pmecChatImgPreviewContainer">
                    <img id="pmecChatImgPreview" src="" alt="Preview">
                    <i class="bi bi-x-circle-fill pmec-chat-remove-img" onclick="removeChatImage()"></i>
                </div>
                
                <div class="pmec-chat-input-wrapper">
                    <!-- Hidden file input -->
                    <input type="file" id="pmecChatImage" accept="image/jpeg, image/png, image/jpg, image/webp" style="display: none;" onchange="previewChatImage(this)">
                    
                    <button type="button" id="pmecChatClearBtn" class="pmec-chat-attach-btn border-0 bg-transparent" title="Xóa lịch sử trò chuyện">
                        <i class="bi bi-eraser-fill"></i>
                    </button>
                    
                    <label for="pmecChatImage" class="pmec-chat-attach-btn mb-0" title="Đính kèm hình ảnh (Max 5MB)">
                        <i class="bi bi-paperclip"></i>
                    </label>
                    
                    <input type="text" id="pmecChatInput" placeholder="Nhập tin nhắn..." autocomplete="off">
                    
                    <button type="submit" class="pmec-chat-send-btn" id="pmecChatSendBtn">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button class="pmec-chat-toggle" id="pmecChatToggle" onclick="toggleChat()">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

<script>
    const chatWindow = document.getElementById('pmecChatWindow');
    const chatBody = document.getElementById('pmecChatBody');
    const chatInput = document.getElementById('pmecChatInput');
    const chatImageInput = document.getElementById('pmecChatImage');
    const imgPreviewContainer = document.getElementById('pmecChatImgPreviewContainer');
    const imgPreview = document.getElementById('pmecChatImgPreview');
    const sendBtn = document.getElementById('pmecChatSendBtn');
    
    let isChatOpen = false;

    function toggleChat() {
        isChatOpen = !isChatOpen;
        if (isChatOpen) {
            chatWindow.classList.add('open');
            document.getElementById('pmecChatToggle').innerHTML = '<i class="bi bi-x-lg"></i>';
            setTimeout(() => {
                chatInput.focus();
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 300);
        } else {
            chatWindow.classList.remove('open');
            document.getElementById('pmecChatToggle').innerHTML = '<i class="bi bi-chat-dots-fill"></i>';
        }
    }

    // Load history from localStorage
    window.addEventListener('DOMContentLoaded', () => {
        const savedHtml = localStorage.getItem('pmec_chat_html');
        const savedTime = localStorage.getItem('pmec_chat_time');
        
        if (savedHtml && savedTime) {
            // Check if older than 2 hours (2 * 60 * 60 * 1000 ms)
            if (Date.now() - parseInt(savedTime) < 7200000) {
                chatBody.innerHTML = savedHtml;
            } else {
                // Expired, clear frontend and backend history
                clearChatHistory(false);
            }
        }
    });

    function saveChatHistoryToLocal() {
        localStorage.setItem('pmec_chat_html', chatBody.innerHTML);
        localStorage.setItem('pmec_chat_time', Date.now().toString());
    }

    document.getElementById('pmecChatClearBtn').addEventListener('click', function() {
        clearChatHistory(true);
    });

    async function clearChatHistory(notifyUser = true) {
        if (notifyUser && !confirm("Bạn có chắc chắn muốn xóa toàn bộ lịch sử trò chuyện?")) return;

        // Reset frontend
        chatBody.innerHTML = `
            <div class="pmec-chat-msg bot">
                <div class="pmec-chat-bubble">
                    Lịch sử đã được dọn dẹp. Tôi có thể giúp gì cho bạn?
                </div>
            </div>
        `;
        localStorage.removeItem('pmec_chat_html');
        localStorage.removeItem('pmec_chat_time');

        // Reset backend session
        try {
            const token = document.querySelector('meta[name="csrf-token"]') 
                          ? document.querySelector('meta[name="csrf-token"]').content 
                          : '{{ csrf_token() }}';
                          
            await fetch("{{ route('chatbot.clear') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
        } catch (e) {
            console.error("Failed to clear backend session", e);
        }
    }

    function previewChatImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            if (file.size > 5 * 1024 * 1024) {
                alert("Vui lòng chọn ảnh nhỏ hơn 5MB.");
                removeChatImage();
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreviewContainer.style.display = 'flex';
                chatInput.focus();
            }
            reader.readAsDataURL(file);
        }
    }

    function removeChatImage() {
        chatImageInput.value = '';
        imgPreview.src = '';
        imgPreviewContainer.style.display = 'none';
    }

    function formatBotMessage(text) {
        // Convert Markdown bold (**text**) to HTML <strong>text</strong>
        let formatted = text.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");
        
        // Convert Markdown lists (- item) to HTML <ul><li>item</li></ul>
        if (formatted.includes('- ')) {
            let lines = formatted.split('\n');
            let inList = false;
            let result = '';
            
            for (let i = 0; i < lines.length; i++) {
                let line = lines[i].trim();
                if (line.startsWith('- ')) {
                    if (!inList) {
                        result += '<ul>';
                        inList = true;
                    }
                    result += '<li>' + line.substring(2) + '</li>';
                } else {
                    if (inList) {
                        result += '</ul>';
                        inList = false;
                    }
                    result += line + '<br>';
                }
            }
            if (inList) result += '</ul>';
            return result;
        }
        
        return formatted.replace(/\n/g, '<br>');
    }

    async function sendChatMessage(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        const hasFile = chatImageInput.files.length > 0;
        
        if (!message && !hasFile) return;

        // Xây dựng tin nhắn user để hiển thị
        let userHtml = `<div class="pmec-chat-msg user"><div class="pmec-chat-bubble">`;
        if (hasFile) {
            userHtml += `<img src="${imgPreview.src}" class="pmec-chat-img-preview" alt="User Image">`;
        }
        if (message) {
            userHtml += `<div>${message}</div>`;
        }
        userHtml += `</div></div>`;
        
        chatBody.insertAdjacentHTML('beforeend', userHtml);
        chatBody.scrollTop = chatBody.scrollHeight;
        saveChatHistoryToLocal();

        // Lấy dữ liệu file trước khi clear form
        const formData = new FormData();
        if (message) formData.append('message', message);
        if (hasFile) formData.append('image', chatImageInput.files[0]);

        // Clear input form
        chatInput.value = '';
        removeChatImage();
        sendBtn.disabled = true;

        // Thêm Typing Indicator
        const typingId = 'typing-' + Date.now();
        const typingHtml = `
            <div class="pmec-chat-msg bot" id="${typingId}">
                <div class="pmec-chat-bubble">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', typingHtml);
        chatBody.scrollTop = chatBody.scrollHeight;

        try {
            const token = document.querySelector('meta[name="csrf-token"]') 
                          ? document.querySelector('meta[name="csrf-token"]').content 
                          : '{{ csrf_token() }}';

            const response = await fetch("{{ route('chatbot.send') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                body: formData // FormData tự động set Content-Type là multipart/form-data
            });

            const data = await response.json();
            
            // Xóa Typing Indicator
            document.getElementById(typingId).remove();

            // Hiển thị phản hồi
            const botResponse = data.message || "Lỗi phản hồi từ hệ thống.";
            const formattedResponse = formatBotMessage(botResponse);
            
            const botHtml = `
                <div class="pmec-chat-msg bot">
                    <div class="pmec-chat-bubble">
                        ${formattedResponse}
                    </div>
                </div>
            `;
            chatBody.insertAdjacentHTML('beforeend', botHtml);
            saveChatHistoryToLocal();
            
        } catch (error) {
            console.error(error);
            document.getElementById(typingId)?.remove();
            
            const errorHtml = `
                <div class="pmec-chat-msg bot">
                    <div class="pmec-chat-bubble" style="color: #ef4444;">
                        Xin lỗi, đã có lỗi kết nối. Vui lòng thử lại sau!
                    </div>
                </div>
            `;
            chatBody.insertAdjacentHTML('beforeend', errorHtml);
        } finally {
            sendBtn.disabled = false;
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }
</script>
