<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->func('file');
load()->model('article');
load()->model('account');

$dos = array('display', 'post', 'del');
$do = in_array($do, $dos) ? $do : 'display';

permission_check_account_user('platform_site_article');
$category = table('site_category')->getBySnake(array('id', 'parentid', 'name'), array('uniacid' => $_W['uniacid'], 'enabled' => 1), array('parentid' => 'ASC', 'displayorder' => 'ASC', 'id' => 'ASC'))->getall('id');

$parent = array();
$children = array();

if (!empty($category)) {
	foreach ($category as $cid => $cate) {
		if (!empty($cate['parentid'])) {
			$children[$cate['parentid']][] = $cate;
		} else {
			$parent[$cate['id']] = $cate;
		}
	}
}

if ($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$where = array('uniacid' => $_W['uniacid']);
	if (!empty($_GPC['keyword'])) {
		$keyword = safe_gpc_string($_GPC['keyword']);
		$where['title LIKE'] = "%$keyword%";
	}
	if (!empty($_GPC['childid'])) {
		$cid = safe_gpc_int($_GPC['childid']);
		$where['ccate'] = $cid;
	} elseif (!empty($_GPC['parentid'])) {
		$cid = safe_gpc_int($_GPC['parentid']);
		$where['pcate'] = $cid;
	}
	$list = table('site_article')
		->searchWithPage($pindex, $psize)
		->getBySnake('*', $where, array('displayorder' => 'DESC', 'edittime' => 'DESC', 'id' => 'DESC'))
		->getall();
	$total = table('site_article')
		->getBySnake('*', $where, array('displayorder' => 'DESC', 'edittime' => 'DESC', 'id' => 'DESC'))
		->count();
	$pager = pagination($total, $pindex, $psize);

	$article_ids = array();
	if (!empty($list)) {
		foreach ($list as $item) {
			$article_ids[] = $item['id'];
		}
	}
	$article_comment = table('site_article_comment')->srticleCommentUnread($article_ids);

	$setting = uni_setting($_W['uniacid']);
	template('site/article-display');
} elseif ($do == 'post') {
	$id = intval($_GPC['id']);
		$template = uni_templates();
	$pcate = intval($_GPC['pcate']);
	$ccate = intval($_GPC['ccate']);
	if (!empty($id)) {
		$item = table('site_article')->getById($id);
		$item['type'] = explode(',', $item['type']);
		$pcate = $item['pcate'];
		$ccate = $item['ccate'];
		if (empty($item)) {
			itoast('抱歉，文章不存在或是已经删除！', '', 'error');
		}
		$key = table('rule_keyword')
			->select('content')
			->where(array(
				'rid' => $item['rid'],
				'uniacid' => $_W['uniacid']
			))
			->getall();
		if (!empty($key)) {
			$keywords = array();
			foreach ($key as $row) {
				$keywords[] = $row['content'];
			}
			$keywords = implode(',', array_values($keywords));
		}
		$item['credit'] = iunserializer($item['credit']) ? iunserializer($item['credit']) : array();
		if (!empty($item['credit']['limit'])) {
						$credit_num = table('mc_handsel')->getBySnake('*', array('uniacid' => $_W['uniacid'], 'module' => 'article', 'sign' => md5(iserializer(array('id' => $id)))))->getcolumn('SUM(credit_value)');
			if (is_null($credit_num)) {
				$credit_num = 0;
			}
			$credit_yu = (($item['credit']['limit'] - $credit_num) < 0) ? 0 : $item['credit']['limit'] - $credit_num;
		}
	} else {
		$item['credit'] = array();
		$keywords = '';
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			itoast('标题不能为空，请输入标题！', '', '');
		}
		$sensitive_title = detect_sensitive_word($_GPC['title']);
		if (!empty($sensitive_title)) {
			itoast('不能使用敏感词:' . $sensitive_title, '', '');
		}

		$sensitive_content = detect_sensitive_word($_GPC['content']);
		if (!empty($sensitive_content)) {
			itoast('不能使用敏感词:' . $sensitive_content, '', '');
		}

		$data = array(
			'uniacid' => $_W['uniacid'],
			'iscommend' => intval($_GPC['option']['commend']),
			'ishot' => intval($_GPC['option']['hot']),
			'pcate' => intval($_GPC['category']['parentid']),
			'ccate' => intval($_GPC['category']['childid']),
			'template' => addslashes($_GPC['template']),
			'title' => addslashes($_GPC['title']),
			'description' => addslashes($_GPC['description']),
			'content' => safe_gpc_html(htmlspecialchars_decode($_GPC['content'], ENT_QUOTES)),
			'incontent' => intval($_GPC['incontent']),
			'source' => addslashes($_GPC['source']),
			'author' => addslashes($_GPC['author']),
			'displayorder' => intval($_GPC['displayorder']),
			'linkurl' => addslashes($_GPC['linkurl']),
			'createtime' => TIMESTAMP,
			'edittime' => TIMESTAMP,
			'click' => intval($_GPC['click'])
		);
		if (!empty($_GPC['thumb'])) {
			if (file_is_image($_GPC['thumb'])) {
				$data['thumb'] = $_GPC['thumb'];
			}
		} elseif (!empty($_GPC['autolitpic'])) {
			$match = array();
			preg_match('/&lt;img.*?src=&quot;(.+\.(jpg|jpeg|gif|bmp|png))&quot;/U', $_GPC['content'], $match);
			if (file_is_image($match[1])) {
				$data['thumb'] = $match[1];
							}
		} else {
			$data['thumb'] = '';
		}
		$keyword = str_replace('，', ',', trim($_GPC['keyword']));
		$keyword = explode(',', $keyword);
		if (!empty($keyword)) {
			$rule['uniacid'] = $_W['uniacid'];
			$rule['name'] = '文章：' . $_GPC['title'] . ' 触发规则';
			$rule['module'] = 'news';
			$rule['status'] = 1;
			$keywords = array();
			foreach ($keyword as $key) {
				$key = trim($key);
				if (empty($key)) continue;
				$keywords[] = array(
					'uniacid' => $_W['uniacid'],
					'module' => 'news',
					'content' => $key,
					'status' => 1,
					'type' => 1,
					'displayorder' => 1,
				);
			}
			$reply['title'] = $_GPC['title'];
			$reply['description'] = $_GPC['description'];
			$reply['thumb'] = $data['thumb'];
			$reply['url'] = murl('site/site/detail', array('id' => $id));
		}
				if (!empty($_GPC['credit']['status'])) {
			$credit['status'] = intval($_GPC['credit']['status']);
			$credit['limit'] = intval($_GPC['credit']['limit']) ? intval($_GPC['credit']['limit']) : itoast('请设置积分上限', '', '');
			$credit['share'] = intval($_GPC['credit']['share']) ? intval($_GPC['credit']['share']) : itoast('请设置分享时赠送积分多少', '', '');
			$credit['click'] = intval($_GPC['credit']['click']) ? intval($_GPC['credit']['click']) : itoast('请设置阅读时赠送积分多少', '', '');
			$data['credit'] = iserializer($credit);
		} else {
			$data['credit'] = iserializer(array('status' => 0, 'limit' => 0, 'share' => 0, 'click' => 0));
		}
		if (empty($id)) {
			unset($data['edittime']);
			if (!empty($keywords)) {
				table('rule')
					->fill($rule)
					->save();
				$rid = pdo_insertid();
				foreach ($keywords as $li) {
					$li['rid'] = $rid;
					table('rule_keyword')
						->fill($li)
						->save();
				}
				$reply['rid'] = $rid;
				table('news_reply')
					->fill($reply)
					->save();
				$data['rid'] = $rid;
			}
			table('site_article')
				->fill($data)
				->save();
			$aid = pdo_insertid();
			table('news_reply')
				->where(array('rid' => $rid))
				->fill(array('url' => murl('site/site/detail', array('id' => $aid))))
				->save();
		} else {
			unset($data['createtime']);
			uni_delete_rule($item['rid'], 'news_reply');
			if (!empty($keywords)) {
				$rid = table('rule')
					->fill($rule)
					->save();
				$rid = pdo_insertid();
				foreach ($keywords as $li) {
					$li['rid'] = $rid;
					table('rule_keyword')
						->fill($li)
						->save();
				}
				$reply['rid'] = $rid;
				table('news_reply')
					->fill($reply)
					->save();
				$data['rid'] = $rid;
			} else {
				$data['rid'] = 0;
				$data['kid'] = 0;
			}
			table('site_article')
				->where(array(
					'id' => $id,
					'uniacid' => $_W['uniacid']
				))
				->fill($data)
				->save();
		}
		itoast('文章更新成功！', url('site/article/display'), 'success');
	} else {
		template('site/article-post');
	}
} elseif($do == 'del') {
	if (checksubmit('submit')) {
		foreach ($_GPC['rid'] as $key => $id) {
			$id = intval($id);
			$row = table('site_article')->getById();
			if (empty($row)) {
				itoast('抱歉，文章不存在或是已经被删除！', '', '');
			}

			if (!empty($row['rid'])) {
				uni_delete_rule($row['rid'], 'news_reply');
			}
			table('site_article')
				->where(array(
					'id' => $id,
					'uniacid'=>$_W['uniacid']
				))
				->delete();
		}
		itoast('批量删除成功！', referer(), 'success');
	} else {
		$id = intval($_GPC['id']);
		$row = table('site_article')
			->where(array(
				'id' => $id,
				'uniacid' => $_W['uniacid']
			))
			->get();
		if (empty($row)) {
			itoast('抱歉，文章不存在或是已经被删除！', '', '');
		}
		if (!empty($row['rid'])) {
			uni_delete_rule($row['rid'], 'news_reply');
		}
		if (table('site_article')->where(array('id' => $id, 'uniacid'=>$_W['uniacid']))->delete()) {
			itoast('删除成功！', referer(), 'success');
		} else {
			itoast('删除失败！', referer(), 'error');
		}
	}
}

