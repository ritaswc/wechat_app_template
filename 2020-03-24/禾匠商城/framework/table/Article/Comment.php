<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Article;

class Comment extends \We7Table {
	protected $tableName = 'article_comment';
	protected $primaryKey = 'id';
	protected $field = array(
		'articleid',
		'parentid',
		'uid',
		'content',
		'is_like',
		'is_reply',
		'like_num',
		'createtime',
	);
	protected $default = array(
		'articleid' => '',
		'parentid' => 0,
		'uid' => '',
		'content' => '',
		'is_like' => 2,
		'is_reply' => 2,
		'like_num' => 0,
		'createtime' => '',
	);

	public function addComment($comment) {
		if (!empty($comment['parentid'])) {
			$result = $this->where('id', $comment['parentid'])->fill('is_reply', 1)->save();
			if ($result === false) {
				return false;
			}
		}
		$comment['createtime'] = TIMESTAMP;
		$comment['is_like'] = 2;
		return $this->fill($comment)->save();
	}

	public function getCommentsByArticleid($articleid) {
		$comments = $this->where('articleid', $articleid)->where('parentid', 0)->where('is_like', 2)->getall('id');
		if (!empty($comments)) {
			foreach ($comments as $k => &$comment) {
				$comment['createtime'] = date('Y-m-d H:i', $comment['createtime']);
			}
		}
		return $comments;
	}

	public function getLikeComment($uid, $articleid, $comment_id) {
		return $this->where(array('articleid' => $articleid, 'parentid' => $comment_id, 'is_like' => 1, 'uid' => $uid))->get();
	}

	public function likeComment($uid, $articleid, $comment_id) {
		$like_num = $this->where('id', $comment_id)->getcolumn('like_num');
		$result = $this->where('id', $comment_id)->fill('like_num', $like_num + 1)->save();
		if ($result === false) {
			return false;
		}
		$this->fill(array(
			'uid' => $uid,
			'articleid' => $articleid,
			'parentid' => $comment_id,
			'is_like' => 1,
			'is_reply' => 1,
			'like_num' => 0,
			'content' => '',
			'createtime' => TIMESTAMP,
		));
		return $this->save();
	}
}