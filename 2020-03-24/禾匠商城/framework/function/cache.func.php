<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('cache.' . cache_type());


function cache_type() {
	global $_W;
	$cacher = $connect = '';
	$cache_type = strtolower($_W['config']['setting']['cache']);

	if (extension_loaded($cache_type)) {
		$config = $_W['config']['setting'][$cache_type];
		if (!empty($config['server']) && !empty($config['port'])) {
			if ('memcache' == $cache_type) {
				$cacher = new Memcache();
			} elseif ('redis' == $cache_type) {
				$cacher = new Redis();
			}
			$connect = $cacher->connect($config['server'], $config['port']);
		}
	}
	if (empty($cacher) || empty($connect)) {
		$cache_type = 'mysql';
	}

	return $cache_type;
}


function cache_load($key, $unserialize = false) {
	global $_W;
	static $we7_cache;

	if (is_error($key)) {
		trigger_error($key['message'], E_USER_WARNING);

		return false;
	}
	if (!empty($we7_cache[$key])) {
		return $we7_cache[$key];
	}
	$data = $we7_cache[$key] = cache_read($key);
	if ('setting' == $key) {
		$_W['setting'] = $data;

		return $_W['setting'];
	} elseif ('modules' == $key) {
		$_W['modules'] = $data;

		return $_W['modules'];
	} elseif ('module_receive_enable' == $key && empty($data)) {
				cache_build_module_subscribe_type();

		return cache_read($key);
	} else {
		return $unserialize ? iunserializer($data) : $data;
	}
}

function &cache_global($key) {
}


function cache_system_key($cache_key) {
	$cache_key_all = cache_key_all();

		$params = array();
	$args = func_get_args();
	if (empty($args[1])) {
		$args[1] = '';
	}
	if (!is_array($args[1])) {
		$cache_key = $cache_key_all['caches'][$cache_key]['key'];
		preg_match_all('/\%([a-zA-Z\_\-0-9]+)/', $cache_key, $matches);
		for ($i = 0; $i < func_num_args() - 1; ++$i) {
			$cache_key = str_replace($matches[0][$i], $args[$i + 1], $cache_key);
		}

		return 'we7:' . $cache_key;
	} else {
		$params = $args[1];
	}

		if (empty($params)) {
		$res = preg_match_all('/([a-zA-Z\_\-0-9]+):/', $cache_key, $matches);
		if ($res) {
			$key = count($matches[1]) > 0 ? $matches[1][0] : $matches[1];
		} else {
			$key = $cache_key;
		}
		if (empty($cache_key_all['caches'][$key])) {
			return error(1, '缓存' . $key . ' 不存在!');
		} else {
			$cache_info_key = $cache_key_all['caches'][$key]['key'];
			preg_match_all('/\%([a-zA-Z\_\-0-9]+)/', $cache_info_key, $key_params);
			preg_match_all('/\:([a-zA-Z\_\-0-9]+)/', $cache_key, $val_params);

			if (count($key_params[1]) != count($val_params[1])) {
				foreach ($key_params[1] as $key => $val) {
					if (in_array($val, array_keys($cache_key_all['common_params']))) {
						$cache_info_key = str_replace('%' . $val, $cache_key_all['common_params'][$val], $cache_info_key);
						unset($key_params[1][$key]);
					}
				}

								if (count($key_params[1]) == count($val_params[1])) {
					$arr = array_combine($key_params[1], $val_params[1]);
					foreach ($arr as $key => $val) {
						if (preg_match('/\%' . $key . '/', $cache_info_key)) {
							$cache_info_key = str_replace('%' . $key, $val, $cache_info_key);
						}
					}
				}

				if (strexists($cache_info_key, '%')) {
					return error(1, '缺少缓存参数或参数不正确!');
				} else {
					return 'we7:' . $cache_info_key;
				}
			} else {
				return 'we7:' . $cache_key;
			}
		}
	}

	$cache_info = $cache_key_all['caches'][$cache_key];
	$cache_common_params = $cache_key_all['common_params'];

	if (empty($cache_info)) {
		return error(2, '缓存 ' . $cache_key . ' 不存在!');
	} else {
		$cache_key = $cache_info['key'];
	}

		foreach ($cache_common_params as $param_name => $param_val) {
		preg_match_all('/\%([a-zA-Z\_\-0-9]+)/', $cache_key, $matches);
		if (in_array($param_name, $matches[1]) && !in_array($param_name, array_keys($params))) {
			$params[$param_name] = $cache_common_params[$param_name];
		}
	}

	if (is_array($params) && !empty($params)) {
		foreach ($params as $key => $param) {
			$cache_key = str_replace('%' . $key, $param, $cache_key);
		}

		if (strexists($cache_key, '%')) {
			return error(1, '缺少缓存参数或参数不正确!');
		}
	}

	$cache_key = 'we7:' . $cache_key;
	if (strlen($cache_key) > CACHE_KEY_LENGTH) {
		trigger_error('Cache name is over the maximum length');
	}

	return $cache_key;
}


function cache_relation_keys($key) {
	if (!is_string($key)) {
		return $key;
	}

	if (!strexists($key, 'we7:')) {
		return array($key);
	}

		$cache_param_values = explode(':', $key);
	$cache_name = $cache_param_values[1];
	unset($cache_param_values[0]);
	unset($cache_param_values[1]);

	if (empty($cache_param_values)) {
		preg_match_all('/\:([a-zA-Z\_\-0-9]+)/', $key, $matches);
		$cache_name = $matches[1][0];
	}

	$cache_key_all = cache_key_all();
	$cache_relations = $cache_key_all['groups'];
	$cache_common_params = $cache_key_all['common_params'];
	$cache_info = $cache_key_all['caches'][$cache_name];
	if (empty($cache_info)) {
		return error(2, '缓存 : ' . $key . '不存在');
	}

	if (!empty($cache_info['group'])) {
		if (empty($cache_relations[$cache_info['group']])) {
			return error(1, '关联关系未定义');
		}
		$relation_keys = $cache_relations[$cache_info['group']]['relations'];
		$cache_keys = array();
		foreach ($relation_keys as $key => $val) {
						if ($val == $cache_name) {
				$relation_cache_key = $cache_key_all['caches'][$val]['key'];
			} else {
				$relation_cache_key = $cache_key_all['caches'][$cache_name]['key'];
			}
			foreach ($cache_common_params as $param_name => $param_val) {
								preg_match_all('/\%([a-zA-Z\_\-0-9]+)/', $relation_cache_key, $matches);
				if (in_array($param_name, $matches[1])) {
										$cache_key_params[$param_name] = $cache_common_params[$param_name];
				}
								if (!empty($cache_prams_values) || count($matches[1]) == count($cache_param_values)) {
					$cache_key_params = array_combine($matches[1], $cache_param_values);
				} else {
					$cache_key_params = array();
				}
			}

			$cache_key = cache_system_key($val, $cache_key_params);
			if (!is_error($cache_key)) {
				$cache_keys[] = $cache_key;
			} else {
				return error(1, $cache_key['message']);
			}
		}
	} else {
		$cache_keys[] = $key;
	}

	return $cache_keys;
}


function cache_key_all() {
	global $_W;
	$caches_all = array(
		'common_params' => array(
			'uniacid' => $_W['uniacid'],
			'uid' => $_W['uid'],
		),

		'caches' => array(
			'module_info' => array(
								'key' => 'module_info:%module_name',
				'group' => 'module',
			),

			'module_setting' => array(
								'key' => 'module_setting:%module_name:%uniacid',
				'group' => 'module',
			),

			'last_account' => array(
								'key' => 'last_account:%switch:%uid',
				'group' => '',
			),

			'last_account_type' => array(
				'key' => 'last_account_type',
				'group' => '',
			),

			'user_modules' => array(
								'key' => 'user_modules:%uid',
				'group' => '',
			),

			'user_accounts' => array(
								'key' => 'user_accounts:%type:%uid',
				'group' => '',
			),

			'unimodules' => array(
								'key' => 'unimodules:%uniacid',
				'group' => '',
			),

			'unimodules_binding' => array(
								'key' => 'unimodules_binding:%uniacid',
				'group' => '',
			),

			'uni_groups' => array(
								'key' => 'uni_groups:%groupids',
				'group' => '',
			),

			'permission' => array(
								'key' => 'permission:%uniacid:%uid',
				'group' => '',
			),

			'memberinfo' => array(
				'key' => 'memberinfo:%uid',
				'group' => '',
			),

			'statistics' => array(
				'key' => 'statistics:%uniacid',
				'group' => '',
			),

			'uniacid_visit' => array(
				'key' => 'uniacid_visit:%uniacid:%today',
				'group' => '',
			),

			'material_reply' => array(
								'key' => 'material_reply:%attach_id',
				'group' => '',
			),

			'keyword' => array(
								'key' => 'keyword:%content:%uniacid',
				'group' => '',
			),

			'back_days' => array(
				'key' => 'back_days',
				'group' => '',
			),

			'miniapp_version' => array(
				'key' => 'miniapp_version:%version_id',
				'group' => '',
			),

			'site_store_buy' => array(
				'key' => 'site_store_buy:%type:%uniacid',
				'group' => '',
			),

			'proxy_wechatpay_account' => array(
				'key' => 'proxy_wechatpay_account',
				'group' => '',
			),

			'recycle_module' => array(
				'key' => 'recycle_module',
				'group' => '',
			),
			'random' => array(
				'key' => 'random',
				'group' => '',
			),
			'sync_fans_pindex' => array(
				'key' => 'sync_fans_pindex:%uniacid',
				'group' => '',
			),

			'uniaccount' => array(
								'key' => 'uniaccount:%uniacid',
				'group' => 'uniaccount',
			),

			'unisetting' => array(
								'key' => 'unisetting:%uniacid',
				'group' => 'uniaccount',
			),

			'defaultgroupid' => array(
				'key' => 'defaultgroupid:%uniacid',
				'group' => 'uniaccount',
			),

			'uniaccount_type' => array(
				'key' => 'uniaccount_type:%account_type',
				'group' => '',
			),

			
			'accesstoken' => array(
				'key' => 'accesstoken:%uniacid',
				'group' => 'accesstoken',
			),

			'jsticket' => array(
				'key' => 'jsticket:%uniacid',
				'group' => 'accesstoken',
			),

			'cardticket' => array(
				'key' => 'cardticket:%uniacid',
				'group' => 'accesstoken',
			),
			

			'accesstoken_key' => array(
				'key' => 'accesstoken_key:%key',
				'group' => '',
			),

			'account_oauth_refreshtoken' => array(
				'key' => 'account_oauth_refreshtoken:%acid',
				'group' => '',
			),

			'account_auth_refreshtoken' => array(
				'key' => 'account_auth_refreshtoken:%uniacid',
				'group' => '',
			),

			'account_tags' => array(
				'key' => 'account_tags:%uniacid',
				'group' => '',
			),

			'unicount' => array(
								'key' => 'unicount:%uniacid',
				'group' => '',
			),

			'checkupgrade' => array(
				'key' => 'checkupgrade',
				'group' => '',
			),

			'cloud_transtoken' => array(
				'key' => 'cloud_transtoken',
				'group' => '',
			),

			'upgrade' => array(
				'key' => 'upgrade',
				'group' => '',
			),

			'account_ticket' => array(
				'key' => 'account_ticket',
				'group' => '',
			),

			'oauthaccesstoken' => array(
				'key' => 'oauthaccesstoken:%acid',
				'group' => '',
			),

			'account_component_assesstoken' => array(
				'key' => 'account_component_assesstoken',
				'group' => '',
			),
			'cloud_api' => array(
				'key' => 'cloud_api:%method',
				'group' => '',
			),
			'cloud_ad_uniaccount' => array(
				'key' => 'cloud_ad_uniaccount:%uniacid',
				'group' => '',
			),

			'cloud_ad_uniaccount_list' => array(
				'key' => 'cloud_ad_uniaccount_list',
				'group' => '',
			),

			'cloud_flow_master' => array(
				'key' => 'cloud_flow_master',
				'group' => '',
			),

			'cloud_ad_tags' => array(
				'key' => 'cloud_ad_tags',
				'group' => '',
			),

			'cloud_ad_type_list' => array(
				'key' => 'cloud_ad_type_list',
				'group' => '',
			),

			'cloud_ad_app_list' => array(
				'key' => 'cloud_ad_app_list:%uniacid',
				'group' => '',
			),

			'cloud_ad_app_support_list' => array(
				'key' => 'cloud_ad_app_support_list',
				'group' => '',
			),

			'cloud_ad_site_finance' => array(
				'key' => 'cloud_ad_site_finance',
				'group' => '',
			),

			'cloud_ad_store_notice' => array(
				'key' => 'cloud_ad_store_notice',
				'group' => '',
			),

			'couponsync' => array(
				'key' => 'couponsync:%uniacid',
				'group' => '',
			),

			'storesync' => array(
				'key' => 'storesync:%uniacid',
				'group' => '',
			),

			'cloud_auth_transfer' => array(
				'key' => 'cloud_auth_transfer',
				'group' => '',
			),

			'modulesetting' => array(
				'key' => 'modulesetting:%module:%acid',
				'group' => '',
			),

			'scan_config' => array(
				'key' => 'scan_config',
				'group' => 'scan_file',
			),

			'scan_file' => array(
				'key' => 'scan_file',
				'group' => 'scan_file',
			),

			'scan_badfile' => array(
				'key' => 'scan_badfile',
				'group' => 'scan_file',
			),

			'bomtree' => array(
				'key' => 'bomtree',
				'group' => '',
			),

			'setting' => array(
				'key' => 'setting',
				'group' => '',
			),

			'stat_todaylock' => array(
				'key' => 'stat_todaylock:%uniacid',
				'group' => '',
			),

			'account_preauthcode' => array(
				'key' => 'account_preauthcode',
				'group' => '',
			),

			'account_auth_accesstoken' => array(
				'key' => 'account_auth_accesstoken:%key',
				'group' => '',
			),

			'usersfields' => array(
				'key' => 'usersfields',
				'group' => '',
			),

			'userbasefields' => array(
				'key' => 'userbasefields',
				'group' => '',
			),

			'system_frame' => array(
				'key' => 'system_frame:%uniacid',
				'group' => '',
			),

			'module_receive_enable' => array(
				'key' => 'module_receive_enable',
				'group' => '',
			),

			'module_entry_call' => array(
				'key' => 'module_entry_call:%module_name',
				'group' => '',
			),

			'system_check' => array(
				'key' => 'system_check',
				'group' => '',
			),

			'delete_visit_ip' => array(
				'key' => 'delete_visit_ip:%date',
				'group' => '',
			),
		),
				'groups' => array(
			'uniaccount' => array(
				'relations' => array('uniaccount', 'unisetting', 'defaultgroupid'),
			),

			'accesstoken' => array(
				'relations' => array('accesstoken', 'jsticket', 'cardticket'),
			),

			'scan_file' => array(
				'relations' => array('scan_file', 'scan_config', 'scan_badfile'),
			),

						'module' => array(
				'relations' => array('module_info', 'module_setting'),
			),
		),
	);

	return $caches_all;
}
