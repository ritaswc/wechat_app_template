<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class Handsel extends \We7Table {
	protected $tableName = 'mc_handsel';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'touid',
		'fromuid',
		'module',
		'sign',
		'action',
		'credit_value',
		'createtime'
	);
	protected $default = array(
		'uniacid' => '',
		'touid' => '',
		'fromuid' => '',
		'module' => '',
		'sign' => '',
		'action' => '',
		'credit_value' => '',
		'createtime' => ''
	);

	public function getByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid)->get();
	}

	public function getBySnake($fields = '*', $where = array(), $order = array('id' => 'DESC')) {
		return $this->query->select($fields)->where($where)->orderby($order);
	}
}