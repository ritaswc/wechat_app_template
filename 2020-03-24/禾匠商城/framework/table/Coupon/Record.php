<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Coupon;

class Record extends \We7Table {
	protected $tableName = 'coupon_record';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'acid',
		'card_id',
		'openid',
		'friend_openid',
		'givebyfriend',
		'code',
		'hash',
		'addtime',
		'usetime',
		'status',
		'clerk_name',
		'clerk_id',
		'store_id',
		'clerk_type',
		'couponid',
		'uid',
		'grantmodule',
		'remark',
	);
	protected $default = array(
		'uniacid' => '',
		'acid' => '',
		'card_id' => '',
		'openid' => '',
		'friend_openid' => '',
		'givebyfriend' => '',
		'code' => '',
		'hash' => '',
		'addtime' => '',
		'usetime' => '',
		'status' => '',
		'clerk_name' => '',
		'clerk_id' => '',
		'store_id' => '',
		'clerk_type' => '',
		'couponid' => '',
		'uid' => '',
		'grantmodule' => '',
		'remark' => '',
	);
}