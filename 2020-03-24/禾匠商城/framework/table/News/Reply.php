<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\News;

class Reply extends \We7Table {
	protected $tableName = 'news_reply';
	protected $primaryKey = 'id';
	protected $field = array(
		'rid',
		'parent_id',
		'title',
		'author',
		'description',
		'thumb',
		'content',
		'url',
		'displayorder',
		'incontent',
		'createtime',
		'media_id',
	);
	protected $default = array(
		'rid' => '',
		'parent_id' => -1,
		'title' => '',
		'author' => '',
		'description' => '',
		'thumb' => '',
		'content' => '',
		'url' => '',
		'displayorder' => '',
		'incontent' => 0,
		'createtime' => '',
		'media_id' => '',
	);
}