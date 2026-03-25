<?php
namespace App\Libraries;
use Closure;

class CacheDB
{
    protected $cache;
    protected int $lockTtl = 10;
    protected string $cacheKeyVersionName = 'cdb_version_' . ENVIRONMENT;

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    /**
     * GET CACHE
     * Get cache by key, return null if not found
     */
    public function get(string $key)
    {
        $key    =   $this->sanitizeKey($key);
        return $this->cache->get($key);
    }

    /**
     * Save
     * Save data to cache
     */
    public function save(string $key, $data, int $ttl = 0): void
    {
        $key    =   $this->sanitizeKey($key);
        $this->cache->save($key, $data, $ttl);
    }

    /**
     * Clear
     * Clear data from cache
     */
    public function clear(string $key): void
    {
        $key    =   $this->sanitizeKey($key);
        $this->cache->delete($key);
    }

    /**
     * REMEMBER
     * Get cache, if null, execute callback and store result in cache
     */
    public function remember(string $key, int $ttl, Closure $callback)
    {
        $key    =   $this->sanitizeKey($key);
        $data   =   $this->cache->get($key);

        if ($data !== null) return $data;

        $data   =   $callback();
        $this->cache->save($key, $data, $ttl);
        
        return $data;
    }

    /**
     * INCR
     * Increment counter
     */
    public function increment(string $key, int $step = 1, int $ttl = 0): int
    {
        $key    =   $this->sanitizeKey($key);
        $value  =   $this->cache->get($key);

        if ($value === null) $value = 0;

        $value += $step;
        $this->cache->save($key, $value, $ttl);
        return $value;
    }

    /**
     * LOCK
     * Simple mutex lock (best-effort)
     */
    public function lock(string $key): bool
    {
        $lockKey = $this->sanitizeKey('lock_' . $key);

        if ($this->cache->get($lockKey)) return false;
        $this->cache->save($lockKey, 1, $this->lockTtl);
        return true;
    }

    public function unlock(string $key): void
    {
        $lockKey = $this->sanitizeKey('lock_' . $key);
        $this->cache->delete($lockKey);
    }
    
    public function getCacheKeyName(...$parts): string
    {
        $versionNumber  =   $this->getCacheVersionNumber();
        $key            =   'cdb_' . ENVIRONMENT . '_' . $versionNumber . '_' . implode('_', array_filter($parts));
        return $this->sanitizeKey($key);
    }
    
    public function updateCacheVersionNumber(): void
    {
        $key    =   $this->sanitizeKey($this->cacheKeyVersionName);
        $version=   date('YmdHis');

        $this->cache->save($key, $version, 0);
    }
    
    public function getCacheVersionNumber(): string
    {
        $key    =   $this->sanitizeKey($this->cacheKeyVersionName);
        $this->cache->get($key) ?? $this->updateCacheVersionNumber();
        return $this->cache->get($key);
    }
    
    /**
     * Sanitize cache key to remove reserved characters
     * Replace reserved characters with underscores
     * Reserved: {}()/\@:
     */
    protected function sanitizeKey(string $key): string
    {
        return str_replace(['|', '{', '}', '(', ')', '/', '\\', '@', ':'], '_', $key);
    }
}