<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('material');
$dos = array('message_list', 'message_info', 'message_reply', 'message_mark', 'message_del', 'message_reply_del', 'message_switch');
$do = in_array($do, $dos) ? $do : 'message_list';

if ('message_list' == $do) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = array('uniacid' => $_W['uniacid'], 'status' => 0);
	$lists = table('mc_mass_record')
		->where($condition)
		->orderby(array('id' => 'DESC'))
		->limit(($pindex - 1) * $psize, $psize)
		->getall();
	$pager = pagination($total, $pindex, $psize);

	$news_arr = array();
	if (is_array($lists) && !empty($lists)) {
		foreach ($lists as $key => &$record) {
			$material = material_get($record['attach_id']);
			if (is_array($material['news']) && !empty($material['news'])) {
				foreach ($material['news'] as $news_key => &$news) {
					$news['msg_id'] = $record['msg_id'];
					$news['msg_data_id'] = $record['msg_data_id'];
					$news['index'] = $news_key;
					$news_arr[] = $news;
				}
			} else {
				unset($lists[$key]);
			}
		}
	}

	template('mc/message_list');
}

if ('message_info' == $do) {
	$index = intval($_GPC['index']) > 0 ? intval($_GPC['index']) : 0;
	$msg_data_id = safe_gpc_string($_GPC['msg_data_id']);
	$type = intval($_GPC['type']) > 0 ? intval($_GPC['type']) : 0;

	$account_api = WeAccount::createByUniacid();
	$res = $account_api->getComment($msg_data_id, $index, $type);
	$comments = $res['comment'];
	$total = $res['total'];

	if (is_array($comments) && !empty($comments)) {
		foreach ($comments as $key => &$comment) {
			$comment['index'] = $index;
			$comment['msg_data_id'] = $msg_data_id;
			$comment['create_time'] = date('Y-m-d H:i:s', $comment['create_time']);
			$fans_info = table('mc_mapping_fans')
				->searchWithOpenid($comment['openid'])
				->get();
			if (is_base64($fans_info['tag'])) {
				$fans_info['tag'] = base64_decode($fans_info['tag']);
			}

			if (!empty($fans_info['tag']) && is_string($fans_info['tag'])) {
				if (is_base64($fans_info['tag'])) {
					$fans_info['tag'] = base64_decode($fans_info['tag']);
				}
								if (is_serialized($fans_info['tag'])) {
					$fans_info['tag'] = @iunserializer($fans_info['tag']);
				}
				if (!empty($fans_info['tag']['headimgurl'])) {
					$fans_info['avatar'] = tomedia($fans_info['tag']['headimgurl']);
				}
				if (empty($fans_info['nickname']) && !empty($fans_info['tag']['nickname'])) {
					$fans_info['nickname'] = strip_emoji($fans_info['tag']['nickname']);
				}
			}
			$comment['fans_info'] = $fans_info;
		}
	}
	template('mc/message_info');
}

if ('message_reply' == $do) {
	$msg_data_id = safe_gpc_string($_GPC['msg_data_id']);
	$index = intval($_GPC['index']);
	$user_comment_id = intval($_GPC['user_comment_id']);
	$content = safe_gpc_string($_GPC['replycontent']);

	$account_api = WeAccount::createByUniacid();
	$res = $account_api->commentReply($msg_data_id, $user_comment_id, $content, $index);
	if (is_error($res)) {
		iajax($res['errno'], $res['message']);
	} else {
		iajax(0, '回复成功!');
	}
}

if ('message_mark' == $do) {
	$msg_data_id = safe_gpc_string($_GPC['msg_data_id']);
	$index = intval($_GPC['index']);
	$user_comment_id = intval($_GPC['user_comment_id']);
	$comment_type = intval($_GPC['comment_type']);

	$account_api = WeAccount::createByUniacid();
	$res = $account_api->commentMark($msg_data_id, $user_comment_id, $comment_type, $index);
	if (is_error($res)) {
		iajax($res['errno'], $res['message']);
	} else {
		if (1 == $comment_type) {
			$message = '取消精选成功!';
		} else {
			$message = '精选成功!';
		}
		iajax(0, $message);
	}
}

if ('message_del' == $do) {
	$msg_data_id = safe_gpc_string($_GPC['msg_data_id']);
	$index = intval($_GPC['index']);
	$user_comment_id = intval($_GPC['user_comment_id']);

	$account_api = WeAccount::createByUniacid();
	$res = $account_api->commentDelete($msg_data_id, $user_comment_id, $index);
	if (is_error($res)) {
		iajax($res['errno'], $res['message']);
	} else {
		iajax(0, '删除成功!');
	}
}

if ('message_reply_del' == $do) {
	$msg_data_id = safe_gpc_string($_GPC['msg_data_id']);
	$index = intval($_GPC['index']);
	$user_comment_id = intval($_GPC['user_comment_id']);
	$account_api = WeAccount::createByUniacid();
	$res = $account_api->commentReplyDelete($msg_data_id, $user_comment_id, $index);
	if (is_error($res)) {
		iajax($res['errno'], $res['message']);
	} else {
		iajax(0, '删除成功!');
	}
}

if ('message_switch' == $do) {
	$msg_data_id = safe_gpc_string($_GPC['msg_data_id']);
	$index = intval($_GPC['index']);
	$need_open_comment = intval($_GPC['need_open_comment']);
	$attach_id = intval($_GPC['attach_id']);
	$title = safe_gpc_string($_GPC['title']);

	$account_api = WeAccount::createByUniacid();
	$res = $account_api->commentSwitch($msg_data_id, $need_open_comment, $index);
	if (is_error($res)) {
		iajax($res['errno'], $res['message']);
	} else {
		$update_message = "，修改数据失败! 文章attach_id : {$attach_id} , 文章标题： {$title}";
		if (1 == $need_open_comment) {
			$message = '关闭评论';
			$res = table('wechat_news')
				->where(array(
					'attach_id' => $attach_id,
					'title' => $title,
					'uniacid' => $_W['uniacid']
				))
				->fill(array('need_open_comment' => 0))
				->save();
			if (!$res) {
				iajax(-1, $message . $update_message);
			}
		} else {
			$message = '打开评论';
			$res = table('wechat_news')
				->where(array(
					'attach_id' => $attach_id,
					'title' => $title,
					'uniacid' => $_W['uniacid']
				))
				->fill(array('need_open_comment' => 1))
				->save();
			if (!$res) {
				iajax(-1, $message . $update_message);
			}
		}
		iajax(0, $message . '成功!');
	}
}
