<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function menu_languages() {
	$languages = array(
		array('ch'=>'简体中文', 'en'=>'zh_CN'),
		array('ch'=>'繁体中文TW', 'en'=>'zh_TW'),
		array('ch'=>'繁体中文HK', 'en'=>'zh_HK'),
		array('ch'=>'英文', 'en'=>'en'),
		array('ch'=>'印尼', 'en'=>'id'),
		array('ch'=>'马来', 'en'=>'ms'),
		array('ch'=>'西班牙', 'en'=>'es'),
		array('ch'=>'韩国', 'en'=>'ko'),
		array('ch'=>'意大利 ', 'en'=>'it'),
		array('ch'=>'日本', 'en'=>'ja'),
		array('ch'=>'波兰', 'en'=>'pl'),
		array('ch'=>'葡萄牙', 'en'=>'pt'),
		array('ch'=>'俄国', 'en'=>'ru'),
		array('ch'=>'泰文', 'en'=>'th'),
		array('ch'=>'越南', 'en'=>'vi'),
		array('ch'=>'阿拉伯语', 'en'=>'ar'),
		array('ch'=>'北印度', 'en'=>'hi'),
		array('ch'=>'希伯来', 'en'=>'he'),
		array('ch'=>'土耳其', 'en'=>'tr'),
		array('ch'=>'德语', 'en'=>'de'),
		array('ch'=>'法语', 'en'=>'fr')
	);
	return $languages;
}


function menu_get($id) {
	global $_W;
	$id = intval($id);
	if (empty($id)) {
		return array();
	}
	$menu_info = table('uni_account_menus')->getById($id);
	if (!empty($menu_info)) {
		return $menu_info;
	} else {
		return array();
	}
}


function menu_default() {
	return table('uni_account_menus')->where('status', STATUS_ON)->getByType(MENU_CURRENTSELF);
}


function menu_update_currentself() {
	global $_W;
	$account_api = WeAccount::createByUniacid();
	$default_menu_info = $account_api->menuCurrentQuery();
	if (is_error($default_menu_info)) {
		return error(-1, $default_menu_info['message']);
	}

	if ($account_api->type != ACCOUNT_TYPE_XZAPP_NORMAL && $account_api->type != ACCOUNT_TYPE_XZAPP_AUTH) {
		if (empty($default_menu_info['is_menu_open']) || empty($default_menu_info['selfmenu_info'])) {
			return true;
		}
		$default_menu = $default_menu_info['selfmenu_info'];
	} else {
		$default_menu = $default_menu_info['menu'];
	}
	$default_sub_button = array();
	if (!empty($default_menu['button'])) {
		foreach ($default_menu['button'] as $key => &$button) {
			if (!empty($button['sub_button'])) {
				$default_sub_button[$key] = $button['sub_button'];
			} else {
				unset($button['sub_button']);
			}
			ksort($button);
		}
		unset($button);
	}
	if (!empty($default_menu)) {
		ksort($default_menu);
	}
	$wechat_menu_data = base64_encode(iserializer($default_menu));
	$all_default_menus = table('uni_account_menus')->getAllByType(MENU_CURRENTSELF);
	if (!empty($all_default_menus)) {
		foreach ($all_default_menus as $menus_key => $menu_data) {
			if (empty($menu_data['data'])) {
				continue;
			}
			$single_menu_info = iunserializer(base64_decode($menu_data['data']));
			if (!is_array($single_menu_info) || empty($single_menu_info['button'])) {
				continue;
			}
			foreach ($single_menu_info['button'] as $key => &$single_button) {
				if (!empty($default_sub_button[$key])) {
					$single_button['sub_button'] = $default_sub_button[$key];
				} else {
					unset($single_button['sub_button']);
				}
				ksort($single_button);
			}
			unset($single_button);
			ksort($single_menu_info);
			$local_menu_data = base64_encode(iserializer($single_menu_info));
			if ($wechat_menu_data == $local_menu_data) {
				$default_menu_id = $menus_key;
			}
		}
	}

	if (!empty($default_menu_id)) {
		pdo_update('uni_account_menus', array('status' => STATUS_ON), array('id' => $default_menu_id));
		pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF, 'id !=' => $default_menu_id));
	} else {
		$insert_data = array(
			'uniacid' => $_W['uniacid'],
			'type' => MENU_CURRENTSELF,
			'group_id' => -1,
			'sex' => 0,
			'data' => $wechat_menu_data,
			'client_platform_type' => 0,
			'area' => '',
			'menuid' => 0,
			'status' => STATUS_ON
		);
		pdo_insert('uni_account_menus', $insert_data);
		$insert_id = pdo_insertid();
		pdo_update('uni_account_menus', array('title' => '默认菜单_'.$insert_id), array('id' => $insert_id));
		pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF, 'id !=' => $insert_id));
	}
	return true;
}


function menu_update_conditional() {
	global $_W;
	$account_api = WeAccount::createByUniacid();
	$conditional_menu_info = $account_api->menuQuery();
	if (is_error($conditional_menu_info)) {
		return error(-1, $conditional_menu_info['message']);
	}
	pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('uniacid' => $_W['uniacid'], 'type' => MENU_CONDITIONAL));
	if (!empty($conditional_menu_info['conditionalmenu'])) {
		foreach ($conditional_menu_info['conditionalmenu'] as $menu) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'type' => MENU_CONDITIONAL,
				'group_id' => isset($menu['matchrule']['tag_id']) ? $menu['matchrule']['tag_id'] : (isset($menu['matchrule']['group_id']) ? $menu['matchrule']['group_id'] : '-1'),
				'sex' => $menu['matchrule']['sex'],
				'client_platform_type' => $menu['matchrule']['client_platform_type'],
				'area' => trim($menu['matchrule']['country']) . trim($menu['matchrule']['province']) . trim($menu['matchrule']['city']),
				'data' => base64_encode(iserializer($menu)),
				'menuid' => $menu['menuid'],
				'status' => STATUS_ON,
			);
			if (!empty($menu['matchrule'])) {
				$menu_info = table('uni_account_menus')->where('menuid',$menu['menuid'])->getByType(MENU_CONDITIONAL);
				$menu_id = $menu_info['id'];
			}
			if (!empty($menu_id)) {
				$data['title'] = !empty($menu_info['title']) ? $menu_info['title'] : '个性化菜单_' . $menu_id;
				pdo_update('uni_account_menus', $data, array('uniacid' => $_W['uniacid'], 'id' => $menu_id));
			} else {
				pdo_insert('uni_account_menus', $data);
				$insert_id = pdo_insertid();
				pdo_update('uni_account_menus', array('title' => '个性化菜单_'.$insert_id), array('id' => $insert_id));
			}
		}
	}
	return true;
}


function menu_delete($id) {
	global $_W;
	$menu_info = menu_get($id);
	if (empty($menu_info)) {
		return error(-1, '菜单不存在或已经删除');
	}
	if ($menu_info['status'] == STATUS_OFF) {
		pdo_delete('uni_account_menus', array('uniacid' => $_W['uniacid'], 'id' => $id));
		return error(0, '删除菜单成功！');
	}
	if ($menu_info['type'] == MENU_CONDITIONAL && $menu_info['menuid'] > 0 && $menu_info['status'] != STATUS_OFF) {
		$account_api = WeAccount::createByUniacid();
		$result = $account_api->menuDelete($menu_info['menuid']);
		if (is_error($result)) {
			return error(-1, $result['message']);
		}
		pdo_delete('uni_account_menus', array('uniacid' => $_W['uniacid'], 'id' => $id));
	}
	return true;
}


function menu_push($id) {
	global $_W;
	$menu_info = menu_get($id);
	if (empty($menu_info)) {
		return error(-1, '菜单不存在或已删除');
	}
	if ($menu_info['status'] == STATUS_OFF) {
		$post = iunserializer(base64_decode($menu_info['data']));
		if (empty($post)) {
			return error(-1, '菜单数据错误');
		}
		$is_conditional = (!empty($post['matchrule']) && $menu_info['type'] == MENU_CONDITIONAL) ? true : false;

		$account_api = WeAccount::createByUniacid();
		$menu = $account_api->menuBuild($post, $is_conditional);
		$result = $account_api->menuCreate($menu);
		if (is_error($result)) {
			return error(-1, $result['message']);
		}
		if ($menu_info['type'] == MENU_CURRENTSELF) {
			pdo_update('uni_account_menus', array('status' => '1'), array('id' => $menu_info['id']));
			pdo_update('uni_account_menus', array('status' => '0'), array('id !=' => $menu_info['id'], 'uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF));
		} elseif ($menu_info['type'] == MENU_CONDITIONAL) {
						if ($post['matchrule']['group_id'] != -1) {
				$menu['matchrule']['groupid'] = $menu['matchrule']['tag_id'];
				unset($menu['matchrule']['tag_id']);
			}
			$status = pdo_update('uni_account_menus', array('status' => STATUS_ON, 'menuid' => $result), array('uniacid' => $_W['uniacid'], 'id' => $menu_info['id']));
		}
		return true;
	}
		if ($menu_info['status'] == STATUS_ON && $menu_info['type'] == MENU_CONDITIONAL && $menu_info['menuid'] > 0) {
		$account_api = WeAccount::createByUniacid();
		$result = $account_api->menuDelete($menu_info['menuid']);
		if (is_error($result)) {
			return error(-1, $result['message']);
		} else {
			pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('id' => $menu_info['id']));
			return true;
		}
	}
}