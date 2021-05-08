<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->func('file');
load()->model('material');
load()->model('account');
$dos = array('news', 'tomedia', 'addnews', 'upload_material', 'upload_news');
$do = in_array($do, $dos) ? $do : 'news';

permission_check_account_user('platform_material');

if ('tomedia' == $do) {
	iajax('0', tomedia($_GPC['url']), '');
}

if ('news' == $do) {
	$type = trim($_GPC['type']);
	$newsid = intval($_GPC['newsid']);
	$upload_limit = material_upload_limit();
	if (empty($newsid)) {
		if ('reply' == $type) {
			$reply_news_id = intval($_GPC['reply_news_id']);
			$news = pdo_get('news_reply', array(
				'id' => $reply_news_id,
			));
			$news_list = pdo_getall('news_reply', array(
				'parent_id' => $news['id'],
			), array(), '', ' displayorder ASC');
			$news_list = array_merge(array(
				$news,
			), $news_list);
			if (!empty($news_list)) {
				foreach ($news_list as $key => &$row_news) {
					$row_news = array(
						'uniacid' => $_W['uniacid'],
						'thumb_url' => $row_news['thumb'],
						'title' => $row_news['title'],
						'author' => $row_news['author'],
						'digest' => $row_news['description'],
						'content' => $row_news['content'],
						'url' => $row_news['url'],
						'displayorder' => $key,
						'show_cover_pic' => intval($row_news['incontent']),
						'content_source_url' => $row_news['content_source_url'],
					);
				}
				unset($row_news);
			}
		}
	} else {
		$attachment = material_get($newsid);
		if (is_error($attachment)) {
			itoast('图文素材不存在，或已删除', url('platform/material'), 'warning');
		}
		$news_list = $attachment['news'];
	}
	if (!empty($_GPC['new_type'])) {
		$new_type = trim($_GPC['new_type']);
		if (!in_array($new_type, array('reply', 'link'))) {
			$new_type = 'reply';
		}
	}
	if (!empty($news_list)) {
		foreach ($news_list as $key => $row_news) {
			if (empty($row_news['author']) && empty($row_news['content'])) {
				$new_type = 'link';
			} else {
				$new_type = 'reply';
			}
		}
	}
	template('platform/material-post');
}

if ('addnews' == $do) {
	$is_sendto_wechat = 'wechat' == trim($_GPC['target']) ? true : false;
	$attach_id = intval($_GPC['attach_id']);
	if (empty($_GPC['news'])) {
		iajax(-1, '提交内容参数有误');
	}
	$attach_id = material_news_set($_GPC['news'], $attach_id);
	if (is_error($attach_id)) {
		iajax(-1, $attach_id['message']);
	}
	if (!empty($_GPC['news_rid'])) {
		pdo_update('news_reply', array('media_id' => $attach_id), array('id' => intval($_GPC['news_rid'])));
	}
	if ($is_sendto_wechat) {
		$result = material_local_news_upload($attach_id);
	}
	if (is_error($result)) {
		iajax(-1, $result['message']);
	} else {
		iajax(0, '编辑图文素材成功');
	}
}

if ('upload_material' == $do) {
	$material_id = intval($_GPC['material_id']);
	$result = material_local_upload($material_id);
	if (is_error($result)) {
		iajax(1, $result['message']);
	}
	iajax(0, json_encode($result));
}

if ('upload_news' == $do) {
	$material_id = intval($_GPC['material_id']);
	$result = material_local_news_upload($material_id);
	if (is_error($result)) {
		iajax(-1, $result['message']);
	} else {
		iajax(0, '转换成功');
	}
}