<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
set_time_limit(60);

load()->model('mc');

$dos = array('display', 'add_tag', 'del_tag', 'edit_tagname', 'edit_fans_tag', 'batch_edit_fans_tag', 'download_fans', 'sync', 'fans_sync_set', 'register', 'sync_member');
$do = in_array($do, $dos) ? $do : 'display';

if ('display' == $do) {
	permission_check_account_user('mc_fans_display');
	$sync_member = uni_setting_load('sync_member');
	$sync_member = empty($sync_member['sync_member']) ? 0 : 1;
	$fans_tag = mc_fans_groups(true);
	$pageindex = max(1, intval($_GPC['page']));
	$search_mod = '' == intval($_GPC['search_mod']) ? 1 : intval($_GPC['search_mod']);
	$pagesize = 10;

	$param = array(':uniacid' => $_W['uniacid'], ':user_from' => 0);
	$condition = ' WHERE f.`uniacid` = :uniacid AND f.`user_from` = :user_from';
	$tag = intval($_GPC['tag']) ? intval($_GPC['tag']) : 0;
	if (!empty($tag)) {
		$param[':tagid'] = $tag;
		$condition .= ' AND m.`tagid` = :tagid';
	}
	if ('bind' == $_GPC['type']) {
		$condition .= ' AND f.`uid` > 0';
		$type = 'bind';
	}
	$nickname = $_GPC['nickname'] ? addslashes(trim($_GPC['nickname'])) : '';
	if (!empty($nickname)) {
		if (1 == $search_mod) {
			$condition .= ' AND ((f.`nickname` = :nickname) OR (f.`openid` = :openid))';
			$param[':nickname'] = $nickname;
			$param[':openid'] = $nickname;
		} else {
			$condition .= ' AND ((f.`nickname` LIKE :nickname) OR (f.`openid` LIKE :openid))';
			$param[':nickname'] = '%' . $nickname . '%';
			$param[':openid'] = '%' . $nickname . '%';
		}
	}

	$follow = intval($_GPC['follow']) ? intval($_GPC['follow']) : 1;
	if (1 == $follow) {
		$condition .= ' AND f.`follow` = 1';
	} elseif (2 == $follow) {
		$condition .= ' AND f.`follow` = 0';
	}
	if (!empty($tag)) {
		$select_sql = 'SELECT %s FROM ' . tablename('mc_mapping_fans') . ' AS f LEFT JOIN ' . tablename('mc_fans_tag_mapping') . ' AS m ON m.`fanid` = f.`fanid` ' . $condition . ' %s';
	} else {
		$select_sql = 'SELECT %s FROM ' . tablename('mc_mapping_fans') . ' AS f ' . $condition . ' %s';
	}

	$fans_list_sql = sprintf($select_sql, 'f.fanid, f.acid, f.uniacid, f.uid, f.tag,f.openid, f.nickname as nickname, f.groupid, f.follow, f.followtime, f.unfollowtime', ' GROUP BY f.`fanid` ORDER BY f.`fanid` DESC LIMIT ' . ($pageindex - 1) * $pagesize . ',' . $pagesize);

	$fans_list = pdo_fetchall($fans_list_sql, $param);

	$openids = array();
	if (!empty($fans_list)) {
		foreach ($fans_list as &$v) {
			$v['tag_show'] = mc_show_tag($v['groupid']);
			$v['tag_show'] = explode(',', $v['tag_show']);
			$v['groupid'] = trim($v['groupid'], ',');
			if (!empty($v['uid'])) {
				$user = mc_fetch($v['uid'], array('realname', 'nickname', 'mobile', 'email', 'avatar'));
			}
			if (!empty($user)) {
				$v['member'] = $user;
			}
			$v['nickname'] = !empty($v['nickname']) ? strip_emoji($v['nickname']) : '';

			if (empty($v['headimgurl']) && !empty($v['tag'])) {
				if (is_base64($v['tag'])) {
					$v['tag'] = @base64_decode($v['tag']);
				}
				if (is_serialized($v['tag'])) {
					$v['tag'] = @iunserializer($v['tag']);
				}

				if (is_array($v['tag']) && !empty($v['tag']['headimgurl'])) {
					$v['tag']['avatar'] = tomedia($v['tag']['headimgurl']);
					unset($v['tag']['headimgurl']);
					if (empty($v['nickname']) && !empty($v['tag']['nickname'])) {
						$v['nickname'] = strip_emoji($v['tag']['nickname']);
					}
					$v['gender'] = $v['sex'] = $v['tag']['sex'];
					$v['avatar'] = $v['headimgurl'] = $v['tag']['avatar'];
				}
			}

			$openids[] = $v['openid'];
			unset($user);
		}
		unset($v);
	}
	$fans_tag_list = table('mc_fans_tag')
		->where(array('openid' => $openids))
		->getall('openid');
	if (!empty($fans_list) && !empty($fans_tag_list)) {
		foreach ($fans_list as &$fans_info) {
			if (!empty($fans_tag_list[$fans_info['openid']]['headimgurl'])) {

				$fans_info['headimgurl'] = $fans_tag_list[$fans_info['openid']]['headimgurl'];
			}
		}
	}

	$total_sql = sprintf($select_sql, 'COUNT(DISTINCT f.`fanid`) ', '');
	$total = pdo_fetchcolumn($total_sql, $param);
	$pager = pagination($total, $pageindex, $pagesize);
	$fans['total'] = table('mc_mapping_fans')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'follow' => 1
		))
		->getcolumn('count(*)');
}

if ('add_tag' == $do) {
	$tag_name = trim($_GPC['tag']);
	if (empty($tag_name)) {
		iajax(1, '请填写标称名称', '');
	}
	$account_api = WeAccount::createByUniacid();
	$result = $account_api->fansTagAdd($tag_name);
	if (is_error($result)) {
		iajax(1, $result);
	} else {
		iajax(0, '');
	}
}

if ('del_tag' == $do) {
	$tagid = intval($_GPC['tag']);
	if (empty($tagid)) {
		iajax(1, '标签id为空', '');
	}
	$account_api = WeAccount::createByUniacid();
	$tags = $account_api->fansTagDelete($tagid);

	if (is_error($tags)) {
		iajax(-1, $tags['message'], '');
	}
	$fans_list = table('mc_mapping_fans')
		->where(array('groupid LIKE' => "%,{$tagid},%"))
		->getall();
	$count = count($fans_list);
	if (!empty($count)) {
		$buffSize = ceil($count / 500);
		for ($i = 0; $i < $buffSize; ++$i) {
			$sql = '';
			$wechat_fans = array_slice($fans_list, $i * 500, 500);
			foreach ($wechat_fans as $fans) {
				$tagids = trim(str_replace(',' . $tagid . ',', ',', $fans['groupid']), ',');
				if (',' == $tagids) {
					$tagids = '';
				}
				$sql .= 'UPDATE ' . tablename('mc_mapping_fans') . " SET `groupid`='" . $tagids . "' WHERE `fanid`={$fans['fanid']};";
			}
			pdo_query($sql);				}
	}
	table('mc_fans_tag_mapping')
		->where(array('tagid' => $tagid))
		->delete();
	iajax(0, 'success', '');
}

if ('edit_tagname' == $do) {
	$tag = intval($_GPC['tag']);
	if (empty($tag)) {
		iajax(1, '标签id为空', '');
	}
	$tag_name = trim($_GPC['tag_name']);
	if (empty($tag_name)) {
		iajax(1, '标签名为空', '');
	}

	$account_api = WeAccount::createByUniacid();
	$result = $account_api->fansTagEdit($tag, $tag_name);
	if (is_error($result)) {
		iajax(1, $result);
	} else {
		iajax(0, '');
	}
}

if ('edit_fans_tag' == $do) {
	$fanid = intval($_GPC['fanid']);
	$tagids = $_GPC['tags'];
	if (is_array($tagids)) {
		foreach ($tagids as $key => $id) {
			$tagids[$key] = intval($id);
		}
	} else {
		$tagids = intval($tagids);
	}
	$openid = table('mc_mapping_fans')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'fanid' => $fanid
		))
		->getcolumn('openid');
	$account_api = WeAccount::createByUniacid();
	if (empty($tagids) || !is_array($tagids)) {
		$fans_tags = table('mc_fans_tag_mapping')->where(array('fanid' => $fanid))->getall('tagid');
		if (!empty($fans_tags)) {
			foreach ($fans_tags as $tag) {
				$result = $account_api->fansTagBatchUntagging(array($openid), $tag['tagid']);
			}
		} else {
			iajax(0);
		}
	} else {
		$result = $account_api->fansTagTagging($openid, $tagids);
	}

	if (!is_error($result)) {
		table('mc_fans_tag_mapping')->where(array('fanid' => $fanid))->delete();
		if (!empty($tagids)) {
			foreach ($tagids as $tag) {
				table('mc_fans_tag_mapping')
					->fill(array(
						'fanid' => $fanid,
						'tagid' => $tag
					))
					->save();
			}
			$tagids = implode(',', $tagids);
		}
		table('mc_mapping_fans')
			->where(array('fanid' => $fanid))
			->fill(array('groupid' => $tagids))
			->save();
	}
	iajax(0, $result);
}

if ('batch_edit_fans_tag' == $do) {
	$openid_list = $_GPC['openid'];
	if (empty($openid_list) || !is_array($openid_list)) {
		iajax(1, '请选择粉丝', '');
	}
	$tags = $_GPC['tag'];
	if (empty($tags) || !is_array($tags)) {
		iajax(1, '请选择标签', '');
	}

	$account_api = WeAccount::createByUniacid();
	foreach ($tags as $tag) {
		$result = $account_api->fansTagBatchTagging($openid_list, $tag);
		if (is_error($result)) {
			iajax(-1, $result);
		}
		foreach ($openid_list as $openid) {
			$fan_info = table('mc_mapping_fans')
				->searchWithUniacid($_W['uniacid'])
				->searchWithOpenid($openid)
				->get();
			table('mc_fans_tag_mapping')
				->fill(array(
					'fanid' => $fan_info['fanid'],
					'tagid' => $tag
				))
				->save(true);
			$groupid = $fan_info['group'] . ',' . $tag;
			table('mc_mapping_fans')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'openid' => $openid
				))
				->fill(array('groupid' => $groupid))
				->save();
		}
	}
	iajax(0, '');
}

if ('download_fans' == $do) {
	$account_api = WeAccount::createByUniacid();
	$wechat_fans_list = $account_api->fansAll();

		if (!empty($account_api->same_account_exist)) {
		table('mc_mapping_fans')
			->where(array('uniacid' => array_keys($account_api->same_account_exist)))
			->fill(array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid']
			))
			->save();
	}

	if (!is_error($wechat_fans_list)) {
		$wechat_fans_count = count($wechat_fans_list['fans']);
		$total_page = ceil($wechat_fans_count / 500);
		for ($i = 0; $i < $total_page; ++$i) {
			$wechat_fans = array_slice($wechat_fans_list['fans'], $i * 500, 500);
			$system_fans = table('mc_mapping_fans')
				->where(array('openid' => $wechat_fans))
				->getall('openid');
			$add_fans_sql = '';
			foreach ($wechat_fans as $openid) {
				if (empty($system_fans) || empty($system_fans[$openid])) {
					$salt = random(8);
					$add_fans_sql .= "('{$_W['acid']}', '{$_W['uniacid']}', 0, '{$openid}', '{$salt}', 1, 0, ''),";
				}
			}
			if (!empty($add_fans_sql)) {
				$add_fans_sql = rtrim($add_fans_sql, ',');
				$add_fans_sql = 'INSERT INTO ' . tablename('mc_mapping_fans') . ' (`acid`, `uniacid`, `uid`, `openid`, `salt`, `follow`, `followtime`, `tag`) VALUES ' . $add_fans_sql;
				$result = pdo_query($add_fans_sql);
			}
			table('mc_mapping_fans')
				->where(array('openid' => $wechat_fans))
				->fill(array(
					'follow' => 1,
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid']
				))
				->save();
		}
		$return['total'] = $wechat_fans_list['total'];
		$return['count'] = !empty($wechat_fans_list['fans']) ? $wechat_fans_count : 0;
		$return['next'] = $wechat_fans_list['next'];
		iajax(0, $return, '');
	} else {
		iajax(1, $wechat_fans_list['message']);
	}
}

if ('sync' == $do) {
	$type = 'all' == $_GPC['type'] ? 'all' : 'check';
	$sync_member = uni_setting_load('sync_member');
	$force_init_member = empty($sync_member['sync_member']) ? false : true;

	if ('all' == $type) {
		$pageindex = $_GPC['pageindex'];
		++$pageindex;
		$sync_fans = pdo_getslice('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'follow' => '1'), array($pageindex, 100), $total, array(), 'openid', 'fanid DESC');
		$total = ceil($total / 100);
		$start = time();
		if (!empty($sync_fans)) {
			mc_init_fans_info(array_keys($sync_fans), $force_init_member);
		}
		if ($total == $pageindex) {
			setcookie(cache_system_key('sync_fans_pindex', array('uniacid' => $_W['uniacid'])), '', -1);
		} else {
			setcookie(cache_system_key('sync_fans_pindex', array('uniacid' => $_W['uniacid'])), $pageindex);
		}
		iajax(0, array('pageindex' => $pageindex, 'total' => $total), '');
	}
	if ('check' == $type) {
		$openids = $_GPC['openids'];
		if (empty($openids) || !is_array($openids)) {
			iajax(1, '请选择粉丝', '');
		}
		$sync_fans = table('mc_mapping_fans')
			->where(array('openid' => $openids))
			->getall();
		if (!empty($sync_fans)) {
			foreach ($sync_fans as $fans) {
				mc_init_fans_info($fans['openid'], $force_init_member);
			}
		}
		iajax(0, 'success', '');
	}
}

if ('fans_sync_set' == $do) {
	permission_check_account_user('mc_fans_fans_sync_set');
	$operate = $_GPC['operate'];
	if ('save_setting' == $operate) {
		uni_setting_save('sync', intval($_GPC['setting']));
		iajax(0, '');
	}
	$setting = uni_setting($_W['uniacid'], array('sync'));
	$sync_setting = $setting['sync'];
}

if ('register' == $do) {
	$open_id = trim($_GPC['openid']);
	$password = trim($_GPC['password']);
	$repassword = trim($_GPC['repassword']);
	if (empty($open_id) || empty($password) || empty($repassword)) {
		iajax('-1', '参数错误', url('mc/fans/display'));
	}
	if ($password != $repassword) {
		iajax('-1', '密码不一致', url('mc/fans/display'));
	}
	$member_info = mc_init_fans_info($open_id, true);
	$member_salt = table('mc_members')
		->where(array('uid' => $member_info['uid']))
		->getcolumn('salt');
	$password = md5($password . $member_salt . $_W['config']['setting']['authkey']);
	table('mc_members')
		->where(array('uid' => $member_info['uid']))
		->fill(array('password' => $password))
		->save();
	iajax('0', '注册成功', url('mc/member/base_information', array('uid' => $member_info['uid'])));
}

if ('sync_member' == $do) {
	$sync_member = 1 == $_GPC['sync_member'] ? 1 : 0;
	uni_setting_save('sync_member', $sync_member);
	iajax(0, $sync_member);
}
template('mc/fans');
