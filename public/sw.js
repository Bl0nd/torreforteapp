const CACHE_NAME = "app-cache-v0";

const FILES_TO_CACHE = [
  "/torreforte_app/public/",
  "/torreforte_app/public/assets/css/style.css",
  "/torreforte_app/public/assets/js/script.js"
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(async cache => {
      for (const file of FILES_TO_CACHE) {
        try {
          await cache.add(file);
        } catch (e) {
          console.error(`Erro ao adicionar ${file} ao cache`, e);
        }
      }
    })
  );
  self.skipWaiting();
});

self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
      );
    })
  );
  self.clients.claim();
});

self.addEventListener("fetch", event => {
  if (event.request.url.includes(".css") || event.request.url.includes(".js")) {
    event.respondWith(
      caches.open(CACHE_NAME).then(cache => {
        return fetch(event.request).then(response => {
          cache.put(event.request, response.clone());
          return response;
        }).catch(() => caches.match(event.request));
      })
    );
  } else {
    event.respondWith(
      caches.match(event.request).then(response => {
        return response || fetch(event.request);
      })
    );
  }
});
