<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class Category extends \We7Table {
	protected $tableName = 'site_category';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'nid',
		'name',
		'parentid',
		'displayorder',
		'enabled',
		'icon',
		'description',
		'styleid',
		'linkurl',
		'ishomepage',
		'icontype',
		'css',
		'multiid'
	);
	protected $default = array(
		'uniacid' => 0,
		'nid' => 0,
		'name' => '',
		'parentid' => 0,
		'displayorder' => 0,
		'enabled' => 1,
		'icon' => '',
		'description' => '',
		'styleid' => '',
		'linkurl' => '',
		'ishomepage' => 0,
		'icontype' => '',
		'css' => '',
		'multiid' => ''
	);

	public function getAllByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid)->getall();
	}
	public function getBySnake($fields = '*', $where = array(), $order = array('id' => 'DESC')) {
		return $this->query()->select($fields)->where($where)->orderBy($where);
	}
}