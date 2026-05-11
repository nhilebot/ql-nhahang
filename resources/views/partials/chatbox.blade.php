{{-- resources/views/partials/chatbox.blade.php --}}
{{-- Nhúng vào layout chính: @include('partials.chatbox') --}}

<style>
/* ── Chatbox floating button & panel ── */
:root {
    --cb-primary: #D4AF37; /* Màu vàng Gold sang trọng */
    --cb-primary-dark: #B5952F;
    --cb-bg: #ffffff;
    --cb-text-main: #2d3748;
    --cb-text-light: #718096;
    --cb-msg-bot: #f8f9fa;
    --cb-msg-user: #D4AF37;
    --cb-font: 'Helvetica Neue', Arial, sans-serif; /* Bạn có thể đổi sang font web đang dùng */
}

#cb-toggle {
    position: fixed; bottom: 30px; right: 30px; z-index: 9990;
    width: 60px; height: 60px; border-radius: 50%;
    background: linear-gradient(135deg, var(--cb-primary), var(--cb-primary-dark));
    border: none; cursor: pointer; 
    box-shadow: 0 8px 24px rgba(212, 175, 55, 0.4);
    display: flex; align-items: center; justify-content: center;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}
#cb-toggle:hover { 
    transform: translateY(-5px) scale(1.05); 
    box-shadow: 0 12px 28px rgba(212, 175, 55, 0.6); 
}
#cb-toggle svg { width: 30px; height: 30px; fill: #fff; }

#cb-panel {
    position: fixed; bottom: 100px; right: 30px; z-index: 9991;
    width: 380px; max-height: 600px; height: 80vh;
    background: var(--cb-bg); border-radius: 20px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    display: flex; flex-direction: column;
    transform: scale(0.95) translateY(20px); opacity: 0; pointer-events: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
    font-family: var(--cb-font);
}
#cb-panel.open { transform: scale(1) translateY(0); opacity: 1; pointer-events: auto; }

/* Header */
#cb-header {
    background: #1a1a1a; /* Nền header đen nhám */
    color: #fff; padding: 18px 20px;
    display: flex; align-items: center; gap: 12px;
    border-bottom: 2px solid var(--cb-primary);
}
#cb-header .cb-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: rgba(255,255,255,0.1);
    display: flex; align-items: center; justify-content: center; font-size: 22px;
    border: 1px solid rgba(212, 175, 55, 0.5);
}
#cb-header .cb-info { display: flex; flex-direction: column; }
#cb-header .cb-title { font-weight: 600; font-size: 1.1rem; letter-spacing: 0.5px; }
#cb-header .cb-sub   { font-size: 0.8rem; opacity: 0.7; font-weight: 300;}
#cb-close {
    margin-left: auto; background: transparent;
    border: none; color: #fff; border-radius: 50%; width: 32px; height: 32px;
    cursor: pointer; font-size: 20px; display: flex;
    align-items: center; justify-content: center; opacity: 0.6; transition: opacity 0.2s;
}
#cb-close:hover { opacity: 1; background: rgba(255,255,255,0.1); }

/* Messages Area */
#cb-messages {
    flex: 1; overflow-y: auto; padding: 20px; display: flex;
    flex-direction: column; gap: 16px; scroll-behavior: smooth;
    background-color: #fafafa;
}
#cb-messages::-webkit-scrollbar { width: 6px; }
#cb-messages::-webkit-scrollbar-track { background: transparent; }
#cb-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.cb-msg { max-width: 85%; display: flex; flex-direction: column; gap: 4px; animation: fadeIn 0.3s ease-out forwards; opacity: 0; transform: translateY(10px); }
@keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

.cb-msg.bot  { align-self: flex-start; }
.cb-msg.user { align-self: flex-end; }

.cb-bubble {
    padding: 12px 16px; font-size: 0.95rem; line-height: 1.5;
    word-break: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    white-space: pre-wrap; /* Quan trọng: Giữ lại dấu xuống dòng */
}
.cb-msg.bot  .cb-bubble { 
    background: var(--cb-msg-bot); color: var(--cb-text-main); 
    border-radius: 18px 18px 18px 4px; border: 1px solid #eee;
}
.cb-msg.user .cb-bubble { 
    background: linear-gradient(135deg, var(--cb-primary), var(--cb-primary-dark)); 
    color: #fff; border-radius: 18px 18px 4px 18px; 
}

/* Thêm style cho thẻ strong trong bot bubble */
.cb-msg.bot .cb-bubble strong {
    font-weight: 700;
    color: var(--cb-primary-dark);
}

/* Typing indicator */
.cb-typing .cb-bubble { display: flex; align-items: center; gap: 6px; padding: 14px 18px; }
.cb-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--cb-text-light); animation: cb-bounce 1.4s infinite ease-in-out both; }
.cb-dot:nth-child(1) { animation-delay: -0.32s; }
.cb-dot:nth-child(2) { animation-delay: -0.16s; }
@keyframes cb-bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

/* Quick replies */
#cb-quick { display: flex; gap: 8px; flex-wrap: nowrap; overflow-x: auto; padding: 10px 20px; background: #fafafa; border-top: 1px solid #eee; }
#cb-quick::-webkit-scrollbar { display: none; } /* Ẩn scrollbar ngang cho mượt */
.cb-quick-btn {
    flex: 0 0 auto; /* Tránh bị co lại */
    font-size: 0.85rem; padding: 8px 14px; border-radius: 20px;
    border: 1px solid var(--cb-primary); color: var(--cb-primary-dark); background: transparent;
    cursor: pointer; transition: all 0.2s ease; font-weight: 500;
}
.cb-quick-btn:hover { background: var(--cb-primary); color: #fff; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2); }

/* Input */
#cb-input-row {
    display: flex; gap: 12px; padding: 16px 20px;
    background: #fff; box-shadow: 0 -5px 15px rgba(0,0,0,0.02);
}
#cb-input {
    flex: 1; border: 1px solid #e2e8f0; border-radius: 24px;
    padding: 12px 18px; font-size: 0.95rem; outline: none;
    transition: all 0.2s; background: #f8f9fa; color: var(--cb-text-main);
}
#cb-input:focus { border-color: var(--cb-primary); background: #fff; box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
#cb-input::placeholder { color: #a0aec0; }

#cb-send {
    width: 46px; height: 46px; border-radius: 50%; border: none; cursor: pointer;
    background: var(--cb-primary); display: flex;
    align-items: center; justify-content: center; flex-shrink: 0;
    transition: all 0.2s; box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
}
#cb-send:hover { background: var(--cb-primary-dark); transform: scale(1.05); }
#cb-send:disabled { background: #cbd5e1; box-shadow: none; cursor: not-allowed; transform: none; }
#cb-send svg { width: 20px; height: 20px; fill: #fff; margin-left: 2px; /* Căn chỉnh nhẹ icon send */ }
</style>

{{-- Toggle button --}}
<button id="cb-toggle" title="Trợ lý AI nhà hàng" aria-label="Mở chatbot">
    <svg viewBox="0 0 24 24"><path d="M20 2H4C2.9 2 2 2.9 2 4v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 10H6v-2h12v2zm0-3H6V7h12v2z"/></svg>
</button>

{{-- Chat panel --}}
<div id="cb-panel" role="dialog" aria-label="Chatbot trợ lý">
    <div id="cb-header">
                            <img src="{{ asset('images/logo.png') }}" alt="Món Việt Logo" style="height: 50px; width: auto; filter: drop-shadow(0px 0px 2px rgba(0,0,0,0.5)); margin-right: -10px;">
        <div>
            <div class="cb-title">Trợ lý Nhà hàng</div>
            <div class="cb-sub">Powered by AI</div>
        </div>
        <button id="cb-close" aria-label="Đóng">✕</button>
    </div>

    <div id="cb-messages" aria-live="polite"></div>

    <div id="cb-quick">
        <button class="cb-quick-btn" data-q="Xem thực đơn hôm nay">🍽 Thực đơn</button>
        <button class="cb-quick-btn" data-q="Đặt bàn như thế nào?">📅 Đặt bàn</button>
        <!-- <button class="cb-quick-btn" data-q="Giờ mở cửa của nhà hàng?">🕐 Giờ mở cửa</button> -->
        <button class="cb-quick-btn" data-q="Liên hệ nhà hàng">📞 Liên hệ</button>
    </div>

    <div id="cb-input-row">
        <input id="cb-input" type="text" placeholder="Nhập câu hỏi…" maxlength="500" autocomplete="off"/>
        <button id="cb-send" aria-label="Gửi">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

<script>
(function () {
    const panel    = document.getElementById('cb-panel');
    const toggle   = document.getElementById('cb-toggle');
    const closeBtn = document.getElementById('cb-close');
    const msgs     = document.getElementById('cb-messages');
    const input    = document.getElementById('cb-input');
    const sendBtn  = document.getElementById('cb-send');
    const ENDPOINT = '{{ route("chatbot.ask", [], false) }}';
    const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    let history = [];   // [{role:'user'|'model', text:'...'}]
    let busy    = false;

    // Helpers
    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // 🌟 ĐÃ SỬA HÀM NÀY ĐỂ XỬ LÝ IN ĐẬM VÀ XUỐNG DÒNG 🌟
    function addMsg(role, text) {
        const wrap = document.createElement('div');
        wrap.className = 'cb-msg ' + role;
        
        let formattedText = escHtml(text);
        
        // Nếu là tin nhắn của bot, thực hiện format Markdown cơ bản
        if (role === 'bot') {
            // Chuyển **chữ in đậm** thành thẻ <strong>
            formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            // Đổi dấu * đầu dòng thành dấu chấm tròn (•)
            formattedText = formattedText.replace(/(^|\n)\* /g, '$1• ');
        }

        wrap.innerHTML = '<div class="cb-bubble">' + formattedText + '</div>';
        msgs.appendChild(wrap);
        msgs.scrollTop = msgs.scrollHeight;
        return wrap;
    }

    function addTyping() {
        const wrap = document.createElement('div');
        wrap.className = 'cb-msg bot cb-typing';
        wrap.innerHTML = '<div class="cb-bubble"><span class="cb-dot"></span><span class="cb-dot"></span><span class="cb-dot"></span></div>';
        msgs.appendChild(wrap);
        msgs.scrollTop = msgs.scrollHeight;
        return wrap;
    }

    // Toggle open/close
    toggle.addEventListener('click', function () {
        const isOpen = panel.classList.toggle('open');
        if (isOpen && msgs.children.length === 0) {
            addMsg('bot', 'Xin chào! 👋 Tôi là trợ lý AI của nhà hàng. Tôi có thể giúp bạn về thực đơn, đặt bàn và nhiều thông tin khác. Bạn cần hỗ trợ gì ạ?');
        }
    });
    closeBtn.addEventListener('click', function () { panel.classList.remove('open'); });

    // Quick replies
    document.querySelectorAll('.cb-quick-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!busy) send(btn.dataset.q);
        });
    });

    // Enter key
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey && !busy) send(input.value);
    });
    sendBtn.addEventListener('click', function () { if (!busy) send(input.value); });

    async function send(text) {
        text = (text || '').trim();
        if (!text) return;
        busy = true;
        sendBtn.disabled = true;
        input.value = '';

        addMsg('user', text);
        const typing = addTyping();

        history.push({ role: 'user', text: text });

        try {
            const res = await fetch(ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  CSRF,
                    'Accept':        'application/json',
                },
                body: JSON.stringify({ message: text, history: history.slice(-10) }),
            });

            const raw = await res.text();
            let data = {};

            try {
                data = raw ? JSON.parse(raw) : {};
            } catch (parseErr) {
                data = {
                    error: 'Máy chủ trả về dữ liệu không hợp lệ. Vui lòng thử lại sau.'
                };
            }

            typing.remove();

            const reply = data.reply || data.error?.message || data.error || 'Có lỗi xảy ra, vui lòng thử lại.';
            addMsg('bot', reply);
            history.push({ role: 'model', text: reply });

            // Giữ lịch sử tối đa 20 lượt
            if (history.length > 20) history = history.slice(-20);
        } catch (err) {
            typing.remove();
            addMsg('bot', '⚠️ Không thể kết nối. Vui lòng kiểm tra mạng và thử lại.');
        }

        busy = false;
        sendBtn.disabled = false;
        input.focus();
    }
})();
</script>