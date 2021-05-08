<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CreditsRecharge extends \We7Table {
	protected $tableName = 'mc_credits_recharge';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'openid',
		'tid',
		'transid',
		'fee',
		'type',
		'tag',
		'status',
		'createtime',
		'backtype',
	);
	protected $default = array(
		'uniacid' => '',
		'uid' => '',
		'openid' => '',
		'tid' => '',
		'transid' => '',
		'fee' => '',
		'type' => 'credit',
		'tag' => '0',
		'status' => 0,
		'createtime' => '',
		'backtype' => '',
	);


}