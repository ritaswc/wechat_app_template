<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('bind_domain', 'delete', 'default_module');
$do = in_array($do, $dos) ? $do : 'bind_domain';

if ('bind_domain' == $do) {
	
		if (checksubmit('submit')) {
			$bind_domain = safe_gpc_string($_GPC['bind_domain']);
			if (!starts_with($bind_domain, 'http')) {
				iajax(-1, '要绑定的域名请以http://或以https://开头');
			}
			$special_domain = array('.com.cn', '.net.cn', '.gov.cn', '.org.cn', '.com.hk', '.com.tw');
			$domain = str_replace($special_domain, '.com', $bind_domain);
			$domain_array = explode('.', $domain);
			if (count($domain_array) > 3 || count($domain_array) < 2) {
				iajax(-1, '只支持一级域名和二级域名！');
			}
			$nohttp_domain = preg_replace('/^https?/', '', $bind_domain);
			$uniacid = table('uni_settings')
				->where(array('bind_domain' => array('http' . $nohttp_domain, 'https' . $nohttp_domain)))
				->getcolumn('uniacid');
			if (empty($uniacid) || $uniacid == $_W['uniacid']) {
				uni_setting_save('bind_domain', $bind_domain);
				iajax(0, '绑定成功！', referer());
			} else {
				$account_name = table('uni_account')
					->where(array('uniacid' => $uniacid))
					->getcolumn('name');
				iajax(-1, "绑定失败, 该域名已被 {$account_name} 绑定！", referer());
			}
		}
	
	$modulelist = uni_modules();
	if (!empty($modulelist)) {
		foreach ($modulelist as $key => $module_val) {
			if (!empty($module_val['issystem']) || MODULE_SUPPORT_WEBAPP != $module_val['webapp_support']) {
				unset($modulelist[$key]);
				continue;
			}
		}
	}
	template('webapp/bind-domain');
}

if ('delete' == $do) {
	uni_setting_save('bind_domain', '');
	itoast('删除成功！', referer(), 'success');
}

if ('default_module' == $do) {
	$module_name = safe_gpc_string($_GPC['module_name']);
	if (empty($module_name)) {
		iajax(-1, '请选择一个模块！');
	}
	$modulelist = array_keys(uni_modules());
	if (!in_array($module_name, $modulelist)) {
		iajax(-1, '模块不可用！');
	}
	uni_setting_save('default_module', $module_name);
	iajax(0, '修改成功！', referer());
}