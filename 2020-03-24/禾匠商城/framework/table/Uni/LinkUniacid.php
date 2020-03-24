<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class LinkUniacid extends \We7Table {
	protected $tableName = 'uni_link_uniacid';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'link_uniacid',
		'module_name',
		'version_id',
	);
	protected $default = array(
		'uniacid' => 0,
		'link_uniacid' => 0,
		'module_name' => '',
		'version_id' => 0,
	);

	public function searchWithUniacidModulenameVersionid($uniacid, $module_name, $version_id = 0) {
		if (!empty($version_id)) {
			$this->where('version_id', $version_id);
		}
		$this->where('uniacid', $uniacid)->where('module_name', $module_name);
		return $this;
	}

	public function getMainUniacid($sub_uniacid, $module_name, $version_id = 0) {
		$this->searchWithUniacidModulenameVersionid($sub_uniacid, $module_name, $version_id);
		return $this->getcolumn('link_uniacid');
	}

	public function getSubUniacids($main_uniacid, $module_name, $version_id = 0) {
		if (!empty($version_id)) {
			$this->where('version_id', $version_id);
		}
		$data = $this->where('link_uniacid', $main_uniacid)
			->where('module_name', $module_name)
			->getall('uniacid');

		if (empty($data)) {
			return array();
		} else {
			return array_keys($data);
		}
	}

	public function getAllMainUniacidsByModuleName($module_name) {
		$data = $this->where('module_name', $module_name)
			->where('link_uniacid >', 0)
			->getall('link_uniacid');
		if (empty($data)) {
			$data = array();
		}
		return array_keys($data);
	}

	public function getAllSubUniacidsByModuleName($module_name) {
		$data = $this->where('module_name', $module_name)
			->where('link_uniacid >', 0)
			->getall('uniacid');
		if (empty($data)) {
			$data = array();
		}
		return array_keys($data);
	}
}