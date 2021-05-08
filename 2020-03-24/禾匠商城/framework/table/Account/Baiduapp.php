<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Account;

class Baiduapp extends \We7Table {
	protected $tableName = 'account_baiduapp';
	protected $primaryKey = 'acid';
	protected $field = array(
		'uniacid',
		'name',
		'appid',
		'key',
		'secret',
		'description',
	);
	protected $default = array(
		'uniacid' => '',
		'name' => '',
		'appid' => '',
		'key' => '',
		'secret' => '',
		'description' => '',
	);

	public function getAccount($acid) {
		return $this->query->where('acid', $acid)->get();
	}
}