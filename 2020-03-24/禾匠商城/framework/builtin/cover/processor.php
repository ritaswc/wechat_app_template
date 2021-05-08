<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class CoverModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		$reply = table('cover_reply')->where(array('rid' => $this->rule))->get();
		if (!empty($reply)) {
			load()->model('module');
			$module = module_fetch($reply['module']);
			if (empty($module) && !in_array($reply['module'], array('site', 'mc', 'card', 'page', 'clerk'))) {
				return '';
			}
			$url = $reply['url'];
			if (empty($reply['url'])) {
				$entry = table('modules_bindings')
					->select('eid')
					->where(array(
						'module' => $reply['module'],
						'do' => $reply['do']
					))
					->get();
				$url = url('entry', array('eid' => $entry['eid']));
			}
			$news = array();
			$news[] = array(
				'title' => $reply['title'],
				'description' => $reply['description'],
				'picurl' => $reply['thumb'],
				'url' => $url,
			);

			return $this->respNews($news);
		}

		return '';
	}
}
