<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CashRecord extends \We7Table {
	protected $tableName = 'mc_cash_record';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'clerk_id',
		'store_id',
		'clerk_type',
		'fee',
		'final_fee',
		'credit1',
		'credit1_fee',
		'credit2',
		'cash',
		'return_cash',
		'final_cash',
		'remark',
		'createtime',
		'trade_type',
	);
	protected $default = array(
		'uniacid' => 0,
		'uid' => 0,
		'clerk_id' => 0,
		'store_id' => 0,
		'clerk_type' => 2,
		'fee' => 0,
		'final_fee' => 0,
		'credit1' => 0,
		'credit1_fee' => 0,
		'credit2' => 0,
		'cash' => 0,
		'return_cash' => 0,
		'final_cash' => 0,
		'remark' => '',
		'createtime' => 0,
		'trade_type' => '',
	);

}