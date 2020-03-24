<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class SitetemplatesTable extends We7Table {
	protected $tableName = 'site_templates';

	public function getAllTemplates() {
		return $this->query->getall('name');
	}
	public function getTemplateInfo($name) {
		return $this->query->from($this->tableName)->where('name', $name)->get();
	}
}