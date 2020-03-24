<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class LoginLogs extends \We7Table {
	protected $tableName = 'users_login_logs';
	protected $primaryKey = 'id';
	protected $field = array(
		'ip',
		'uid',
		'city',
		'login_at',
	);
	protected $default = array(
		'ip' => 0,
		'uid' => 0,
		'city' => '',
		'login_at' => 0,
	);
}