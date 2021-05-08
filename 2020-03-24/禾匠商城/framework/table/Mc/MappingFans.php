<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class MappingFans extends \We7Table {
	protected $tableName = 'mc_mapping_fans';
	protected $primaryKey = 'fanid';
	protected $field = array(
		'acid',
		'uniacid',
		'uid',
		'openid',
		'nickname',
		'groupid',
		'salt',
		'follow',
		'followtime',
		'unfollowtime',
		'tag',
		'updatetime',
		'unionid',
		'user_from',
	);
	protected $default = array(
		'acid' => '',
		'uniacid' => '0',
		'uid' => '0',
		'openid' => '',
		'nickname' => '',
		'groupid' => '',
		'salt' => '',
		'follow' => '1',
		'followtime' => '',
		'unfollowtime' => '',
		'tag' => '',
		'updatetime' => '0',
		'unionid' => '',
		'user_from' => '',
	);

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithOpenid($openid) {
		return $this->query->where('openid', $openid);
	}

	public function searchWithUnionid($unionid) {
		return $this->query->where('unionid', $unionid);
	}
}