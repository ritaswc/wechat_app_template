<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class UniaccountmodulesTable extends We7Table {
	protected $tableName = 'uni_account_modules';
	protected $field = array('uniacid', 'name', 'module', 'containtype', 'displayorder', 'status', 'reply_type');
}