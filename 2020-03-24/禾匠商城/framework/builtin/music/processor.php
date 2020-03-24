<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class MusicModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$item = table('music_reply')->where(array('rid' => $rid))->orderby('RAND()')->get();
		if (empty($item['id'])) {
			return false;
		}
		return $this->respMusic(array(
			'Title' => $item['title'],
			'Description' => $item['description'],
			'MusicUrl' => $item['url'],
			'HQMusicUrl' => $item['hqurl'],
		));
	}
}
