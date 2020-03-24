<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class CustomModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
				if($this->rule == -1) {
			return $this->respCustom();
		}
		$reply = table('custom_reply')->where(array('rid IN' => $this->rule))->orderby('RAND()')->get();
		$nhour = date('H', TIMESTAMP);
		$flag = 0;
		if($reply['start1'] == 0 && $reply['end'] == 24) {
			$flag = 1;
		} elseif($reply['start1'] != '-1' && ($nhour >= $reply['start1']) && ($nhour < $reply['end1'])) {
			$flag = 1;
		} elseif($reply['start2'] != '-1' &&  ($nhour >= $reply['start2']) && ($nhour < $reply['end2'])) {
			$flag = 1;
		}

		if($flag == 1) {
			return $this->respCustom();
		} else {
			$content = '多客服接入时间为：' . intval($reply['start1']) .'时~' . $reply['end1'] . '时';
			if($reply['start2'] != '-1') {
				$content .= ',' . $reply['start2'] . '时~' . $reply['end2'] . '时';
			}
			$reply['content'] = $content;
			return $this->respText($reply['content']);
		}
	}
}
