<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('article');

$dos = array('list');
$do = in_array($do, $dos) ? $do : 'list';

if ('list' == $do) {
	$page = max(1, intval($_GPC['page']));
	$page_size = 20;
	$news = article_news_all($filter, $page, $page_size);
	$notices = article_notice_all($filter, $page, $page_size);
	$list = array();
	if (!empty($news['news'])) {
		foreach ($news['news'] as $new) {
			$new['type'] = 'news';
			$new['link'] = url('article/news-show/detail', array('id' => $new['id']));
			$new['createtime'] = date('Y-m-d H:i:s', $new['createtime']);
			$list[] = $new;
		}
	}
	if (!empty($notices['notice'])) {
		foreach ($notices['notice'] as $notice) {
			$notice['type'] = 'notice';
			$notice['link'] = url('article/notice-show/detail', array('id' => $notice['id']));
			$notice['createtime'] = date('Y-m-d H:i:s', $notice['createtime']);
			$list[] = $notice;
		}
	}
	
	$total = intval($notices['total']) + intval($news['total']);
	$pager = pagination($total, $page, $page_size);
	
}
template('article/notice-news');
