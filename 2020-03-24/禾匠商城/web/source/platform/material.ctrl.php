<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('material');
load()->model('mc');
load()->model('account');
load()->model('attachment');
load()->func('file');

$dos = array('display', 'sync', 'delete', 'send', 'detail');
$do = in_array($do, $dos) ? $do : 'display';

if ('send' == $do) {
	$group = intval($_GPC['group']);
	$type = trim($_GPC['type']);
	$id = intval($_GPC['id']);
	$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (empty($media)) {
		iajax(1, '素材不存在', '');
	}
	$group = $group > 0 ? $group : -1;
	$account_api = WeAccount::createByUniacid();
	$result = $account_api->fansSendAll($group, $type, $media['media_id']);
	if (is_error($result)) {
		iajax(1, $result['message'], '');
	}
	$groups = pdo_get('mc_fans_groups', array('uniacid' => $_W['uniacid']));
	if (!empty($groups)) {
		$groups = iunserializer($groups['groups']);
	}
	if ($group == -1) {
		$groups = array(
				$group => array(
						'name' => '全部粉丝',
						'count' => 0,
				),
		);
	}
	$record = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'groupname' => $groups[$group]['name'],
		'fansnum' => $groups[$group]['count'],
		'msgtype' => $type,
		'group' => $group,
		'attach_id' => $id,
		'media_id' => $media['media_id'],
		'status' => 0,
		'type' => 0,
		'sendtime' => TIMESTAMP,
		'createtime' => TIMESTAMP,
	);
	pdo_insert('mc_mass_record', $record);
	iajax(0, '发送成功！', '');
}

if ('display' == $do) {
	$type = trim($_GPC['type']);
	$type = in_array($type, array('news', 'image', 'voice', 'video')) ? $type : 'news';
	permission_check_account_user('platform_material_' . $type);
	$server = in_array(trim($_GPC['server']), array(MATERIAL_LOCAL, MATERIAL_WEXIN)) ? trim($_GPC['server']) : '';
	$group = mc_fans_groups(true);
	$page_index = max(1, intval($_GPC['page']));
	$page_size = 24;
	$search = addslashes($_GPC['title']);

	if ('news' == $type) {
		$material_news_list = material_news_list($server, $search, array('page_index' => $page_index, 'page_size' => $page_size));
	} else {
		if (empty($server)) {
			$server = MATERIAL_WEXIN;
		}
		$material_news_list = material_list($type, $server, array('page_index' => $page_index, 'page_size' => $page_size));
	}
	$material_list = $material_news_list['material_list'];
	$pager = $material_news_list['page'];
	template('platform/material');
}

if ('detail' == $do) {
	$type = trim($_GPC['type']);
	$newsid = intval($_GPC['newsid']);
	$attachment = material_get($newsid);
	if (is_error($attachment)) {
		itoast('图文素材不存在，或已删除', url('platform/material'), 'warning');
	}
	$news = $attachment['news'][0];
	template('platform/material-detail');
}

if ('delete' == $do) {

	permission_check_account_user('platform_material_delete');

	if (empty($_W['isfounder']) && ACCOUNT_MANAGE_NAME_MANAGER != $_W['role'] && ACCOUNT_MANAGE_NAME_OWNER != $_W['role']) {
		iajax(1, '您没有权限删除文件');
	}

	if (isset($_GPC['uniacid'])) { 		$requniacid = intval($_GPC['uniacid']);
		attachment_reset_uniacid($requniacid);
	}

	$material_id = intval($_GPC['material_id']);
	$server = 'local' == $_GPC['server'] ? 'local' : 'wechat';
	$type = trim($_GPC['type']);
	$cron_record = pdo_get('mc_mass_record', array('uniacid' => $_W['uniacid'], 'attach_id' => $material_id, 'sendtime >=' => TIMESTAMP), array('id'));
	if (!empty($cron_record)) {
		iajax('-1', '有群发消息未发送，不可删除');
	}
	if ('news' == $type) {
		$result = material_news_delete($material_id);
	} else {
				$result = material_delete($material_id, $server);
	}
	if (is_error($result)) {
		iajax('-1', $result['message']);
	}
	iajax('0', '删除素材成功');
}

if ('sync' == $do) {
	$account_api = WeAccount::createByUniacid();
	$pageindex = max(1, $_GPC['pageindex']);
	$type = empty($_GPC['type']) ? 'news' : $_GPC['type'];
	$news_list = $account_api->batchGetMaterial($type, ($pageindex - 1) * 20);
	$wechat_existid = empty($_GPC['wechat_existid']) ? array() : $_GPC['wechat_existid'];
	if (1 == $pageindex) {
		$original_newsid = pdo_getall('wechat_attachment', array('uniacid' => $_W['uniacid'], 'type' => $type, 'model' => 'perm'), array('id'), 'id');
		$original_newsid = array_keys($original_newsid);
		$wechat_existid = material_sync($news_list['item'], array(), $type);
		if ($news_list['total_count'] > 20) {
			$total = ceil($news_list['total_count'] / 20);
			iajax('1', array('type' => $type, 'total' => $total, 'pageindex' => $pageindex + 1, 'wechat_existid' => $wechat_existid, 'original_newsid' => $original_newsid), '');
		}
	} else {
		$wechat_existid = material_sync($news_list['item'], $wechat_existid, $type);
		$total = intval($_GPC['total']);
		$original_newsid = $_GPC['original_newsid'];
		if ($total != $pageindex) {
			iajax('1', array('type' => $type, 'total' => $total, 'pageindex' => $pageindex + 1, 'wechat_existid' => $wechat_existid, 'original_newsid' => $original_newsid), '');
		}
		if (empty($original_newsid)) {
			$original_newsid = array();
		}
		$original_newsid = array_filter($original_newsid, function ($item) {
			return is_numeric($item);
		});
	}
	$delete_id = array_diff($original_newsid, $wechat_existid);
	if (!empty($delete_id) && is_array($delete_id)) {
		foreach ($delete_id as $id) {
			pdo_delete('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));
			pdo_delete('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $id));
		}
	}
	iajax(0, '更新成功！', '');
}
