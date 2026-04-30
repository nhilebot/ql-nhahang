{{-- resources/views/partials/chatbox.blade.php --}}
{{-- Nhúng vào layout chính: @include('partials.chatbox') --}}

<style>
/* ── Chatbox floating button & panel ── */
#cb-toggle {
    position: fixed; bottom: 24px; right: 24px; z-index: 9990;
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, #ff6b35, #ff9a3c);
    border: none; cursor: pointer; box-shadow: 0 4px 20px rgba(255,107,53,.45);
    display: flex; align-items: center; justify-content: center;
    transition: transform .2s, box-shadow .2s;
}
#cb-toggle:hover { transform: scale(1.08); box-shadow: 0 6px 28px rgba(255,107,53,.55); }
#cb-toggle svg { width: 26px; height: 26px; fill: #fff; }

#cb-panel {
    position: fixed; bottom: 90px; right: 24px; z-index: 9991;
    width: 340px; max-height: 520px;
    background: #fff; border-radius: 18px;
    box-shadow: 0 12px 48px rgba(0,0,0,.18);
    display: flex; flex-direction: column;
    transform: scale(.9) translateY(12px); opacity: 0; pointer-events: none;
    transition: transform .22s cubic-bezier(.34,1.56,.64,1), opacity .18s;
}
#cb-panel.open { transform: scale(1) translateY(0); opacity: 1; pointer-events: auto; }

/* Header */
#cb-header {
    background: linear-gradient(135deg, #ff6b35, #ff9a3c);
    color: #fff; padding: 14px 16px; border-radius: 18px 18px 0 0;
    display: flex; align-items: center; gap: 10px;
}
#cb-header .cb-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
#cb-header .cb-title { font-weight: 700; font-size: .95rem; line-height: 1.2; }
#cb-header .cb-sub   { font-size: .75rem; opacity: .85; }
#cb-close {
    margin-left: auto; background: rgba(255,255,255,.2);
    border: none; color: #fff; border-radius: 50%; width: 28px; height: 28px;
    cursor: pointer; font-size: 16px; line-height: 1; display: flex;
    align-items: center; justify-content: center;
}

/* Messages */
#cb-messages {
    flex: 1; overflow-y: auto; padding: 14px 12px; display: flex;
    flex-direction: column; gap: 10px; scroll-behavior: smooth;
}
#cb-messages::-webkit-scrollbar { width: 4px; }
#cb-messages::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

.cb-msg { max-width: 82%; display: flex; flex-direction: column; gap: 2px; }
.cb-msg.bot  { align-self: flex-start; }
.cb-msg.user { align-self: flex-end; }

.cb-bubble {
    padding: 9px 13px; border-radius: 16px; font-size: .87rem; line-height: 1.5;
    word-break: break-word;
}
.cb-msg.bot  .cb-bubble { background: #f3f4f6; color: #1f2937; border-bottom-left-radius: 4px; }
.cb-msg.user .cb-bubble { background: linear-gradient(135deg,#ff6b35,#ff9a3c); color:#fff; border-bottom-right-radius: 4px; }

/* Typing indicator */
.cb-typing .cb-bubble { display: flex; align-items: center; gap: 5px; padding: 12px 16px; }
.cb-dot { width: 7px; height: 7px; border-radius: 50%; background: #9ca3af; animation: cb-bounce .9s infinite; }
.cb-dot:nth-child(2) { animation-delay: .15s; }
.cb-dot:nth-child(3) { animation-delay: .30s; }
@keyframes cb-bounce { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-5px)} }

/* Quick replies */
#cb-quick { display: flex; gap: 6px; flex-wrap: wrap; padding: 0 12px 8px; }
.cb-quick-btn {
    font-size: .78rem; padding: 5px 11px; border-radius: 20px;
    border: 1.5px solid #ff6b35; color: #ff6b35; background: #fff;
    cursor: pointer; transition: all .15s;
}
.cb-quick-btn:hover { background: #ff6b35; color: #fff; }

/* Input */
#cb-input-row {
    display: flex; gap: 8px; padding: 10px 12px;
    border-top: 1px solid #f1f5f9;
}
#cb-input {
    flex: 1; border: 1.5px solid #e2e8f0; border-radius: 10px;
    padding: 8px 12px; font-size: .88rem; outline: none;
    transition: border .18s; font-family: inherit;
}
#cb-input:focus { border-color: #ff6b35; }
#cb-send {
    width: 38px; height: 38px; border-radius: 10px; border: none; cursor: pointer;
    background: linear-gradient(135deg,#ff6b35,#ff9a3c); display: flex;
    align-items: center; justify-content: center; flex-shrink: 0;
    transition: opacity .15s;
}
#cb-send:disabled { opacity: .5; cursor: default; }
#cb-send svg { width: 17px; height: 17px; fill: #fff; }
</style>

{{-- Toggle button --}}
<button id="cb-toggle" title="Trợ lý AI nhà hàng" aria-label="Mở chatbot">
    <svg viewBox="0 0 24 24"><path d="M20 2H4C2.9 2 2 2.9 2 4v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 10H6v-2h12v2zm0-3H6V7h12v2z"/></svg>
</button>

{{-- Chat panel --}}
<div id="cb-panel" role="dialog" aria-label="Chatbot trợ lý">
    <div id="cb-header">
        <div class="cb-avatar">🍜</div>
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
        <button class="cb-quick-btn" data-q="Giờ mở cửa của nhà hàng?">🕐 Giờ mở cửa</button>
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

    function addMsg(role, text) {
        const wrap = document.createElement('div');
        wrap.className = 'cb-msg ' + role;
        wrap.innerHTML = '<div class="cb-bubble">' + escHtml(text) + '</div>';
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