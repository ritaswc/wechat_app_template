<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class ImagesModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$mediaid = table('images_reply')->where(array('rid' => $rid))->getcolumn('mediaid');
		if (empty($mediaid)) {
			return false;
		}
		return $this->respImage($mediaid);
	}
}
