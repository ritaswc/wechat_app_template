<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Qrcode;

class Qrcode extends \We7Table {
	protected $tableName = 'qrcode';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'acid',
		'type',
		'extra',
		'qrcid',
		'scene_str',
		'name',
		'keyword',
		'model',
		'ticket',
		'url',
		'expire',
		'subnum',
		'createtime',
		'status',
	);
	protected $default = array(
		'uniacid' => '0',
		'acid' => '',
		'type',
		'extra',
		'qrcid',
		'scene_str',
		'name',
		'keyword',
		'model' => 1,
		'ticket',
		'url',
		'expire',
		'subnum',
		'createtime' => 0,
		'status',
	);

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}
}