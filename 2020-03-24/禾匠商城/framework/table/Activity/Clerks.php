<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Activity;

class Clerks extends \We7Table {
	protected $tableName = 'activity_clerks';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'storeid',
		'name',
		'password',
		'mobile',
		'openid',
		'nickname',
	);
	protected $default = array(
		'uniacid' => '',
		'uid' => 0,
		'storeid' => 0,
		'name' => '',
		'password' => '',
		'mobile' => '',
		'openid' => '',
		'nickname' => '',
	);

	public function getByUid($uid, $uniacid) {
		return $this->query->where('uid', $uid)->where('uniacid', $uniacid)->get();
	}

	public function getByOpenid($openid, $uniacid) {
		return $this->query->where('openid', $openid)->where('uniacid', $uniacid)->get();
	}
}