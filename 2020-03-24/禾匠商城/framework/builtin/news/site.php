<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class NewsModuleSite extends WeModuleSite {
	public function doMobileDetail() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$row = table('news_reply')->getById($id);
		if (!empty($row['url'])) {
			header('Location: ' . $row['url']);
		}
		$row = istripslashes($row);
		$title = $row['title'];
		
		if ('android' == $_W['os'] && 'wechat' == $_W['container'] && $_W['account']['account']) {
			$subscribeurl = "weixin://profile/{$_W['account']['account']}";
		} else {
			$subscribeurl = table('account_wechats')->where(array('uniacid' => intval($_W['uniacid'])))->getcolumn('subscribeurl');
		}
		include $this->template('detail');
	}
}