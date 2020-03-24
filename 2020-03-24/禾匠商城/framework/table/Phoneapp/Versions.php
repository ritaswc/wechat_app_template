<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Phoneapp;

class Versions extends \We7Table {
	protected $tableName = 'phoneapp_versions';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'version',
		'description',
		'modules',
		'createtime',
	);
	protected $default = array(
		'uniacid' => '',
		'version' => '',
		'description' => '',
		'modules' => '',
		'createtime' => '',
	);

	public function getById($id) {
		$data = $this->where('id', $id)->get();
		if (empty($data)) {
			return array();
		}
		$data['modules'] = iunserializer($data['modules']);
		return $data;
	}

	public function getByUniacidAndVersion($uniacid, $version) {
		$data = $this->where('uniacid', $uniacid)->where('version', $version)->get();
		if (empty($data)) {
			return array();
		}
		$data['modules'] = iunserializer($data['modules']);
		return $data;
	}

	public function getLatestByUniacid($uniacid) {
		return $this->where('uniacid', $uniacid)->orderby('id', 'desc')->limit(4)->getall('id');
	}

	public function getLastByUniacid($uniacid) {
		$data = $this->where('uniacid', $uniacid)->orderby('id', 'desc')->get();
		if (empty($data)) {
			return array();
		}
		$data['modules'] = iunserializer($data['modules']);
		return $data;
	}

	public function getByUniacid($uniacid) {
		return $this->where('uniacid', $uniacid)->orderby('id', 'desc')->getall();
	}
}