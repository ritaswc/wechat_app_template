<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('miniapp');
load()->model('mc');
$dos = array('nav', 'slide', 'commend', 'wxapp_web', 'wxappweb_pay', 'wxappweb_pay_result', 'package_app', 'go_paycenter', 'oauth', 'credit_info');
$do = in_array($_GPC['do'], $dos) ? $_GPC['do'] : 'nav';

$multiid = intval($_GPC['t']);

if ($do == 'nav') {
	$navs = table('site_nav')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'multiid' => $multiid,
			'status' => 1,
			'icon !=' => '',
		))
		->select(array('url', 'name', 'icon'))
		->orderby(array('displayorder' => 'DESC'))
		->getall();

	if (!empty($navs)) {
		foreach ($navs as $i => &$row) {
			$row['icon'] = tomedia($row['icon']);
		}
	}
	message(error(0, $navs), '', 'ajax');
} elseif ($do == 'slide') {
	$slide = table('site_slide')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'multiid' => $multiid,
		))
		->select(array('url', 'title', 'thumb'))
		->orderby(array('displayorder' => 'DESC'))
		->getall();
	if (!empty($slide)) {
		foreach ($slide as $i => &$row) {
			$row['thumb'] = tomedia($row['thumb']);
		}
	}
	message(error(0, $slide), '', 'ajax');
} elseif ($do == 'commend') {
		$category = table('site_category')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'multiid' => $multiid,
		))
		->select(array('id', 'name', 'parentid'))
		->orderby(array('displayorder' => 'DESC'))
		->getall();
		if (!empty($category)) {
		foreach ($category as $id => &$category_row) {
			if (empty($category_row['parentid'])) {
				$condition['pcate'] = $category_row['id'];
			} else {
				$condition['ccate'] = $category_row['id'];
			}
			$category_row['article'] = table('site_article')
				->where($condition)
				->select(array('id', 'title', 'thumb'))
				->orderby(array('displayorder' => 'DESC'))
				->limit(8)
				->getall();
			if (!empty($category_row['article'])) {
				foreach ($category_row['article'] as &$row) {
					$row['thumb'] = tomedia($row['thumb']);
				}
			} else {
				unset($category[$id]);
			}
		}
	}
	message(error(0, $category), '', 'ajax');
}

if ($do == 'wxapp_web') {
	$version = trim($_GPC['v']);
	$version_info = miniapp_version_by_version($version);
	$url = $_GPC['url'];
	if (empty($url)) {
				if (count($version_info['modules']) > 1) {
			$url = murl('wxapp/home/package_app', array('v'=>$version));		} else {
			if (!empty($version_info['modules'])) {
				foreach ($version_info['modules'] as $module) {
					if (!empty($module['account']) && intval($module['account']['uniacid']) > 0) {
						$_W['uniacid'] = $module['account']['uniacid'];
						$_W['account']['link_uniacid'] = $module['account']['uniacid'];
					}
				}
			}
			$url = murl('entry', array('eid'=>$version_info['entry_id']), true, true);
		}
	}
	if ($url) {
		setcookie(session_name(), $_W['session_id']);
		header('Location:' . $url);
		exit;
	}
		message('找不到模块入口', 'refresh', 'error');
}


if ($do == 'package_app') {
	$version = trim($_GPC['v']);
	$version_info = miniapp_version_by_version($version);

	$version_info['modules'] = array_map(function($module) {
		 $module['url'] = murl('entry', array('eid'=>$module['defaultentry']), true, true);
		 return $module;
	}, $version_info['modules']);



	$version_info['quickmenu']['menus'] = array_map(function($menu){
		 $menu['url'] = murl('entry', array('eid'=>$menu['defaultentry']), true, true);
		 return $menu;
	}, $version_info['quickmenu']['menus']);

	template('wxapp/wxapp');
}


if ($do == 'wxappweb_pay') {
	$site = WeUtility::createModuleWxapp('core');
	$site->doPagePay();
}

if ($do == 'wxappweb_pay_result') {
	$site = WeUtility::createModuleWxapp('core');
	$site->doPagePayResult();
}

if ($do == 'go_paycenter') {
	$plid = intval($_GPC['plid']);
	$params = table('core_paylog')->where(array('plid' => $plid))->get();
	$params['title'] = safe_gpc_string($_GPC['title']);
	template('common/paycenter');
}

if ($do == 'oauth') {
	$url = safe_gpc_url($_GPC['url'], false);
	$oauth_userinfo = mc_oauth_account_userinfo($url);
	if (is_error($oauth_userinfo)) {
		message($oauth_userinfo['message'], $url, 'info');
	}
	header('Location: ' . $url);
}

if ($do == 'credit_info') {
	$member_info = mc_fetch($_W['member']['uid'], array('credit2'));
	$credit2 = !empty($member_info['credit2']) ? $member_info['credit2'] : 0;
	message(error(0, $credit2), '', 'ajax');
}