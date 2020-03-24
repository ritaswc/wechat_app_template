<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class SitearticlecommentTable extends We7Table {
	protected $tableName = 'site_article_comment';
	protected $primaryKey = 'id';
	protected $field = array('id', 'uniacid', 'articleid', 'parentid', 'uid', 'openid', 'content', 'is_read', 'iscomment', 'createtime');

	public function articleCommentList() {
		global $_W;
		return $this->query->from($this->tableName)->where('uniacid', $_W['uniacid'])->getall();
	}


	public function articleCommentOrder($order = 'DESC') {
		$order = !empty($order) ? $order : 'DESC';
		return $this->query->orderby('id', $order);
	}

	public function articleCommentAdd($comment) {
		if (!empty($comment['parentid'])) {
			table('sitearticlecomment')->where('id', $comment['parentid'])->fill('iscomment', ARTICLE_COMMENT)->save();
		}
		$comment['createtime'] = TIMESTAMP;
		table('sitearticlecomment')->fill($comment)->save();
		return true;
	}

	public function srticleCommentUnread($articleIds) {
		global $_W;
		return $this->query->from($this->tableName)->select('articleid, count(*) as count')->where('uniacid', $_W['uniacid'])->where('articleid', $articleIds)->where('is_read', ARTICLE_COMMENT_NOREAD)->groupby('articleid')->getall('articleid');
	}
}