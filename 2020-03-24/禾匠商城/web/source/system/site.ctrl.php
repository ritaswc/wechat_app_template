<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('system');

$dos = array('basic', 'copyright', 'about', 'save_setting');
$do = in_array($do, $dos) ? $do : 'basic';
$settings = $_W['setting']['copyright'];
if (empty($settings) || !is_array($settings)) {
	$settings = array();
} else {
	$settings['slides'] = iunserializer($settings['slides']);
}

if ('basic' == $do) {
	
		$path = IA_ROOT . '/web/themes/';
		if (is_dir($path)) {
			if ($handle = opendir($path)) {
				while (false !== ($templatepath = readdir($handle))) {
					if ('.' != $templatepath && '..' != $templatepath) {
						if (is_dir($path . $templatepath)) {
							$template[] = $templatepath;
						}
					}
				}
			}
		}

		$template_ch_name = system_template_ch_name();
		$login_ch_name = system_login_template_ch_name();
		$_W['setting']['basic']['login_template'] = !empty($_W['setting']['basic']['login_template']) ? $_W['setting']['basic']['login_template'] : 'base';
		$templates_ch = array_keys($template_ch_name);
		if (!empty($template)) {
			foreach ($template as $template_val) {
				if (!in_array($template_val, $templates_ch)) {
					$template_ch_name[$template_val] = $template_val;
				}
			}
		}
	
	if (!empty($settings['autosignout'])) {
		if ($settings['autosignout'] >= 60){
			$hour = floor($settings['autosignout']/60);
			$min = $settings['autosignout']%60;
			$res = $hour.'小时';
			$min != 0  &&  $res .= $min.'分钟';
		}else{
			$res = $settings['autosignout'].'分钟';
		}

		$settings['autosignout_notice'] = "系统无操作，{$res}后自动退出";
	}

}

if ('save_setting' == $do) {
	$system_setting_items = system_setting_items();
	$key = safe_gpc_string($_GPC['key']);

	switch ($key) {
		case 'policeicp':
			$settings[$key] = array(
				'policeicp_location' => safe_gpc_string($_GPC['value']['location']),
				'policeicp_code' => safe_gpc_string($_GPC['value']['code']),
			);
			break;
		case 'statcode':
			$settings[$key] = system_check_statcode($_GPC['value']);
			break;
		case 'url':
			$settings[$key] = (strexists($_GPC['value'], 'http://') || strexists($_GPC['value'], 'https://')) ? $_GPC['value'] : "http://{$_GPC['value']}";
			break;
		case 'footerleft':
			$settings[$key] = safe_gpc_html(htmlspecialchars_decode($_GPC['value']));
			break;
		case 'footerright':
			$settings[$key] = safe_gpc_html(htmlspecialchars_decode($_GPC['value']));
			break;
		case 'slides':
			$settings[$key] = iserializer($_GPC['value']);
			break;
		case 'companyprofile':
			$settings[$key] = safe_gpc_html(htmlspecialchars_decode($_GPC['value']));
			break;
		case 'template':
			break;
		case 'baidumap':
			break;
		case 'autosignout':
			$limit_time = 1*24*60;
			if ($limit_time < safe_gpc_int($_GPC['value']) || safe_gpc_int($_GPC['value'] < 1)) {
				iajax(-1, '自动退出时间请在1-'. $limit_time .'分钟内设置！', url('system/site'));
			}
			$settings[$key] = safe_gpc_int($_GPC['value']);
			break;
		case 'login_verify_status':
			if (!$_W['isfounder']) {
				iajax(-1, '您没有权限', url('system/site'));
			}
			if (empty($_W['user']['mobile'])) {
				iajax(-1, '您的账户还未绑定手机号，请先绑定手机号，再开启此功能');
			}
			$settings[$key] = 1 == $_GPC['is_int'] ? intval($_GPC['value']) : safe_gpc_string($_GPC['value']);
			$settings['login_verify_mobile'] = $_W['user']['mobile'];
			break;
		default:
			$settings[$key] = 1 == $_GPC['is_int'] ? intval($_GPC['value']) : safe_gpc_string($_GPC['value']);
			break;
	}

	if (!in_array($key, $system_setting_items)) {
		iajax(-1, '参数错误！', url('system/site'));
	}
	if (in_array($key, array('template', 'login_template'))) {
		$basic_setting = $_W['setting']['basic'];
		$basic_setting[$key] = safe_gpc_string($_GPC['value']);
		setting_save($basic_setting, 'basic');
	} elseif ($key = 'baidumap') {
		$settings['baidumap'] = array('lng' => $_GPC['lng'], 'lat' => $_GPC['lat']);
		setting_save($settings, 'copyright');
	} else {
		setting_save($settings, 'copyright');
	}

	iajax(0, '更新设置成功！', referer());
}

template('system/site');