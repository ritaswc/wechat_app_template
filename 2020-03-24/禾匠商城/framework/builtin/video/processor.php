<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class VideoModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$item = table('video_reply')->where(array('rid' => $rid))->get();
		if (empty($item)) {
			return false;
		}

		return $this->respVideo(array(
			'MediaId' => $item['mediaid'],
			'Title' => $item['title'],
			'Description' => $item['description'],
		));
	}
}
