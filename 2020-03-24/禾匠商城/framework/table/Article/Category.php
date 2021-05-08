<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Article;

class Category extends \We7Table {
	protected $tableName = 'article_category';
	protected $primaryKey = 'id';
	protected $field = array(
		'title',
		'displayorder',
		'type',
	);
	protected $default = array(
		'title' => '',
		'displayorder' => 0,
		'type' => '',
	);

	public function getNewsCategoryLists() {
		return $this->where('type', 'news')->orderby(array('displayorder' => 'DESC', 'id' => 'DESC'))->getall('id');
	}

	public function getNoticeCategoryLists() {
		return $this->where('type', 'notice')->orderby(array('displayorder' => 'DESC', 'id' => 'DESC'))->getall('id');
	}
}