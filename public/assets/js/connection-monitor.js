/**
 * Connection Monitor for AyoSilat
 * Handles dual-layer connectivity detection:
 * 1. Native browser offline/online events
 * 2. Pusher / Soketi WebSocket connection states
 */
(function () {
    'use strict';

    function initConnectionMonitor() {
        const banner = document.getElementById('ayosilat-connection-banner');
        const textSpan = document.getElementById('ayosilat-connection-text');
        if (!banner || !textSpan) return;

        let hideTimeout = null;
        let isCurrentlyOffline = false;

        function setStatus(status, message) {
            clearTimeout(hideTimeout);
            banner.style.display = 'flex';

            if (status === 'disconnected') {
                isCurrentlyOffline = true;
                banner.className = 'ayosilat-conn-banner status-disconnected';
                textSpan.innerHTML = message || '&#9888; Layanan Notif Terputus &mdash; Memeriksa koneksi arena...';
            } else if (status === 'connecting') {
                isCurrentlyOffline = true;
                banner.className = 'ayosilat-conn-banner status-connecting';
                textSpan.innerHTML = message || '&#128260; Menyambungkan ke layanan notif...';
            } else if (status === 'connected') {
                if (isCurrentlyOffline) {
                    isCurrentlyOffline = false;
                    banner.className = 'ayosilat-conn-banner status-connected';
                    textSpan.innerHTML = message || '&#9989; Terhubung ke Layanan Notif';
                    hideTimeout = setTimeout(function () {
                        banner.style.display = 'none';
                    }, 2500);
                } else {
                    banner.style.display = 'none';
                }
            }
        }

        // Layer 1: Native browser network events
        window.addEventListener('offline', function () {
            setStatus('disconnected', '&#9888; Jaringan Offline &mdash; Periksa sambungan kabel LAN / Wi-Fi arena');
        });

        window.addEventListener('online', function () {
            setStatus('connecting', '&#128260; Jaringan terdeteksi, menghubungkan ke server...');
        });

        // Layer 2: Soketi / Pusher WebSocket events
        function bindPusherConnection(pusherInstance) {
            if (!pusherInstance || !pusherInstance.connection) return;

            pusherInstance.connection.bind('state_change', function (states) {
                if (states.current === 'unavailable' || states.current === 'failed' || states.current === 'disconnected') {
                    setStatus('disconnected', '&#9888; Layanan Notif Terputus &mdash; Menunggu reconnect...');
                } else if (states.current === 'connecting') {
                    setStatus('connecting', '&#128260; Menyambungkan ke layanan notif...');
                } else if (states.current === 'connected') {
                    setStatus('connected', '&#9989; Terhubung ke Layanan Notif');
                }
            });
        }

        // Check if Echo / Pusher is already initialized or wait for it
        let checkAttempts = 0;
        const checkEchoInterval = setInterval(function () {
            checkAttempts++;
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                bindPusherConnection(window.Echo.connector.pusher);
                clearInterval(checkEchoInterval);
            } else if (window.pusher) {
                bindPusherConnection(window.pusher);
                clearInterval(checkEchoInterval);
            } else if (checkAttempts > 30) {
                clearInterval(checkEchoInterval);
            }
        }, 500);

        // Initial check on load
        if (!navigator.onLine) {
            setStatus('disconnected', '&#9888; Jaringan Offline &mdash; Periksa sambungan kabel LAN / Wi-Fi arena');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initConnectionMonitor);
    } else {
        initConnectionMonitor();
    }
})();
