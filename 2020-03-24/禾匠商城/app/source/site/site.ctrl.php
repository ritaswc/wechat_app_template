<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$do = in_array($do, array('list', 'detail', 'handsel', 'comment')) ? $do : 'list';
load()->model('site');
load()->model('mc');
load()->model('article');
load()->model('account');

if ($do == 'list') {
	$cid = intval($_GPC['cid']);
	$category = table('site_category')->getById($cid, $_W['uniacid']);
	if (empty($category)) {
		message('分类不存在或是已经被删除！');
	}
	if (! empty($category['linkurl'])) {
		header('Location: ' . $category['linkurl']);
		exit();
	}
	$_share['desc'] = $category['description'];
	$_share['title'] = $category['name'];

	$title = $category['name'];
	$category['template'] = table('site_styles')
		->searchWithTemplates()
		->where(array('a.id' => $category['styleid']))
		->getcolumn('b.name');
	if (! empty($category['template'])) {
		$styles_vars = table('site_styles_vars')
			->where(array('styleid' => $category['styleid']))
			->get();
		if (! empty($styles_vars)) {
			foreach ($styles_vars as $row) {
				if (strexists($row['variable'], 'img')) {
					$row['content'] = tomedia($row['content']);
				}
				$_W['styles'][$row['variable']] = $row['content'];
			}
		}
	}

	if (empty($category['ishomepage'])) {
		$ishomepage = 0;
				if (! empty($category['template'])) {
			$_W['template'] = $category['template'];
		}
		template('site/list');
		exit();
	} else {
		if (! empty($category['template'])) {
			$_W['template'] = $category['template'];
		}
		$ishomepage = 1;
				$navs = table('site_category')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'parentid' => $cid,
				'enabled' => 1
			))
			->orderby(array(
				'displayorder' => 'DESC',
				'id' => 'DESC'
			))
			->getall();
		if (! empty($navs)) {
			foreach ($navs as &$row) {
				if (empty($row['linkurl']) || (! strexists($row['linkurl'], 'http://') && ! strexists($row['linkurl'], 'https://'))) {
					$row['url'] = url('site/site/list', array(
						'cid' => $row['id']
					));
				} else {
					$row['url'] = $row['linkurl'];
				}
				if (! empty($row['icontype']) && $row['icontype'] == 1) {
					$row['css'] = iunserializer($row['css']);
					$row['icon'] = '';
					$row['css']['icon']['style'] = "color:{$row['css']['icon']['color']};font-size:{$row['css']['icon']['font-size']}px;";
					$row['css']['name'] = "color:{$row['css']['name']['color']};";
				}
				if (! empty($row['icontype']) && $row['icontype'] == 2) {
					$row['css'] = '';
				}
			}
			template('home/home');
		}else {
			template('site/list');
		}
		exit();
	}
} elseif ($do == 'detail') {
	$id = safe_gpc_int($_GPC['id']);
	$detail = table('site_article')->getById($id, $_W['uniacid']);
	if (empty($detail)) {
		message('文章已不存在或已被删除！', referer(), 'info');
	}
	if (! empty($detail['linkurl'])) {
		if (strtolower(substr($detail['linkurl'], 0, 4)) != 'tel:' && ! strexists($detail['linkurl'], 'http://') && ! strexists($detail['linkurl'], 'https://')) {
			$detail['linkurl'] = $_W['siteroot'] . 'app/' . $detail['linkurl'];
		}
		header('Location: ' . $detail['linkurl']);
		exit();
	}
	$detail = istripslashes($detail);

	$detail['content'] = preg_replace("/<img(.*?)(http[s]?\:\/\/mmbiz.qpic.cn[^\?]*?)(\?[^\"]*?)?\"/i", '<img $1$2"', $detail['content']);

	if (! empty($detail['incontent'])) {
		$detail['content'] = '<p><img src="' . tomedia($detail['thumb']) . '" title="' . $detail['title'] . '" /></p>' . $detail['content'];
	}
	if (! empty($detail['thumb'])) {
		$detail['thumb'] = tomedia($detail['thumb']);
	} else {
		$detail['thumb'] = '';
	}
		$title = $_W['page']['title'] = '';
		if (! empty($detail['template'])) {
		$_W['template'] = $detail['template'];
	}
	
	if ($_W['os'] == 'android' && $_W['container'] == 'wechat' && $_W['account']['account']) {
		$subscribeurl = "weixin://profile/{$_W['account']['account']}";
	} else {
		$subscribeurl = table('account_wechats')->where(array('uniacid' => intval($_W['uniacid'])))->getcolumn('subscribeurl');
	}
		$detail['click'] = intval($detail['click']) + 1;
	table('site_article')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'id' => $id
		))
		->fill(array(
			'click' => $detail['click']
		))
		->save();
		$_share = array(
		'desc' => $detail['description'],
		'title' => $detail['title'],
		'imgUrl' => $detail['thumb']
	);

	$setting = uni_setting($_W['uniacid']);
	if (!empty($setting['comment_status'])) {
		mc_oauth_userinfo();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$comment_table = table('site_article_comment');
		$comment_table->searchWithArticleid($id);
		$comment_table->searchWithParentid(ARTICLE_COMMENT_DEFAULT);
		$comment_table->searchWithPage($pindex, $psize);

		$article_lists = $comment_table->getAllByUniacid();
		$total = $comment_table->getLastQueryTotal();
		$pager = pagination($total, $pindex, $psize);
		$article_lists = article_comment_detail($article_lists);
	}

	template('site/detail');
} elseif ($do == 'handsel') {
		if ($_W['ispost']) {
		$id = safe_gpc_int($_GPC['id']);
		$article = table('site_article')->getById($id, $_W['uniacid']);
		$credit = iunserializer($article['credit']) ? iunserializer($article['credit']) : array();
		if (! empty($article) && $credit['status'] == 1) {
			if ($_GPC['action'] == 'share') {
				$touid = $_W['member']['uid'];
				$formuid = - 1;
				$handsel = array(
					'module' => 'article',
					'sign' => md5(iserializer(array(
						'id' => $id
					))),
					'action' => 'share',
					'credit_value' => $credit['share'],
					'credit_log' => '分享文章,赠送积分'
				);
			} elseif ($_GPC['action'] == 'click') {
				$touid = intval($_GPC['u']);
				$formuid = !empty($_W['member']['uid']) ? $_W['member']['uid'] : CLIENT_IP;
				$handsel = array(
					'module' => 'article',
					'sign' => md5(iserializer(array(
						'id' => $id
					))),
					'action' => 'click',
					'credit_value' => $credit['click'],
					'credit_log' => '分享的文章在朋友圈被阅读,赠送积分'
				);
			}
			$total = table('mc_handsel')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'module' => 'article',
					'sign' => $handsel['sign']
				))
				->getcolumn('SUM(credit_value)');

			if (($total >= $credit['limit']) || (($total + $handsel['credit_value']) > $credit['limit'])) {
				exit(json_encode(error(- 1, '赠送积分已达到上限')));
			}

			$status = mc_handsel($touid, $formuid, $handsel, $_W['uniacid']);
			if (is_error($status)) {
				exit(json_encode($status));
			} else {
				if ($handsel['action'] == 'share') {
					$credit_num = $credit['share'];
				}else if ($handsel['action'] == 'click') {
					$credit_num = $credit['click'];
				}
				$openid = table('mc_mapping_fans')
					->where(array(
						'uniacid' => $_W['uniacid'],
						'uid' => $touid
					))
					->getcolumn('openid');
				mc_notice_credit1($openid, $touid, $credit_num, $handsel['credit_log']);
				exit('success');
			}
		} else {
			exit(json_encode(array(
				- 1,
				'文章没有设置赠送积分'
			)));
		}
	} else {
		exit(json_encode(array(
			- 1,
			'非法操作'
		)));
	}
}


if ($do == 'comment') {
	mc_oauth_userinfo();
	$article_id = safe_gpc_int($_GPC['article_id']);
	$parent_id = safe_gpc_int($_GPC['parent_id']);
	$article_info = table('site_article')->getById($article_id, $_W['uniacid']);
	if ($_W['ispost']) {
		$comment = array(
			'uniacid' => $_W['uniacid'],
			'articleid' => $article_id,
			'openid' => $_W['openid'],
			'content' => safe_gpc_html(htmlspecialchars_decode($_GPC['content']))
		);
		$comment_add = article_comment_add($comment);
		if (is_error($comment_add)) {
			message($comment_add['message'], referer(), 'error');
		}
		header('Location: ' . murl('site/site/detail', array('id' => intval($_GPC['article_id']))));
		exit();
	}
	template('site/comment');
}