<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class UserapiModule extends WeModule {
	public $tablename = 'userapi_reply';

	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if (!empty($rid)) {
			$row = table($this->tablename)->where(array('rid' => $rid))->orderby(array('id' => 'DESC'))->get();
			$row['type'] = 1; 			if (!strexists($row['apiurl'], 'http://') && !strexists($row['apiurl'], 'https://')) {
				$row['apilocal'] = $row['apiurl'];
				$row['type'] = 0; 				$row['apiurl'] = '';
			}
		} else {
			$row = array(
				'cachetime' => 0,
				'type' => 1,
			);
		}
		$path = IA_ROOT . '/framework/builtin/userapi/api/';
		if (is_dir($path)) {
			$apis = array();
			if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
					if ('.' != $file && '..' != $file) {
						$apis[] = $file;
					}
				}
			}
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		global $_GPC;
		if (($_GPC['type'] && empty($_GPC['apiurl'])) || (empty($_GPC['type']) && empty($_GPC['apilocal']))) {
			itoast('请填写接口地址！', '', '');
		}
		if ($_GPC['type'] && empty($_GPC['token'])) {
			itoast('请填写Token值！', '', '');
		}

		return '';
	}

	public function fieldsFormSubmit($rid = 0) {
		global $_GPC, $_W;
		permission_check_account_user('platform_reply_userapi');
		$rid = intval($rid);
		$reply = array(
			'rid' => $rid,
			'description' => safe_gpc_string($_GPC['description']),
			'apiurl' => empty($_GPC['type']) ? safe_gpc_string($_GPC['apilocal']) : safe_gpc_string($_GPC['apiurl']),
			'token' => safe_gpc_string($_GPC['wetoken']),
			'default_text' => safe_gpc_string($_GPC['default-text']),
			'cachetime' => intval($_GPC['cachetime']),
		);
		$rule_exists = table('rule')->getById($rid, $_W['uniacid']);
		if (empty($rule_exists)) {
			return false;
		}
		$is_exists = table($this->tablename)->where(array('rid' => $rid))->getcolumn('id');
		if (!empty($is_exists)) {
			if (false !== table($this->tablename)->where(array('rid' => $rid))->fill($reply)->save()) {
				return true;
			}
		} else {
			if (table($this->tablename)->fill($reply)->save()) {
				return true;
			}
		}

		return false;
	}

	public function ruleDeleted($rid = 0) {
		global $_W;
		$rid = intval($rid);
		permission_check_account_user('platform_reply_userapi');
		$rule_exists = table('rule')->getById($rid, $_W['uniacid']);
		if (empty($rule_exists)) {
			return false;
		}
		$result = table($this->tablename)->where(array('rid' => $rid))->delete();
		return $result;
	}

	public function doSwitch() {
		global $_W, $_GPC;
		$m = array_merge($_W['modules']['userapi'], $_W['account']['modules']['userapi']);
		$cfg = $m['config'];
		if ($_W['ispost']) {
			$rids = explode(',', $_GPC['rids']);
			if (is_array($rids)) {
				$cfg = array();
				foreach ($rids as $rid) {
					$cfg[intval($rid)] = true;
				}
				$this->saveSettings($cfg);
			}
			exit();
		}
		load()->model('reply');
		$rs = reply_search("uniacid = 0 AND module = 'userapi' AND `status`=1");
		$ds = array();
		foreach ($rs as $row) {
			$reply = table($this->tablename)->where(array('rid' => $row['id']))->get();
			$r = array();
			$r['title'] = $row['name'];
			$r['rid'] = $row['id'];
			$r['description'] = $reply['description'];
			$r['switch'] = $cfg[$r['rid']] ? ' checked="checked"' : '';
			$ds[] = $r;
		}
		include $this->template('switch');
	}
}
