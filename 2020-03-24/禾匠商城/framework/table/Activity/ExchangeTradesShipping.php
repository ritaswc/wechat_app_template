<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Activity;

class ExchangeTradesShipping extends \We7Table {
	protected $tableName = 'activity_exchange_trades_shipping';
	protected $primaryKey = 'tid';
	protected $field = array(
		'uniacid',
		'uid',
		'exid',
		'status',
		'createtime',
		'province',
		'city',
		'district',
		'address',
		'zipcode',
		'mobile',
		'name'
	);
	protected $default = array(
		'uniacid' => '',
		'uid' => '',
		'exid' => '',
		'status' => 0,
		'createtime' => '',
		'province' => '',
		'city' => '',
		'district' => '',
		'address' => '',
		'zipcode' => '',
		'mobile' => '',
		'name' => ''
	);
}