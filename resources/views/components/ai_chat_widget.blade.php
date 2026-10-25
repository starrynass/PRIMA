<!-- Widget Chat Floating Modern & Premium -->
<div id="ai-support-widget" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <style>
        .ai-pulse-glow {
            box-shadow: 0 8px 24px rgba(122, 28, 56, 0.35), 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ai-pulse-glow:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 28px rgba(122, 28, 56, 0.45), 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .ai-pulse-glow:active {
            transform: translateY(0) scale(0.98);
        }
        .ai-chat-card {
            display: none;
            width: 380px;
            height: 520px;
            max-width: calc(100vw - 32px);
            max-height: calc(100vh - 110px);
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.22), 0 0 1px 1px rgba(0, 0, 0, 0.05);
            flex-direction: column;
            overflow: hidden;
            position: absolute;
            bottom: 68px;
            right: 0;
            animation: aiSlideUp 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            border: 1px solid rgba(122, 28, 56, 0.1);
        }
        @keyframes aiSlideUp {
            from { opacity: 0; transform: translateY(16px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .ai-chat-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .ai-chat-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .ai-chat-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
        .ai-chat-input-focus:focus {
            border-color: #7A1C38 !important;
            box-shadow: 0 0 0 3px rgba(122, 28, 56, 0.12) !important;
        }
        .ai-typing-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #7A1C38;
            opacity: 0.6;
            animation: aiTyping 1.4s infinite ease-in-out both;
        }
        .ai-typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .ai-typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes aiTyping {
            0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>

    <!-- Tombol Mengapung (Floating Trigger) -->
    <button id="ai-toggle-btn" class="ai-pulse-glow" style="background: linear-gradient(135deg, #7A1C38 0%, #992347 100%); color: white; border: none; border-radius: 50px; padding: 11px 22px; cursor: pointer; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 9px; letter-spacing: 0.2px;">
        <span style="display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; background: rgba(255,255,255,0.18); border-radius: 50%;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </span>
        <span>Tanya AI Support</span>
        <span style="display: inline-block; width: 8px; height: 8px; background: #10b981; border-radius: 50%; border: 2px solid #7A1C38;" title="AI Online"></span>
    </button>

    <!-- Box Chat Panel -->
    <div id="ai-chat-box" class="ai-chat-card">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #7A1C38 0%, #911F41 100%); color: white; padding: 16px 18px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 11px;">
                <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 14px; letter-spacing: 0.2px; line-height: 1.2;">PRIMA AI Assistant</div>
                    <div style="font-size: 11px; opacity: 0.85; display: flex; align-items: center; gap: 5px; margin-top: 2px;">
                        <span style="width: 6px; height: 6px; background: #34d399; border-radius: 50%; display: inline-block;"></span>
                        <span>Siap menganalisis data Anda</span>
                    </div>
                </div>
            </div>
            <button id="ai-close-btn" style="background: rgba(255,255,255,0.12); border: none; color: white; width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <!-- Ruang Pesan -->
        <div id="ai-chat-messages" class="ai-chat-scrollbar" style="flex: 1; padding: 18px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 12px; font-size: 13.5px; line-height: 1.55;">
            <div style="background: #ffffff; color: #1e293b; padding: 12px 15px; border-radius: 16px 16px 16px 4px; max-width: 85%; align-self: flex-start; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
                Halo! Saya Asisten AI PRIMA. Ada informasi pegawai, penilaian, atau periode kinerja yang bisa saya bantu carikan hari ini?
            </div>
        </div>

        <!-- Input Form -->
        <div style="padding: 12px 14px; background: #ffffff; border-top: 1px solid #f1f5f9; display: flex; gap: 8px; align-items: center;">
            <input type="text" id="ai-chat-input" class="ai-chat-input-focus" placeholder="Tanya nilai, verifikasi, atau data pegawai..." style="flex: 1; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 12px; outline: none; font-size: 13.5px; transition: all 0.2s; background: #f8fafc;">
            <button id="ai-send-btn" style="background: linear-gradient(135deg, #7A1C38 0%, #992347 100%); color: white; border: none; width: 40px; height: 40px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 8px rgba(122, 28, 56, 0.25); transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="transform: translate(1px, -1px);">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
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
        const isHidden = chatBox.style.display === 'none' || chatBox.style.display === '';
        chatBox.style.display = isHidden ? 'flex' : 'none';
        if (isHidden) {
            setTimeout(() => chatInput.focus(), 150);
        }
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

        appendBubble('User', text, 'linear-gradient(135deg, #7A1C38 0%, #911F41 100%)', 'flex-end', '#ffffff', '16px 16px 4px 16px');
        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        const loadingId = appendTypingIndicator();
        chatMessages.scrollTop = chatMessages.scrollHeight;

        try {
            const response = await fetch("{{ route('ai.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            });
            const data = await response.json();

            const loadingEl = document.getElementById(loadingId);
            if (loadingEl) loadingEl.remove();

            appendBubble('AI', data.reply || 'Tidak ada balasan dari sistem.', '#ffffff', 'flex-start', '#1e293b', '16px 16px 16px 4px', true);
        } catch (error) {
            const loadingEl = document.getElementById(loadingId);
            if (loadingEl) loadingEl.remove();

            appendBubble('AI', 'Maaf, terjadi gangguan saat menghubungi server.', '#fee2e2', 'flex-start', '#991b1b', '16px 16px 16px 4px');
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendBubble(sender, text, bgColor, align, textColor = '#1e293b', borderRadius = '14px', withShadow = false) {
        const div = document.createElement('div');
        const id = 'msg-' + Math.random().toString(36).substr(2, 9);
        div.id = id;
        
        let shadowStyle = withShadow ? 'box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;' : 'border: 1px solid rgba(0,0,0,0.04);';
        
        // Render baris baru jika ada
        const formattedText = text.replace(/\n/g, '<br>');
        
        div.style.cssText = `background: ${bgColor}; color: ${textColor}; padding: 11px 15px; border-radius: ${borderRadius}; max-width: 84%; align-self: ${align}; word-break: break-word; line-height: 1.5; ${shadowStyle}`;
        div.innerHTML = formattedText;
        chatMessages.appendChild(div);
        return id;
    }

    function appendTypingIndicator() {
        const div = document.createElement('div');
        const id = 'typing-' + Math.random().toString(36).substr(2, 9);
        div.id = id;
        div.style.cssText = 'background: #ffffff; padding: 12px 16px; border-radius: 16px 16px 16px 4px; align-self: flex-start; display: flex; align-items: center; gap: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;';
        div.innerHTML = `
            <span style="font-size: 12px; color: #64748b; margin-right: 4px;">Sedang berpikir</span>
            <span class="ai-typing-dot"></span>
            <span class="ai-typing-dot"></span>
            <span class="ai-typing-dot"></span>
        `;
        chatMessages.appendChild(div);
        return id;
    }
</script>