<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('cron');
load()->model('cloud');
load()->model('module');
load()->model('mc');
load()->model('material');

$dos = array('list', 'post', 'cron', 'send', 'del', 'preview');
$do = in_array($do, $dos) ? $do : 'post';

if ('list' == $do) {
	$time = strtotime(date('Y-m-d'));
	$record = pdo_getall('mc_mass_record', array('uniacid' => $_W['uniacid'], 'sendtime >=' => $time), array(), 'sendtime', 'sendtime ASC', array(1, 7));

	$days = array();
	for ($i = 0; $i < 8; ++$i) {
		$day_info = array();
		$day_info['day'] = date('Y-m-d', strtotime("+{$i} days", $time));

		$starttime = strtotime("+{$i} days", $time);
		$endtime = $i + 1;
		$endtime = strtotime("+{$endtime} days", $time);
		$massdata = pdo_fetch('SELECT id, `groupname`, `msgtype`, `group`, `attach_id`, `media_id`, `sendtime` FROM ' . tablename('mc_mass_record') . ' WHERE uniacid = :uniacid AND sendtime BETWEEN :starttime AND :endtime AND status = 1', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));

		if (!empty($massdata)) {
			$massdata['media'] = pdo_get('wechat_attachment', array('id' => $massdata['attach_id']));
			$massdata['media']['attach'] = tomedia($massdata['media']['attachment']);
			$massdata['media']['createtime_cn'] = date('Y-m-d H:i', $massdata['media']['createtime']);
			switch ($massdata['msgtype']) {
				case 'news':
					$massdata['msgtype_zh'] = '图文';
					$massdata['media']['items'] = pdo_getall('wechat_news', array('attach_id' => $massdata['attach_id']));
					foreach ($massdata['media']['items'] as  &$news_val) {
						$news_val['thumb_url'] = tomedia($news_val['thumb_url']);
					}
					unset($news_val);
					break;
				case 'image':
					$massdata['msgtype_zh'] = '图片';
					break;
				case 'voice':
					$massdata['msgtype_zh'] = '语音';
					break;
				case 'video':
					$massdata['msgtype_zh'] = '视频';
					$massdata['media']['attach']['tag'] = iunserializer($massdata['media']['tag']);
					break;
			}
			$massdata['clock'] = date('H:m', $massdata['sendtime']);
		}
		$day_info['info'] = $massdata;
		$days[] = $day_info;
	}

	template('platform/mass-display');
}

if ('del' == $do) {
	$mass = pdo_get('mc_mass_record', array('uniacid' => $_W['uniacid'], 'id' => intval($_GPC['id'])));
	if (!empty($mass) && $mass['cron_id'] > 0) {
		$status = cron_delete(array($mass['cron_id']));
		if (is_error($status)) {
			itoast($status['message'], '', 'error');
		}
	}
	pdo_delete('mc_mass_record', array('uniacid' => $_W['uniacid'], 'id' => intval($_GPC['id'])));
	itoast('删除成功', '', 'info');
}

if ('post' == $do) {
	permission_check_account_user('platform_masstask_post');
	$id = intval($_GPC['id']);
	$mass_info = pdo_get('mc_mass_record', array('id' => $id));
	$groups = mc_fans_groups();
	$account_api = WeAccount::createByUniacid();
	$supports = $account_api->getMaterialSupport();
	$show_post_content = $supports['mass'];

	if (checksubmit('submit')) {
		$type = in_array(intval($_GPC['type']), array(0, 1)) ? intval($_GPC['type']) : 0;
		$group = json_decode(htmlspecialchars_decode($_GPC['group']), true);

		if (empty($_GPC['reply'])) {
			itoast('请选择要群发的素材', '', 'error');
		}
		$mass_record = array(
			'uniacid' => $_W['uniacid'],
			'acid' => $_W['acid'],
			'groupname' => $group['name'],
			'fansnum' => $group['count'],
			'msgtype' => '',
			'group' => $group['id'],
			'attach_id' => '',
			'media_id' => '',
			'status' => 1,
			'type' => $type,
			'sendtime' => TIMESTAMP,
			'createtime' => TIMESTAMP,
			'cron_id' => 0,
		);
		foreach ($_GPC['reply'] as $material_type => $material) {
			if (empty($material)) {
				continue;
			}
			list($temp, $msgtype) = explode('_', $material_type);
			$attachment = material_get($material);
			if ('local' == $attachment['model']) {
				itoast('图文素材请选择微信素材', '', 'error');
			}

			if ('reply_basic' == $material_type) {
				$material = htmlspecialchars_decode($material);
				$material = trim($material, '\"');
			}

			$mass_record['media_id'] = $material;
			$mass_record['attach_id'] = $attachment['id'];
			$mass_record['msgtype'] = $msgtype;
			break;
		}

				if (1 == $type) {
			$cloud = cloud_prepare();
			if (is_error($cloud)) {
				itoast($cloud['message'], '', 'error');
			}
			set_time_limit(0);

			$starttime = strtotime(date('Y-m-d', strtotime($_GPC['sendtime'])));
			$endtime = strtotime(date('Y-m-d', strtotime($_GPC['sendtime']))) + 86400;
			$cron_title = date('Y-m-d', strtotime($_GPC['sendtime'])) . '微信群发任务';

			$mass_record['sendtime'] = strtotime($_GPC['sendtime']);

			$records = pdo_fetchall('SELECT id, cron_id FROM ' . tablename('mc_mass_record') . ' WHERE uniacid = :uniacid AND sendtime BETWEEN :starttime AND :endtime AND status = 1 ORDER BY sendtime ASC LIMIT 8', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime), 'id');
			if (!empty($records)) {
				foreach ($records as $record) {
					if (!$record['cron_id']) {
						continue;
					}
					$corn_ids[] = $record['cron_id'];
				}
				if (!empty($corn_ids)) {
					$status = cron_delete($corn_ids);
					if (is_error($status)) {
						itoast('删除群发错误,请重新提交', referer());
					}
				}
				$ids = implode(',', array_keys($records));
				pdo_delete('mc_mass_record', array('uniacid' => $_W['uniacid'], 'id' => array($ids)));
			}

			pdo_insert('mc_mass_record', $mass_record);
			$mass_record_id = pdo_insertid();

			$cron_data = array(
				'uniacid' => $_W['uniacid'],
				'name' => $cron_title,
				'filename' => 'mass',
				'type' => 1,
				'lastruntime' => $mass_record['sendtime'],
				'extra' => $mass_record_id,
				'module' => 'task',
				'status' => 1,
			);
			$status = cron_add($cron_data);
			if (is_error($status)) {
				$message = $status['message'];
				if (empty($message)) {
					$message = "{$cron_title}同步到云服务失败,请手动同步<br>";
				}
				itoast($message, url('platform/mass/send'), 'info');
			}

			pdo_update('mc_mass_record', array('cron_id' => $status), array('id' => $mass_record_id, 'uniacid' => $_W['uniacid']));
			itoast('定时群发设置成功', url('platform/mass/send'), 'success');
		} else {
			$account_api = WeAccount::createByUniacid();
			$msgtype = 'basic' == $msgtype ? 'text' : $msgtype;
			if ('text' == $msgtype) {
				$mass_record['media_id'] = urlencode(emoji_unicode_decode($mass_record['media_id']));
			}
			$result = $account_api->fansSendAll($group['id'], $msgtype, $mass_record['media_id']);
			if (is_error($result)) {
				itoast($result['message'], url('platform/mass'), 'info');
			}
			if ('news' == $msgtype) {
				$mass_record['msg_id'] = $result['msg_id'];
				$mass_record['msg_data_id'] = $result['msg_data_id'];
			}
			$mass_record['status'] = 0;
			pdo_insert('mc_mass_record', $mass_record);
			itoast('立即发送设置成功', url('platform/mass/send'), 'success');
		}
	}
	template('platform/mass-post');
}

if ('cron' == $do) {
	$id = intval($_GPC['id']);
	$record = pdo_get('mc_mass_record', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (empty($record)) {
		itoast('群发任务不存在或已删除', referer(), 'error');
	}
	$cron = array(
		'uniacid' => $_W['uniacid'],
		'name' => date('Y-m-d', $record['sendtime']) . '微信群发任务',
		'filename' => 'mass',
		'type' => 1,
		'lastruntime' => $record['sendtime'],
		'extra' => $record['id'],
		'module' => 'task',
		'status' => 1,
	);
	$status = cron_add($cron);
	if (is_error($status)) {
		itoast($status['message'], referer(), 'error');
	}
	pdo_update('mc_mass_record', array('cron_id' => $status), array('uniacid' => $_W['uniacid'], 'id' => $id));
	itoast('同步到云服务成功', referer(), 'success');
}

if ('preview' == $do) {
	$wxname = trim($_GPC['wxname']);
	if (empty($wxname)) {
		iajax(1, '微信号不能为空', '');
	}
	$type = trim($_GPC['type']);
	$media_id = trim($_GPC['media_id']);
	$account_api = WeAccount::createByUniacid();
	$data = $account_api->fansSendPreview($wxname, $media_id, $type);
	if (is_error($data)) {
		iajax(-1, $data['message'], '');
	}
	iajax(0, 'success', '');
}

if ('send' == $do) {
	permission_check_account_user('platform_masstask_send');
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = ' WHERE `uniacid` = :uniacid AND `acid` = :acid';
	$params = array();
	$params[':uniacid'] = $_W['uniacid'];
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mass_record') . $condition, $params);
	$lists = pdo_getall('mc_mass_record', array('uniacid' => $_W['uniacid']), array(), '', 'id DESC', 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize);
	$types = array('basic' => '文本消息', 'text' => '文本消息', 'image' => '图片消息', 'voice' => '语音消息', 'video' => '视频消息', 'news' => '图文消息', 'wxcard' => '微信卡券');
	$pager = pagination($total, $pindex, $psize);
	template('platform/mass-send');
}