<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function cache_read($key) {
	$cachedata = pdo_getcolumn('core_cache', array('key' => $key), 'value');
	if (empty($cachedata)) {
		return '';
	}
	$cachedata = iunserializer($cachedata);
	if (is_array($cachedata) && !empty($cachedata['expire']) && !empty($cachedata['data'])) {
		if ($cachedata['expire'] > TIMESTAMP) {
			return $cachedata['data'];
		} else {
			return '';
		}
	} else {
		return $cachedata;
	}
}


function cache_search($prefix) {
	$sql = 'SELECT * FROM ' . tablename('core_cache') . ' WHERE `key` LIKE :key';
	$params = array();
	$params[':key'] = "{$prefix}%";
	$rs = pdo_fetchall($sql, $params);
	$result = array();
	foreach ((array) $rs as $v) {
		$result[$v['key']] = iunserializer($v['value']);
	}

	return $result;
}


function cache_write($key, $data, $expire = 0) {
	if (empty($key) || !isset($data)) {
		return false;
	}

	$record = array();
	$record['key'] = $key;
	if (!empty($expire)) {
		$cache_data = array(
			'expire' => TIMESTAMP + $expire,
			'data' => $data,
		);
	} else {
		$cache_data = $data;
	}
	$record['value'] = iserializer($cache_data);

	return pdo_insert('core_cache', $record, true);
}


function cache_delete($key) {
	$cache_relation_keys = cache_relation_keys($key);
	if (is_error($cache_relation_keys)) {
		return $cache_relation_keys;
	}
	if (is_array($cache_relation_keys) && !empty($cache_relation_keys)) {
		foreach ($cache_relation_keys as $key) {
			$cache_info = cache_load($key);
			if (!empty($cache_info)) {
				$sql = 'DELETE FROM ' . tablename('core_cache') . ' WHERE `key`=:key';
				$params = array();
				$params[':key'] = $key;
				$result = pdo_query($sql, $params);
				if (!$result) {
					return error(1, '缓存：' . $key . ' 删除失败!');
				}
			}
		}

		return true;
	}
}


function cache_clean($prefix = '') {
	global $_W;
	if (empty($prefix)) {
		$sql = 'DELETE FROM ' . tablename('core_cache');
		$result = pdo_query($sql);
		if ($result) {
			unset($_W['cache']);
		}
	} else {
		$cache_relation_keys = cache_relation_keys($prefix);
		if (is_error($cache_relation_keys)) {
			return $cache_relation_keys;
		}

		if (is_array($cache_relation_keys) && !empty($cache_relation_keys)) {
			foreach ($cache_relation_keys as $key) {
				preg_match_all('/\:([a-zA-Z0-9\-\_]+)/', $key, $matches);
				$sql = 'DELETE FROM ' . tablename('core_cache') . ' WHERE `key` LIKE :key';
				$params = array();
				$params[':key'] = "we7:{$matches[1][0]}%";
				$result = pdo_query($sql, $params);
				if (!$result) {
					return error(-1, '缓存 ' . $key . '删除失败!');
				}
			}
		}
	}

	return true;
}
