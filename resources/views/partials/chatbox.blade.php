<style>
/* ========== CHATBOT WIDGET ========== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.pmec-chat-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: 'Inter', 'Poppins', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

/* ---- Toggle Button ---- */
.pmec-chat-toggle {
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0f4c9e, #1a8fe3);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    cursor: pointer;
    box-shadow: 0 6px 24px rgba(26,143,227,0.45);
    transition: all 0.3s ease;
    border: none;
    outline: none;
    position: relative;
    z-index: 10;
    animation: chatPulse 2.5s infinite;
}

.pmec-chat-toggle:hover {
    transform: scale(1.07);
    box-shadow: 0 8px 32px rgba(26,143,227,0.6);
    animation: none;
}

@keyframes chatPulse {
    0%   { box-shadow: 0 0 0 0 rgba(26,143,227,0.5); }
    70%  { box-shadow: 0 0 0 16px rgba(26,143,227,0); }
    100% { box-shadow: 0 0 0 0 rgba(26,143,227,0); }
}

/* ---- Chat Window ---- */
.pmec-chat-window {
    width: 380px;
    height: 580px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 16px 60px rgba(0,0,0,0.18);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: absolute;
    bottom: 80px;
    right: 0;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: bottom right;
    transform: scale(0.8) translateY(20px);
    opacity: 0;
    pointer-events: none;
}

.pmec-chat-window.open {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: auto;
}

/* Expanded mode */
.pmec-chat-window.expanded {
    width: 520px;
    height: 700px;
}

/* ---- Header ---- */
.pmec-chat-header {
    background: linear-gradient(135deg, #0f4c9e 0%, #1a8fe3 100%);
    color: #fff;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.pmec-chat-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pmec-chat-avatar-sm {
    width: 38px;
    height: 38px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.pmec-chat-avatar-sm img {
    width: 26px;
    height: 26px;
    object-fit: contain;
}

.pmec-chat-title h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.3;
}

.pmec-chat-title p {
    margin: 0;
    font-size: 11px;
    opacity: 0.78;
    display: flex;
    align-items: center;
    gap: 4px;
}

.pmec-online-dot {
    width: 7px;
    height: 7px;
    background: #4ade80;
    border-radius: 50%;
    display: inline-block;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.pmec-chat-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pmec-header-btn {
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 13px;
}

.pmec-header-btn:hover {
    background: rgba(255,255,255,0.28);
}

/* ---- Welcome Screen ---- */
.pmec-welcome-screen {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px 24px;
    background: linear-gradient(180deg, #eaf3fb 0%, #f8fbff 60%, #ffffff 100%);
    text-align: center;
}

.pmec-welcome-logo-wrap {
    width: 90px;
    height: 90px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 28px rgba(26,143,227,0.15);
    margin-bottom: 20px;
    border: 2px solid rgba(26,143,227,0.1);
}

.pmec-welcome-logo-wrap img {
    width: 62px;
    height: 62px;
    object-fit: contain;
}

.pmec-welcome-screen h3 {
    font-size: 20px;
    font-weight: 700;
    color: #0f4c9e;
    margin: 0 0 8px;
}

.pmec-welcome-screen p {
    font-size: 13.5px;
    color: #64748b;
    margin: 0 0 28px;
    line-height: 1.5;
}

.pmec-start-btn {
    background: linear-gradient(135deg, #0f4c9e, #1a8fe3);
    color: #fff;
    border: none;
    padding: 13px 32px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 4px 20px rgba(26,143,227,0.4);
    letter-spacing: 0.3px;
}

.pmec-start-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(26,143,227,0.5);
}

/* ---- Chat Area ---- */
.pmec-chat-area {
    display: none;
    flex: 1;
    flex-direction: column;
    overflow: hidden;
}

.pmec-chat-area.active {
    display: flex;
}

.pmec-chat-body {
    flex: 1;
    padding: 16px 14px;
    overflow-y: auto;
    background: #f6f9fc;
    display: flex;
    flex-direction: column;
    gap: 12px;
    scroll-behavior: smooth;
}

.pmec-chat-body::-webkit-scrollbar {
    width: 4px;
}

.pmec-chat-body::-webkit-scrollbar-track {
    background: transparent;
}

.pmec-chat-body::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}

/* ---- Messages ---- */
.pmec-chat-msg {
    max-width: 82%;
    display: flex;
    flex-direction: column;
    animation: msgFade 0.3s ease;
}

@keyframes msgFade {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.pmec-chat-msg.bot { align-self: flex-start; }
.pmec-chat-msg.user { align-self: flex-end; }

.pmec-chat-bubble {
    padding: 11px 15px;
    font-size: 13.5px;
    line-height: 1.55;
    word-wrap: break-word;
}

.pmec-chat-msg.bot .pmec-chat-bubble {
    background: #fff;
    color: #1e293b;
    border-radius: 4px 16px 16px 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e8edf5;
}

.pmec-chat-msg.user .pmec-chat-bubble {
    background: linear-gradient(135deg, #1a8fe3, #0f4c9e);
    color: #fff;
    border-radius: 16px 4px 16px 16px;
}

.pmec-chat-msg.bot .pmec-chat-bubble strong {
    font-weight: 600;
    color: #0f4c9e;
}

.pmec-chat-msg.bot .pmec-chat-bubble ul {
    margin: 8px 0 0;
    padding-left: 18px;
}

.pmec-chat-msg.bot .pmec-chat-bubble li {
    margin-bottom: 3px;
}

.pmec-chat-img-preview {
    max-width: 100%;
    border-radius: 10px;
    margin-top: 6px;
    display: block;
}

/* ---- Typing Indicator ---- */
.typing-indicator {
    display: flex;
    gap: 4px;
    align-items: center;
    padding: 2px 0;
}

.typing-dot {
    width: 7px;
    height: 7px;
    background: #1a8fe3;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
}

/* ---- Footer / Input ---- */
.pmec-chat-footer {
    padding: 10px 14px 8px;
    background: #fff;
    border-top: 1px solid #e8edf5;
    flex-shrink: 0;
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
    border-radius: 5px;
    object-fit: cover;
}

.pmec-chat-remove-img {
    color: #ef4444;
    cursor: pointer;
    font-size: 16px;
}

.pmec-chat-input-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f0f4f8;
    border-radius: 26px;
    padding: 6px 6px 6px 14px;
    border: 1.5px solid transparent;
    transition: border-color 0.2s;
}

.pmec-chat-input-wrapper:focus-within {
    border-color: #1a8fe3;
    background: #fff;
}

.pmec-chat-input-wrapper input[type="text"] {
    flex: 1;
    border: none;
    background: transparent;
    padding: 5px 0;
    font-size: 13.5px;
    outline: none;
    font-family: 'Inter', sans-serif;
    color: #1e293b;
}

.pmec-chat-input-wrapper input::placeholder {
    color: #94a3b8;
}

.pmec-chat-action-btns {
    display: flex;
    align-items: center;
    gap: 2px;
}

.pmec-chat-attach-btn {
    color: #94a3b8;
    font-size: 17px;
    cursor: pointer;
    padding: 5px 6px;
    border: none;
    background: transparent;
    border-radius: 50%;
    transition: color 0.2s, background 0.2s;
    display: flex;
    align-items: center;
}

.pmec-chat-attach-btn:hover {
    color: #0f4c9e;
    background: rgba(15,76,158,0.08);
}

.pmec-chat-send-btn {
    background: linear-gradient(135deg, #1a8fe3, #0f4c9e);
    color: #fff;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
    flex-shrink: 0;
    box-shadow: 0 2px 10px rgba(26,143,227,0.35);
}

.pmec-chat-send-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 16px rgba(26,143,227,0.5);
}

.pmec-chat-send-btn:disabled {
    background: #cbd5e1;
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
}

/* ---- Footer Bar ---- */
.pmec-chat-footer-bar {
    text-align: center;
    font-size: 10.5px;
    color: #94a3b8;
    padding: 5px 0 2px;
    letter-spacing: 0.2px;
}

.pmec-chat-footer-bar span {
    font-weight: 600;
    color: #0f4c9e;
}

/* ---- Responsive ---- */
@media (max-width: 480px) {
    .pmec-chat-window {
        width: calc(100vw - 24px);
        height: calc(100vh - 100px);
        bottom: 80px;
        right: -8px;
        border-radius: 16px;
    }

    .pmec-chat-window.expanded {
        width: calc(100vw - 24px);
        height: calc(100vh - 80px);
    }
}

/* ---- Inline Booking Form ---- */
.pmec-booking-form {
    background: linear-gradient(135deg, #eef6ff 0%, #f0f8ff 100%);
    border: 1.5px solid #bbd6f5;
    border-radius: 14px;
    padding: 16px;
    margin-top: 8px;
    animation: msgFade 0.3s ease;
}

.pmec-booking-form h5 {
    font-size: 14px;
    font-weight: 700;
    color: #0f4c9e;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.pmec-booking-form .form-group {
    margin-bottom: 10px;
}

.pmec-booking-form label {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 3px;
    display: block;
}

.pmec-booking-form input,
.pmec-booking-form select {
    width: 100%;
    padding: 8px 10px;
    border: 1.5px solid #d1dce8;
    border-radius: 8px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: #1e293b;
    background: #fff;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.pmec-booking-form input:focus,
.pmec-booking-form select:focus {
    outline: none;
    border-color: #1a8fe3;
    box-shadow: 0 0 0 2px rgba(26,143,227,0.12);
}

.pmec-booking-form .form-row {
    display: flex;
    gap: 8px;
}

.pmec-booking-form .form-row .form-group {
    flex: 1;
}

.pmec-booking-submit {
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #0f4c9e, #1a8fe3);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s;
    margin-top: 4px;
    font-family: 'Inter', sans-serif;
}

.pmec-booking-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(26,143,227,0.4);
}

.pmec-booking-submit:disabled {
    background: #94a3b8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.pmec-slot-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin: 10px 0;
}

.pmec-slot-card {
    width: 100%;
    text-align: left;
    background: #fff;
    border: 1.5px solid #d7e6f7;
    border-radius: 10px;
    padding: 10px 12px;
    cursor: pointer;
    color: #1e293b;
    transition: all 0.2s;
    font-family: 'Inter', sans-serif;
}

.pmec-slot-card:hover,
.pmec-slot-card.selected {
    border-color: #1a8fe3;
    box-shadow: 0 4px 14px rgba(26,143,227,0.14);
}

.pmec-slot-card strong {
    display: block;
    color: #0f4c9e;
    font-size: 13px;
    margin-bottom: 3px;
}

.pmec-slot-card span {
    display: block;
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
}

.pmec-booking-note {
    font-size: 12px;
    color: #64748b;
    line-height: 1.45;
    margin: 6px 0 10px;
}

.pmec-booking-success {
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    border: 1.5px solid #86efac;
    border-radius: 14px;
    padding: 14px 16px;
    margin-top: 8px;
    animation: msgFade 0.3s ease;
    color: #166534;
    font-size: 13px;
    line-height: 1.5;
}

.pmec-booking-success i {
    color: #22c55e;
    font-size: 18px;
    margin-right: 6px;
}
</style>

<div class="pmec-chat-widget">
    <!-- Chat Window -->
    <div class="pmec-chat-window" id="pmecChatWindow">
        
        <!-- Header -->
        <div class="pmec-chat-header">
            <div class="pmec-chat-header-info">
                <div class="pmec-chat-avatar-sm">
                    <img src="/img/logo.webp" alt="PhenikaaMec Logo">
                </div>
                <div class="pmec-chat-title">
                    <h4>Trò chuyện cùng</h4>
                    <p style="font-size:13px;font-weight:600;opacity:1;">PhenikaaMec</p>
                </div>
            </div>
            <div class="pmec-chat-header-actions">
                <button class="pmec-header-btn" id="pmecExpandBtn" onclick="toggleExpand()" title="Phóng to">
                    <i class="bi bi-arrows-angle-expand" id="pmecExpandIcon"></i>
                </button>
                <button class="pmec-header-btn" onclick="toggleChat()" title="Đóng">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <!-- Welcome Screen -->
        <div class="pmec-welcome-screen" id="pmecWelcomeScreen">
            <div class="pmec-welcome-logo-wrap">
                <img src="/img/logo.webp" alt="PhenikaaMec">
            </div>
            <h3>PhenikaaMec</h3>
            <p>Xin chào! Tôi có thể giúp gì cho bạn?</p>
            <button class="pmec-start-btn" onclick="startChat()">
                Bắt đầu trò chuyện!
            </button>
        </div>

        <!-- Chat Area (hidden until "Bắt đầu trò chuyện" clicked) -->
        <div class="pmec-chat-area" id="pmecChatArea">
            <!-- Body -->
            <div class="pmec-chat-body" id="pmecChatBody">
                <div class="pmec-chat-msg bot">
                    <div class="pmec-chat-bubble">
                        Xin chào! Tôi là <strong>PhenikaaMec AI</strong> - trợ lý y tế chuyên về Da liễu.<br>Tôi có thể giúp gì cho bạn?
                    </div>
                </div>
            </div>

            <!-- Footer Input -->
            <div class="pmec-chat-footer">
                <form id="pmecChatForm" onsubmit="sendChatMessage(event)">
                    <div class="pmec-chat-image-preview-container" id="pmecChatImgPreviewContainer">
                        <img id="pmecChatImgPreview" src="" alt="Preview">
                        <i class="bi bi-x-circle-fill pmec-chat-remove-img" onclick="removeChatImage()"></i>
                    </div>
                    <div class="pmec-chat-input-wrapper">
                        <input type="file" id="pmecChatImage" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;" onchange="previewChatImage(this)">
                        <input type="text" id="pmecChatInput" placeholder="Nhập tin nhắn của bạn..." autocomplete="off">
                        <div class="pmec-chat-action-btns">
                            <button type="button" id="pmecChatClearBtn" class="pmec-chat-attach-btn" title="Xóa lịch sử trò chuyện">
                                <i class="bi bi-eraser-fill"></i>
                            </button>
                            <label for="pmecChatImage" class="pmec-chat-attach-btn mb-0" title="Đính kèm ảnh (Max 5MB)">
                                <i class="bi bi-paperclip"></i>
                            </label>
                            <button type="submit" class="pmec-chat-send-btn" id="pmecChatSendBtn">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="pmec-chat-footer-bar">
                    1.0.5 · Powered by <span>XGeni</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Toggle Button -->
    <button class="pmec-chat-toggle" id="pmecChatToggle" onclick="toggleChat()">
        <i class="bi bi-chat-dots-fill" id="pmecToggleIcon"></i>
    </button>
</div>

<script>
    const chatWindow   = document.getElementById('pmecChatWindow');
    const chatBody     = document.getElementById('pmecChatBody');
    const chatInput    = document.getElementById('pmecChatInput');
    const chatImageInput = document.getElementById('pmecChatImage');
    const imgPreviewContainer = document.getElementById('pmecChatImgPreviewContainer');
    const imgPreview   = document.getElementById('pmecChatImgPreview');
    const sendBtn      = document.getElementById('pmecChatSendBtn');
    const welcomeScreen = document.getElementById('pmecWelcomeScreen');
    const chatArea     = document.getElementById('pmecChatArea');

    let isChatOpen = false;
    let isExpanded = false;
    let chatStarted = false;
    let lastClinicalMessage = '';
    let lastAiClinicalResponse = localStorage.getItem('pmec_last_ai_clinical_response') || '';

    function toggleChat() {
        isChatOpen = !isChatOpen;
        if (isChatOpen) {
            chatWindow.classList.add('open');
            document.getElementById('pmecToggleIcon').className = 'bi bi-x-lg';
            if (chatStarted) {
                setTimeout(() => {
                    chatInput.focus();
                    chatBody.scrollTop = chatBody.scrollHeight;
                }, 300);
            }
        } else {
            chatWindow.classList.remove('open');
            document.getElementById('pmecToggleIcon').className = 'bi bi-chat-dots-fill';
        }
    }

    function toggleExpand() {
        isExpanded = !isExpanded;
        chatWindow.classList.toggle('expanded', isExpanded);
        const icon = document.getElementById('pmecExpandIcon');
        icon.className = isExpanded ? 'bi bi-arrows-angle-contract' : 'bi bi-arrows-angle-expand';
    }

    function startChat() {
        chatStarted = true;
        welcomeScreen.style.display = 'none';
        chatArea.classList.add('active');
        setTimeout(() => {
            chatInput.focus();
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 200);

        // Load localStorage history if exists
        const savedHtml = localStorage.getItem('pmec_chat_html');
        const savedTime = localStorage.getItem('pmec_chat_time');
        if (savedHtml && savedTime) {
            if (Date.now() - parseInt(savedTime) < 7200000) {
                chatBody.innerHTML = savedHtml;
                hydrateBookingMemoryFromDom();
                refreshExistingBookingFormsFromMemory();
            } else {
                clearChatHistory(false);
            }
        }
    }

    function saveChatHistoryToLocal() {
        localStorage.setItem('pmec_chat_html', chatBody.innerHTML);
        localStorage.setItem('pmec_chat_time', Date.now().toString());
    }

    function isClinicalAiResponse(text) {
        const normalized = normalizeForMatch(text);
        if (!normalized) return false;

        const bookingOnlyKeywords = [
            'ai se tim bac si',
            'vui long dien thong tin',
            'dat lich thanh cong',
            'tim lich trong',
            'xac nhan dat lich',
            'he thong se tu tim'
        ];
        if (bookingOnlyKeywords.some(keyword => normalized.includes(keyword))) {
            return false;
        }

        return [
            'trieu chung',
            'tinh trang',
            'hinh anh',
            'chuyen khoa phu hop',
            'viem',
            'mun',
            'ngua',
            'do',
            'sung',
            'nam',
            'seo',
            'da'
        ].some(keyword => normalized.includes(keyword));
    }

    function rememberAiClinicalResponse(text) {
        const cleaned = cleanAiBookingText(text);
        if (!isClinicalAiResponse(cleaned)) return;

        lastAiClinicalResponse = cleaned;
        localStorage.setItem('pmec_last_ai_clinical_response', cleaned);
    }

    function hydrateBookingMemoryFromDom() {
        const botBubbles = Array.from(chatBody.querySelectorAll('.pmec-chat-msg.bot .pmec-chat-bubble'));
        for (let i = botBubbles.length - 1; i >= 0; i--) {
            const bubble = botBubbles[i];
            if (bubble.querySelector('.pmec-booking-form, .pmec-booking-success')) {
                continue;
            }

            const text = bubble.textContent || '';
            if (isClinicalAiResponse(text)) {
                rememberAiClinicalResponse(text);
                return;
            }
        }
    }

    function refreshExistingBookingFormsFromMemory() {
        const context = [
            lastAiClinicalResponse,
            lastClinicalMessage
        ].filter(Boolean).join('\n');

        if (!context) return;

        document.querySelectorAll('.pmec-booking-form').forEach((form, index) => {
            if (!form.id) {
                form.id = 'booking-restored-' + Date.now() + '-' + index;
            }

            const description = extractDescriptionFromAi(context, '', lastClinicalMessage);
            const descInput = form.querySelector('.booking-desc');
            if (descInput && !descInput.value.trim() && description) {
                descInput.value = description;
            }

            if (form.querySelector('.booking-specialty')) {
                loadDoctorsIntoForm(form.id, [context, description].filter(Boolean).join('\n'));
            }
        });
    }

    document.getElementById('pmecChatClearBtn').addEventListener('click', function() {
        clearChatHistory(true);
    });

    async function clearChatHistory(notifyUser = true) {
        if (notifyUser && !confirm("Bạn có chắc chắn muốn xóa toàn bộ lịch sử trò chuyện?")) return;

        chatBody.innerHTML = `
            <div class="pmec-chat-msg bot">
                <div class="pmec-chat-bubble">
                    Lịch sử đã được dọn dẹp. Tôi có thể giúp gì cho bạn? 😊
                </div>
            </div>
        `;
        localStorage.removeItem('pmec_chat_html');
        localStorage.removeItem('pmec_chat_time');
        localStorage.removeItem('pmec_last_ai_clinical_response');
        lastAiClinicalResponse = '';
        lastClinicalMessage = '';

        try {
            const token = document.querySelector('meta[name="csrf-token"]')
                          ? document.querySelector('meta[name="csrf-token"]').content
                          : '{{ csrf_token() }}';
            await fetch("{{ route('chatbot.clear') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token }
            });
        } catch (e) { console.error("Failed to clear backend session", e); }
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
            };
            reader.readAsDataURL(file);
        }
    }

    function removeChatImage() {
        chatImageInput.value = '';
        imgPreview.src = '';
        imgPreviewContainer.style.display = 'none';
    }

    function formatBotMessage(text) {
        // Remove [SHOW_BOOKING_FORM] marker from display text
        let cleaned = text.replace(/\[SHOW_BOOKING_FORM\]/g, '').trim();
        let formatted = cleaned.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        if (formatted.includes('- ')) {
            let lines = formatted.split('\n');
            let inList = false;
            let result = '';
            for (let i = 0; i < lines.length; i++) {
                let line = lines[i].trim();
                if (line.startsWith('- ')) {
                    if (!inList) { result += '<ul>'; inList = true; }
                    result += '<li>' + line.substring(2) + '</li>';
                } else {
                    if (inList) { result += '</ul>'; inList = false; }
                    if (line) result += line + '<br>';
                }
            }
            if (inList) result += '</ul>';
            return result;
        }
        return formatted.replace(/\n/g, '<br>');
    }

    function normalizeForMatch(text) {
        return (text || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/đ/g, 'd');
    }

    function escapeAttr(value) {
        return (value || '')
            .toString()
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function isShortBookingReply(text) {
        const normalized = normalizeForMatch(text).trim();
        return ['co', 'ok', 'oke', 'dat lich', 'muon dat lich', 'toi muon dat lich', 'vang'].includes(normalized);
    }

    function cleanAiBookingText(text) {
        return (text || '')
            .toString()
            .replace(/\[SHOW_BOOKING_FORM\]/g, '')
            .replace(/\*\*/g, '')
            .replace(/[`_>#]/g, '')
            .replace(/\r/g, '')
            .trim();
    }

    function compactBookingLine(line) {
        return (line || '')
            .replace(/^[\s\-•*\d.)]+/, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function extractDescriptionFromAi(botResponse, userMessage, fallbackText) {
        const cleaned = cleanAiBookingText(botResponse);
        const lines = cleaned.split('\n').map(compactBookingLine).filter(Boolean);
        const symptomKeywords = [
            'triệu chứng', 'tình trạng', 'hình ảnh', 'quan sát', 'nhận thấy',
            'biểu hiện', 'đỏ', 'sưng', 'ngứa', 'mụn', 'viêm', 'nám', 'sẹo',
            'bong tróc', 'mẩn', 'da'
        ];
        const skipKeywords = [
            'chuyên khoa phù hợp', 'bác sĩ', 'đặt lịch', 'phòng khám',
            'bạn có muốn', 'hệ thống sẽ', 'vui lòng điền', 'khuyên đi khám'
        ];

        const usefulLines = lines.filter(line => {
            const normalized = normalizeForMatch(line);
            if (skipKeywords.some(keyword => normalized.includes(normalizeForMatch(keyword)))) {
                return false;
            }

            return symptomKeywords.some(keyword => normalized.includes(normalizeForMatch(keyword)));
        });

        const description = usefulLines.slice(0, 2).join(' ');
        if (description) {
            return description.length > 220 ? description.slice(0, 217).trim() + '...' : description;
        }

        if (userMessage && !isShortBookingReply(userMessage)) {
            return userMessage;
        }

        const fallback = (fallbackText || '').trim();
        if (fallback && normalizeForMatch(fallback) !== 'da gui hinh anh can tu van') {
            return fallback;
        }

        const shortAiSummary = lines
            .filter(line => {
                const normalized = normalizeForMatch(line);
                return !skipKeywords.some(keyword => normalized.includes(normalizeForMatch(keyword)));
            })
            .slice(0, 2)
            .join(' ');

        return shortAiSummary.length > 220 ? shortAiSummary.slice(0, 217).trim() + '...' : shortAiSummary;
    }

    function inferSpecialtyFromContext(specialties, contextText) {
        const normalizedContext = normalizeForMatch(contextText);
        if (!normalizedContext) return '';

        const specialtyLine = (contextText || '').toString().match(/chuyên\s*khoa\s*(?:phù\s*hợp)?\s*:\s*([^\n.]+)/i);
        if (specialtyLine && specialtyLine[1]) {
            const normalizedLine = normalizeForMatch(specialtyLine[1]);
            const lineMatch = (specialties || []).find(specialty => {
                const normalizedSpecialty = normalizeForMatch(specialty);
                return normalizedSpecialty && normalizedLine.includes(normalizedSpecialty);
            });
            if (lineMatch) return lineMatch;
        }

        const exactMatch = (specialties || []).find(specialty => {
            const normalizedSpecialty = normalizeForMatch(specialty);
            return normalizedSpecialty && normalizedContext.includes(normalizedSpecialty);
        });
        if (exactMatch) return exactMatch;

        const keywordRules = [
            { keywords: ['mun', 'acne'], specialtyKeywords: ['mun', 'da lieu', 'tham my'] },
            { keywords: ['nam', 'tan nhang', 'sac to'], specialtyKeywords: ['nam', 'sac to', 'tham my', 'da lieu'] },
            { keywords: ['seo', 'tham'], specialtyKeywords: ['seo', 'tham', 'tham my', 'da lieu'] },
            { keywords: ['viem da', 'ngua', 'di ung', 'kich ung'], specialtyKeywords: ['viem', 'di ung', 'da lieu'] },
            { keywords: ['nam da dau', 'toc', 'rung toc'], specialtyKeywords: ['toc', 'da dau', 'da lieu'] },
            { keywords: ['do', 'sung', 'man', 'ngua', 'loet'], specialtyKeywords: ['viem', 'di ung', 'da lieu'] },
            { keywords: ['cham soc da', 'kiem tra da', 'tu van da'], specialtyKeywords: ['cham soc', 'da lieu'] },
        ];

        for (const rule of keywordRules) {
            if (!rule.keywords.some(keyword => normalizedContext.includes(normalizeForMatch(keyword)))) {
                continue;
            }

            const match = (specialties || []).find(specialty => {
                const normalizedSpecialty = normalizeForMatch(specialty);
                return rule.specialtyKeywords.some(keyword => normalizedSpecialty.includes(normalizeForMatch(keyword)));
            });

            if (match) return match;
        }

        const scored = (specialties || [])
            .map(specialty => {
                const normalizedSpecialty = normalizeForMatch(specialty);
                const tokens = normalizedSpecialty.split(/\s+/).filter(token => token.length > 2);
                const matchedTokens = tokens.filter(token => normalizedContext.includes(token)).length;
                return { specialty, score: tokens.length ? matchedTokens / tokens.length : 0 };
            })
            .sort((a, b) => b.score - a.score);

        return scored.length && scored[0].score >= 0.5 ? scored[0].specialty : '';
    }

    function buildBookingFormHtml(formId, context = {}) {
        const description = escapeAttr(context.description || '');
        return `
            <div class="pmec-booking-form" id="${formId}">
                <h5><i class="bi bi-calendar-check"></i> Đặt lịch khám nhanh</h5>
                <div class="form-group">
                    <label>Họ tên *</label>
                    <input type="text" class="booking-name" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="tel" class="booking-phone" placeholder="0912 345 678" required>
                </div>
                <div class="form-group">
                    <label>Bác sĩ *</label>
                    <select class="booking-doctor">
                        <option value="">Đang tải danh sách...</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ngày khám *</label>
                        <input type="date" class="booking-date" min="${getTomorrowDate()}">
                    </div>
                    <div class="form-group">
                        <label>Ca khám *</label>
                        <select class="booking-shift">
                            <option value="morning">Buổi sáng</option>
                            <option value="afternoon">Buổi chiều</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mô tả triệu chứng</label>
                    <input type="text" class="booking-desc" placeholder="VD: Mụn vùng trán 2 tuần...">
                </div>
                <button type="button" class="pmec-booking-submit" onclick="submitBookingForm('${formId}')">
                    <i class="bi bi-check-circle"></i> Xác nhận đặt lịch
                </button>
            </div>
        `;
    }

    function getTomorrowDate() {
        const d = new Date();
        d.setDate(d.getDate() + 1);
        return d.toISOString().split('T')[0];
    }

    async function loadDoctorsIntoForm(formId) {
        try {
            const res = await fetch("{{ route('chatbot.doctors') }}");
            const data = await res.json();
            const form = document.getElementById(formId);
            if (!form) return;
            const select = form.querySelector('.booking-doctor');
            select.innerHTML = '<option value="">-- Chọn bác sĩ --</option>';
            (data.doctors || []).forEach(doc => {
                const opt = document.createElement('option');
                opt.value = doc.id;
                opt.textContent = `BS. ${doc.name} — ${doc.specialty}`;
                select.appendChild(opt);
            });
        } catch (e) {
            console.error('Failed to load doctors', e);
        }
    }

    async function submitBookingForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const name = form.querySelector('.booking-name').value.trim();
        const phone = form.querySelector('.booking-phone').value.trim();
        const doctorId = form.querySelector('.booking-doctor').value;
        const date = form.querySelector('.booking-date').value;
        const shift = form.querySelector('.booking-shift').value;
        const desc = form.querySelector('.booking-desc').value.trim();

        if (!name || !phone || !doctorId || !date) {
            alert('Vui lòng điền đầy đủ thông tin bắt buộc (*).');
            return;
        }

        const btn = form.querySelector('.pmec-booking-submit');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang xử lý...';

        try {
            const token = document.querySelector('meta[name="csrf-token"]')
                          ? document.querySelector('meta[name="csrf-token"]').content
                          : '{{ csrf_token() }}';

            const res = await fetch("{{ route('chatbot.book') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name, phone, doctor_id: doctorId,
                    appointment_date: date, shift, description: desc
                })
            });

            const data = await res.json();

            // Replace form with success message
            form.outerHTML = `
                <div class="pmec-booking-success">
                    <i class="bi bi-check-circle-fill"></i>
                    ${data.message || 'Đặt lịch thành công!'}
                </div>
            `;

            // Also add as bot message in chat history
            saveChatHistoryToLocal();

        } catch (e) {
            console.error('Booking error', e);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Xác nhận đặt lịch';
            alert('Đặt lịch thất bại. Vui lòng thử lại!');
        }
    }

    function getTodayDate() {
        const d = new Date();
        return d.toISOString().split('T')[0];
    }

    function buildBookingFormHtml(formId, options = {}) {
        const description = escapeAttr(options.description || '');

        return `
            <div class="pmec-booking-form" id="${formId}">
                <h5><i class="bi bi-calendar-check"></i> Đặt lịch khám bằng AI</h5>
                <p class="pmec-booking-note">AI sẽ tìm bác sĩ còn lịch trống theo chuyên khoa và thời gian bạn mong muốn.</p>
                <div class="form-group">
                    <label>Họ tên *</label>
                    <input type="text" class="booking-name" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="tel" class="booking-phone" placeholder="0912 345 678" required>
                </div>
                <div class="form-group">
                    <label>Chuyên khoa/dịch vụ *</label>
                    <select class="booking-specialty">
                        <option value="">Đang tải chuyên khoa...</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tìm từ ngày</label>
                        <input type="date" class="booking-date" min="${getTodayDate()}" value="${getTodayDate()}">
                    </div>
                    <div class="form-group">
                        <label>Ca ưu tiên</label>
                        <select class="booking-shift">
                            <option value="">Sớm nhất có thể</option>
                            <option value="morning">Buổi sáng</option>
                            <option value="afternoon">Buổi chiều</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mô tả triệu chứng</label>
                    <input type="text" class="booking-desc" placeholder="VD: Mụn vùng trán 2 tuần..." value="${description}">
                </div>
                <button type="button" class="pmec-booking-submit booking-find" onclick="findAvailableSlots('${formId}')">
                    <i class="bi bi-search"></i> Tìm lịch trống
                </button>
                <div class="pmec-slot-list" style="display:none;"></div>
                <button type="button" class="pmec-booking-submit booking-confirm" style="display:none;" onclick="submitBookingForm('${formId}')">
                    <i class="bi bi-check-circle"></i> Xác nhận đặt lịch
                </button>
            </div>
        `;
    }

    async function loadDoctorsIntoForm(formId, contextText = '') {
        try {
            const res = await fetch("{{ route('chatbot.doctors') }}");
            const data = await res.json();
            const form = document.getElementById(formId);
            if (!form) return;

            const specialtySelect = form.querySelector('.booking-specialty');
            specialtySelect.innerHTML = '<option value="">-- Chọn chuyên khoa --</option>';
            (data.specialties || []).forEach(specialty => {
                const opt = document.createElement('option');
                opt.value = specialty;
                opt.textContent = specialty;
                specialtySelect.appendChild(opt);
            });

            const inferredSpecialty = inferSpecialtyFromContext(data.specialties || [], contextText);
            if (inferredSpecialty) {
                specialtySelect.value = inferredSpecialty;
            }
        } catch (e) {
            console.error('Failed to load specialties', e);
        }
    }

    async function findAvailableSlots(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const specialty = form.querySelector('.booking-specialty').value;
        const dateFrom = form.querySelector('.booking-date').value || getTodayDate();
        const preferredShift = form.querySelector('.booking-shift').value;
        const slotList = form.querySelector('.pmec-slot-list');
        const findBtn = form.querySelector('.booking-find');
        const confirmBtn = form.querySelector('.booking-confirm');

        if (!specialty) {
            alert('Vui lòng chọn chuyên khoa/dịch vụ.');
            return;
        }

        form.dataset.selectedSlot = '';
        confirmBtn.style.display = 'none';
        slotList.style.display = 'flex';
        slotList.innerHTML = '<div class="pmec-booking-note">Đang tìm lịch trống...</div>';
        findBtn.disabled = true;

        try {
            const params = new URLSearchParams({
                specialty: specialty,
                date_from: dateFrom
            });
            if (preferredShift) params.set('preferred_shift', preferredShift);

            const res = await fetch("{{ route('chatbot.available_slots') }}?" + params.toString(), {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            const slots = data.slots || [];

            if (!slots.length) {
                slotList.innerHTML = '<div class="pmec-booking-note">Chưa tìm thấy lịch trống phù hợp trong 14 ngày tới. Vui lòng đổi chuyên khoa, ngày hoặc ca ưu tiên.</div>';
                return;
            }

            slotList.innerHTML = slots.map((slot, index) => `
                <button type="button" class="pmec-slot-card" onclick="selectAiSlot('${formId}', ${index})">
                    <strong>BS. ${slot.doctor_name}</strong>
                    <span>${slot.specialty}</span>
                    <span>${slot.appointment_date} - ${slot.shift_label}</span>
                </button>
            `).join('');
            form.dataset.slots = JSON.stringify(slots);
        } catch (e) {
            console.error('Slot search error', e);
            slotList.innerHTML = '<div class="pmec-booking-note">Không thể tìm lịch trống lúc này. Vui lòng thử lại.</div>';
        } finally {
            findBtn.disabled = false;
        }
    }

    function selectAiSlot(formId, index) {
        const form = document.getElementById(formId);
        if (!form) return;

        const slots = JSON.parse(form.dataset.slots || '[]');
        const slot = slots[index];
        if (!slot) return;

        form.dataset.selectedSlot = JSON.stringify(slot);
        form.querySelectorAll('.pmec-slot-card').forEach((card, cardIndex) => {
            card.classList.toggle('selected', cardIndex === index);
        });
        form.querySelector('.booking-confirm').style.display = 'block';
    }

    async function submitBookingForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const name = form.querySelector('.booking-name').value.trim();
        const phone = form.querySelector('.booking-phone').value.trim();
        const desc = form.querySelector('.booking-desc').value.trim();
        const slot = JSON.parse(form.dataset.selectedSlot || 'null');

        if (!name || !phone || !slot) {
            alert('Vui lòng điền họ tên, số điện thoại và chọn một lịch trống.');
            return;
        }

        const btn = form.querySelector('.booking-confirm');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang đặt lịch...';

        try {
            const token = document.querySelector('meta[name="csrf-token"]')
                          ? document.querySelector('meta[name="csrf-token"]').content
                          : '{{ csrf_token() }}';

            const res = await fetch("{{ route('chatbot.book') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    phone,
                    doctor_id: slot.doctor_id,
                    appointment_date: slot.appointment_date,
                    shift: slot.shift,
                    description: desc
                })
            });

            const data = await res.json();

            if (!res.ok || data.success === false) {
                throw new Error(data.message || 'Đặt lịch thất bại.');
            }

            form.outerHTML = `
                <div class="pmec-booking-success">
                    <i class="bi bi-check-circle-fill"></i>
                    ${data.message || 'Đặt lịch thành công!'}
                </div>
            `;
            saveChatHistoryToLocal();
        } catch (e) {
            console.error('Booking error', e);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Xác nhận đặt lịch';
            alert(e.message || 'Đặt lịch thất bại. Vui lòng thử lại!');
        }
    }

    async function sendChatMessage(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        const hasFile = chatImageInput.files.length > 0;
        if (!message && !hasFile) return;

        if (message && !isShortBookingReply(message)) {
            lastClinicalMessage = message;
        } else if (hasFile && !lastClinicalMessage) {
            lastClinicalMessage = message || 'Đã gửi hình ảnh cần tư vấn';
        }

        let userHtml = `<div class="pmec-chat-msg user"><div class="pmec-chat-bubble">`;
        if (hasFile) userHtml += `<img src="${imgPreview.src}" class="pmec-chat-img-preview" alt="User Image">`;
        if (message) userHtml += `<div>${message}</div>`;
        userHtml += `</div></div>`;

        chatBody.insertAdjacentHTML('beforeend', userHtml);
        chatBody.scrollTop = chatBody.scrollHeight;
        saveChatHistoryToLocal();

        const formData = new FormData();
        if (message) formData.append('message', message);
        if (hasFile) formData.append('image', chatImageInput.files[0]);

        chatInput.value = '';
        removeChatImage();
        sendBtn.disabled = true;

        const typingId = 'typing-' + Date.now();
        chatBody.insertAdjacentHTML('beforeend', `
            <div class="pmec-chat-msg bot" id="${typingId}">
                <div class="pmec-chat-bubble">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
        `);
        chatBody.scrollTop = chatBody.scrollHeight;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')
                          ? document.querySelector('meta[name="csrf-token"]').content
                          : '{{ csrf_token() }}';

            const response = await fetch("{{ route('chatbot.send') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
                body: formData
            });

            const data = await response.json();
            document.getElementById(typingId)?.remove();

            const botResponse = data.message || "Lỗi phản hồi từ hệ thống.";
            const hasBookingForm = botResponse.includes('[SHOW_BOOKING_FORM]');
            const formattedResponse = formatBotMessage(botResponse);
            const memoryContext = [
                botResponse,
                lastAiClinicalResponse,
                lastClinicalMessage
            ].filter(Boolean).join('\n');

            let botHtml = `<div class="pmec-chat-msg bot"><div class="pmec-chat-bubble">${formattedResponse}</div>`;

            if (hasBookingForm) {
                const formId = 'booking-' + Date.now();
                const initialDescription = extractDescriptionFromAi(memoryContext, message, lastClinicalMessage);
                const bookingContext = [
                    botResponse,
                    lastAiClinicalResponse,
                    initialDescription,
                    message,
                    lastClinicalMessage
                ].filter(Boolean).join('\n');

                botHtml += buildBookingFormHtml(formId, {
                    description: initialDescription
                });
                // Load doctors after DOM update
                setTimeout(() => loadDoctorsIntoForm(formId, bookingContext), 100);
            } else {
                rememberAiClinicalResponse(botResponse);
            }

            botHtml += `</div>`;
            chatBody.insertAdjacentHTML('beforeend', botHtml);
            saveChatHistoryToLocal();

        } catch (error) {
            console.error(error);
            document.getElementById(typingId)?.remove();
            chatBody.insertAdjacentHTML('beforeend', `
                <div class="pmec-chat-msg bot">
                    <div class="pmec-chat-bubble" style="color:#ef4444;">
                        Xin lỗi, đã có lỗi kết nối. Vui lòng thử lại sau!
                    </div>
                </div>
            `);
        } finally {
            sendBtn.disabled = false;
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }

    // Auto-open if previously chatting (skip welcome)
    window.addEventListener('DOMContentLoaded', () => {
        const savedHtml = localStorage.getItem('pmec_chat_html');
        const savedTime = localStorage.getItem('pmec_chat_time');
        if (savedHtml && savedTime && Date.now() - parseInt(savedTime) < 7200000) {
            chatStarted = true;
            welcomeScreen.style.display = 'none';
            chatArea.classList.add('active');
            chatBody.innerHTML = savedHtml;
            hydrateBookingMemoryFromDom();
            refreshExistingBookingFormsFromMemory();
        }
    });
</script>
