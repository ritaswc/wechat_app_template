<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Rule;

class Keyword extends \We7Table {
	protected $tableName = 'rule_keyword';
	protected $primaryKey = 'id';
	protected $field = array(
		'rid',
		'uniacid',
		'module',
		'content',
		'type',
		'displayorder',
		'status',
	);
	protected $default = array(
		'rid' => '0',
		'uniacid' => '0',
		'module' => '',
		'content' => '',
		'type' => '1',
		'displayorder' => '1',
		'status' => '1',
	);

	public function getByUniacidAndContent($uniacid, $content, $status = 1) {
		return $this->where(array(
			'uniacid' => $uniacid,
			'content' => $content,
			'status' => $status,
		))->get();
	}
}