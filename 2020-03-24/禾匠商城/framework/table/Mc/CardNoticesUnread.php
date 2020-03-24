<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CardNoticesUnread extends \We7Table {
	protected $tableName = 'mc_card_notices_unread';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'notice_id',
		'uid',
		'is_new',
		'type',
	);
	protected $default = array(
		'uniacid' => 0,
		'notice_id' => 0,
		'uid' => 0,
		'is_new' => 1,
		'type' => 1,
	);
}