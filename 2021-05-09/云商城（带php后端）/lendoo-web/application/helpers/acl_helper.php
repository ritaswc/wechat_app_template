<?php
use \LeanCloud\User;

function acl () {
	if (User::getCurrentUser() != null && User::getCurrentUser()->get('username') == 'lendoo') {
		return true;
	}
	return false;
}