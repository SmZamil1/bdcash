self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open('my-invest-store').then((cache) => cache.addAll([
      '/',
    ])),
  );
});

self.addEventListener('fetch', (e) => {
  e.respondWith(
    caches.match(e.request).then((response) => response || fetch(e.request)),
  );
});
