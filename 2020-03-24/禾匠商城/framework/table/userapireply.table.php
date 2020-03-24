<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class UserapireplyTable extends We7Table {
	protected $tableName = 'userapi_reply';
	protected $field = array('rid', 'description', 'apiurl', 'token', 'default_text', 'cachetime');

	public function userapiSave($all_service, $file) {
		$rule_info = array('uniacid' => 0, 'name' => $all_service[$file]['title'], 'module' => 'userapi', 'displayorder' => 255, 'status' => 1);
		table('rule')->fill($rule_info)->save();

		$rule_id = pdo_insertid();
		$rule_keyword_info = array('rid' => $rule_id, 'uniacid' => 0, 'module' => 'userapi', 'displayorder' => $rule_info['displayorder'], 'status' => $rule_info['status']);
		if (!empty($all_service[$file]['keywords'])) {
			foreach ($all_service[$file]['keywords'] as $keyword_info) {
				$rule_keyword_info['content'] = $keyword_info[1];
				$rule_keyword_info['type'] = $keyword_info[0];
				table('rulekeyword')->fill($rule_keyword_info)->save();
			}
		}

		$userapi_reply = array('rid' => $rule_id, 'description' => htmlspecialchars($all_service[$file]['description']), 'apiurl' => $file);
		table('userapireply')->fill($userapi_reply)->save();
		return $rule_id;
	}
}