<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CardNotices extends \We7Table {
	protected $tableName = 'mc_card_notices';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'type',
		'title',
		'thumb',
		'groupid',
		'content',
		'addtime',
	);
	protected $default = array(
		'uniacid' => 0,
		'uid' => 0,
		'type' => 1,
		'title' => '',
		'thumb' => '',
		'groupid' => 0,
		'content' => '',
		'addtime' => 0,
	);
}