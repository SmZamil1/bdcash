<style>
    .ai-chat-wrapper {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        font-family: 'Poppins', sans-serif;
    }

    /* Floating Button */
    .ai-chat-btn {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .ai-chat-btn:hover {
        transform: scale(1.1) rotate(5deg);
    }

    .ai-chat-btn img {
        width: 35px;
        filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));
    }

    /* Pulse Effect */
    .ai-chat-btn::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: 50%;
        z-index: -1;
        animation: ai-pulse 2s infinite;
        opacity: 0.5;
    }

    @keyframes ai-pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    /* Chat Window */
    .ai-chat-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 320px;
        height: 450px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: translateY(20px) scale(0.9);
        opacity: 0;
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-origin: bottom right;
    }

    .ai-chat-window.active {
        transform: translateY(0) scale(1);
        opacity: 1;
        pointer-events: auto;
    }

    /* Header */
    .ai-chat-header {
        padding: 20px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ai-avatar-small {
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-chat-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
    }

    .ai-chat-header p {
        margin: 0;
        font-size: 11px;
        opacity: 0.8;
    }

    /* Body */
    .ai-chat-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: rgba(255, 255, 255, 0.5);
    }

    .message {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
        font-size: 13px;
        line-height: 1.4;
        position: relative;
        animation: message-slide 0.3s ease-out;
    }

    @keyframes message-slide {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message.ai {
        background: #f1f5f9;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 2px;
    }

    .message.user {
        background: #a855f7;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 2px;
    }

    /* Footer */
    .ai-chat-footer {
        padding: 15px;
        background: white;
        display: flex;
        gap: 8px;
    }

    .ai-chat-input {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 8px 15px;
        font-size: 13px;
        outline: none;
        transition: border 0.3s;
    }

    .ai-chat-input:focus {
        border-color: #a855f7;
    }

    .ai-send-btn {
        width: 35px;
        height: 35px;
        background: #a855f7;
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
    }

    .ai-send-btn:hover {
        background: #9333ea;
        transform: scale(1.05);
    }

    /* Typing Indicator */
    .typing {
        display: flex;
        gap: 4px;
        padding: 10px 15px;
        background: #f1f5f9;
        border-radius: 15px;
        align-self: flex-start;
        display: none;
    }

    .dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: bounce 1.3s infinite;
    }

    .dot:nth-child(2) { animation-delay: 0.15s; }
    .dot:nth-child(3) { animation-delay: 0.3s; }

    @keyframes bounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-4px); }
    }
</style>

<div class="ai-chat-wrapper">
    <!-- Chat Window -->
    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <div class="ai-avatar-small">
                <img src="https://cdn-icons-png.flaticon.com/128/8943/8943377.png" width="22" alt="AI">
            </div>
            <div style="flex: 1;">
                <h4>Investment Advisor</h4>
                <p>Always online to help you</p>
            </div>
            <button onclick="clearAiChat()" style="background:none; border:none; color:white; cursor:pointer; opacity:0.7; transition:0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                <i class="las la-sync-alt"></i>
            </button>
        </div>
        
        <div class="ai-chat-body" id="aiChatBody">
            <div class="message ai">
                Hello <strong>{{ auth()->user()->username }}</strong>! 👋 I'm your AI Investment Advisor. How can I help you with your financial goals today?
            </div>
            
            <div class="typing" id="aiTyping">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        <div class="ai-chat-footer">
            <input type="text" class="ai-chat-input" id="aiChatInput" placeholder="Ask anything...">
            <button class="ai-send-btn" onclick="sendAiMessage()">
                <i class="las la-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- Toggle Button -->
    <div class="ai-chat-btn" onclick="toggleAiChat()">
        <img src="https://cdn-icons-png.flaticon.com/128/8943/8943377.png" alt="AI">
    </div>
</div>

<script>
    function toggleAiChat() {
        const window = document.getElementById('aiChatWindow');
        window.classList.toggle('active');
    }

    function clearAiChat() {
        const body = document.getElementById('aiChatBody');
        const typing = document.getElementById('aiTyping');
        // Keep only the first message (the greeting)
        const messages = body.querySelectorAll('.message');
        for (let i = 1; i < messages.length; i++) {
            messages[i].remove();
        }
        body.scrollTop = 0;
    }

    function sendAiMessage() {
        const input = document.getElementById('aiChatInput');
        const body = document.getElementById('aiChatBody');
        const typing = document.getElementById('aiTyping');
        const message = input.value.trim();

        if (message === '') return;

        // Add User Message
        const userDiv = document.createElement('div');
        userDiv.className = 'message user';
        userDiv.textContent = message;
        body.insertBefore(userDiv, typing);
        
        input.value = '';
        body.scrollTop = body.scrollHeight;

        // Show Typing
        typing.style.display = 'flex';
        body.scrollTop = body.scrollHeight;

        // Real AI Request
        fetch("{{ route('user.ai.chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            typing.style.display = 'none';
            const aiDiv = document.createElement('div');
            aiDiv.className = 'message ai';
            
            if (data.success) {
                // Use innerHTML to allow the AI to send formatted text (bold, etc)
                aiDiv.innerHTML = data.message.replace(/\n/g, '<br>');
            } else {
                aiDiv.innerHTML = "I'm having a bit of trouble connecting to my brain right now. Please try again! 🤖";
            }
            
            body.insertBefore(aiDiv, typing);
            body.scrollTop = body.scrollHeight;
        })
        .catch(error => {
            typing.style.display = 'none';
            const aiDiv = document.createElement('div');
            aiDiv.className = 'message ai';
            aiDiv.innerHTML = "I'm offline for a quick reboot. Be right back! 🔌";
            body.insertBefore(aiDiv, typing);
            body.scrollTop = body.scrollHeight;
        });
    }

    // Enter key support
    document.getElementById('aiChatInput').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendAiMessage();
        }
    });
</script>
