<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class Page extends \We7Table {
	protected $tableName = 'site_page';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'multiid',
		'title',
		'description',
		'params',
		'html',
		'multipage',
		'type',
		'status',
		'createtime',
		'goodnum',
	);
	protected $default = array(
		'uniacid' => 0,
		'multiid' => 0,
		'title' => '',
		'description' => '',
		'params' => '',
		'html' => '',
		'multipage' => '',
		'type' => 1,
		'status' => 1,
		'createtime' => 0,
		'goodnum' => 0,
	);
	public function searchWithMultiid($id) {
		return $this->query->where('multiid', $id);
	}
}