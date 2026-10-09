<!-- AyoSilat Connection Status Indicator -->
<style>
    .ayosilat-conn-banner {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 6px 16px;
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        transition: all 0.3s ease;
    }
    .ayosilat-conn-banner.status-disconnected {
        background-color: #dc3545;
        color: #ffffff;
        animation: ayosilat-pulse 2s infinite ease-in-out;
    }
    .ayosilat-conn-banner.status-connecting {
        background-color: #fd7e14;
        color: #ffffff;
    }
    .ayosilat-conn-banner.status-connected {
        background-color: #198754;
        color: #ffffff;
    }
    @keyframes ayosilat-pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.85;
        }
    }
</style>

<div id="ayosilat-connection-banner" class="ayosilat-conn-banner" role="alert" aria-live="assertive">
    <span id="ayosilat-connection-text">&#9888; Layanan Notif Terputus &mdash; Memeriksa koneksi arena...</span>
</div>

<script src="{{ asset('assets/js/connection-monitor.js') }}"></script>
