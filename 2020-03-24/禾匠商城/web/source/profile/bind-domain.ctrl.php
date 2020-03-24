<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('bind_domain', 'delete');
$do = in_array($do, $dos) ? $do : 'bind_domain';

if ('bind_domain' == $do) {
	if (checksubmit('submit')) {
		$bind_domain = trim($_GPC['bind_domain']);
		if (!starts_with($bind_domain, 'http')) {
			iajax(-1, '要绑定的域名请以http://或以https://开头');
		}
		$special_domain = array('.com.cn', '.net.cn', '.gov.cn', '.org.cn', '.com.hk', '.com.tw');
		$bind_domain = str_replace($bind_domain, '.com', $bind_domain);
		$domain_array = explode('.', $bind_domain);
		if (count($domain_array) > 3 || count($domain_array) < 2) {
			iajax(-1, '只支持一级域名和二级域名！');
		}
		$data = array('domain' => safe_gpc_url(rtrim($_GPC['bind_domain'], '/'), false));
		uni_setting_save('bind_domain', iserializer($data));
		iajax(0, '更新成功！', referer());
	}
	template('profile/bind-domain');
}

if ('delete' == $do) {
	uni_setting_save('bind_domain', array());
	itoast('删除成功！', referer(), 'success');
}