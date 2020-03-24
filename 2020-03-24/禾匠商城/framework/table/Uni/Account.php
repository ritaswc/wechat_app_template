<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class Account extends \We7Table {
	protected $tableName = 'uni_account';
	protected $primaryKey = 'uniacid';
	protected $field = array(
		'groupid',
		'default_acid',
		'name',
		'description',
		'rank',
		'createtime',
		'title_initial',
		'create_uid',
	);
	protected $default = array(
		'groupid' => '0',
		'default_acid' => '0',
		'name' => '',
		'description' => '',
		'rank' => '0',
		'createtime' => '',
		'title_initial' => '',
		'create_uid' => '0',
	);
	public function searchWithAccount() {
		return $this->query->from($this->tableName, 'a')
			->leftjoin('account', 'b')
			->on('a.uniacid', 'b.uniacid');
	}
}