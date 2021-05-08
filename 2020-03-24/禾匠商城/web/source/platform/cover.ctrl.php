<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('reply');
load()->model('module');

$dos = array('module', 'post');
$do = in_array($do, $dos) ? $do : 'module';

$system_modules = module_system();
if (!in_array($_GPC['m'], $system_modules) && 'post' == $do) {
	permission_check_account_user('', true, 'cover');
}
define('IN_MODULE', true);

if ('module' == $do) {
	$modulename = $_GPC['m'];
	$entry_id = intval($_GPC['eid']);
	$cover_keywords = array();
	if (empty($modulename)) {
		$entry = module_entry($entry_id);
		$modulename = $entry['module'];
	}
	$module = $_W['current_module'] = module_fetch($modulename);

	if (empty($module)) {
		itoast('模块不存在或是未安装', '', 'error');
	}
	if (!empty($module['isrulefields'])) {
		$url = url('platform/reply', array('m' => $module['name'], 'eid' => $entry_id));
	}
	if (empty($url)) {
		$url = url('platform/cover', array('m' => $module['name'], 'eid' => $entry_id));
	}

	define('ACTIVE_FRAME_URL', $url);
	$entries = module_entries($modulename);
	$sql = 'SELECT b.`do`, a.`type`, a.`content` FROM ' . tablename('rule_keyword') . ' as a LEFT JOIN ' . tablename('cover_reply') . ' as b ON a.rid = b.rid WHERE b.uniacid = :uniacid AND b.module = :module';
	$params = array(':uniacid' => $_W['uniacid'], ':module' => $module['name']);
	$replies = pdo_fetchall($sql, $params);
	foreach ($replies as $replay) {
		$cover_keywords[$replay['do']][] = $replay;
	}
	$module_permission = permission_account_user_menu($_W['uid'], $_W['uniacid'], $modulename);

	foreach ($entries['cover'] as $key => &$cover) {
		$cover['url'] = ltrim($cover['url'], './');
		$permission_name = $modulename . '_cover_' . trim($cover['do']);
		if ('all' != $module_permission[0] && !in_array($permission_name, $module_permission)) {
			unset($entries['cover'][$key]);
		}
		if (!empty($cover_keywords[$cover['do']])) {
			$cover['cover']['rule']['keywords'] = $cover_keywords[$cover['do']];
		}
	}
	unset($cover);
} elseif ('post' == $do) {
	$entry_id = intval($_GPC['eid']);
	if (empty($entry_id)) {
		itoast('访问错误', '', '');
	}
	$entry = module_entry($entry_id);
	if (is_error($entry)) {
		itoast('模块菜单不存在或是模块已经被删除', '', '');
	}
	$module = $_W['current_module'] = module_fetch($entry['module']);
	$reply = pdo_get('cover_reply', array('module' => $entry['module'], 'do' => $entry['do'], 'uniacid' => $_W['uniacid']));

	if (checksubmit('submit')) {
		$keywords = @json_decode(htmlspecialchars_decode($_GPC['keywords']), true);
		$rule = array(
			'uniacid' => $_W['uniacid'],
			'name' => $entry['title'],
			'module' => 'cover',
			'containtype' => '',
			'status' => 'true' == $_GPC['status'] ? 1 : 0,
			'displayorder' => intval($_GPC['displayorder_rule']),
		);
		if (1 == $_GPC['istop']) {
			$rule['displayorder'] = 255;
		} else {
			$rule['displayorder'] = range_limit($rule['displayorder'], 0, 254);
		}
		if (!empty($reply)) {
			$rid = $reply['rid'];
			$result = pdo_update('rule', $rule, array('id' => $rid, 'uniacid' => $_W['uniacid']));
		} else {
			$result = pdo_insert('rule', $rule);
			$rid = pdo_insertid();
		}

		if (!empty($rid)) {
						pdo_delete('rule_keyword', array('rid' => $rid, 'uniacid' => $_W['uniacid']));
			if (!empty($keywords)) {
				$keyword_row = array(
					'rid' => $rid,
					'uniacid' => $_W['uniacid'],
					'module' => 'cover',
					'status' => $rule['status'],
					'displayorder' => $rule['displayorder'],
				);
				foreach ($keywords as $keyword) {
					$keyword_insert = $keyword_row;
					$keyword_insert['type'] = range_limit($keyword['type'], 1, 4);
					$keyword_insert['content'] = $keyword['content'];
					pdo_insert('rule_keyword', $keyword_insert);
				}
			}

			$entry = array(
				'uniacid' => $_W['uniacid'],
				'multiid' => 0,
				'rid' => $rid,
				'title' => $_GPC['rulename'],
				'description' => $_GPC['description'],
				'thumb' => $_GPC['thumb'],
				'url' => $entry['url'],
				'do' => $entry['do'],
				'module' => $entry['module'],
			);
			if (empty($reply['id'])) {
				pdo_insert('cover_reply', $entry);
			} else {
				pdo_update('cover_reply', $entry, array('id' => $reply['id'], 'uniacid' => $_W['uniacid']));
			}
			itoast('封面保存成功！', url('platform/cover', array('m' => $entry['module'])), 'success');
		} else {
			itoast('封面保存失败, 请联系网站管理员！', '', 'error');
		}
	}

	if (!empty($module['isrulefields'])) {
		$url = url('platform/reply', array('m' => $module['name']));
	}
	if (empty($url)) {
		$url = url('platform/cover', array('m' => $module['name']));
	}
	define('ACTIVE_FRAME_URL', $url);

	if (!empty($reply)) {
		if (!empty($reply['thumb'])) {
			$reply['src'] = tomedia($reply['thumb']);
		}
		$reply['rule'] = reply_single($reply['rid']);
		$reply['url_show'] = $entry['url_show'];
	} else {
		$reply = array(
			'title' => $entry['title'],
			'url_show' => $entry['url_show'],
			'rule' => array(
				'displayorder' => '0',
				'status' => '1',
			),
		);
	}
}
template('platform/cover');