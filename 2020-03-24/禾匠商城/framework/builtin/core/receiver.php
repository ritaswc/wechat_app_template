<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
class CoreModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
		if ('subscribe' == $this->message['event'] && !empty($this->message['ticket'])) {
			$sceneid = $this->message['scene'];
			$acid = $this->acid;
			$uniacid = $this->uniacid;
			$ticket = trim($this->message['ticket']);
			if (!empty($ticket)) {
				$qr = table('qrcode')
					->select(array('id', 'keyword', 'name', 'acid'))
					->where(array(
						'uniacid' => $uniacid,
						'ticket' => $ticket
					))
					->getall();
				if (!empty($qr)) {
					if (1 != count($qr)) {
						$qr = array();
					} else {
						$qr = $qr[0];
					}
				}
			}
			if (empty($qr)) {
				$sceneid = trim($this->message['scene']);
				$where = array(
					'uniacid' => $_W['uniacid']
				);
				if (is_numeric($sceneid)) {
					$where['qrcid'] = $sceneid;
				} else {
					$where['scene_str'] = $sceneid;
				}
				$qr = table('qrcode')
					->select(array('id', 'keyword', 'name', 'acid'))
					->where($where)
					->get();
			}
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $qr['acid'],
				'qid' => $qr['id'],
				'openid' => $this->message['from'],
				'type' => 1,
				'qrcid' => intval($sceneid),
				'scene_str' => $sceneid,
				'name' => $qr['name'],
				'createtime' => TIMESTAMP,
			);
			table('qrcode_stat')->fill($insert)->save();
		} elseif ('SCAN' == $this->message['event']) {
			$sceneid = trim($this->message['scene']);
			$where = array('uniacid' => $_W['uniacid']);
			if (is_numeric($sceneid)) {
				$where['qrcid'] = $sceneid;
			} else {
				$where['scene_str'] = $sceneid;
			}
			$row = table('qrcode')
				->select(array('id', 'keyword', 'name', 'acid'))
				->where($where)
				->get();
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $row['acid'],
				'qid' => $row['id'],
				'openid' => $this->message['from'],
				'type' => 2,
				'qrcid' => intval($sceneid),
				'scene_str' => $sceneid,
				'name' => $row['name'],
				'createtime' => TIMESTAMP,
			);
			table('qrcode_stat')->fill($insert)->save();

		} elseif ('user_get_card' == $this->message['event']) {
			$sceneid = $this->message['outerid'];
			$row = table('qrcode')->where(array('qrcid' => $sceneid))->get();
			if (!empty($row)) {
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $row['acid'],
					'qid' => $row['id'],
					'openid' => $this->message['from'],
					'type' => 2,
					'qrcid' => $sceneid,
					'scene_str' => $sceneid,
					'name' => $row['name'],
					'createtime' => TIMESTAMP,
				);
				table('qrcode_stat')->fill($insert)->save();
			}
		}
		if ('subscribe' == $this->message['event'] && !empty($_W['account']) && ($_W['account']['level'] == ACCOUNT_SERVICE_VERIFY || $_W['account']['level'] == ACCOUNT_SUBSCRIPTION_VERIFY)) {
			$account_obj = WeAccount::createByUniacid();
			$userinfo = $account_obj->fansQueryInfo($this->message['from']);
			if (!is_error($userinfo) && !empty($userinfo) && !empty($userinfo['subscribe'])) {
				$userinfo['nickname'] = stripcslashes($userinfo['nickname']);
				$userinfo['avatar'] = $userinfo['headimgurl'];
				$fans = array(
					'unionid' => $userinfo['unionid'],
					'nickname' => strip_emoji($userinfo['nickname']),
					'tag' => base64_encode(iserializer($userinfo)),
				);
				table('mc_mapping_fans')
					->where(array('openid' => $this->message['from']))
					->fill($fans)
					->save();
				$uid = !empty($_W['member']['uid']) ? $_W['member']['uid'] : $this->message['from'];
				if (!empty($uid)) {
					$member = array();
					if (!empty($userinfo['nickname'])) {
						$member['nickname'] = $fans['nickname'];
					}
					if (!empty($userinfo['headimgurl'])) {
						$member['avatar'] = $userinfo['headimgurl'];
					}
					load()->model('mc');
					mc_update($uid, $member);
				}
			}
		}
	}
}
