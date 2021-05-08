<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class Article extends \We7Table {
	protected $tableName = 'site_article';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'rid',
		'kid',
		'iscommend',
		'ishot',
		'pcate',
		'ccate',
		'template',
		'title',
		'description',
		'content',
		'thumb',
		'incontent',
		'source',
		'author',
		'displayorder',
		'linkurl',
		'createtime',
		'edittime',
		'click',
		'type',
		'credit'
	);
	protected $default = array(
		'uniacid' => '',
		'rid' => '',
		'kid' => '',
		'iscommend' => 0,
		'ishot' => 0,
		'pcate' => 0,
		'ccate' => 0,
		'template' => '',
		'title' => '',
		'description' => '',
		'content' => '',
		'thumb' => '',
		'incontent' => 0,
		'source' => '',
		'author' => '',
		'displayorder' => 0,
		'linkurl' => '',
		'createtime' => 0,
		'edittime' => '',
		'click' => 0,
		'type' => '',
		'credit' => ''
	);
	public function getAllByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid)->getall();
	}

	public function getBySnake($fields = '*', $where = array(), $order = array('id' => 'DESC')) {
		return $this->query->select($fields)->where($where)->orderby($order);
	}
}