<link href="{{ asset('assets/global/css/iziToast.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/global/css/iziToast_custom.css') }}" rel="stylesheet">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>
<style>
    @charset "utf-8";

    /* Core Notification Animations */
    @keyframes go1268368563 { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes go1310225428 { from { transform: scale(0) rotate(45deg); opacity: 0; } to { transform: scale(1) rotate(45deg); opacity: 1; } }
    @keyframes go651618207 { 0% { height: 0; width: 0; opacity: 0; } 40% { height: 0; width: 6px; opacity: 1; } 100% { opacity: 1; height: 10px; } }
    @keyframes go2264125279 { from { transform: scale(0) rotate(45deg); opacity: 0; } to { transform: scale(1) rotate(45deg); opacity: 1; } }
    @keyframes go3020080000 { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    @keyframes go463499852 { from { transform: scale(0) rotate(90deg); opacity: 0; } to { transform: scale(1) rotate(90deg); opacity: 1; } }
    @keyframes go3223188581 { 0% { transform: translate3d(0, -200%, 0) scale(0.6); opacity: 0.5; } 100% { transform: translate3d(0, 0, 0) scale(1); opacity: 1; } }
    @keyframes go502128938 { 0% { transform: translate3d(0, 0, 0) scale(1); opacity: 1; } 100% { transform: translate3d(0, -150%, 0) scale(0.6); opacity: 0; } }

    /* Container Styles */
    .go4109123758 {
        z-index: 20000 !important; /* Extremely high to stay above everything */
        position: fixed; 
        top: 20px;
        right: 20px;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    /* Individual Notification Styles */
    .go2072408551 {
        display: flex;
        align-items: center;
        background: #fff;
        color: #363636;
        line-height: 1.3;
        will-change: transform;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1), 0 3px 3px rgba(0, 0, 0, 0.05);
        max-width: 350px;
        pointer-events: auto;
        padding: 8px 10px;
        border-radius: 8px;
        margin-bottom: 10px;
        animation: go3223188581 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .go685806154 { position: relative; display: flex; justify-content: center; align-items: center; min-width: 20px; min-height: 20px; }
    .go1858758034 { width: 12px; height: 12px; border: 2px solid #e0e0e0; border-radius: 100%; border-right-color: #616161; animation: go1268368563 1s linear infinite; }
    .go3958317564 { margin: 4px 10px; flex: 1 1 auto; white-space: pre-line; font-size: 14px; font-weight: 500; }
    .go1579819456 { position: absolute; }
    
    /* Success Icon */
    .go2344853693 { width: 20px; height: 20px; border-radius: 10px; background: #61d345; transform: rotate(45deg); animation: go1310225428 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards 100ms; opacity: 0; }
    .go2344853693:after { content: ""; position: absolute; border-right: 2px solid #fff; border-bottom: 2px solid #fff; bottom: 6px; left: 6px; height: 10px; width: 6px; animation: go651618207 0.2s ease-out forwards 200ms; opacity: 0; }

    /* Error Icon */
    .go2534082608 { width: 20px; height: 20px; border-radius: 10px; background: #ff4b4b; transform: rotate(45deg); animation: go2264125279 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards 100ms; opacity: 0; }
    .go2534082608:after, .go2534082608:before { content: ""; position: absolute; background: #fff; bottom: 9px; left: 4px; height: 2px; width: 12px; border-radius: 3px; animation: go3020080000 0.15s ease-out forwards 150ms; opacity: 0; }
    .go2534082608:before { transform: rotate(90deg); animation: go463499852 0.15s ease-out forwards 180ms; }
</style>

<script>
    "use strict";
    const notifications = @json(session('notify', []));
    const errors = @json(@$errors ? collect($errors->all())->unique() : []);

    const triggerCustomNotification = (status, message) => {
        let container = document.querySelector('.go4109123758');
        if (!container) {
            container = document.createElement('div');
            container.className = 'go4109123758';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'go2072408551';
        
        const isError = status === 'error';
        toast.innerHTML = `
            <div class="go685806154">
                <div class="go1858758034"></div>
                <div class="go1579819456">
                    <div class="${isError ? 'go2534082608' : 'go2344853693'}"></div>
                </div>
            </div>
            <div role="status" aria-live="polite" class="go3958317564">${message}</div>
        `;
        
        container.appendChild(toast);

        // Auto-remove with exit animation
        setTimeout(() => {
            toast.style.animation = 'go502128938 0.4s ease-in forwards';
            setTimeout(() => {
                toast.remove();
                if (container.childNodes.length === 0) container.remove();
            }, 400);
        }, 4000);
    };

    if (notifications.length) {
        notifications.forEach(element => triggerCustomNotification(element[0], element[1]));
    }

    if (errors.length) {
        errors.forEach(error => triggerCustomNotification('error', error));
    }

    function notify(status, message) {
        if (typeof message == 'string') {
            triggerCustomNotification(status, message);
        } else {
            message.forEach(val => triggerCustomNotification(status, val));
        }
    }
</script>
<script  type="module" src="{{asset('assets/global/js/main.js')}}"></script>
