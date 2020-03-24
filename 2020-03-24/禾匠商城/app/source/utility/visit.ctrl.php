<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('visit');

$dos = array('showjs', 'health');
$do = in_array($do, $dos) ? $do : 'showjs';

if ($do == 'showjs') {
	$module_name = !empty($_GPC['m']) ? $_GPC['m'] : 'wesite';
	visit_app_update_today_visit($module_name);
}


if($do == 'health') {
	echo json_encode(error(0, 'success'));
	exit;
}