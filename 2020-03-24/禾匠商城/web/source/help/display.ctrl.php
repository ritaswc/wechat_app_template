<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
global $_W;
load()->model('user');
load()->model('system');

$_W['page']['title'] = '帮助系统';
$dos = array('custom', 'system');
$do = in_array($do, $dos) ? $do : 'system';

if ('custom' == $do) {
	$site_info = system_site_info();
	$wiki_id = !empty($site_info['wiki']) ? intval($site_info['wiki']) : 0;
}
template('help/display');