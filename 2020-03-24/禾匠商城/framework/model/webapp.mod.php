<?php

defined('IN_IA') or exit('Access Denied');


function webapp_can_create($uid) {
	if(user_is_founder($uid)) {
		return true;
	}
	$data = permission_user_account_num($uid);
	return isset($data['webapp_limit']) && $data['webapp_limit'] > 0;
}