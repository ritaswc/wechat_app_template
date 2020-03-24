<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function cache_redis() {
	global $_W;
	static $redisobj;
	if (!extension_loaded('redis')) {
		return error(1, 'Class Redis is not found');
	}
	if (empty($redisobj)) {
		$config = $_W['config']['setting']['redis'];
		$redisobj = new Redis();
		try {
			if ($config['pconnect']) {
				$connect = $redisobj->pconnect($config['server'], $config['port']);
			} else {
				$connect = $redisobj->connect($config['server'], $config['port']);
			}
			if (!empty($config['auth'])) {
				$auth = $redisobj->auth($config['auth']);
			}
		} catch (Exception $e) {
			return error(-1, 'redis连接失败，错误信息：' . $e->getMessage());
		}
	}

	return $redisobj;
}


function cache_read($key) {
	$redis = cache_redis();
	if (is_error($redis)) {
		return $redis;
	}
	if ($redis->exists(cache_prefix($key))) {
		$data = $redis->get(cache_prefix($key));
		$data = iunserializer($data);

		return $data;
	}

	return '';
}


function cache_search($key) {
	$redis = cache_redis();
	if (is_error($redis)) {
		return $redis;
	}
	$search_keys = $redis->keys(cache_prefix($key) . '*');
	$search_data = array();
	if (!empty($search_keys)) {
		foreach ($search_keys as $search_key => $search_value) {
			$search_data[$search_value] = iunserializer($redis->get($search_value));
		}
	}

	return $search_data;
}


function cache_write($key, $value, $ttl = CACHE_EXPIRE_LONG) {
	$redis = cache_redis();
	if (is_error($redis)) {
		return $redis;
	}
	$value = iserializer($value);
	if ($redis->set(cache_prefix($key), $value, $ttl)) {
		return true;
	}

	return false;
}


function cache_delete($key) {
	$redis = cache_redis();
	if (is_error($redis)) {
		return $redis;
	}

	$cache_relation_keys = cache_relation_keys($key);
	if (is_error($cache_relation_keys)) {
		return $cache_relation_keys;
	}
	if (is_array($cache_relation_keys) && !empty($cache_relation_keys)) {
		foreach ($cache_relation_keys as $key) {
			$cache_info = cache_load($key);
			if (!empty($cache_info)) {
				$result = $redis->delete(cache_prefix($key));
				if ($result) {
					unset($GLOBALS['_W']['cache'][$key]);
				} else {
					return error(1, '缓存：' . $key . ' 删除失败！');
				}
			}
		}
	}

	return true;
}


function cache_clean($key = '') {
	$redis = cache_redis();
	if (is_error($redis)) {
		return $redis;
	}
	if (!empty($key)) {
		$cache_relation_keys = cache_relation_keys($key);
		if (is_error($cache_relation_keys)) {
			return $cache_relation_keys;
		}

		if (is_array($cache_relation_keys) && !empty($cache_relation_keys)) {
			foreach ($cache_relation_keys as $key) {
				preg_match_all('/\:([a-zA-Z0-9\-\_]+)/', $key, $matches);
				if ($keys = $redis->keys(cache_prefix('we7:' . $matches[1][0]) . '*')) {
					unset($GLOBALS['_W']['cache']);
					$res = $redis->delete($keys);
					if (!$res) {
						return error(-1, '缓存 ' . $key . ' 删除失败');
					}
				}
			}
		}

		return true;
	}
	if ($redis->flushAll()) {
		unset($GLOBALS['_W']['cache']);

		return true;
	}

	return false;
}


function cache_prefix($key) {
	return $GLOBALS['_W']['config']['setting']['authkey'] . $key;
}
