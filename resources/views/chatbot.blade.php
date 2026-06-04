@extends('layouts.app')

@section('title', 'Tư Vấn Trực Tiếp')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-3">🤖 Tư Vấn Trực Tiếp với PhenikaaMec AI</h2>
    <div class="card shadow-lg">
        <div class="card-body">
            <div id="chat-box" class="border p-3 rounded"
                style="height: 400px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column;">
                <div class="bot-message">
                    <div class="chat-bubble bot">Xin chào! Tôi có thể giúp gì cho bạn? 😊</div>
                </div>
            </div>
            <div class="mt-3 d-flex">
                <input type="text" id="user-message" class="form-control me-2" placeholder="Nhập tin nhắn..."
                    onkeypress="handleKeyPress(event)">
                <button class="btn btn-primary" onclick="sendMessage()">Gửi</button>
            </div>
        </div>
    </div>
</div>

<style>
/* 🌟 Hiệu ứng tin nhắn */
#chat-box {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.chat-bubble {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 15px;
    font-size: 14px;
    display: inline-block;
    word-wrap: break-word;
}

/* 💬 Tin nhắn của Chatbot */
.bot-message {
    text-align: left;
}

.bot {
    background-color: #e1f5fe;
    color: #01579b;
}

/* 🧑‍💻 Tin nhắn của Người dùng */
.user-message {
    text-align: right;
}

.user {
    background-color: #bbdefb;
    color: #0d47a1;
}

/* 🌟 Hiệu ứng gõ tin nhắn */
.typing {
    font-style: italic;
    color: gray;
}
</style>

<script>
const chatHistory = @json($chatHistory ?? []);

document.addEventListener("DOMContentLoaded", function() {
    let chatBox = document.getElementById("chat-box");
    if (chatHistory.length > 0) {
        // Xóa tin nhắn chào mừng mặc định nếu có lịch sử cũ
        chatBox.innerHTML = '';
        chatHistory.forEach(msg => {
            if (msg.role === 'user') {
                chatBox.innerHTML += `<div class="user-message"><div class="chat-bubble user">${msg.content}</div></div>`;
            } else if (msg.role === 'assistant') {
                let formatted = formatMessage(msg.content);
                chatBox.innerHTML += `<div class="bot-message"><div class="chat-bubble bot">${formatted}</div></div>`;
            }
        });
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});

function formatMessage(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // In đậm
        .replace(/\*(.*?)\*/g, '<em>$1</em>') // In nghiêng
        .replace(/\n/g, '<br>') // Xuống dòng
        .replace(/- /g, '• '); // Dấu chấm tròn cho danh sách
}

function handleKeyPress(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}

function sendMessage() {
    let message = document.getElementById("user-message").value;
    let chatBox = document.getElementById("chat-box");

    if (message.trim() === "") return;

    // Hiển thị tin nhắn người dùng
    chatBox.innerHTML += `<div class="user-message"><div class="chat-bubble user">${message}</div></div>`;

    // Hiệu ứng "Chatbot đang gõ..."
    let typingIndicator = document.createElement("div");
    typingIndicator.classList.add("bot-message");
    typingIndicator.innerHTML = `<div class="chat-bubble bot typing">Chatbot đang trả lời...</div>`;
    chatBox.appendChild(typingIndicator);
    chatBox.scrollTop = chatBox.scrollHeight;

    // Gửi tin nhắn đến API Laravel
    fetch("{{ route('chatbot.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log("API Response:", data);

            let botResponse = data.message || "Lỗi phản hồi từ chatbot.";
            
            // Format Markdown cơ bản (Bold, Newline, List)
            let formattedResponse = formatMessage(botResponse);

            // Xóa hiệu ứng "đang gõ..."
            chatBox.removeChild(typingIndicator);

            // Hiển thị tin nhắn chatbot
            chatBox.innerHTML += `<div class="bot-message"><div class="chat-bubble bot">${formattedResponse}</div></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            console.error("Lỗi gửi tin nhắn:", error);
            chatBox.removeChild(typingIndicator);
            chatBox.innerHTML +=
                `<div class="bot-message"><p class="chat-bubble bot">Lỗi khi gửi tin nhắn. Vui lòng thử lại!</p></div>`;
        });

    // Xóa input
    document.getElementById("user-message").value = "";
}
</script>
@endsection