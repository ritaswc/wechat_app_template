<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Qrcode;

class Stat extends \We7Table {
	protected $tableName = 'qrcode_stat';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'acid',
		'qid',
		'openid',
		'type',
		'qrcid',
		'scene_str',
		'name',
		'createtime',
	);
	protected $default = array(
		'uniacid' => '0',
		'acid' => '',
		'qid' => '',
		'openid' => '',
		'type' => '0',
		'qrcid' => '0',
		'scene_str' => '',
		'name' => '',
		'createtime' => '0',
	);

	public function searchWithTime($start_time, $end_time) {
		return $this->query->where('createtime >=', $start_time)->where('createtime <=', $end_time);
	}

	public function searchWithKeyword($keyword) {
		return $this->query->where('name LIKE', "%{$keyword}%");
	}

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}
}