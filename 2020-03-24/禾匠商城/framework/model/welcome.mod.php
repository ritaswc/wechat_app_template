<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function welcome_get_ads() {
	load()->classs('cloudapi');
	$result = array();
	$api = new CloudApi();
	$result = $api->get('store', 'we7_index_a');
	return $result;
}



function welcome_notices_get() {
	global $_W;
	$order = !empty($_W['setting']['notice_display']) ? $_W['setting']['notice_display'] : 'displayorder';
	$article_table = table('article_notice');
	$article_table->orderby($order, 'DESC');
	$article_table->searchWithIsDisplay();
	$article_table->searchWithPage(0, 15);
	$notices = $article_table->getall();
	if(!empty($notices)) {
		foreach ($notices as $key => $notice_val) {
			$notices[$key]['url'] = url('article/notice-show/detail', array('id' => $notice_val['id']));
			$notices[$key]['createtime'] = date('Y-m-d', $notice_val['createtime']);
			$notices[$key]['style'] = iunserializer($notice_val['style']);
			$notices[$key]['group'] = empty($notice_val['group']) ? array('vice_founder' => array(), 'normal' => array()) : iunserializer($notice_val['group']);
			if (!empty($_W['user']['groupid']) && !empty($notice_val['group']) && !in_array($_W['user']['groupid'], $notices[$key]['group']['vice_founder']) && !in_array($_W['user']['groupid'], $notices[$key]['group']['normal'])) {
				unset($notices[$key]);
			}
		}
	}
	return $notices;
}

function welcome_database_backup_days($time) {
	global $_W;
	$cachekey = cache_system_key('back_days');
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$backup_days = 0;
	if (is_array($time)) {
		$max_backup_time = $time[0];
		foreach ($time as $key => $backup_time) {
			if ($backup_time <= $max_backup_time) {
				continue;
			}
			$max_backup_time = $backup_time;
		}
		$backup_days = ceil((time() - $max_backup_time) / (3600 * 24));
	}
	if (is_numeric($time)) {
		$backup_days = ceil((time() - $time) / (3600 * 24));
	}
	cache_write($cachekey, $backup_days, 24 * 3600);
	return $backup_days;
}

function welcome_get_cloud_upgrade() {
	load()->model('cloud');
	$upgrade_cache = cache_load(cache_system_key('upgrade'));
	if (empty($upgrade_cache) || TIMESTAMP - $upgrade_cache['lastupdate'] >= 3600 * 24 || empty($upgrade_cache['data'])) {
		$upgrade = cloud_build();
	} else {
		$upgrade = $upgrade_cache['data'];
	}
	cache_delete(cache_system_key('cloud_transtoken'));
	if (is_error($upgrade) || empty($upgrade['upgrade'])) {
		$upgrade = array();
	}
	if (!empty($upgrade['schemas'])) {
		$upgrade['database'] = cloud_build_schemas($upgrade['schemas']);
	}
	if (!empty($upgrade['files'])) {
		$file_nums = count($upgrade['files']);
	}
	if (!empty($upgrade['database'])) {
		$database_nums = count($upgrade['database']);
	}
	if (!empty($upgrade['scripts'])) {
		$script_nums = count($upgrade['scripts']);
	}
	$upgrade['file_nums'] = $file_nums;
	$upgrade['database_nums'] = $database_nums;
	$upgrade['script_nums'] = $script_nums;
	return $upgrade;
}