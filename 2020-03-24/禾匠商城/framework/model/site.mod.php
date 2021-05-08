<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function site_cover($coverparams = array()) {
	$coverreply_table = table('core_cover_reply');
	if (!empty($coverparams['multiid'])) {
		$coverreply_table->searchWithMultiid(intval($coverparams['multiid']));
	}
	$cover = $coverreply_table->getByModuleAndUniacid($coverparams['module'], $coverparams['uniacid']);
	if (empty($cover['rid'])) {
		$rule = array(
			'uniacid' => intval($coverparams['uniacid']),
			'name' => safe_gpc_string($coverparams['title']),
			'module' => 'cover',
			'status' => 1,
		);
		table('rule')->fill($rule)->save();
		$rid = pdo_insertid();
	} else {
		$rule = array(
			'name' => $coverparams['title'],
		);
		table('rule')->fill($rule)->whereId($cover['rid'])->save();
		$rid = $cover['rid'];
	}
	if (!empty($rid)) {
				pdo_delete('rule_keyword', array('rid' => $rid, 'uniacid' => $coverparams['uniacid']));
		
		$keywordrow = array(
			'rid' => $rid,
			'uniacid' => intval($coverparams['uniacid']),
			'module' => 'cover',
			'status' => 1,
			'displayorder' => 0,
			'type' => 1,
			'content' => safe_gpc_string($coverparams['keyword']),
		);
		table('rule_keyword')->fill($keywordrow)->save();
	}
	$entry = array(
		'uniacid' => intval($coverparams['uniacid']),
		'multiid' => intval($coverparams['multiid']),
		'rid' => $rid,
		'title' => safe_gpc_string($coverparams['title']),
		'description' => safe_gpc_string($coverparams['description']),
		'thumb' => safe_gpc_path($coverparams['thumb']),
		'url' => safe_gpc_url($coverparams['url']),
		'do' => '',
		'module' => safe_gpc_string($coverparams['module']),
	);

	if (empty($cover['id'])) {
		$coverreply_table->fill($entry)->save();
	} else {
		$coverreply_table->fill($entry)->whereId($cover['id'])->save();
	}
	return true;
}

function site_cover_delete($page_id) {
	global $_W;
	$page_id = intval($page_id);
	$coverreply_table = table('core_cover_reply');
	$coverreply_table->searchWithMultiid($page_id);
	$cover = $coverreply_table->getByModuleAndUniacid('page', $_W['uniacid']);
	if(!empty($cover)) {
		$rid = intval($cover['rid']);
		uni_delete_rule($rid, 'cover_reply');
	}
	return true;
}

function site_ip_validate($ip) {
	$ip = trim($ip);
	if (empty($ip)) {
		return error(-1, 'ip不能为空');
	}
	$ip_data = explode("\n", $ip);
	foreach ($ip_data as $ip) {
		if (!filter_var($ip, FILTER_VALIDATE_IP)) {
			return error(-1, $ip . '不合法');
		}
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			return error(-1, $ip . '为外网ip,外网ip不可填');
		}
	}
	return $ip_data;
}

function site_ip_add($ip = '') {
	load()->model('setting');
	$ip_data = site_ip_validate($ip);
	if (is_error($ip_data)) {
		return error(-1, $ip_data['message']);
	}
	$ip_data_format = setting_load('ip_white_list');
	$ip_data_format = $ip_data_format['ip_white_list'];
	foreach ($ip_data as $ip) {
		$ip_data_format[$ip]['ip'] = $ip;
		$ip_data_format[$ip]['status'] = 1;
	}
	return setting_save($ip_data_format, 'ip_white_list');
}


function site_app_navs($type = 'home', $multiid = 0) {
	global $_W;
	$pos = array();
	$pos['home'] = 1;
	$pos['profile'] = 2;
	$pos['shortcut'] = 3;
	if (empty($multiid) && $type != 'profile') {
		load()->model('account');
		$setting = uni_setting($_W['uniacid'], array('default_site'));
		$multiid = $setting['default_site'];
	}

	$site_nav_table = table('site_nav');
	$site_nav_table->searchWithMultiid(intval($multiid));
	$site_nav_table->searchWithPosition($pos[$type]);
	$site_nav_table->searchWithStatus(1);
	$site_nav_table->searchWithUniacid($_W['uniacid']);
	$site_nav_table->orderby(array('displayorder' => 'DESC', 'id' => 'ASC'));
	$navs = $site_nav_table->getall();
	if (!empty($navs)) {
		foreach ($navs as &$row) {
			if (!strexists($row['url'], 'tel:') && !strexists($row['url'], '://') && !strexists($row['url'], 'www') && !strexists($row['url'], 'i=')) {
				$row['url'] .= strexists($row['url'], '?') ? "&i={$_W['uniacid']}" : "?i={$_W['uniacid']}";
			}
			if (is_serialized($row['css'])) {
				$row['css'] = iunserializer($row['css']);
			}
			if (empty($row['css'])) {
				$row['css'] = array(
					'icon' => array(
						'icon' => 'fa fa-external-link',
						'font-size' => '35px',
						'color' => '',
					),
					'name' => array('color' => ''),
				);
			}
			if (empty($row['css']['icon']['icon'])) {
				$row['css']['icon']['icon'] = 'fa fa-external-link';
			}
			if ($row['position'] == '3') {
				if (!empty($row['css'])) {
					unset($row['css']['icon']['font-size']);
				}
			}
			$row['css']['icon']['style'] = "color:{$row['css']['icon']['color']};font-size:{$row['css']['icon']['font-size']}px;";
			$row['css']['name'] = "color:{$row['css']['name']['color']};";
		}
		unset($row);
	}
	return $navs;
}