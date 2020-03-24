<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class Members extends \We7Table {
	protected $tableName = 'mc_members';
	protected $primaryKey = 'uid';
	protected $field = array(
		'address',
		'affectivestatus',
		'alipay',
		'avatar',
		'bio',
		'birthday',
		'birthmonth',
		'birthyear',
		'bloodtype',
		'company',
		'constellation',
		'createtime',
		'credit1',
		'credit2',
		'credit3',
		'credit4',
		'credit5',
		'credit6',
		'education',
		'email',
		'gender',
		'grade',
		'graduateschool',
		'groupid',
		'height',
		'idcard',
		'interest',
		'lookingfor',
		'mobile',
		'msn',
		'nationality',
		'nickname',
		'occupation',
		'password',
		'pay_password',
		'position',
		'qq',
		'realname',
		'residecity',
		'residedist',
		'resideprovince',
		'revenue',
		'salt',
		'site',
		'studentid',
		'taobao',
		'telephone',
		'uniacid',
		'user_from',
		'vip',
		'weight',
		'zipcode',
		'zodiac',
	);
	protected $default = array(
		'address' => '',
		'affectivestatus' => '',
		'alipay' => '',
		'avatar' => '',
		'bio' => '',
		'birthday' => '0',
		'birthmonth' => '0',
		'birthyear' => '0',
		'bloodtype' => '',
		'company' => '',
		'constellation' => '',
		'createtime' => '',
		'credit1' => '0.00',
		'credit2' => '0.00',
		'credit3' => '',
		'credit4' => '0.00',
		'credit5' => '0.00',
		'credit6' => '0.00',
		'education' => '',
		'email' => '',
		'gender' => '0',
		'grade' => '',
		'graduateschool' => '',
		'groupid' => '0',
		'height' => '',
		'idcard' => '',
		'interest' => '',
		'lookingfor' => '',
		'mobile' => '',
		'msn' => '',
		'nationality' => '',
		'nickname' => '',
		'occupation' => '',
		'password' => '',
		'pay_password' => '',
		'position' => '',
		'qq' => '',
		'realname' => '',
		'residecity' => '',
		'residedist' => '',
		'resideprovince' => '',
		'revenue' => '',
		'salt' => '',
		'site' => '',
		'studentid' => '',
		'taobao' => '',
		'telephone' => '',
		'uniacid' => '',
		'user_from' => '',
		'vip' => '0',
		'weight' => '',
		'zipcode' => '',
		'zodiac' => '',
	);

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithEmail($email) {
		return $this->query->where('email', $email);
	}

	public function searchWithMobile($mobile) {
		return $this->query->where('mobile', $mobile);
	}

	public function searchWithoutUid($uid) {
		return $this->query->where('uid <>', $uid);
	}

	public function searchCreditsRecordUid($uid) {
		$this->query->where('r.uid', $uid);
		return $this;
	}

	public function searchCreditsRecordType($type) {
		$this->query->where('r.credittype', $type);
		return $this;
	}

	public function searchWithMobileOrEmail($mobile_or_email) {
		$this->query->where('mobile', $mobile_or_email)->whereor('email', $mobile_or_email);
		return $this;
	}
	public function searchWithMappingFans() {
		return $this->query->from('mc_members', 'a')
			->leftjoin('mc_mapping_fans', 'b')
			->on(array('a.uid' => 'b.uid'));
	}

	public function searchWithAccount() {
		return $this->query->from('mc_members', 'm')
			->leftjoin('account', 'a')
			->on(array('m.uniacid' => 'a.uniacid'));
	}
}