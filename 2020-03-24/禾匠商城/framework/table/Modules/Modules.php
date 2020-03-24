<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Modules extends \We7Table {
	protected $tableName = 'modules';
	protected $primaryKey = 'mid';
	protected $field = array(
		'name',
		'type',
		'title',
		'title_initial',
		'version',
		'ability',
		'description',
		'author',
		'url',
		'settings',
		'subscribes',
		'handles',
		'isrulefields',
		'issystem',
		'target',
		'iscard',
		'permissions',
		'wxapp_support',
		'account_support',
		'welcome_support',
		'webapp_support',
		'oauth_type',
		'phoneapp_support',
		'xzapp_support',
		'aliapp_support',
		'logo',
		'baiduapp_support',
		'toutiaoapp_support',
		'cloud_record',
	);
	protected $default = array(
		'name' => '',
		'type' => '',
		'title' => '',
		'title_initial' => '',
		'version' => '',
		'ability' => '',
		'description' => '',
		'author' => '',
		'url' => '',
		'settings' => '0',
		'subscribes' => '',
		'handles' => '',
		'isrulefields' => '0',
		'issystem' => '0',
		'target' => '0',
		'iscard' => '0',
		'permissions' => '',
		'wxapp_support' => '1',
		'account_support' => '1',
		'welcome_support' => '1',
		'webapp_support' => '1',
		'oauth_type' => '1',
		'phoneapp_support' => '1',
		'xzapp_support' => '1',
		'aliapp_support' => '1',
		'logo' => '',
		'baiduapp_support' => '1',
		'toutiaoapp_support' => '1',
		'cloud_record' => 0
	);

	public function bindings() {
		return $this->hasMany('modules_bindings', 'module', 'name');
	}

	public function getByName($modulename) {
		if (empty($modulename)) {
			return array();
		}
		return $this->query->where('name', $modulename)->get();
	}

	public function getByNameList($modulename_list, $get_system = false) {
		$this->query->whereor('name', $modulename_list)->orderby('mid', 'desc');
		if (!empty($get_system)) {
			$this->whereor('issystem', 1);
		}
		return $this->query->getall('name');
	}

	public function deleteByName($modulename) {
		return $this->query->where('name', $modulename)->delete();
	}

	public function getByHasSubscribes() {
		return $this->query->select('name', 'subscribes')->where('subscribes !=', '')->getall();
	}

	public function getSupportWxappList() {
		return $this->query->where('wxapp_support', MODULE_SUPPORT_WXAPP)->getall('mid');
	}

	public function searchWithType($type, $method = '=') {
		if ($method == '=') {
			$this->query->where('type', $type);
		} else {
			$this->query->where('type <>', $type);
		}
		return $this;
	}

	public function getNonRecycleModules() {
		load()->model('module');
		$modules = $this->where('issystem' , 0)->orderby('mid', 'DESC')->getall('name');
		if (empty($modules)) {
			return array();
		}
		foreach ($modules as &$module) {
			$module_info = module_fetch($module['name']);
			if (empty($module_info)) {
				unset($module);
			}
			if (!empty($module_info['recycle_info'])) {
				foreach (module_support_type() as $support => $value) {
					if ($module_info['recycle_info'][$support] > 0 && $module_info[$support] == $value['support']) {
						$module[$support] = $value['not_support'];
					}
				}
			}
		}
		return $modules;
	}

	public function getInstalled() {
		load()->model('module');
		$fields = array_keys(module_support_type());
		$fields = array_merge(array('name', 'version', 'cloud_record'), $fields);
		return $this->query->select($fields)->where(array('issystem' => '0'))->getall('name');
	}
}