<?php

use Config\GeoIP;

if (!function_exists('getGeoDataFromIP')) {
    /**
     * Récupère l'adresse IP et le pays à partir d'une adresse IP via l'API IPStack,
     * avec mise en cache pour éviter les appels API répétitifs.
     *
     * @param string $ip L'adresse IP à rechercher.
     * @return array Contient l'adresse IP et le pays (ou "Unknown" si non trouvé).
     */
    function getGeoDataFromIP(string $ip): array
    {
        // Charger la configuration IPStack
        $config = new GeoIP();
        $cache = \Config\Services::cache(); // Récupère le service de cache

        // Vérifier si les données sont en cache
        $cacheKey = 'geo_' . md5($ip); // Clé de cache unique basée sur l'IP
        $cachedData = $cache->get($cacheKey);

        if ($cachedData !== null) {
            return $cachedData; // Retourner les données mises en cache si disponibles
        }

        // Sinon, effectuer la requête API
        $url = $config->ipstack_api_url . $ip . '?access_key=' . $config->ipstack_api_key;

        try {
            // Effectuer la requête API
            $response = file_get_contents($url);

            if ($response === false) {
                throw new Exception("Impossible de récupérer les données depuis IPStack.");
            }

            $data = json_decode($response, true);

            // Données à mettre en cache
            $geoData = [
                'ip' => $ip,
                'country' => $data['country_name'] ?? 'Unknown',
            ];

            // Mettre en cache les données pendant 1 heure (3600 secondes)
            $cache->save($cacheKey, $geoData, 3600); // 1 heure de cache

            return $geoData;

        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return [
                'ip' => $ip,
                'country' => 'Unknown',
            ];
        }
    }
}
