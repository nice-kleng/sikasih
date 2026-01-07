const CACHE_NAME = 'sikasih-v1';
const urlsToCache = ['/app/beranda', '/app/kesehatan', '/app/edukasi', '/app/profil'];
self.addEventListener('install', e => { e.waitUntil(caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))); });
self.addEventListener('fetch', e => { e.respondWith(caches.match(e.request).then(r => r || fetch(e.request).then(response => { return caches.open(CACHE_NAME).then(cache => { cache.put(e.request, response.clone()); return response; }); }))); });
self.addEventListener('activate', e => { e.waitUntil(caches.keys().then(keys => Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))))); });
