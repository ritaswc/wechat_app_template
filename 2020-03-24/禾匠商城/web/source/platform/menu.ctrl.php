<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('mc');
load()->model('menu');
load()->model('material');

$dos = array('display', 'delete', 'refresh', 'post', 'push', 'copy', 'current_menu');
$do = in_array($do, $dos) ? $do : 'display';

if ($_W['isajax']) {
	if (!empty($_GPC['method'])) {
		$do = $_GPC['method'];
	}
}

if ('display' == $do) {
	permission_check_account_user('platform_menu_conditional');
	set_time_limit(0);

	$type = !empty($_GPC['type']) ? intval($_GPC['type']) : MENU_CURRENTSELF;
	if (MENU_CONDITIONAL == $type) {
		$update_conditional_menu = menu_update_conditional();
		if (is_error($update_conditional_menu)) {
			itoast($update_conditional_menu['message'], '', 'error');
		}
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$condition = ' WHERE uniacid = :uniacid';
	$params[':uniacid'] = $_W['uniacid'];
	if (isset($_GPC['keyword'])) {
		$condition .= ' AND title LIKE :keyword';
		$params[':keyword'] = "%{$_GPC['keyword']}%";
	}
	if (!empty($type)) {
		$condition .= ' AND type = :type';
		$params[':type'] = $type;
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('uni_account_menus') . $condition, $params);
	$data = pdo_fetchall('SELECT * FROM ' . tablename('uni_account_menus') . $condition . ' ORDER BY type ASC, status DESC,id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);
	if (MENU_CONDITIONAL == $type) {
		$names = array(
			'sex' => array('不限', '男', '女'),
			'client_platform_type' => array('不限', '苹果', '安卓', '其他'),
		);
		$groups = mc_fans_groups(true);
	}
	template('platform/menu');
}

if ('push' == $do) {
	$id = intval($_GPC['id']);
	$result = menu_push($id);

	if (is_error($result)) {
		iajax(-1, $result['message']);
	} else {
		iajax(0, '修改成功！', referer());
	}
}

if ('copy' == $do) {
	$id = intval($_GPC['id']);
	$menu = menu_get($id);
	if (empty($menu)) {
		itoast('菜单不存在或已经删除', url('platform/menu/display'), 'error');
	}
	if (MENU_CONDITIONAL != $menu['type']) {
		itoast('该菜单不能复制', url('platform/menu/display'), 'error');
	}
	unset($menu['id'], $menu['menuid']);
	$menu['status'] = STATUS_OFF;
	$menu['title'] = $menu['title'] . '- 复本';
	pdo_insert('uni_account_menus', $menu);
	$id = pdo_insertid();
	itoast('', url('platform/menu/post', array('id' => $id, 'copy' => 1, 'type' => MENU_CONDITIONAL)));
}

if ('post' == $do) {
	permission_check_account_user('platform_menu_default');
	$type = intval($_GPC['type']);
	$id = intval($_GPC['id']);
	$copy = intval($_GPC['copy']);
	if (empty($type)) {
		if (!$_W['isajax']) {
			$update_self_menu = menu_update_currentself();
			if (is_error($update_self_menu)) {
				itoast($update_self_menu['message'], '', 'info');
			}
		}
		$type = MENU_CURRENTSELF;
		$default_menu = menu_default();
		$id = intval($default_menu['id']);
	}
	$params = array();
	if ($id > 0) {
		$menu = menu_get($id);
		if (empty($menu)) {
			itoast('菜单不存在或已经删除', url('platform/menu/display'), 'error');
		}
		if (!empty($menu['data'])) {
			$menu['data'] = iunserializer(base64_decode($menu['data']));
			if (!empty($menu['data']['button'])) {
				foreach ($menu['data']['button'] as &$button) {
					if (!empty($button['url'])) {
						$button['url'] = preg_replace('/(.*)redirect_uri=(.*)&response_type(.*)wechat_redirect/', '$2', $button['url']);
					}
					if (empty($button['sub_button'])) {
						if ('media_id' == $button['type']) {
							$button['type'] = 'click';
						}
						$button['sub_button'] = array();
					} else {
						$button['sub_button'] = !empty($button['sub_button']['list']) ? $button['sub_button']['list'] : $button['sub_button'];
						foreach ($button['sub_button'] as &$subbutton) {
							if (!empty($subbutton['url'])) {
								$subbutton['url'] = preg_replace('/(.*)redirect_uri=(.*)&response_type(.*)wechat_redirect/', '$2', $subbutton['url']);
							}
							if ('media_id' == $subbutton['type']) {
								$subbutton['type'] = 'click';
							}
						}
						unset($subbutton);
					}
				}
				unset($button);
			}
			if (!empty($menu['data']['matchrule']['province'])) {
				$menu['data']['matchrule']['province'] .= '省';
			}
			if (!empty($menu['data']['matchrule']['city'])) {
				$menu['data']['matchrule']['city'] .= '市';
			}
			if (empty($menu['data']['matchrule']['sex'])) {
				$menu['data']['matchrule']['sex'] = 0;
			}
			if (empty($menu['data']['matchrule']['group_id'])) {
				$menu['data']['matchrule']['group_id'] = -1;
			}
			if (empty($menu['data']['matchrule']['client_platform_type'])) {
				$menu['data']['matchrule']['client_platform_type'] = 0;
			}
			if (empty($menu['data']['matchrule']['language'])) {
				$menu['data']['matchrule']['language'] = '';
			}
			$params = $menu['data'];
			$params['title'] = $menu['title'];
			$params['type'] = $menu['type'];
			$params['id'] = $menu['id'];
			$params['status'] = $menu['status'];
		}
		$type = $menu['type'];
	}
	$status = $params['status'];
	$groups = mc_fans_groups();
	$languages = menu_languages();
	if ($_W['isajax'] && $_W['ispost']) {
		set_time_limit(0);
		$_GPC['group']['title'] = trim($_GPC['group']['title']);
		$_GPC['group']['type'] = 0 == intval($_GPC['group']['type']) ? 1 : intval($_GPC['group']['type']);
		$post = $_GPC['group'];
				if (empty($post['title'])) {
			iajax(-1, '请填写菜单组名称！', '');
		}
		$check_title_exist_condition = array(
			'title' => $post['title'],
			'type' => $type,
		);
		if (!empty($id)) {
			$check_title_exist_condition['id <>'] = $id;
		}
		$check_title_exist = pdo_getcolumn('uni_account_menus', $check_title_exist_condition, 'id');
		if (!empty($check_title_exist)) {
			iajax(-1, '菜单组名称已存在，请重新命名！', '');
		}
				if (MENU_CONDITIONAL == $post['type'] && empty($post['matchrule'])) {
			iajax(-1, '请选择菜单显示对象', '');
		}
		if (!empty($post['button'])) {
			foreach ($post['button'] as $key => &$button) {
				$keyword_exist = strexists($button['key'], 'keyword:');
				if ($keyword_exist) {
					$button['key'] = substr($button['key'], 8);
				}
				if (!empty($button['sub_button'])) {
					foreach ($button['sub_button'] as &$subbutton) {
						$sub_keyword_exist = strexists($subbutton['key'], 'keyword:');
						if ($sub_keyword_exist) {
							$subbutton['key'] = substr($subbutton['key'], 8);
						}
					}
					unset($subbutton);
				}
			}
			unset($button);
		}

		$is_conditional = MENU_CONDITIONAL == $post['type'] ? true : false;
		$account_api = WeAccount::createByUniacid();
		$menu = $account_api->menuBuild($post, $is_conditional);
		if ('publish' == $_GPC['submit_type'] || $is_conditional) {
			$result = $account_api->menuCreate($menu);
		} else {
			$result = true;
		}
		if (is_error($result)) {
			iajax($result['errno'], $result['message']);
		} else {
						if ($post['matchrule']['group_id'] != -1) {
				$menu['matchrule']['groupid'] = $menu['matchrule']['tag_id'];
				unset($menu['matchrule']['tag_id']);
			}
			$menu = json_decode(urldecode(json_encode($menu)), true);

			$insert = array(
				'uniacid' => $_W['uniacid'],
				'menuid' => $result,
				'title' => $post['title'],
				'type' => $post['type'],
				'sex' => intval($menu['matchrule']['sex']),
				'group_id' => isset($menu['matchrule']['group_id']) ? $menu['matchrule']['group_id'] : -1,
				'client_platform_type' => intval($menu['matchrule']['client_platform_type']),
				'area' => trim($menus['matchrule']['country']) . trim($menu['matchrule']['province']) . trim($menu['matchrule']['city']),
				'data' => base64_encode(iserializer($menu)),
				'status' => STATUS_ON,
				'createtime' => TIMESTAMP,
			);

			if (MENU_CURRENTSELF == $post['type']) {
				if (!empty($id)) {
					pdo_update('uni_account_menus', $insert, array('uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF, 'id' => $id));
				} else {
					pdo_insert('uni_account_menus', $insert);
				}
				iajax(0, '创建菜单成功', url('platform/menu/display'));
			} elseif (MENU_CONDITIONAL == $post['type']) {
				if (STATUS_OFF == $post['status'] && $post['id'] > 0) {
					pdo_update('uni_account_menus', $insert, array('uniacid' => $_W['uniacid'], 'type' => MENU_CONDITIONAL, 'id' => $post['id']));
				} else {
					pdo_insert('uni_account_menus', $insert);
				}
				iajax(0, '创建菜单成功', url('platform/menu/display', array('type' => MENU_CONDITIONAL)));
			}
		}
	}
	template('platform/menu');
}

if ('delete' == $do) {
	$id = intval($_GPC['id']);
	$result = menu_delete($id);
	if (is_error($result)) {
		itoast($result['message'], referer(), 'error');
	}
	itoast('删除菜单成功', referer(), 'success');
}

if ('current_menu' == $do) {
	$current_menu = $_GPC['current_menu'];
	if ('click' == $current_menu['type']) {
		if (!empty($current_menu['media_id']) && empty($current_menu['key'])) {
			$wechat_attachment = pdo_get('wechat_attachment', array('media_id' => $current_menu['media_id']));
			if ('news' == $wechat_attachment['type']) {
				$material = pdo_get('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $wechat_attachment['id']));
				$material['items'][0]['thumb_url'] = tomedia($material['thumb_url']);
				$material['items'][0]['title'] = $material['title'];
				$material['items'][0]['digest'] = $material['digest'];
				$material['type'] = 'news';
			} elseif ('video' == $wechat_attachment['type']) {
				$material['tag'] = iunserializer($wechat_attachment['tag']);
				$material['attach'] = tomedia($wechat_attachment['attachment'], true);
				$material['type'] = 'video';
			} elseif ('voice' == $wechat_attachment['type']) {
				$material['attach'] = tomedia($wechat_attachment['attachment'], true);
				$material['type'] = 'voice';
				$material['filename'] = $wechat_attachment['filename'];
			} elseif ('image' == $wechat_attachment['type']) {
				$material['attach'] = tomedia($wechat_attachment['attachment'], true);
				$material['url'] = "url({$material['attach']})";
				$material['type'] = 'image';
			}
		} else {
			$keyword_info = explode(':', $current_menu['key']);
			if ('keyword' == $keyword_info[0]) {
				$rule_info = pdo_get('rule', array('name' => $keyword_info[1]), array('id'));
				$material['child_items'][0] = pdo_get('rule_keyword', array('rid' => $rule_info['id']), array('content'));
				$material['name'] = $keyword_info[1];
				$material['type'] = 'keyword';
			}
		}
	}
	if ('click' != $current_menu['type'] && 'view' != $current_menu['type']) {
		$material = array();
		if ('module' == $current_menu['etype']) {
			$module_name = explode(':', $current_menu['key']);
			load()->model('module');
			$material = module_fetch($module_name[1]);
			if ($material['issystem']) {
				$path = '/framework/builtin/' . $material['name'];
			} else {
				$path = '../addons/' . $material['name'];
			}
			$cion = $path . '/icon-custom.jpg';
			if (!file_exists($cion)) {
				$cion = $path . '/icon.jpg';
				if (!file_exists($cion)) {
					$cion = './resource/images/nopic-small.jpg';
				}
			}
			$material['icon'] = $cion;
			$material['type'] = $current_menu['type'];
			$material['etype'] = 'module';
		} elseif ('click' == $current_menu['etype']) {
			$keyword_info = explode(':', $current_menu['key']);
			if ('keyword' == $keyword_info[0]) {
				$rule_info = pdo_get('rule', array('name' => $keyword_info[1]), array('id'));
				$material['child_items'][0] = pdo_get('rule_keyword', array('rid' => $rule_info['id']), array('content'));
				$material['name'] = $keyword_info[1];
				$material['type'] = $current_menu['type'];
				$material['etype'] = 'click';
			}
		}
	}
	iajax(0, $material);
}