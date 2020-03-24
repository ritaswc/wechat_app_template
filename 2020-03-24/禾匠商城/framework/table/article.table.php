<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class ArticleTable extends We7Table {

	protected $article_noticle_table ='article_notice';
	protected $article_news_table = 'article_news';
	protected $article_category_table = 'article_category';
	protected $article_unread_notice_table = 'article_unread_notice';

	public function articleList($params, $type = 'more') {
		global $_W;
		$this->query->from($this->article_noticle_table);
		if (!empty($params['uniacid'])) {
			$this->query->where('uniacid', $params['uniacid']);
		}
		if (!empty($params['is_display'])) {
			$this->query->where('is_display', $params['is_display']);
		}
		if (!empty($params['order'])) {
			$this->query->orderby($params['order']);
		}
		if (!empty($params['limit'])) {
			$this->query->limit($params['limit'][0], $params['limit'][1]);
		}
		if (!empty($params['fields'])) {
			$this->query->select($params['fields']);
		}

		if ($type == 'one') {
			return $this->query->get();
		} else {
			return $this->query->getall();
		}
	}
}