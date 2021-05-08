<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Cloud extends \We7Table {
	protected $tableName = 'modules_cloud';
	protected $primaryKey = 'id';
	protected $field = array(
		'name',
		'title',
		'title_initial',
		'logo',
		'version',
		'install_status',
		'account_support',
		'wxapp_support',
		'webapp_support',
		'phoneapp_support',
		'welcome_support',
		'xzapp_support',
		'aliapp_support',
		'baiduapp_support',
		'toutiaoapp_support',
		'main_module_name',
		'main_module_logo',
		'has_new_version',
		'has_new_branch',
		'is_ban',
		'lastupdatetime',
		'buytime',
		'module_status'
	);
	protected $default = array(
		'name' => '',
		'title' => '',
		'title_initial' => '',
		'logo' => '',
		'version' => '',
		'install_status' => 0,
		'account_support' => 1,
		'wxapp_support' => 1,
		'webapp_support' => 1,
		'phoneapp_support' => 1,
		'welcome_support' => 1,
		'xzapp_support' => 1,
		'aliapp_support' => 1,
		'baiduapp_support' => 1,
		'toutiaoapp_support' => 1,
		'main_module_name' => '',
		'main_module_logo' => '',
		'has_new_version' => 0,
		'has_new_branch' => 0,
		'is_ban' => 0,
		'lastupdatetime' => 0,
		'buytime' => 0,
		'module_status' => 0
	);

	public function getByName($name) {
		if (empty($name)) {
			return array();
		}
		return $this->query->where('name', $name)->get('name');
	}

	public function deleteByName($modulename) {
		return $this->query->where('name', $modulename)->delete();
	}

	public function getUpgradeByModuleNameList($module_name_list) {
		if (empty($module_name_list)) {
			return array();
		}
		return $this->query->where('name', $module_name_list)->where(function ($query){
			$query->where('has_new_version', 1)->whereor('has_new_branch', 1);
		})->orderby('lastupdatetime', 'desc')->getall('name');
	}

	
	public function searchWithoutRecycle($support = '') {
		if (empty($support)) {
			$recycle_module = table('modules_recycle')->getall('name');
		} else {
			$recycle_module = table('modules_recycle')->where($support, 1)->getall('name');
		}

		if (!empty($recycle_module)) {
			$this->where('name <>', array_keys($recycle_module));
		}
		return $this;
	}

	public function getUninstallModulesBySupportType($support) {
		return $this->searchWithoutRecycle($support . '_support')
			->where("{$support}_support", MODULE_SUPPORT_ACCOUNT)
			->where('install_status', array(MODULE_LOCAL_UNINSTALL, MODULE_CLOUD_UNINSTALL))
			->getall('name');
	}

	public function searchWithUninstall($local_or_cloud = 0) {
		if ($local_or_cloud == MODULE_LOCAL_UNINSTALL) {
			return $this->where('install_status', MODULE_LOCAL_UNINSTALL);
		} elseif ($local_or_cloud == MODULE_CLOUD_UNINSTALL) {
			return $this->where('install_status', MODULE_CLOUD_UNINSTALL);
		} else {
			return $this->where('install_status', array(MODULE_LOCAL_UNINSTALL, MODULE_CLOUD_UNINSTALL));
		}
	}

	public function searchUninstallSupport($support) {
		return $this->searchWithUninstall()->where($support, 2);
	}

	public function searchUninstallWithOutWelcome() {
		return $this->searchWithUninstall()
			->where(function ($query) {
				$query->where('account_support', 2)
					->whereor('wxapp_support', 2)
					->whereor('webapp_support', 2)
					->whereor('phoneapp_support', 2)
					->whereor('xzapp_support', 2)
					->whereor('aliapp_support', 2)
					->whereor('baiduapp_support', 2)
					->whereor('toutiaoapp_support', 2);
			});
	}

	public function getUninstallModule() {
		return $this->searchWithUninstall()
			->where(function ($query){
				$query->where('account_support', 2)
					->whereor('wxapp_support', 2)
					->whereor('webapp_support', 2)
					->whereor('phoneapp_support', 2)
					->whereor('welcome_support', 2)
					->whereor('xzapp_support', 2)
					->whereor('aliapp_support', 2)
					->whereor('baiduapp_support', 2)
					->whereor('toutiaoapp_support', 2);
			})
			->orderby('lastupdatetime', 'desc')
			->getall('name');
	}

	public function getUpgradeModulesBySupportType($support) {
		return $this->searchWithoutRecycle($support . '_support')
			->where("{$support}_support" , MODULE_SUPPORT_ACCOUNT)
			->where(function ($query){
				$query->where('has_new_version', 1)->whereor('has_new_branch', 1);
			})->getall('name');
	}
}