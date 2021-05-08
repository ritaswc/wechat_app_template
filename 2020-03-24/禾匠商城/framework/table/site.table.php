<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class SiteTable extends We7Table {
	
	protected $site_nav_table ='site_nav';
	protected $site_multi_table = 'site_multi';
	protected $site_category_table = 'site_category';
	protected $site_article_table = 'site_article';
	protected $site_page_table = 'site_page';
	protected $site_slide_table = 'site_slide';
	
	public function siteNavList($params) {
		global $_W;
		$this->query->from($this->site_nav_table);
		if (!empty($params['uniacid'])) {
			$this->query->where('uniacid', $params['uniacid']);
		}
		if (!empty($params['position'])) {
			$this->query->where('position', $params['position']);
		}
		if (!empty($params['status'])) {
			$this->query->where('status', $params['status']);
		}
		if (!empty($params['multiid'])) {
			$this->query->where('multiid', $params['multiid']);
		}
		$this->query->orderby(array('displayorder' => 'DESC', 'id' => 'ASC'));
		return $this->query->getall();
	}
}