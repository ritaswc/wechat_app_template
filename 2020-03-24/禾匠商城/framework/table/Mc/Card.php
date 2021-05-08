<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class Card extends \We7Table {
	protected $tableName = 'mc_card';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'title',
		'color',
		'background',
		'logo',
		'format_type',
		'format',
		'description',
		'fields',
		'snpos',
		'status',
		'business',
		'discount_type',
		'discount',
		'grant',
		'grant_rate',
		'offset_rate',
		'offset_max',
		'nums_status',
		'nums_text',
		'nums',
		'times_status',
		'times_text',
		'times',
		'params',
		'html',
		'recommend_status',
		'sign_status',
		'brand_name',
		'notice',
		'quantity',
		'max_increase_bonus',
		'least_money_to_use_bonus',
		'source',
		'card_id',
	);
	protected $default = array(
		'uniacid' => '',
		'title' => '',
		'color' => '',
		'background' => '',
		'logo' => '',
		'format_type' => 0,
		'format' => '',
		'description' => '',
		'fields' => '',
		'snpos' => '',
		'status' => 1,
		'business' => '',
		'discount_type' => '',
		'discount' => '',
		'grant' => '',
		'grant_rate' => 0,
		'offset_rate' => 0,
		'offset_max' => 0,
		'nums_status' => 0,
		'nums_text' => '',
		'nums' => '',
		'times_status' => 0,
		'times_text' => '',
		'times' => '',
		'params' => '',
		'html' => '',
		'recommend_status' => 0,
		'sign_status' => 0,
		'brand_name' => '',
		'notice' => '',
		'quantity' => 0,
		'max_increase_bonus' => 0,
		'least_money_to_use_bonus' => 0,
		'source' => 1,
		'card_id' => '',
	);

	public function getByStatus($status, $uniacid) {
		return $this->query->where('status', $status)->where('uniacid', $uniacid)->get();
	}
}