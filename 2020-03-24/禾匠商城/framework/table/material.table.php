<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class MaterialTable extends We7Table {
	public function materialNewsList($attch_id) {
		$this->query->from('wechat_news')
			->where('attach_id', $attch_id)
			->orderby('displayorder', 'ASC');
		return $this->query->getall();
	}
}