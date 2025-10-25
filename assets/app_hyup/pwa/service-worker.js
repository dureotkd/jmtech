const CACHE_NAME = "jmtech-erp-v1";
const urlsToCache = [
  "/",
  "/assets/app_hyup/pwa/manifest.json",
  "/assets/app_hyup/pwa/pwa-icon-192.png",
  "/assets/app_hyup/pwa/pwa-icon-512.png",
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(urlsToCache);
    })
  );
});

self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
