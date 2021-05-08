<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class ExtraLimit extends \We7Table {
	protected $tableName = 'users_extra_limit';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'maxaccount',
		'maxwxapp',
		'maxwebapp',
		'maxphoneapp',
		'maxxzapp',
		'maxaliapp',
		'maxbaiduapp',
		'maxtoutiaoapp',
		'timelimit',

	);
	protected $default = array(
		'uid' => '0',
		'maxaccount' => '0',
		'maxwxapp' => '0',
		'maxwebapp' => '0',
		'maxphoneapp' => '0',
		'maxxzapp' => '0',
		'maxaliapp' => '0',
		'maxbaiduapp' => '0',
		'maxtoutiaoapp' => '0',
		'timelimit' => '0',

	);

	public function getExtraLimitByUid($uid) {
		return $this->where(array('uid' => $uid))->get();
	}

	public function saveExtraLimit($data, $uid) {
		if (!empty($data['uid'])) {
			$update_res = $this->where(array('uid' => $data['uid']))->fill($data)->save();
			return $update_res;
		} else {
			$data['uid'] = $uid;
			$insert_res = $this->fill($data)->save();
			return $insert_res;
		}
	}


}