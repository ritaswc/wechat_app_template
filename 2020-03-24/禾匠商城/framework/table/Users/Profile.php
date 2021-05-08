<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class Profile extends \We7Table {
	protected $tableName = 'users_profile';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'createtime',
		'edittime',
		'realname',
		'nickname',
		'avatar',
		'qq',
		'mobile',
		'fakeid',
		'vip',
		'gender',
		'birthyear',
		'birthmonth',
		'birthday',
		'constellation',
		'zodiac',
		'telephone',
		'idcard',
		'studentid',
		'grade',
		'address',
		'zipcode',
		'nationality',
		'resideprovince',
		'residecity',
		'residedist',
		'graduateschool',
		'company',
		'education',
		'occupation',
		'position',
		'revenue',
		'affectivestatus',
		'lookingfor',
		'bloodtype',
		'height',
		'weight',
		'alipay',
		'msn',
		'email',
		'taobao',
		'site',
		'bio',
		'interest',
		'workerid',
		'send_expire_status',
		'is_send_mobile_status',
		'location',
	);
	protected $default = array(
		'uid' => '',
		'createtime' => '',
		'edittime' => '',
		'realname' => '',
		'nickname' => '',
		'avatar' => '',
		'qq' => '',
		'mobile' => '',
		'fakeid' => '',
		'vip' => '0',
		'gender' => '0',
		'birthyear' => '0',
		'birthmonth' => '0',
		'birthday' => '0',
		'constellation' => '',
		'zodiac' => '',
		'telephone' => '',
		'idcard' => '',
		'studentid' => '',
		'grade' => '',
		'address' => '',
		'zipcode' => '',
		'nationality' => '',
		'resideprovince' => '',
		'residecity' => '',
		'residedist' => '',
		'graduateschool' => '',
		'company' => '',
		'education' => '',
		'occupation' => '',
		'position' => '',
		'revenue' => '',
		'affectivestatus' => '',
		'lookingfor' => '',
		'bloodtype' => '',
		'height' => '',
		'weight' => '',
		'alipay' => '',
		'msn' => '',
		'email' => '',
		'taobao' => '',
		'site' => '',
		'bio' => '',
		'interest' => '',
		'workerid' => '',
		'send_expire_status' => '0',
		'is_send_mobile_status' => '',
		'location' => '',
	);

	public function getByUid($uid) {
		return $this->query->where('uid', $uid)->get();
	}

	public function getByMobile($mobile) {
		return $this->query->where('mobile', $mobile)->get();
	}
}