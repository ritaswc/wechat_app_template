<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('display', 'post');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

$module_name = trim($_GPC['m']);
$modulelist = uni_modules();
$module = $_W['current_module'] = $modulelist[$module_name];
$module_shortcut_talbe = table('uni_account_modules_shortcut');

if (empty($module)) {
	itoast('抱歉，你操作的模块不能被访问！');
}

if ('display' == $do) {
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize = 15;

	$list = $module_shortcut_talbe->getShortcutListByUniacidAndModule($_W['uniacid'], $module_name, $pageindex, $pagesize);
	$list['pager'] = pagination($list['total'], $pageindex, $pagesize, '', array('ajaxcallback' => true, 'callbackfuncname' => 'changePage'));

	if ($_W['ispost'] && $_W['isajax']) {
		iajax(0, $list);
	}

	template('module/shortcut');
}

if ('post' == $do) {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$shortcut_info = $module_shortcut_talbe->getShortcutById($id);
	}

	if ($_W['ispost']) {
		$data = array();
		$data['title'] = safe_gpc_string($_GPC['title']);
		$data['url'] = safe_gpc_url($_GPC['url'], false);
		$data['icon'] = safe_gpc_string($_GPC['icon']);
		$data['version_id'] = intval($_GPC['version_id']);
		if (!empty($shortcut_info) && ($shortcut_info['uniacid'] != $_W['uniacid'] || $shortcut_info['module_name'] != $module_name)) {
			itoast('请不要尝试修改非本账号下本模块的数据', referer(), 'error');
		}
		if (empty($shortcut_info)) {
			$data['uniacid'] = $_W['uniacid'];
			$data['module_name'] = $module_name;
		}
		$res = $module_shortcut_talbe->saveShortcut($data, $id);
		if ($res) {
			itoast('保存成功', url('module/shortcut/display', array('uniacid' => $_W['uniacid'], 'm' => $module_name)));
		} else {
			itoast('保存失败', referer(), 'error');
		}
	}
	template('module/shortcut-post');
}

if ('delete' == $do) {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		itoast('请求错误，请刷新页面重试！', referer(), 'error');
	}
	$if_exist = $module_shortcut_talbe->getShortcutById($id);
	if (empty($if_exist) || $if_exist['uniacid'] != $_W['uniacid'] || $if_exist['module_name'] != $module_name) {
		itoast('本模块无此快捷入口，请刷新重试！', referer(), 'error');
	}
	$res = $module_shortcut_talbe->deleteShortcutById($id);
	if ($res) {
		itoast('删除成功', url('module/shortcut/display', array('uniacid' => $_W['uniacid'], 'm' => $module_name)));
	} else {
		itoast('删除失败', referer(), 'error');
	}
}
