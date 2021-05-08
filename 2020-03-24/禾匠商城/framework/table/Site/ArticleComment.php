<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Site;

class ArticleComment extends \We7Table {
	protected $tableName = 'site_article_comment';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'articleid',
		'parentid',
		'uid',
		'openid',
		'content',
		'is_read',
		'iscomment',
		'createtime',
	);
	protected $default = array(
		'uniacid' => '',
		'articleid' => '',
		'parentid' => '',
		'uid' => '',
		'openid' => '',
		'content' => '',
		'is_read' => 1,
		'iscomment' => 1,
		'createtime' => '',
	);

	public function getAllByUniacid()	{
		global $_W;
		return $this->where('uniacid', $_W['uniacid'])->getall();
	}

	public function addComment($comment) {
		if (!empty($comment['parentid'])) {
			$this->where('id', $comment['parentid'])->fill('iscomment', ARTICLE_COMMENT)->save();
		}
		$comment['createtime'] = TIMESTAMP;
		return $this->fill($comment)->save();
	}

	public function srticleCommentUnread($article_ids) {
		global $_W;
		return $this->query->select('articleid, count(*) as count')
			->where('uniacid', $_W['uniacid'])
			->where('articleid', $article_ids)
			->where('is_read', ARTICLE_COMMENT_NOREAD)
			->groupby('articleid')
			->getall('articleid');
	}
}