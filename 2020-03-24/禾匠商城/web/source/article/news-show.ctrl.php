<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('detail', 'list');
$do = in_array($do, $dos) ? $do : 'list';
load()->model('article');

if ('detail' == $do) {
	$id = intval($_GPC['id']);
	$news = article_news_info($id);
	if (is_error($news)) {
		itoast('新闻不存在或已删除', referer(), 'error');
	}
}

if ('list' == $do) {
	$categroys = article_categorys('news');
	$categroys[0] = array('title' => '所有新闻');
	$cateid = intval($_GPC['cateid']);

	$filter = array('cateid' => $cateid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$newss = article_news_all($filter, $pindex, $psize);
	$total = intval($newss['total']);
	$data = $newss['news'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/news-show');
