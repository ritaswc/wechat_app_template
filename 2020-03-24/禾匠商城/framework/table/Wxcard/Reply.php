<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Wxcard;

class Reply extends \We7Table {
	protected $tableName = 'wxcard_reply';
	protected $primaryKey = 'id';
	protected $field = array(
		'rid',
		'title',
		'card_id',
		'cid',
		'brand_name',
		'logo_url',
		'success',
		'error'
	);
	protected $default = array(
		'rid' => 0,
		'title' => '',
		'card_id' => '',
		'cid' => 0,
		'brand_name' => '',
		'logo_url' => '',
		'success' => '',
		'error' => ''
	);
}