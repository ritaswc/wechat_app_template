<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class Nav extends \We7Table {
	protected $tableName = 'site_nav';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'multiid',
		'categoryid',
		'section',
		'module',
		'displayorder',
		'name',
		'description',
		'position',
		'url',
		'icon',
		'css',
		'status',
	);
	protected $default = array(
		'uniacid' => '',
		'multiid' => '',
		'categoryid' => '0',
		'section' => '0',
		'module' => '',
		'displayorder' => '',
		'name' => '',
		'description' => '',
		'position' => '1',
		'url' => '',
		'icon' => '',
		'css' => '',
		'status' => '1',
	);

	public function searchWithPosition($position) {
		return $this->query->where('position', $position);
	}

	public function searchWithStatus($stauts) {
		return $this->query->where('status', $stauts);
	}

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithMultiid($multiid) {
		return $this->query->where('multiid', $multiid);
	}

	public function getBySnake($fields = '*', $where = array(), $order = array('id' => 'DESC')) {
		return $this->query->select($fields)->where($where)->orderby($order);
	}
}