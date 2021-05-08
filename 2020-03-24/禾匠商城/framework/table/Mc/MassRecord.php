<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class MassRecord extends \We7Table {
	protected $tableName = 'mc_mass_record';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'acid',
		'groupname',
		'fansnum',
		'msgtype',
		'content',
		'group',
		'attach_id',
		'media_id',
		'type',
		'status',
		'cron_id',
		'sendtime',
		'finalsendtime',
		'createtime',
		'msg_id',
		'msg_data_id',
	);
	protected $default = array(
		'uniacid' => '',
		'acid' => '',
		'groupname' => '',
		'fansnum' => 0,
		'msgtype' => '',
		'content' => '',
		'group' => 0,
		'attach_id' => '',
		'media_id' => '',
		'type' => 0,
		'status' => 0,
		'cron_id' => '',
		'sendtime' => '',
		'finalsendtime' => '',
		'createtime' => '',
		'msg_id' => '',
		'msg_data_id' => '',
	);

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}
}