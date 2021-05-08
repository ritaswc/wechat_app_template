<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class ChatsRecord extends \We7Table {
	protected $tableName = 'mc_chats_record';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'acid',
		'flag',
		'openid',
		'msgtype',
		'content',
		'createtime',
	);
	protected $default = array(
		'uniacid' => '',
		'acid' => '',
		'flag' => 1,
		'openid' => '',
		'msgtype' => '',
		'content' => '',
		'createtime' => '',
	);

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}
}