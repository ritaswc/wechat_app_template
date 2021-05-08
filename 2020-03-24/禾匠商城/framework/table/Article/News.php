<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Article;

class News extends \We7Table {
	protected $tableName = 'article_news';
	protected $primaryKey = 'id';
	protected $field = array(
		'cateid',
		'title',
		'content',
		'thumb',
		'source',
		'author',
		'displayorder',
		'is_display',
		'is_show_home',
		'createtime',
		'click',
	);
	protected $default = array(
		'cateid' => 0,
		'title' => '',
		'content' => '',
		'thumb' => '',
		'source' => '',
		'author' => '',
		'displayorder' => 0,
		'is_display' => 1,
		'is_show_home' => 1,
		'createtime' => 0,
		'click' => 0,
	);

	public function searchWithCreatetimeRange($time) {
		return $this->where('createtime >=', strtotime("-{$time} days"));
	}

	public function searchWithTitle($title) {
		return $this->where('title LIKE', "%{$title}%");
	}
}