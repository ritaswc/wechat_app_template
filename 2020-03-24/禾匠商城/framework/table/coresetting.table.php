<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class CoresettingTable extends We7Table  {
	protected $tableName = 'core_settings';
	protected $field = array('key', 'value');

	public function getSettingList() {
		return $this->query->from($this->tableName)->getall('key');
	}

	public function settingSave($key, $data) {
		$is_exists = $this->query->from($this->tableName)->where('key', $key)->get();
		if (!empty($is_exists)) {
			$return = table('coresetting')->fillValue(iserializer($data))->whereKey($key)->save();
		} else {
			$return = table('coresetting')->fill(array('key'=> $key, 'value' => iserializer($data)))->save();
		}

		return $return;
	}
}