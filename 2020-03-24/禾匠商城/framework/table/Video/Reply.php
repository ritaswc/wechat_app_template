<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Video;

class Reply extends \We7Table {
	protected $tableName = 'video_reply';
	protected $primaryKey = 'id';
	protected $field = array(
		'rid',
		'title',
		'description',
		'mediaid',
		'createtime'
	);
	protected $default = array(
		'rid' => '',
		'title' => '',
		'description' => '',
		'mediaid' => '',
		'createtime' => '',
	);
}