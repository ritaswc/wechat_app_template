<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class FansTag extends \We7Table {
	protected $tableName = 'mc_fans_tag';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'fanid',
		'openid',
		'subscribe',
		'nickname',
		'sex',
		'language',
		'city',
		'province',
		'country',
		'headimgurl',
		'subscribe_time',
		'unionid',
		'remark',
		'groupid',
		'tagid_list',
		'subscribe_scene',
		'qr_scene',
		'qr_scene_str',

	);
	protected $default = array(
		'uniacid' => '',
		'fanid' => '',
		'openid' => '',
		'subscribe' => '0',
		'nickname' => '',
		'sex' => '0',
		'language' => '',
		'city' => '',
		'province' => '',
		'country' => '',
		'headimgurl' => '',
		'subscribe_time' => '',
		'unionid' => '',
		'remark' => '',
		'groupid' => '',
		'tagid_list' => '',
		'subscribe_scene' => '',
		'qr_scene' => '',
		'qr_scene_str' => '',

	);

	public function getByOpenid($openid) {
		$result = $this->query->where('openid', $openid)->get();
		return $result;
	}

}