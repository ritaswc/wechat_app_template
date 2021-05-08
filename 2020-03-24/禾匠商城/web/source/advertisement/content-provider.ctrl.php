<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
$_W['page']['title'] = '系统-流量主';
$dos = array('flow_control', 'finance_info', 'register_flow', 'display', 'account_list', 'ad_type_get', 'content_provider');
$do = in_array($do, $dos) ? $do : 'account_list';
load()->model('cloud');
load()->func('file');
$flow_master_info = cloud_flow_master_get();
$flow_uniaccount_list = cloud_flow_uniaccount_list_get();
if (isset($flow_master_info['site_key']) && '0' == $flow_master_info['site_key']) {
	itoast('请注册站点或者重置站点信息', referer(), 'error');
}
$commission_show = false;
if (!empty($flow_uniaccount_list)) {
	foreach ($flow_uniaccount_list as $val) {
		if (!empty($val['ad_tags']) && is_array($val['ad_tags'])) {
			$commission_show = true;
		}
	}
}
$status = cloud_prepare();
if (is_error($status)) {
	itoast($status['message'], url('cloud/profile'), 'error');
}
if ('display' == $do) {
	if (4 == $flow_master_info['status'] || IMS_FAMILY == 'v') {
		header('Location:' . url('advertisement/content-provider/account_list'));
		exit;
	}
}

if ('register_flow' == $do) {
	if ((4 == $flow_master_info['status'] || IMS_FAMILY == 'v') || 2 == $flow_master_info['status']) {
		itoast('权限不足', url('advertisement/content-provider/account_list'), 'error');
	}
	if (checksubmit('submit')) {
		$linkman = trim($_GPC['linkman']);
		$mobile = trim($_GPC['mobile']);
		$address = trim($_GPC['address']);
		if (empty($linkman)) {
			itoast('联系人不能为空', referer(), 'error');
		}
		if (empty($mobile)) {
			itoast('电话不能为空', referer(), 'error');
		} else {
			if (!preg_match(REGULAR_MOBILE, $mobile)) {
				itoast('手机号格式不正确', referer(), 'error');
			}
		}
		if (empty($address)) {
			itoast('联系地址不能为空', referer(), 'error');
		}
		if (!empty($_FILES['id_card_photo']['tmp_name'])) {
			$_W['uploadsetting'] = array();
			$_W['uploadsetting']['image']['folder'] = '';
			$_W['uploadsetting']['image']['extentions'] = array('jpg');
			$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
			$upload = file_upload($_FILES['id_card_photo'], 'image', 'id_card');
			$id_card_photo = $upload['path'];
			if (is_error($upload)) {
				itoast('身份证保存失败,' . $upload['message'], referer(), 'info');
			}
		}
		if (!empty($_FILES['business_licence_photo']['tmp_name'])) {
			$_W['uploadsetting'] = array();
			$_W['uploadsetting']['image']['folder'] = '';
			$_W['uploadsetting']['image']['extentions'] = array('jpg');
			$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
			$upload = file_upload($_FILES['business_licence_photo'], 'image', 'business_licence');
			$business_licence_photo = $upload['path'];
			if (is_error($upload)) {
				itoast('营业执照保存失败,' . $upload['message'], referer(), 'info');
			}
		}
		if (!empty($flow_master_info['id_card_photo']) && empty($id_card_photo)) {
			$id_card_photo = $flow_master_info['id_card_photo'];
		}
		if (!empty($flow_master_info['business_licence_photo']) && empty($business_licence_photo)) {
			$business_licence_photo = $flow_master_info['business_licence_photo'];
		}
		$flow_master = array(
			'linkman' => trim($_GPC['linkman']),
			'mobile' => trim($_GPC['mobile']),
			'address' => trim($_GPC['address']),
			'id_card_photo' => tomedia($id_card_photo),
			'business_licence_photo' => tomedia($business_licence_photo),
		);
		$result = cloud_flow_master_post($flow_master);
		if (is_error($result)) {
			itoast($result['message'], '', 'error');
		} else {
			itoast('提交成功，请等待审核', url('advertisement/content-provider/display'), 'info');
		}
	}
}

if ('account_list' == $do) {
	if (4 != $flow_master_info['status'] && IMS_FAMILY != 'v') {
		header('Location:' . url('advertisement/content-provider/display'));
		exit;
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$start = ($pindex - 1) * $psize;
	$condition = '';
	$pars = array();
	$keyword = trim($_GPC['keyword']);
	$s_uniacid = intval($_GPC['s_uniacid']);
	if (!empty($_W['isfounder'])) {
		$condition .= ' WHERE a.default_acid <> 0 AND b.isdeleted <> 1';
		$order_by = ' ORDER BY a.`rank` DESC';
	} else {
		$condition .= 'LEFT JOIN ' . tablename('uni_account_users') . ' as c ON a.uniacid = c.uniacid WHERE a.default_acid <> 0 AND c.uid = :uid AND b.isdeleted <> 1';
		$pars[':uid'] = $_W['uid'];
		$order_by = ' ORDER BY c.`rank` DESC';
	}
	if (!empty($keyword)) {
		$condition .= ' AND a.`name` LIKE :name';
		$pars[':name'] = "%{$keyword}%";
	}
	if (!empty($s_uniacid)) {
		$condition .= ' AND a.`uniacid` = :uniacid';
		$pars[':uniacid'] = $s_uniacid;
	}

	if (!empty($_GPC['expiretime'])) {
		$expiretime = intval($_GPC['expiretime']);
		$condition .= ' AND a.`uniacid` IN(SELECT uniacid FROM ' . tablename('uni_account_users') . " WHERE role = 'owner' AND uid IN (SELECT uid FROM " . tablename('users') . ' WHERE endtime > :time AND endtime < :endtime))';
		$pars[':time'] = time();
		$pars[':endtime'] = strtotime(date('Y-m-d', time() + 86400 * ($expiretime + 2)));
	}
	if ('3' == $_GPC['type']) {
		$condition .= ' AND b.type = 3';
	} elseif ('1' == $_GPC['type']) {
		$condition .= ' AND b.type <> 3';
	}
	$tsql = 'SELECT COUNT(*) FROM ' . tablename('uni_account') . ' as a LEFT JOIN' . tablename('account') . " as b ON a.default_acid = b.acid {$condition} {$order_by}, a.`uniacid` DESC";
	$total = pdo_fetchcolumn($tsql, $pars);
	$sql = 'SELECT * FROM ' . tablename('uni_account') . ' as a LEFT JOIN' . tablename('account') . " as b ON a.default_acid = b.acid  {$condition} {$order_by}, a.`uniacid` DESC LIMIT {$start}, {$psize}";
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall($sql, $pars, 'uniacid');
	$flow_uniaccount_list = cloud_flow_uniaccount_list_get();
	if (!empty($list)) {
		foreach ($list as $unia => &$account) {
			$account_details = uni_accounts($account['uniacid']);
						$account['details'][$account['default_acid']] = $account_details[$account['default_acid']];
			$account['role'] = permission_account_user_role($_W['uid'], $account['uniacid']);
			$account['setmeal'] = uni_setmeal($account['uniacid']);
			if (!empty($flow_uniaccount_list[$unia])) {
				$flow_uniaccount_list[$unia]['ad_tags_str'] = @implode($flow_uniaccount_list[$unia]['ad_tags'], ',');
				$flow_uniaccount_list[$unia]['amount'] = $flow_uniaccount_list[$unia]['amount'] / 100;
				$account['flow_setting'] = $flow_uniaccount_list[$unia];
				$account['flow_setting_enable'] = $flow_uniaccount_list[$unia]['enable'];
			} else {
				$account['flow_setting_enable'] = 1;
			}
		}
	}
}

if ('flow_control' == $do) {
	if (empty($_GPC['uniacid'])) {
		itoast('公众号id参数有误，请重新进入', url('advertisement/content-provider/account_list'), 'error');
	}
	$current_account = uni_fetch($_GPC['uniacid']);
	load()->model('account');
	$_W['uniacid'] = $_GPC['uniacid'];
	$installedmodulelist = uni_modules();
	foreach ($installedmodulelist as $val) {
		if (0 == $val['issystem'] && 1 == $val['enabled']) {
			$path = '../addons/' . $val['name'];
			$cion = $path . '/icon-custom.jpg';
			if (!file_exists($cion)) {
				$cion = $path . '/icon.jpg';
				if (!file_exists($cion)) {
					$cion = './resource/images/nopic-small.jpg';
				}
			}
			$val['icon'] = $cion;
			$modulelist[$val['name']] = $val;
			$modulenames[] = $val['name'];
		}
	}
	$cloud_module_support = cloud_flow_app_support_list($modulenames);
	$cloud_module_support_str = json_encode($cloud_module_support);
	$flow_app_list_setting = cloud_flow_app_list_get($current_account['uniacid']);
	if (!empty($flow_app_list_setting)) {
		foreach ($flow_app_list_setting as &$value) {
			$value['ad_types'] = iunserializer($value['ad_types']);
		}
	}
	if (!empty($cloud_module_support)) {
		foreach ($cloud_module_support as $support) {
			if (2 == $support['ad_support']) {
				$modulelist_available[$support['name']] = $modulelist[$support['name']];
				$modulelist_available[$support['name']]['flow_setting'] = $flow_app_list_setting[$support['name']];
				$modulelist_available[$support['name']]['ad_types'] = json_encode($support['ad_types']);
			}
		}
	}
	$tag_list = cloud_flow_ad_tag_list();
	if (!empty($tag_list)) {
		foreach ($tag_list as $value) {
			foreach ($value['items'] as $key => $item) {
				$items[$key] = $item;
			}
		}
	}
	$current_uniaccount = cloud_flow_uniaccount_get($current_account['uniacid']);
	if (!empty($current_uniaccount)) {
		$current_tags = iunserializer($current_uniaccount['ad_tags']);
		$current_uniaccount['current_tags_str'] = @implode($current_tags, ',');
		$current_uniaccount['amount'] = $current_uniaccount['amount'] / 100;
	}
	$ad_type_lists = cloud_flow_ad_type_list();
	if ($_W['isajax'] && $_W['ispost']) {
		if ('tags' == $_GPC['type']) {
			if (2 == $_GPC['enable']) {
				if (!empty($_GPC['tagids'])) {
					foreach ($_GPC['tagids'] as $value) {
						$ad_tags[$value] = $items[$value];
					}
				} else {
					iajax(1, '请至少选择一个标签', '');
				}
			}
			$uniacid = intval($_GPC['uniacid']);
			$uniaccount['uniacid'] = intval($_GPC['uniacid']);
			if (1 == $_GPC['enable']) {
				$uniaccount['enable'] = 1;
			} elseif (2 == $_GPC['enable']) {
				$uniaccount['title'] = trim($current_account['name']);
				$uniaccount['original'] = trim($current_account['original']);
				$uniaccount['gh_type'] = trim($current_account['level']);
				$uniaccount['ad_tags'] = $ad_tags;
				$uniaccount['enable'] = 2;
			}
			$result = cloud_flow_uniaccount_post($uniaccount);
		} elseif ('types' == $_GPC['type']) {
			$uniacid = intval($_GPC['uniacid']);
			$module_name = trim($_GPC['module_name']);
			if (!empty($_GPC['typeids'])) {
				foreach ($_GPC['typeids'] as $value) {
					$ad_types[$value] = $ad_type_lists[$value]['title'];
				}
				$result = cloud_flow_app_post($uniacid, $module_name, '2', $ad_types);
			} else {
				$result = cloud_flow_app_post($uniacid, $module_name, '1', array(''));
			}
		}
		if (!is_error($result)) {
			iajax(0, '设置成功', referer());
		} else {
			iajax(-1, $result['message'], '');
		}
	}
}

if ('ad_type_get' == $do) {
	$module_name = trim($_GPC['module_name']);
	$uniacid = intval($_GPC['uniacid']);
	$flow_app_list_setting = cloud_flow_app_list_get($uniacid);
	$current_app_set = $flow_app_list_setting[$module_name];
	if (!is_error($current_app_set)) {
		iajax(0, $current_app_set['ad_types'], '');
	} else {
		iajax(-1, $result['message'], '');
	}
}

if ('finance_info' == $do) {
	if (empty($commission_show)) {
		itoast('权限不足', url('advertisement/content-provider/account_list'), 'error');
	}
	$params = array(
		'size' => '15',
		'page' => $_GPC['page'],
	);
	if (!empty($_GPC['type'])) {
		if (1 == $_GPC['type']) {
			$params['starttime'] = strtotime(date('Y-m-d'));
			$params['endtime'] = TIMESTAMP;
		} elseif (2 == $_GPC['type']) {
			$params['starttime'] = strtotime('yesterday');
			$params['endtime'] = strtotime(date('Y-m-d'));
		}
	} else {
		$datelimit_start = $_GPC['datelimit']['start'];
		$datelimit_end = $_GPC['datelimit']['end'];
		$params['starttime'] = empty($datelimit_start) ? strtotime('-1 month') : strtotime($_GPC['datelimit']['start']);
		$params['endtime'] = empty($datelimit_end) ? TIMESTAMP : strtotime($_GPC['datelimit']['end']) + 86399;
	}
	$pindex = max(1, intval($_GPC['page']));
	$finance_info = cloud_flow_site_stat_day($params);
	foreach ($finance_info['items'] as &$val) {
		$val['extra_date'] = $val['year'] . '/' . $val['month'] . '/' . $val['day'];
		$val['search_date'] = $val['year'] . $val['month'] . $val['day'];
		$val['amount'] = $val['amount'] / 100;
	}
	$finance_items = $finance_info['items'];
	$pager = pagination($finance_info['total'], $pindex, $finance_info['size']);
}

if ('content_provider' == $do) {
	$iframe = cloud_auth_url('content-provider');
	$title = '广告主';
}

template('advertisement/content-provider');
