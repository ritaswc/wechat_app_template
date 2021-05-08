<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \LeanCloud\User;

class AdminController extends BaseController {
	function __construct() {
		parent::__construct();
		$this->load->helper('acl');
		if (!acl()) {
			redirect('../manager/login');
		}
	}
}
