/**
 * Auto-Reconnect & Offline Score Queue for AyoSilat Judge Tablets
 * 
 * Purpose:
 * Guarantees zero point loss when momentary Wi-Fi disconnects occur in the arena.
 * If fetch() fails due to network drop, the score is stored locally in memory + sessionStorage
 * and automatically retried (flushed) sequentially once the network or WebSocket reconnects.
 */

window.AyoSilatJudge = (function() {
    'use strict';

    let config = {
        arena: null,
        id_juri: null,
        csrfToken: null,
        storeUrl: null
    };

    const STORAGE_KEY = 'ayosilat_pending_scores';
    let queue = [];
    let isFlushing = false;

    // Load persisted queue from sessionStorage if any
    try {
        const saved = sessionStorage.getItem(STORAGE_KEY);
        if (saved) {
            queue = JSON.parse(saved);
        }
    } catch (e) {
        queue = [];
    }

    function saveQueue() {
        try {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(queue));
        } catch (e) {}
    }

    function sendScore(data) {
        return fetch(config.storeUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': config.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
    }

    function flushQueue() {
        if (isFlushing || queue.length === 0 || !navigator.onLine) return;
        isFlushing = true;

        const item = queue[0];
        sendScore(item.data)
            .then(res => {
                if (res.ok) {
                    queue.shift();
                    saveQueue();
                    isFlushing = false;
                    if (queue.length > 0) {
                        setTimeout(flushQueue, 150);
                    } else {
                        if (typeof window.onQueueFlushed === 'function') {
                            window.onQueueFlushed();
                        }
                    }
                } else {
                    isFlushing = false;
                }
            })
            .catch(() => {
                isFlushing = false;
            });
    }

    function enqueueScore(data) {
        const queueItem = {
            id: Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            data: data,
            timestamp: Date.now()
        };
        queue.push(queueItem);
        saveQueue();
    }

    function sendOrQueue(data, onSuccess, onError) {
        // Attempt immediate send
        sendScore(data)
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            })
            .then(resData => {
                if (typeof onSuccess === 'function') onSuccess(resData);
                // Flush any older queued items
                if (queue.length > 0) flushQueue();
            })
            .catch(err => {
                // Network error: silently queue the score for auto-retry
                console.warn('[AyoSilat Auto-Reconnect] Terputus, nilai disimpan ke antrean offline:', data, err);
                enqueueScore(data);
                if (typeof onError === 'function') onError(err, true);
            });
    }

    function init(userConfig) {
        Object.assign(config, userConfig);

        // Bind network events
        window.addEventListener('online', function() {
            flushQueue();
        });

        // Bind Soketi / Pusher WebSocket auto-reconnect
        function bindPusher() {
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                const pusher = window.Echo.connector.pusher;
                pusher.connection.bind('state_change', function(states) {
                    if (states.current === 'connected') {
                        flushQueue();
                    }
                });
                return true;
            }
            return false;
        }

        if (!bindPusher()) {
            let attempts = 0;
            const timer = setInterval(() => {
                attempts++;
                if (bindPusher() || attempts > 20) clearInterval(timer);
            }, 500);
        }

        // Try flushing pending scores if any on load
        if (queue.length > 0 && navigator.onLine) {
            setTimeout(flushQueue, 1000);
        }
    }

    return {
        init: init,
        submitScore: sendOrQueue,
        flush: flushQueue,
        getQueueCount: () => queue.length
    };
})();
