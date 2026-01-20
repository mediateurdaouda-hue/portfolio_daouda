self.addEventListener('install', function (e) {
  e.waitUntil(
    caches.open('v1').then(function (cache) {
      return cache.addAll([
        '/',
        '/index.php',
        '/style.css',
        '/js/app.js',
        '/icons/icon-192.png',
        '/icons/icon-512.png',
        // Ajoute ici d'autres fichiers visibles
      ]);
    })
  );
});

self.addEventListener('fetch', function (e) {
  e.respondWith(
    caches.match(e.request).then(function (response) {
      return response || fetch(e.request);
    })
  );
});
