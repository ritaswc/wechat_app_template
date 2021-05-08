<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Coupon;

class Coupon extends \We7Table {
	protected $tableName = 'coupon';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'acid',
		'card_id',
		'type',
		'logo_url',
		'code_type',
		'brand_name',
		'title',
		'sub_title',
		'color',
		'notice',
		'description',
		'date_info',
		'quantity',
		'use_custom_code',
		'bind_openid',
		'can_share',
		'can_give_friend',
		'get_limit',
		'service_phone',
		'extra',
		'status',
		'is_display',
		'is_selfconsume',
		'promotion_url_name',
		'promotion_url',
		'promotion_url_sub_title',
		'source',
		'dosage'
	);
	protected $default = array(
		'uniacid' => '',
		'acid' => 0,
		'card_id' => 0,
		'type' => '',
		'logo_url' => '',
		'code_type' => 1,
		'brand_name' => '',
		'title' => '',
		'sub_title' => '',
		'color' => '',
		'notice',
		'description' => '',
		'date_info' => '',
		'quantity' => 0,
		'use_custom_code' => 0,
		'bind_openid' => 0,
		'can_share' => 1,
		'can_give_friend' => 1,
		'get_limit' => 0,
		'service_phone' => '',
		'extra' => '',
		'status' => 1,
		'is_display' => 1,
		'is_selfconsume' => 0,
		'promotion_url_name' => '',
		'promotion_url' => '',
		'promotion_url_sub_title' => '',
		'source' => 2,
		'dosage' => 0
	);
}