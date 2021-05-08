<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Paycenter;

class Order extends \We7Table {
	protected $tableName = 'paycenter_order';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'pid',
		'clerk_id',
		'store_id',
		'clerk_type',
		'uniontid',
		'transaction_id',
		'type',
		'trade_type',
		'body',
		'fee',
		'final_fee',
		'credit1',
		'credit1_fee',
		'credit2',
		'cash',
		'remark',
		'auth_code',
		'openid',
		'nickname',
		'follow',
		'status',
		'credit_status',
		'paytime',
		'createtime',
	);
	protected $default = array(
		'uniacid' => 0,
		'uid' => 0,
		'pid' => 0,
		'clerk_id' => 0,
		'store_id' => 0,
		'clerk_type' => 2,
		'uniontid' => '',
		'transaction_id' => '',
		'type' => '',
		'trade_type' => '',
		'body' => '',
		'fee' => '',
		'final_fee' => '0.00',
		'credit1' => 0,
		'credit1_fee' => '0.00',
		'credit2' => '0.00',
		'cash' => '0.00',
		'remark' => '',
		'auth_code' => '',
		'openid' => '',
		'nickname' => '',
		'follow' => 0,
		'status' => 0,
		'credit_status' => 0,
		'paytime' => 0,
		'createtime' => 0,
	);
}