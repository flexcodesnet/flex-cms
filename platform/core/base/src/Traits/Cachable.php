<?php

namespace FXC\Base\Traits;

use App\Support\CacheKey;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Predis\Client;

trait Cachable
{
    use BaseTrait;

    private static $cacheKeys = [];

    /**
     * @param  null  $cacheKeys
     * @return void
     */
    public static function addKeys($cacheKeys = null)
    {
        self::$cacheKeys = collect(self::$cacheKeys)->merge($cacheKeys)->unique()->toArray();
    }

    /**
     * @param  null  $cacheKeys
     * @return void
     */
    public static function clearCacheKeys($cacheKeys = null)
    {
        if (!$cacheKeys) {
            self::addKeys([
                CacheKey::all(self::getTableName()),
                CacheKey::all_cached(self::getTableName()),
                CacheKey::active_cached(self::getTableName()),
            ]);
            $cacheKeys = self::getCacheKeys();
        }

        $cacheKeys = is_array($cacheKeys) ? $cacheKeys : [$cacheKeys];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * @param  null  $prefix
     * @return bool
     */
    public static function clearRedisCachesByPrefix($prefix = null): bool
    {
        if (config('cache.default') != 'redis') {
            Artisan::call('cache:clear');
            return false;
        }

        try {
            $redis = new Client(array(
                'host'     => config("database.redis.cache.host", '127.0.0.1'),
                'port'     => config("database.redis.cache.port", 6379),
                'password' => config("database.redis.cache.password", null),
                'database' => config("database.redis.cache.database", 1),
            ));

            if ($prefix) {
                $keys = $redis->keys("*$prefix*");
            } else {
                $keys = $redis->keys("*");
            }

            $keys = is_array($keys) ? $keys : [$keys];


            foreach ($keys as $key) {
                $redis->del($key);
            }


            return true;

        } catch (Exception $e) {

            return false;
        }
    }

    /**
     * @return array
     */
    public static function getCacheKeys(): array
    {
        return self::$cacheKeys;
    }

    /**
     * @param  array  $cacheKeys
     */
    public static function setCacheKeys(array $cacheKeys): void
    {
        self::$cacheKeys = $cacheKeys;
    }

    /**
     * @return mixed
     */
    public static function allCached()
    {
        $cache_key = CacheKey::all_cached(self::getTableName());

        return get_cached_key_data($cache_key, function () {
            return self::all();
        });
    }

    /**
     * @return mixed
     */
    public static function allActiveCached()
    {
        $cache_key = CacheKey::active_cached(self::getTableName());

        return get_cached_key_data($cache_key, function () {
            return self::active()->get();
        });
    }
}
