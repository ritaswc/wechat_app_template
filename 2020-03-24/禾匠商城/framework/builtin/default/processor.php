<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class DefaultModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W, $engine;
		if ('trace' == $this->message['type']
			|| 'view_miniprogram' == $this->message['event']
			|| 'VIEW' == $this->message['event']
		) {
			return $this->respText('');
		}
		$setting = uni_setting($_W['uniacid'], array('default'));
		if (!empty($setting['default'])) {
			$flag = array('image' => 'url', 'link' => 'url', 'text' => 'content');
			$message = $this->message;
			$message['type'] = 'text';
			$message['content'] = $setting['default'];
			$message['redirection'] = true;
			$message['source'] = 'default';
			$message['original'] = $this->message[$flag[$this->message['type']]];
			$pars = $engine->analyzeText($message);
			if (is_array($pars)) {
				return array('params' => $pars);
			}
		}
	}
}
