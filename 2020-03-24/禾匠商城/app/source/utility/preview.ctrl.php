<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('home');
$do = in_array($do, $dos) ? $do : exit('Access Denied');

if ($do == 'home') {
	$multiid = intval($_GPC['multiid']);
	$multi = table('site_multi')
		->select('styleid')
		->where(array('id' => $multiid))
		->get();
	$style = table('site_styles')
		->searchWithTemplates(array('a.*', 'b.name AS tname', 'b.title'))
		->where(array(
			'a.uniacid' => $_W['uniacid'],
			'a.id' => $multi['styleid']
		))
		->get();
	template("../{$style['tname']}/home/home");
}
