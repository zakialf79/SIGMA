const CACHE_NAME = 'sigma-cache-v2';
const urlsToCache = [
  './index.php',
  './public/css/app.css',
  './public/js/app.js',
  './public/js/helpers.js',
  './public/js/kas.js',
  './public/js/gudang.js',
  './public/img/icon-512.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
