<?php

namespace React\Cache;

use React\Promise\PromiseInterface;

interface CacheInterface
{
    /**
     * Retrieves an item from the cache.
     *
     * This method will resolve with the cached value on success or with the
     * given `$default` value when no item can be found or when an error occurs.
     * Similarly, an expired cache item (once the time-to-live is expired) is
     * considered a cache miss.
     *
     * ```php
     * $cache
     *     ->get('foo')
     *     ->then('var_dump');
     * ```
     *
     * This example fetches the value of the key `foo` and passes it to the
     * `var_dump` function. You can use any of the composition provided by
     * [promises](https://github.com/reactphp/promise).
     *
     * @param string $key
     * @param mixed  $default Default value to return for cache miss or null if not given.
     * @return PromiseInterface
     */
    public function get($key, $default = null);

    /**
     * Stores an item in the cache.
     *
     * This method will resolve with `true` on success or `false` when an error
     * occurs. If the cache implementation has to go over the network to store
     * it, it may take a while.
     *
     * The optional `$ttl` parameter sets the maximum time-to-live in seconds
     * for this cache item. If this parameter is omitted (or `null`), the item
     * will stay in the cache for as long as the underlying implementation
     * supports. Trying to access an expired cache item results in a cache miss,
     * see also [`get()`](#get).
     *
     * ```php
     * $cache->set('foo', 'bar', 60);
     * ```
     *
     * This example eventually sets the value of the key `foo` to `bar`. If it
     * already exists, it is overridden.
     *
     * @param string $key
     * @param mixed  $value
     * @param ?float $ttl
     * @return PromiseInterface Returns a promise which resolves to `true` on success or `false` on error
     */
    public function set($key, $value, $ttl = null);

    /**
     * Deletes an item from the cache.
     *
     * This method will resolve with `true` on success or `false` when an error
     * occurs. When no item for `$key` is found in the cache, it also resolves
     * to `true`. If the cache implementation has to go over the network to
     * delete it, it may take a while.
     *
     * ```php
     * $cache->delete('foo');
     * ```
     *
     * This example eventually deletes the key `foo` from the cache. As with
     * `set()`, this may not happen instantly and a promise is returned to
     * provide guarantees whether or not the item has been removed from cache.
     *
     * @param string $key
     * @return PromiseInterface Returns a promise which resolves to `true` on success or `false` on error
     */
    public function delete($key);
}
