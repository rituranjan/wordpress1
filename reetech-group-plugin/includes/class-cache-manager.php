 
<?php
namespace Reetech;

class CacheManager {
    public static function get_cached_data($cache_key, $callback, $expiration = 3600) {
        $data = get_transient($cache_key);

        if (false === $data) {
            $data = call_user_func($callback);
            set_transient($cache_key, $data, $expiration);
            $data['source'] = 'database';
        } else {
            $data['source'] = 'cache';
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public static function clear_cache($cache_key) {
        delete_transient($cache_key);
    }
}