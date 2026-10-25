<!-- Widget Chat Floating -->
<div id="ai-support-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: sans-serif;">
    <!-- Tombol Mengapung -->
    <button id="ai-toggle-btn" style="background: #7A1C38; color: white; border: none; border-radius: 30px; padding: 12px 20px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        💬 Tanya AI Support
    </button>

    <!-- Box Chat Panel -->
    <div id="ai-chat-box" style="display: none; width: 330px; height: 450px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); flex-direction: column; overflow: hidden; position: absolute; bottom: 60px; right: 0;">
        <!-- Header -->
        <div style="background: #7A1C38; color: white; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
            <span>AI Dashboard Assistant</span>
            <button id="ai-close-btn" style="background: none; border: none; color: white; cursor: pointer; font-size: 18px;">&times;</button>
        </div>
        
        <!-- Ruang Pesan -->
        <div id="ai-chat-messages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
            <div style="background: #e2e3e5; padding: 8px 12px; border-radius: 8px; max-width: 80%; align-self: flex-start;">
                Halo! Ada yang bisa saya bantu terkait dashboard Anda?
            </div>
        </div>

        <!-- Input Form -->
        <div style="padding: 10px; background: white; border-top: 1px solid #dee2e6; display: flex; gap: 8px;">
            <input type="text" id="ai-chat-input" placeholder="Ketik pesan..." style="flex: 1; padding: 8px 12px; border: 1px solid #ced4da; border-radius: 6px; outline: none;">
            <button id="ai-send-btn" style="background: #7A1C38; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer;">Kirim</button>
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('ai-toggle-btn');
    const closeBtn = document.getElementById('ai-close-btn');
    const chatBox = document.getElementById('ai-chat-box');
    const sendBtn = document.getElementById('ai-send-btn');
    const chatInput = document.getElementById('ai-chat-input');
    const chatMessages = document.getElementById('ai-chat-messages');

    toggleBtn.addEventListener('click', () => {
        chatBox.style.display = chatBox.style.display === 'flex' ? 'none' : 'flex';
    });
    closeBtn.addEventListener('click', () => {
        chatBox.style.display = 'none';
    });

    sendBtn.addEventListener('click', sendUserMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendUserMessage();
    });

    async function sendUserMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendBubble('User', text, '#d1e7dd', 'flex-end');
        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        const loadingId = appendBubble('AI', 'Sedang mengetik...', '#e2e3e5', 'flex-start');

        try {
            // Mengirim request AJAX ke route Laravel dengan menyertakan CSRF Token
            const response = await fetch("{{ route('ai.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            });
            const data = await response.json();

            document.getElementById(loadingId).remove();
            appendBubble('AI', data.reply, '#ffffff', 'flex-start');
        } catch (error) {
            document.getElementById(loadingId).remove();
            appendBubble('AI', 'Gagal terhubung ke server.', '#f8d7da', 'flex-start');
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendBubble(sender, text, bgColor, align) {
        const div = document.createElement('div');
        const id = 'msg-' + Math.random().toString(36).substr(2, 9);
        div.id = id;
        div.style.cssText = `background: ${bgColor}; padding: 8px 12px; border-radius: 8px; max-width: 80%; align-self: ${align}; word-break: break-word; border: 1px solid rgba(0,0,0,0.05);`;
        div.innerText = text;
        chatMessages.appendChild(div);
        return id;
    }
</script>