<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Wxapp;

class Versions extends \We7Table {
	protected $tableName = 'wxapp_versions';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'multiid',
		'version',
		'description',
		'modules',
		'design_method',
		'template',
		'quickmenu',
		'createtime',
		'appjson',
		'default_appjson',
		'use_default',
		'type',
		'entry_id',
		'last_modules',
		'upload_time',
		'tominiprogram',
	);
	protected $default = array(
		'uniacid' => '',
		'multiid' => '',
		'version' => '',
		'description' => '',
		'modules' => '',
		'design_method' => '',
		'template' => '',
		'quickmenu' => '',
		'createtime' => '',
		'appjson' => '',
		'default_appjson' => '',
		'use_default' => 1,
		'type' => 0,
		'entry_id' => 0,
		'last_modules' => '',
		'upload_time' => 0,
		'tominiprogram' => '',
	);

	
	public function latestVersion($uniacid) {
		return $this->query->where('uniacid', $uniacid)->orderby('id', 'desc')->limit(4)->getall('id');
	}

	public function getLastByUniacid($uniacid) {
		$result = $this->where('uniacid', $uniacid)->orderby('id', 'desc')->get();
		if (empty($result)) {
			return array();
		}
		$result = $this->dataunserializer($result);
		return $result;
	}

	public function getByUniacidAndVersion($uniacid, $version) {
		$result = $this->query->where('uniacid', $uniacid)->where('version', $version)->get();
		if (empty($result)) {
			return array();
		}
		$result = $this->dataunserializer($result);
		return $result;
	}

	public function getAllByUniacid($uniacid) {
		$result = $this->where('uniacid', $uniacid)->orderby(array('upload_time' => 'DESC', 'id' => 'DESC'))->getall();
		if (empty($result)) {
			return array();
		}
		foreach ($result as $key => $row) {
			$result[$key] = $this->dataunserializer($row);
		}
		return $result;
	}
	public function dataunserializer($data) {
		if (!empty($data['modules'])) {
			$data['modules'] = iunserializer($data['modules']);
		}
		if (!empty($data['quickmenu'])) {
			$data['quickmenu'] = iunserializer($data['quickmenu']);
		}
		if (!empty($data['last_modules'])) {
			$data['last_modules'] = iunserializer($data['last_modules']);
		}
		return $data;
	}
}