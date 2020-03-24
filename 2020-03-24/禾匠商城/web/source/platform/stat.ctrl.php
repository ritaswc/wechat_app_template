<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

$dos = array('keyword', 'rule', 'history', 'trend', 'del', 'setting', 'browser');
$do = !empty($_GPC['do']) && in_array($do, $dos) ? $do : 'keyword';

if ($do == 'history') {
		$_W['page']['title'] = '聊天记录 - 数据统计';
	$where = '';
	$starttime = empty($_GPC['time']['start']) ? TIMESTAMP - 86400 * 60 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$where .= " AND createtime >= '$starttime' AND createtime < '$endtime'";
	!empty($_GPC['keyword']) && $where .= " AND message LIKE '%{$_GPC['keyword']}%'";
	switch ($_GPC['searchtype']) {
		case 'default':
			$where .= " AND module = 'default'";
			break;
		case 'rule':
		default:
			$where .= " AND module <> 'default'";
			break;
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 30;
	$list = pdo_fetchall("SELECT * FROM ".tablename('stat_msg_history')." WHERE uniacid = '{$_W['uniacid']}' $where ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.','. $psize);
	if (!empty($_GPC['export'])) {
		$list = pdo_fetchall("SELECT * FROM ".tablename('stat_msg_history')." WHERE uniacid = '{$_W['uniacid']}' $where ORDER BY createtime DESC");
	}
	if (!empty($list)) {
		foreach ($list as $index => &$history) {
			if (!empty($history['from_user'])) {
				$tag = pdo_fetchcolumn('SELECT tag FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :id AND openid = :oid', array(':id' => $_W['uniacid'], ':oid' => $history['from_user']));
				if (is_base64($tag)){
					$tag = base64_decode($tag);
				}
				if (is_serialized($tag)) {
					$tag = @iunserializer($tag);
				}
				$tag = (array)$tag;
				$history['from_user'] = $tag['nickname'] ? $tag['nickname'] : $history['from_user'];
			}
			if ($history['type'] == 'text') {
				$history['message'] = iunserializer($history['message']);
				$history['module'] = empty($history['message']['source']) ? $history['module'] : $history['message']['source'];
				$history['message'] = emotion($history['message']['original']) ? emotion($history['message']['original']) : emotion($history['message']['content']);
			} elseif ($history['type'] == 'link') {
				$history['message'] = iunserializer($history['message']);
				$history['module'] = empty($history['message']['source']) ? $history['message']['content'] : $history['message']['source'];
				$history['message'] = '<a href="'.$history['message']['url'].'" target="_blank" title="'.$history['message']['description'].'">'.$history['message']['title'].'</a>';
			} elseif ($history['type'] == 'image') {
				$history['message'] = '<a href="'.url('platform/stat/browser', array('attach' => $history['message'])).'" class="btn btn-success btn-sm" target="_blank">查看图片</a>';
			} elseif ($history['type'] == 'location') {
				$history['message'] = iunserializer($history['message']);
				$history['message'] = '<a href="http://st.map.soso.com/api?size=800*600&center='.$history['message']['y'].','.$history['message']['x'].'&zoom=16&markers='.$history['message']['y'].','.$history['message']['x'].',1" target="_blank">查看方位</a>';
			} elseif ($history['type'] == 'click') {
				$history['message'] = emotion($history['message']);
			} elseif ($history['type'] == 'view') {
				$history['message'] = '<a href="'.$history['message']['url'].'" target="_blank">跳转链接</a>';
			} else {
				$history['message'] = emotion($history['message']);
			}
			if (!empty($history['rid'])) {
				$rids[$history['rid']] = $history['rid'];
			}
		}
	}
	if (!empty($rids)) {
		$rules = pdo_fetchall("SELECT name, id, module FROM ".tablename('rule')." WHERE id IN (".implode(',', $rids).")", array(), 'id');
		foreach ($rules as $key => &$li) {
			if ($li['module'] == 'cover') {
				$cover_reply = pdo_fetch('SELECT module,do FROM ' . tablename('cover_reply') . ' WHERE rid = :rid', array(':rid' => $key));
				if (!in_array($cover_reply['module'], array('mc', 'site', 'card'))) {
					$eid = pdo_fetchcolumn('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE module = :m AND do = :do AND entry = :entry', array(':m' => $cover_reply['module'], ':do' => $cover_reply['do'], ':entry' => 'cover'));
					$li['url'] = url('platform/cover/', array('eid' => $eid));
				} else {
					$li['url'] = url('platform/cover/' . $cover_reply['module']);
				}
				$li['module'] = 'cover' . ($cover_reply['module']?'->'.$cover_reply['module']:'');
			} else {
				$li['url'] = url('platform/reply/post', array('m' => $rules[$key]['module'], 'rid' => $key));
			}
		}
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stat_msg_history') . " WHERE uniacid = '{$_W['uniacid']}' $where");
	$pager = pagination($total, $pindex, $psize);
	if (!empty($_GPC['export'])) {
		$header = array(
			'name' => '用户',
			'realname' => '内容',
			'groupid' => '规则',
			'mobile' => '模块',
			'email' => '时间'
		);
		$keys = array_keys($header);
		$html = "\xEF\xBB\xBF";
		foreach ($header as $head) {
			$html .= $head . "\t ,";
		}
		$html .= "\n";
		if (!empty($list)) {
			foreach ($list as $row) {
				$nickname = pdo_fetchcolumn("SELECT nickname FROM ". tablename('mc_mapping_fans')." WHERE openid = :openid", array(':openid' => $row['from_user']));
				$html .= $nickname. "\t ,";
				if ($row['type'] == 'text') {
					$row['message'] = iunserializer($row['message']);
				} elseif ($row['type'] == 'link') {
					$row['message'] = '链接';
				} elseif ($row['type'] == 'image') {
					$row['message'] = '图片';
				} elseif ($row['type'] == 'location') {
					$row['message'] = '位置信息';
				} elseif ($row['type'] == 'click') {
					$row['message'] = '';
				} elseif ($row['type'] == 'view') {
					$row['message'] = '语音';
				} else {
					$row['message'] = '表情';
				}
				$html .= $row['message']. "\t ,";
				if (empty($row['rid']) && $row['module']) {
					$module =  pdo_fetchcolumn("SELECT title from ".tablename('modules')." WHERE  name = :name", array(':name' => $row['module']));
					$html .= $module. "\t , ";
				} elseif (!empty($row['rid'])) {
					$html .= $rules[$row['rid']]['name']. "\t , ";
				}
				if ($row['module'] == 'default') {
					$html .= "没有回复规则, \t, ";
				} else {
					$module = array();
					if ($row['module']) {
							$module =  pdo_fetchcolumn("SELECT title from ".tablename('modules')." WHERE  name = :name", array(':name' => $row['module']));
							if (!empty($module)) {
								$html .= $module. "\t, ";
							}
					}
					if ($rules[$row['rid']]['module'] && empty($module)) {
						$row['module'] = str_replace('cover->', '', $rules[$row['rid']]['module']);
						$module =  pdo_fetchcolumn("SELECT title from ".tablename('modules')." WHERE  name = :name", array(':name' => $row['module']));
						$html .= $module ." \t, ";
					} else {
						if (empty($module)){
							 $html .= "未触发模块". "\t, ";
						}
					}
				}

				if ($row['module'] == 'default') {
					$html .= "没有回复规则, \t, ";
				}
				$html .= date('Y-m-d H:i:s', $row['createtime']). "\t , \n";
			}
		}
		
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=聊天记录.csv");
		echo $html;
		exit();
	}
	template('platform/stat-history');
}

if ($do == 'del') {
	$op = $_GPC['op'] ? trim($_GPC['op']) : itoast('非法访问', '', 'error');
	$id = intval($_GPC['id']);
	if($op == 'history') {
		pdo_delete('stat_msg_history', array('id' => $id, 'uniacid' => $_W['uniacid']));
		itoast('删除聊天数据成功', url('platform/stat/history'), 'success');
	}
}
if ($do == 'rule') {
		$_W['page']['title'] = '回复规则使用情况 - 数据统计';
	$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'hit';
	
	$where = '';
	$starttime = empty($_GPC['time']['start']) ? TIMESTAMP - 86400 * 60 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$where .= " AND createtime >= '$starttime' AND createtime < '$endtime'";
	if ($foo == 'hit') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$list = pdo_fetchall("SELECT * FROM ".tablename('stat_rule')." WHERE  uniacid = '{$_W['uniacid']}' $where ORDER BY hit DESC LIMIT ".($pindex - 1) * $psize.','. $psize, array(), 'rid');
		$rids = array_keys($list);
		if (!empty($rids)) {
			$rules = pdo_fetchall("SELECT name, id, module FROM ".tablename('rule')." WHERE id IN (".implode(',', $rids).")", array(), 'id');
			foreach ($rules as $key => &$li) {
				if ($li['module'] == 'cover') {
					$cover_reply = pdo_fetch('SELECT module,do FROM ' . tablename('cover_reply') . ' WHERE rid = :rid', array(':rid' => $key));
					if (!in_array($cover_reply['module'], array('mc', 'site', 'card'))) {
						$eid = pdo_fetchcolumn('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE module = :m AND do = :do AND entry = :entry', array(':m' => $cover_reply['module'], ':do' => $cover_reply['do'], ':entry' => 'cover'));
						$li['url'] = url('platform/cover/', array('eid' => $eid));
					} else {
						$li['url'] = url('platform/cover/' . $cover_reply['module']);
					}
					$li['module'] = 'cover' . ($cover_reply['module']?'->'.$cover_reply['module']:'');
				} else {
					$li['url'] = url('platform/reply/post', array('m' => $rules[$key]['module'], 'rid' => $key));
				}
			}
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('stat_rule')." WHERE uniacid = '{$_W['uniacid']}' $where");
		$pager = pagination($total, $pindex, $psize);
		template('platform/stat-rule_hit');
	} elseif ($foo == 'miss') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$list = pdo_fetchall("SELECT name, id, module FROM ".tablename('rule')." WHERE uniacid = '{$_W['uniacid']}' AND id NOT IN (SELECT rid FROM ".tablename('stat_rule')." WHERE  uniacid = '{$_W['uniacid']}' $where) LIMIT ".($pindex - 1) * $psize.','. $psize, array(), 'id');
		if (!empty($list)) {
			foreach ($list as $key => &$li) {
				if ($li['module'] == 'cover') {
					$cover_reply = pdo_fetch('SELECT module,do FROM ' . tablename('cover_reply') . ' WHERE rid = :rid', array(':rid' => $key));
					if (!in_array($cover_reply['module'], array('mc', 'site', 'card'))) {
						$eid = pdo_fetchcolumn('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE module = :m AND do = :do AND entry = :entry', array(':m' => $cover_reply['module'], ':do' => $cover_reply['do'], ':entry' => 'cover'));
						$li['url'] = url('platform/cover/', array('eid' => $eid));
					} else {
						$li['url'] = url('platform/cover/' . $cover_reply['module']);
					}
					$li['module'] = 'cover' . ($cover_reply['module']?'->'.$cover_reply['module']:'');
				} else {
					$li['url'] = url('platform/reply/post', array('m' => $li['module'], 'rid' => $key));
				}
			}
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('rule')." WHERE uniacid = '{$_W['uniacid']}' AND id NOT IN (SELECT rid FROM ".tablename('stat_rule')." WHERE  uniacid = '{$_W['uniacid']}' $where)");
		$pager = pagination($total, $pindex, $psize);
		template('platform/stat-rule_miss');
	}
	
}
if ($do == 'keyword') {
		$_W['page']['title'] = '关键字命中情况 - 数据统计';
	$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'hit';
	
	$where = '';
	$starttime = empty($_GPC['time']['start']) ? TIMESTAMP - 86400 * 60 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$where .= " AND createtime >= '$starttime' AND createtime < '$endtime'";
	
	if ($foo == 'hit') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$list = pdo_fetchall("SELECT * FROM ".tablename('stat_keyword')." WHERE  uniacid = '{$_W['uniacid']}' $where ORDER BY hit DESC LIMIT ".($pindex - 1) * $psize.','. $psize);
		if (!empty($list)) {
			foreach ($list as $index => &$history) {
				if (!empty($history['rid'])) {
					$rids[$history['rid']] = $history['rid'];
				}
				$kids[$history['kid']] = $history['kid'];
			}
		}
		if (!empty($rids)) {
			$rules = pdo_fetchall("SELECT name, id, module FROM ".tablename('rule')." WHERE id IN (".implode(',', $rids).")", array(), 'id');
			foreach ($rules as $key => &$li) {
				if ($li['module'] == 'cover') {
					$cover_reply = pdo_fetch('SELECT module,do FROM ' . tablename('cover_reply') . ' WHERE rid = :rid', array(':rid' => $key));
					if (!in_array($cover_reply['module'], array('mc', 'site', 'card'))) {
						$eid = pdo_fetchcolumn('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE module = :m AND do = :do AND entry = :entry', array(':m' => $cover_reply['module'], ':do' => $cover_reply['do'], ':entry' => 'cover'));
						$li['url'] = url('platform/cover/', array('eid' => $eid));
					} else {
						$li['url'] = url('platform/cover/' . $cover_reply['module']);
					}
					$li['module'] = 'cover' . ($cover_reply['module']?'->'.$cover_reply['module']:'');
				} else {
					$li['url'] = url('platform/reply/post', array('m' => $rules[$key]['module'], 'rid' => $key));
				}
			}
		}
		if (!empty($kids)) {
			$keywords = pdo_fetchall("SELECT content, id FROM ".tablename('rule_keyword')." WHERE id IN (".implode(',', $kids).")", array(), 'id');
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('stat_keyword')." WHERE  uniacid = '{$_W['uniacid']}' $where");
		$pager = pagination($total, $pindex, $psize);
		template('platform/stat-keyword_hit');
	} elseif ($foo == 'miss') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$list = pdo_fetchall("SELECT content, id, module, rid FROM ".tablename('rule_keyword')." WHERE uniacid = '{$_W['uniacid']}' AND id NOT IN (SELECT kid FROM ".tablename('stat_keyword')." WHERE  uniacid = '{$_W['uniacid']}' $where) LIMIT ".($pindex - 1) * $psize.','. $psize);
		if (!empty($list)) {
			foreach ($list as $index => $row) {
				if (!empty($row['rid'])) {
					$rids[$row['rid']] = $row['rid'];
				}
			}
		}
		if (!empty($rids)) {
			$rules = pdo_fetchall("SELECT name, id, module FROM ".tablename('rule')." WHERE id IN (".implode(',', $rids).")", array(), 'id');
			foreach($rules as $key => &$li) {
				if ($li['module'] == 'cover') {
					$cover_reply = pdo_fetch('SELECT module,do FROM ' . tablename('cover_reply') . ' WHERE rid = :rid', array(':rid' => $key));
					if (!in_array($cover_reply['module'], array('mc', 'site', 'card'))) {
						$eid = pdo_fetchcolumn('SELECT eid FROM ' . tablename('modules_bindings') . ' WHERE module = :m AND do = :do AND entry = :entry', array(':m' => $cover_reply['module'], ':do' => $cover_reply['do'], ':entry' => 'cover'));
						$li['url'] = url('platform/cover/', array('eid' => $eid));
					} else {
						$li['url'] = url('platform/cover/' . $cover_reply['module']);
					}
					$li['module'] = 'cover' . ($cover_reply['module']?'->'.$cover_reply['module']:'');
				} else {
					$li['url'] = url('platform/reply/post', array('m' => $rules[$key]['module'], 'rid' => $key));
				}
			}
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('rule_keyword')." WHERE uniacid = '{$_W['uniacid']}' AND id NOT IN (SELECT kid FROM ".tablename('stat_keyword')." WHERE  uniacid = '{$_W['uniacid']}' $where)");
		$pager = pagination($total, $pindex, $psize);
		template('platform/stat-keyword_miss');
	}
}

if ($do == 'setting') {
		$_W['page']['title'] = '参数 - 数据统计';
	$settings = uni_setting($_W['uniacid'], array('stat'));
	$settings = $settings['stat'];
	$default  = array('msg_history' => '1','msg_maxday' => '30','use_ratio' => '1');
	$settings = empty($settings) ? $default : $settings;
	if (checksubmit('submit')) {
		$stat = array();
		$stat['msg_history'] = intval($_GPC['msg_history']);
		$stat['msg_maxday'] = intval($_GPC['msg_maxday']);
		$stat['use_ratio'] = intval($_GPC['use_ratio']);
		$stat = iserializer($stat);
		pdo_update('uni_settings', array('stat' => $stat), array('uniacid' => $_W['uniacid']));
		cache_delete(cache_system_key('unisetting', array('uniacid' => $_W['uniacid'])));
		itoast('设置参数成功', 'refresh', 'success');
	}
	template('platform/stat-setting');
}

if ($do == 'trend') {
	$_W['page']['title'] = '关键指标详解 - 数据统计';
	$id = intval($_GPC['id']);
	$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	$list = pdo_fetchall("SELECT createtime, hit  FROM " . tablename('stat_rule') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = :rid AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array(':rid' => $id, ':createtime' => $starttime, ':endtime' => $endtime));
	$day = $hit = array();
	if (!empty($list)) {
		foreach ($list as $row) {
			$day[] = date('m-d', $row['createtime']);
			$hit[] = intval($row['hit']);
		}
	}
	
	for ($i = 0; $i = count($hit) < 2; $i++) {
		$day[] = date('m-d', $endtime);
		$hit[] = $day[$i] == date('m-d', $endtime) ? $hit[0] : '0';
	}
	$list = pdo_fetchall("SELECT createtime, hit, rid, kid FROM " . tablename('stat_keyword') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = :rid AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array(':rid' => $id, ':createtime' => $starttime, ':endtime' => $endtime));
	if (!empty($list)) {
		foreach ($list as $row) {
			$keywords[$row['kid']]['hit'][] = $row['hit'];
			$keywords[$row['kid']]['day'][] = date('m-d', $row['createtime']);
		}
		foreach ($keywords as &$value) {
			
			if (count($value['hit']) < 2) {
				$value['hit'][] = $value['day'][0] == date('m-d', $endtime) ? $value['hit'][0] : '0';
				$value['day'][] = date('m-d', $endtime);
			}
		}
		$keywordnames = pdo_fetchall("SELECT content, id FROM " . tablename('rule_keyword') . " WHERE id IN (" . implode(',', array_keys($keywords)) . ")", array(), 'id');
	}
	template('platform/stat-trend');
}

if ($do == 'browser') {
	$attachment = $_GPC['attach'];
	load()->func('communication');
	$content = ihttp_request($attachment, '', array('CURLOPT_REFERER' => 'http://www.qq.com'));
	header('Content-Type:image/png');
	echo $content['content'];
}
