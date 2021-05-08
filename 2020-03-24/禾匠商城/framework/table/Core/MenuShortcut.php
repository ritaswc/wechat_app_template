<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class MenuShortcut extends \We7Table {
	protected $tableName = 'core_menu_shortcut';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'uniacid',
		'modulename',
		'displayorder',
		'position',
		'updatetime',

	);
	protected $default = array(
		'uid' => '',
		'uniacid' => '',
		'modulename' => '',
		'displayorder' => '0',
		'position' => '',
		'updatetime' => '',

	);

	public function getUserWelcomeShortcutList($uid) {
		return $this->query
			->where('position', 'home_welcome_system_common')
			->where('uid', $uid)
			->orderby('displayorder', 'desc')
			->getall();
	}

	public function getUserWelcomeShortcut($uid, $uniacid, $modulename) {
		return $this->where(array('uid' => $uid, 'uniacid' => $uniacid, 'modulename' => $modulename, 'position' => 'home_welcome_system_common'))->get();
	}

	public function saveUserWelcomeShortcut($uid, $uniacid = 0, $modulename = '') {
		$user_welcome_short_info = $this->getUserWelcomeShortcut($uid, $uniacid, $modulename);
		if (!$user_welcome_short_info) {
			$save_data = array(
				'uid' => $uid,
				'uniacid' => $uniacid,
				'modulename' => $modulename,
				'position' => 'home_welcome_system_common',
			);
			$this->fill($save_data)->save();
		}
	}

	public function getCurrentModuleMenuPluginList($main_module) {
		global $_W;
		$position = 'module_' . $main_module . '_menu_plugin_shortcut';
		return $this->where(array('uid' => $_W['uid'], 'uniacid' => $_W['uniacid'], 'position' => $position))->getall('modulename');
	}

}