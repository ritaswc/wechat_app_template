<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->table('account');
class WebappTable extends AccountTable {

	
	public function createWebappInfo($attr, $uid) {
		$name = $attr['name'];
		$description = $attr['description'];
		$data = array(
			'name' => $name,
			'description' => $description,
			'title_initial' => get_first_pinyin($name),
			'groupid' => 0,
		);
		if (!pdo_insert('uni_account', $data)) {
			 return false;
		}
		$uniacid = pdo_insertid();
		if(!$uniacid) {
			return false;
		}
		$accountdata = array('uniacid' => $uniacid, 'type' => ACCOUNT_TYPE_WEBAPP_NORMAL, 'hash' => random(8));
		pdo_insert('account', $accountdata);
		$acid = pdo_insertid();
		pdo_update('uni_account', array('default_acid'=>$acid), array('uniacid'=>$uniacid));
		pdo_insert('account_webapp', array('uniacid'=>$uniacid, 'acid'=>$acid, 'name'=>$name));

		$unisettings['creditnames'] = array('credit1' => array('title' => '积分', 'enabled' => 1), 'credit2' => array('title' => '余额', 'enabled' => 1));
		$unisettings['creditnames'] = iserializer($unisettings['creditnames']);
		$unisettings['creditbehaviors'] = array('activity' => 'credit1', 'currency' => 'credit2');
		$unisettings['creditbehaviors'] = iserializer($unisettings['creditbehaviors']);
		$unisettings['uniacid'] = $uniacid;
		pdo_insert('uni_settings', $unisettings);

		$this->createLog($uniacid, $uid);
		return $uniacid;
	}

	
	private function createLog($uniacid, $uid) {
		if (empty($_W['isfounder'])) {
			$user_info = permission_user_account_num($uid);
			uni_user_account_role($uniacid, $uid, ACCOUNT_MANAGE_NAME_OWNER);
			if (empty($user_info['usergroup_webapp_limit'])) {
				pdo_update('account', array('endtime' => strtotime('+1 month', time())), array('uniacid' => $uniacid));
				pdo_insert('site_store_create_account', array('uid' => $uid, 'uniacid' => $uniacid, 'type' => ACCOUNT_TYPE_WEBAPP_NORMAL));
			}
		}
		if (user_is_vice_founder()) {
			uni_user_account_role($uniacid, $uid, ACCOUNT_MANAGE_NAME_VICE_FOUNDER);
		}
	}
}