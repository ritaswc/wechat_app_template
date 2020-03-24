<?php

defined('IN_IA') or exit('Access Denied');

load()->table('account');
class PhoneappTable extends AccountTable {
	public function createPhoneApp($data) {
		$account = array(
			'name' => $data['name'],
			'description' => $data['description'],
			'title_initial' => get_first_pinyin($data['name']),
			'groupid' => 0,
		);
		if (!pdo_insert('uni_account', $account)) {
			return false;
		}

		$uniacid = pdo_insertid();
		if (empty($uniacid)) {
			return false;
		}

		$accountdata = array('uniacid' => $uniacid, 'type' => ACCOUNT_TYPE_PHONEAPP_NORMAL, 'hash' => random(8));
		pdo_insert('account', $accountdata);
		$acid = pdo_insertid();
		pdo_update('uni_account', array('default_acid' => $acid), array('uniacid' => $uniacid));

		pdo_insert('account_phoneapp', array('uniacid' => $uniacid, 'acid' => $acid, 'name' => $data['name']));

		pdo_insert('phoneapp_versions', array('uniacid' => $uniacid, 'version' => $data['version'], 'description' => $data['description'], 'modules' => $data['modules'], 'createtime' => TIMESTAMP));

		return true;
	}

	public function phoneappAccountInfo() {
		return $this->query->from('account_phoneapp')->get();
	}
}