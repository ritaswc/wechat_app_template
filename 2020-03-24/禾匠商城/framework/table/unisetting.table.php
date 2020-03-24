<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class UnisettingTable extends We7Table {

	protected $tableName = 'uni_settings';
	protected $primaryKey = 'uniacid';

	public function getOauthByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid)->get();
	}
}