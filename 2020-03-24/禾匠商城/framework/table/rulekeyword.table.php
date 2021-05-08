<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class RulekeywordTable extends We7Table {
	protected $tableName = 'rule_keyword';
	protected $field = array('rid', 'uniacid', 'module', 'module', 'content', 'type', 'displayorder', 'status');
}