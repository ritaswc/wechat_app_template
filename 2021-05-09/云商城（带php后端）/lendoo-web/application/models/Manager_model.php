<?php

use \LeanCloud\User;

class Manager_model extends CI_Model {
	public function verify($username, $password) {
		try {
			User::logIn($username, $password);
			return true;
		} catch (Exception $e) {
		}
		return false;
	}
}