<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('article');
load()->model('account');

$dos = array('display', 'post', 'change_status');
$do = in_array($do, $dos) ? $do : 'display';

if ('display' == $do) {
	$articleId = intval($_GPC['id']);

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$comment_table = table('site_article_comment');
	$comment_table->searchWithArticleid($articleId);
	$comment_table->searchWithParentid(ARTICLE_COMMENT_DEFAULT);
	$comment_table->searchWithPage($pindex, $psize);

	$order_sort = !empty($_GPC['order']) ? intval($_GPC['order']) : 2;
	$order = 1 == $order_sort ? 'ASC' : 'DESC';
	$comment_table->orderby('id', $order);

	$is_comment = intval($_GPC['iscommend']);
	if (!empty($is_comment)) {
		$comment_table->searchWithIscomment($is_comment);
	}

	$article_lists = $comment_table->getAllByUniacid();
	$total = $comment_table->getLastQueryTotal();
	$pager = pagination($total, $pindex, $psize);
	$article_lists = article_comment_detail($article_lists);

	if (!empty($article_lists)) {
		foreach ($article_lists as $list) {
			$parent_article_comment_ids[] = $list['id'];
		}
		table('site_article_comment')
			->where(array('id in' => $parent_article_comment_ids))
			->fill(array('is_read' => ARTICLE_COMMENT_READ))
			->save();
	}

	if ($_W['isajax']) {
		iajax(0, $article_lists);
	}
	template('site/commont-list');
}

if ('post' == $do) {
	$comment = array(
		'uniacid' => $_W['uniacid'],
		'articleid' => intval($_GPC['articleid']),
		'parentid' => intval($_GPC['parentid']),
		'uid' => $_W['uid'],
		'is_read' => ARTICLE_COMMENT_READ,
		'content' => safe_gpc_html(htmlspecialchars_decode($_GPC['content'])),
	);
	$comment_add = article_comment_add($comment);

	if (is_error($comment_add)) {
		iajax(-1, $comment_add['message']);
	}
	$comment['username'] = $_W['username'];
	iajax(0, $comment);
}

if ('change_status' == $do) {
	$setting = uni_setting($_W['uniacid']);
	if (!empty($setting['comment_status'])) {
		uni_setting_save('comment_status', COMMENT_STATUS_OFF);
		iajax(0, COMMENT_STATUS_OFF);
	} else {
		if (empty($setting['oauth']['account']) && !in_array($_W['account']['level'], array(ACCOUNT_SUBSCRIPTION_VERIFY, ACCOUNT_SERVICE_VERIFY))) {
			iajax(-1, '请升级认证号或者借权');
		}
		uni_setting_save('comment_status', COMMENT_STATUS_ON);
		iajax(0, COMMENT_STATUS_ON);
	}
}