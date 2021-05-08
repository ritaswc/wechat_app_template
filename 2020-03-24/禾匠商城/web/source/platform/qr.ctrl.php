<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('module');
load()->model('account');
load()->func('communication');
load()->model('setting');

$dos = array('display', 'post', 'list', 'del', 'extend', 'SubDisplay', 'check_scene_str', 'down_qr', 'change_status');
$do = in_array($do, $dos) ? $do : 'list';

if ('check_scene_str' == $do) {
	$scene_str = trim($_GPC['scene_str']);
	$is_exist = pdo_fetchcolumn('SELECT id FROM ' . tablename('qrcode') . ' WHERE uniacid = :uniacid AND scene_str = :scene_str AND model = 2', array(':uniacid' => $_W['uniacid'], ':scene_str' => $scene_str));
	if (!empty($is_exist)) {
		iajax(1, 'repeat', '');
	}
	iajax(0, 'success', '');
}

if ('list' == $do) {
	permission_check_account_user('platform_qr_qr');
	$wheresql = " WHERE uniacid = :uniacid AND type = 'scene'";
	$param = array(':uniacid' => $_W['uniacid']);
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$wheresql .= " AND name LIKE '%{$keyword}%'";
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_fetchall('SELECT * FROM ' . tablename('qrcode') . $wheresql . ' ORDER BY `id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $param);
	if (!empty($list)) {
		foreach ($list as $index => &$qrcode) {
			$qrcode['showurl'] = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($qrcode['ticket']);
			$qrcode['endtime'] = $qrcode['createtime'] + $qrcode['expire'];
			if (TIMESTAMP > $qrcode['endtime']) {
				$qrcode['endtime'] = '<font color="red">已过期</font>';
			} else {
				$qrcode['endtime'] = date('Y-m-d H:i:s', $qrcode['endtime']);
			}
			if (2 == $qrcode['model']) {
				$qrcode['modellabel'] = '永久';
				$qrcode['expire'] = '永不';
				$qrcode['endtime'] = '<font color="green">永不</font>';
			} else {
				$qrcode['modellabel'] = '临时';
			}
		}
		unset($qrcode);
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('qrcode') . $wheresql, $param);
	$pager = pagination($total, $pindex, $psize);

		pdo_query('UPDATE ' . tablename('qrcode') . " SET status = '0' WHERE uniacid = '{$_W['uniacid']}' AND model = '1' AND createtime < '{$_W['timestamp']}' - expire");
	template('platform/qr-list');
}

if ('del' == $do) {
	if ($_GPC['scgq']) {
		$list = pdo_fetchall('SELECT id FROM ' . tablename('qrcode') . " WHERE uniacid = :uniacid AND status = '0' AND type='scene'", array(':uniacid' => $_W['uniacid']), 'id');
		if (!empty($list)) {
			pdo_delete('qrcode', array('id' => array_keys($list), 'uniacid' => $_W['uniacid']));
			pdo_delete('qrcode_stat', array('id' => array_keys($list), 'uniacid' => $_W['uniacid']));
		}
		itoast('执行成功<br />删除二维码：' . count($list), url('platform/qr/list'), 'success');
	} else {
		$id = intval($_GPC['id']);
		pdo_delete('qrcode', array('id' => $id, 'uniacid' => $_W['uniacid']));
		pdo_delete('qrcode_stat', array('qid' => $id, 'uniacid' => $_W['uniacid']));
		itoast('删除成功', url('platform/qr/list'), 'success');
	}
}

if ('post' == $do) {
	if (checksubmit('submit')) {
				$barcode = array(
			'expire_seconds' => '',
			'action_name' => '',
			'action_info' => array(
				'scene' => array(),
			),
		);
		$qrctype = intval($_GPC['qrc-model']);
		$uniacccount = WeAccount::createByUniacid();
		$id = intval($_GPC['id']);
		$keyword_id = intval(trim(htmlspecialchars_decode($_GPC['reply']['reply_keyword']), '"'));
		$keyword = pdo_get('rule_keyword', array('id' => $keyword_id), array('content'));
		if (!empty($id)) {
			$update = array(
				'keyword' => $keyword['content'],
				'name' => trim($_GPC['scene-name']),
			);
			pdo_update('qrcode', $update, array('uniacid' => $_W['uniacid'], 'id' => $id));
			itoast('恭喜，更新带参数二维码成功！', url('platform/qr/list'), 'success');
		}

		if (1 == $qrctype) {
			$qrcid = pdo_fetchcolumn('SELECT qrcid FROM ' . tablename('qrcode') . " WHERE uniacid = :uniacid AND model = '1' AND type = 'scene' ORDER BY qrcid DESC LIMIT 1", array(':uniacid' => $_W['uniacid']));
			$barcode['action_info']['scene']['scene_id'] = !empty($qrcid) ? ($qrcid + 1) : 100001;
			$barcode['expire_seconds'] = intval($_GPC['expire-seconds']);
			$barcode['action_name'] = 'QR_SCENE';
			$result = $uniacccount->barCodeCreateDisposable($barcode);
		} elseif (2 == $qrctype) {
			$scene_str = trim($_GPC['scene_str']) ? trim($_GPC['scene_str']) : itoast('场景值不能为空', '', '');
			$is_exist = pdo_fetchcolumn('SELECT id FROM ' . tablename('qrcode') . ' WHERE uniacid = :uniacid AND scene_str = :scene_str AND model = 2', array(':uniacid' => $_W['uniacid'], ':scene_str' => $scene_str));
			if (!empty($is_exist)) {
				itoast("场景值:{$scene_str}已经存在,请更换场景值", '', 'error');
			}
			$barcode['action_info']['scene']['scene_str'] = $scene_str;
			$barcode['action_name'] = 'QR_LIMIT_STR_SCENE';
			$result = $uniacccount->barCodeCreateFixed($barcode);
		} else {
			itoast('抱歉，此公众号暂不支持您请求的二维码类型！', '', '');
		}

		if (!is_error($result)) {
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'qrcid' => $barcode['action_info']['scene']['scene_id'],
				'scene_str' => $barcode['action_info']['scene']['scene_str'],
				'keyword' => $keyword['content'],
				'name' => $_GPC['scene-name'],
				'model' => $_GPC['qrc-model'],
				'ticket' => $result['ticket'],
				'url' => $result['url'],
				'expire' => $result['expire_seconds'],
				'createtime' => TIMESTAMP,
				'status' => '1',
				'type' => 'scene',
			);
			pdo_insert('qrcode', $insert);
			itoast('恭喜，生成带参数二维码成功！', url('platform/qr/list', array('name' => 'qrcode')), 'success');
		} else {
			itoast("公众平台返回接口错误. <br />错误代码为: {$result['errorcode']} <br />错误信息为: {$result['message']}", '', '');
		}
	}

	if (!empty($_GPC['id'])) {
		$id = intval($_GPC['id']);
		$row = pdo_fetch('SELECT * FROM ' . tablename('qrcode') . " WHERE uniacid = {$_W['uniacid']} AND id = '{$id}'");
		$rid = pdo_get('rule_keyword', array('uniacid' => $_W['uniacid'], 'content' => $row['keyword']), array('rid'));
		$rid = $rid['rid'];
				$setting_keyword = $row['keyword'];
	}
	template('platform/qr-post');
}

if ('extend' == $do) {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$qrcrow = pdo_fetch('SELECT * FROM ' . tablename('qrcode') . ' WHERE uniacid = :uniacid AND id = :id LIMIT 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));
		$update = array();
		if (1 == $qrcrow['model']) {
			$uniacccount = WeAccount::createByUniacid();
			$barcode['action_info']['scene']['scene_id'] = $qrcrow['qrcid'];
			$barcode['expire_seconds'] = 2592000;
			$barcode['action_name'] = 'QR_SCENE';
			$result = $uniacccount->barCodeCreateDisposable($barcode);
			if (is_error($result)) {
				itoast($result['message'], '', 'error');
			}
			$update['ticket'] = $result['ticket'];
			$update['url'] = $result['url'];
			$update['expire'] = $result['expire_seconds'];
			$update['createtime'] = TIMESTAMP;
			pdo_update('qrcode', $update, array('id' => $id, 'uniacid' => $_W['uniacid']));
		}
		itoast('恭喜，延长临时二维码时间成功！', referer(), 'success');
	}
}

if ('display' == $do || 'change_status' == $do) {
	$status_setting = setting_load('qr_status');
	$status = $status_setting['qr_status']['status'];
}

if ('display' == $do) {
	permission_check_account_user('platform_qr_statistics');
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$qrcode_table = table('qrcode_stat');
	$starttime = !empty($_GPC['time']['start']) ? strtotime($_GPC['time']['start']) :  '';
	$endtime = !empty($_GPC['time']['end']) ? strtotime($_GPC['time']['end']) + 86399 : '';
	if (!empty($starttime)) {
		$qrcode_table->searchWithTime($starttime, $endtime);
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$qrcode_table->searchWithKeyword($keyword);
	}
	$qrcode_table->searchWithPage($pindex, $psize);
	$qrcode_table->searchWithUniacid($_W['uniacid']);
	if (!empty($status)) {
		$qrcode_table->groupby('qid');
		$qrcode_table->groupby('openid');
		$qrcode_table->groupby('type');
	}
	$list = $qrcode_table->orderby('createtime', 'DESC')->getall();
	$total = $count = $qrcode_table->getLastQueryTotal();

	if (!empty($list)) {
		$openid = array();
		foreach ($list as $qrcode) {
			if (!in_array($qrcode['openid'], $openid)) {
				$openid[] = $qrcode['openid'];
			}
		}
		unset($qrcode);
		$mc_mapping_fans_table = table('mc_mapping_fans');
		$mc_mapping_fans_table->searchWithUniacid($_W['uniacid']);
		$mc_mapping_fans_table->searchWithOpenid($openid);
		$nickname = $mc_mapping_fans_table->getall('openid');
	}
	$pager = pagination($total, $pindex, $psize);
	template('platform/qr-display');
}

if ('down_qr' == $do) {
	$id = intval($_GPC['id']);
	$down = pdo_get('qrcode', array('id' => $id));
	$pic = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($down['ticket']);
	header('Cache-control:private');
	header('content-type:image/jpeg');
	header('content-disposition: attachment;filename="' . $down['name'] . '.jpg"');
	readfile($pic);
	exit();
}

if ('change_status' == $do) {
	$up_status['status'] = empty($status) ? 1 : 0;
	$update = setting_save($up_status, 'qr_status');
	if ($update) {
		iajax(0, '');
	}
	iajax(-1, '更新成功!', url('platform/qr/display'));
}