<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class NewsModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$commends = table('news_reply')
			->where(array(
				'rid' => $rid,
				'parent_id' => -1
			))
			->orderby(array(
				'displayorder' => 'DESC',
				'id' => 'ASC'
			))
			->limit(8)
			->getall();
		if (empty($commends)) {
						$main = table('news_reply')
				->where(array(
					'rid' => $rid,
					'parent_id' => 0
				))
				->orderby('RAND()')
				->get();
			if (empty($main['id'])) {
				return false;
			}
			$commends = table('news_reply')
				->where(array('id' => $main['id']))
				->whereor(array('parent_id' => $main['id']))
				->orderby(array(
					'parent_id' => 'ASC',
					'displayorder' => 'DESC',
					'id' => 'ASC'
				))
				->getall();
		}
		if (empty($commends)) {
			return false;
		}
		$news = array();
		foreach ($commends as $c) {
			$row = array();
			$row['title'] = $c['title'];
			$row['description'] = $c['description'];
			!empty($c['thumb']) && $row['picurl'] = tomedia($c['thumb']);
			$row['url'] = empty($c['url']) ? $this->createMobileUrl('detail', array('id' => $c['id'])) : $c['url'];
			$news[] = $row;
		}

		return $this->respNews($news);
	}
}
