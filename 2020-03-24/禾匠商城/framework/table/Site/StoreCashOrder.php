<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class StoreCashOrder extends \We7Table {
	protected $tableName = 'site_store_cash_order';
	protected $primaryKey = 'id';
	protected $field = array(
		'number',
		'founder_uid',
		'order_id',
		'goods_id',
		'order_amount',
		'cash_log_id',
		'status',
		'create_time'
	);
	protected $default = array(
		'number' => '',
		'founder_uid' => 0,
		'order_id' => 0,
		'goods_id' => 0,
		'order_amount' => 0,
		'cash_log_id' => 0,
		'status' => 1,
		'create_time' =>0
	);
}