/*
var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});

(() => {
    'use strict'


    const WebPush = {
        init () {
            self.addEventListener('push', this.notificationPush.bind(this))
            self.addEventListener('notificationclick', this.notificationClick.bind(this))
            self.addEventListener('notificationclose', this.notificationClose.bind(this))
        },


        notificationPush (event) {
            if (!(self.Notification && self.Notification.permission === 'granted')) {
                return
            }

            //handle received notification
            const promiseChain = isClientFocused()
                .then((clientIsFocused) => {
                    // received data of notification
                    var data = event.data.json();

                    //windowClientsPublic has all clients browser tabs opened
                    windowClientsPublic.forEach((windowClient) => {
                        // send an event " message " for all subscribed users received notification
                        // message event will be listened in app.blade.php page
                        windowClient.postMessage({
                            message: data,
                            time: new Date().toString()
                        });
                    });

                    //if client not in the page of the website, browser will send notification
                    if (!clientIsFocused) {
                        return self.registration.showNotification(data.title,data);
                    }
                });

            event.waitUntil(promiseChain);
        },


        notificationClick (event) {
            // console.log(event.notification)

            if (event.action === 'notification_action') {
                // Do something...
            } else {
                self.clients.openWindow('/login')
            }
        },

        notificationClose (event) {
            self.registration.pushManager.getSubscription().then(subscription => {
                if (subscription) {
                    this.dismissNotification(event, subscription)
                }
            })
        },

        sendNotification (data) {
            return self.registration.showNotification(data.title, data)
        },

 
        dismissNotification ({ notification }, { endpoint }) {
            if (!notification.data || !notification.data.id) {
                return
            }

            const data = new FormData()
            data.append('endpoint', endpoint)

            // Send a request to the server to mark the notification as read.
            fetch(`/notifications/${notification.data.id}/dismiss`, {
                method: 'POST',
                body: data
            })
        }
    }
    */
   /*
    function isClientFocused() {
        return clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        })
            .then((windowClients) => {
                let clientIsFocused = false;
                windowClientsPublic = windowClients;
                for (let i = 0; i < windowClients.length; i++) {
                    const windowClient = windowClients[i];
                    if (windowClient.focused) {
                        clientIsFocused = true;
                        break;
                    }
                }

                return clientIsFocused;
            });
    }
    
    let windowClientsPublic = '';
    WebPush.init()
})()
*/
