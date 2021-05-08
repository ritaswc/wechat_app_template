<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Article;

class Notice extends \We7Table {
	protected $tableName = 'article_notice';
	protected $primaryKey = 'id';
	protected $field = array(
		'cateid',
		'title',
		'content',
		'displayorder',
		'is_display',
		'is_show_home',
		'createtime',
		'click',
		'style',
		'group',
	);
	protected $default = array(
		'cateid' => 0,
		'title' => '',
		'content' => '',
		'displayorder' => 0,
		'is_display' => 1,
		'is_show_home' => 1,
		'createtime' => 0,
		'click' => 0,
		'style' => '',
		'group' => '',
	);

	public function getList() {
		$data = $this->getall();
		if (empty($data)) {
			return array();
		}
		foreach ($data as $key => $row) {
			$data[$key]['style'] = iunserializer($row['style']);
			$data[$key]['group'] = iunserializer($row['group']);
		}
		return $data;
	}

	public function searchWithCreatetimeRange($time) {
		return $this->where('createtime >=', strtotime("-{$time} days"));
	}

	public function searchWithTitle($title) {
		return $this->where('title LIKE', "%{$title}%");
	}

	public function searchWithIsDisplay() {
		return $this->where('is_display', 1);
	}
}