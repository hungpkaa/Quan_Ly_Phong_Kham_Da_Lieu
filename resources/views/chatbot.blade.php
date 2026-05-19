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
                    <p class="chat-bubble bot">Xin chào! Tôi có thể giúp gì cho bạn? 😊</p>
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
    chatBox.innerHTML += `<div class="user-message"><p class="chat-bubble user">${message}</p></div>`;

    // Hiệu ứng "Chatbot đang gõ..."
    let typingIndicator = document.createElement("div");
    typingIndicator.classList.add("bot-message");
    typingIndicator.innerHTML = `<p class="chat-bubble bot typing">Chatbot đang trả lời...</p>`;
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

           let botResponse = data.message 
    ? data.message.replace(/\n/g, "<br>")
    : "Lỗi phản hồi từ chatbot.";

            // Xóa hiệu ứng "đang gõ..."
            chatBox.removeChild(typingIndicator);

            // Hiển thị tin nhắn chatbot
            chatBox.innerHTML += `<div class="bot-message"><p class="chat-bubble bot">${botResponse}</p></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
       .catch(error => {
    console.error("Lỗi gửi tin nhắn:", error);

    if (chatBox.contains(typingIndicator)) {
        chatBox.removeChild(typingIndicator);
    }

    chatBox.innerHTML += `
        <div class="bot-message">
            <p class="chat-bubble bot">
                Không thể kết nối tới AI.
            </p>
        </div>
    `;
});

    // Xóa input
    document.getElementById("user-message").value = "";
}
</script>
@endsection