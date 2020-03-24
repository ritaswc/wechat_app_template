<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Account;

class Wechats extends \We7Table {
	protected $tableName = 'account_wechats';
	protected $primaryKey = 'acid';
	protected $field = array(
		'uniacid',
		'token',
		'encodingaeskey',
		'auth_refresh_token',
		'level',
		'name',
		'account',
		'original',
		'signature',
		'country',
		'province',
		'city',
		'username',
		'password',
		'lastupdate',
		'key',
		'secret',
		'styleid',
		'subscribeurl',
		'createtime',
	);
	protected $default = array(
		'uniacid' => '',
		'token' => '',
		'encodingaeskey' => '',
		'auth_refresh_token' => '',
		'level' => '0',
		'name' => '',
		'account' => '',
		'original' => '',
		'signature' => '',
		'country' => '',
		'province' => '',
		'city' => '',
		'username' => '',
		'password' => '',
		'lastupdate' => '0',
		'key' => '',
		'secret' => '',
		'styleid' => '1',
		'subscribeurl' => '',
		'createtime' => '',
	);

}