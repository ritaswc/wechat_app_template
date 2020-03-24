<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class ArticlecategoryTable extends We7Table {
	protected $tableName = 'article_category';

	public function getNewsCategoryLists() {
		return $this->query->from($this->tableName)->where('type', 'news')->orderby('displayorder', 'DESC')->getall('id');
	}

	public function getNoticeCategoryLists() {
		return $this->query->from($this->tableName)->where('type', 'notice')->orderby('displayorder', 'DESC')->getall('id');
	}
}