<?php

defined('IN_IA') or exit('Access Denied');

class PhoneappversionsTable extends We7Table {
	protected $tableName = 'phoneapp_versions';
	protected $primaryKey = 'id';

	public function phoneappLatestVersion($uniacid) {
		return $this->query->from($this->tableName)->where('uniacid', $uniacid)->orderby('id', 'desc')->limit(4)->getall('id');
	}

	public function phoneappLastVersion($uniacid) {
		return $this->query->from($this->tableName)->where('uniacid', $uniacid)->orderby('id', 'desc')->limit(1)->get();
	}

	public function phoneappAllVersion($uniacid) {
		return $this->query->from($this->tableName)->where('uniacid', $uniacid)->orderby('id', 'desc')->getall();
	}
}