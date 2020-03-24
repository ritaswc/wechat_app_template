<?php

namespace React\Cache;

use React\Promise;

class ArrayCache implements CacheInterface
{
    private $limit;
    private $data = array();
    private $expires = array();

    /**
     * The `ArrayCache` provides an in-memory implementation of the [`CacheInterface`](#cacheinterface).
     *
     * ```php
     * $cache = new ArrayCache();
     *
     * $cache->set('foo', 'bar');
     * ```
     *
     * Its constructor accepts an optional `?int $limit` parameter to limit the
     * maximum number of entries to store in the LRU cache. If you add more
     * entries to this instance, it will automatically take care of removing
     * the one that was least recently used (LRU).
     *
     * For example, this snippet will overwrite the first value and only store
     * the last two entries:
     *
     * ```php
     * $cache = new ArrayCache(2);
     *
     * $cache->set('foo', '1');
     * $cache->set('bar', '2');
     * $cache->set('baz', '3');
     * ```
     *
     * @param int|null $limit maximum number of entries to store in the LRU cache
     */
    public function __construct($limit = null)
    {
        $this->limit = $limit;
    }

    public function get($key, $default = null)
    {
        // delete key if it is already expired => below will detect this as a cache miss
        if (isset($this->expires[$key]) && $this->expires[$key] < microtime(true)) {
            unset($this->data[$key], $this->expires[$key]);
        }

        if (!array_key_exists($key, $this->data)) {
            return Promise\resolve($default);
        }

        // remove and append to end of array to keep track of LRU info
        $value = $this->data[$key];
        unset($this->data[$key]);
        $this->data[$key] = $value;

        return Promise\resolve($value);
    }

    public function set($key, $value, $ttl = null)
    {
        // unset before setting to ensure this entry will be added to end of array (LRU info)
        unset($this->data[$key]);
        $this->data[$key] = $value;

        // sort expiration times if TTL is given (first will expire first)
        unset($this->expires[$key]);
        if ($ttl !== null) {
            $this->expires[$key] = microtime(true) + $ttl;
            asort($this->expires);
        }

        // ensure size limit is not exceeded or remove first entry from array
        if ($this->limit !== null && count($this->data) > $this->limit) {
            // first try to check if there's any expired entry
            // expiration times are sorted, so we can simply look at the first one
            reset($this->expires);
            $key = key($this->expires);

            // check to see if the first in the list of expiring keys is already expired
            // if the first key is not expired, we have to overwrite by using LRU info
            if ($key === null || $this->expires[$key] > microtime(true)) {
                reset($this->data);
                $key = key($this->data);
            }
            unset($this->data[$key], $this->expires[$key]);
        }

        return Promise\resolve(true);
    }

    public function delete($key)
    {
        unset($this->data[$key], $this->expires[$key]);

        return Promise\resolve(true);
    }
}
