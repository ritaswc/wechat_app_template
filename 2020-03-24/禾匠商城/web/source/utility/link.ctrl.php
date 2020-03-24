<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('site');

$dos = array('entry', 'modulelink', 'articlelist', 'pagelist', 'newslist', 'catelist', 'page', 'news', 'article');
$do = in_array($do, $dos) ? $do : 'entry';

$_W['page']['title'] = '';
$callback = $_GPC['callback'];

if ($do == 'modulelink') {
	$modules = uni_modules_app_binding();
	$entries = array();
	foreach ($modules as $module => $item) {
		$entries[$module] = module_entries($module, array('menu'));
		$entries[$module]['title'] = $item['title'];
	}
}
if ($do == 'articlelist') {
	$result = array();
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE :title";
		$param = array(':uniacid' => $_W['uniacid'], ':title' => '%'. trim($_GPC['keyword']) .'%');
	} else {
		$param = array(':uniacid' => $_W['uniacid']);
	}
	$result['list'] = pdo_fetchall("SELECT id, title, thumb, description, content, author, incontent, linkurl,  createtime, uniacid FROM ".tablename('site_article')." WHERE uniacid = :uniacid". $condition ." ORDER BY displayorder DESC, id  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $param, 'id');
	if (!empty($result['list'])) {
		foreach ($result['list'] as $k => &$v) {
			$v['thumb_url'] = tomedia($v['thumb']);
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
			$v['name'] = cutstr($v['name'], 10);
		}
		unset($v);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('site_article')." WHERE uniacid = :uniacid". $condition, $param);
		$result['pager'] = pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
	iajax(0, $result);
}
if ($do == 'pagelist') {
	$result = array();
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE :title";
		$param = array(':uniacid' => $_W['uniacid'], ':title' => '%'. trim($_GPC['keyword']) .'%');
	} else {
		$param = array(':uniacid' => $_W['uniacid']);
	}
	$result['list'] = pdo_fetchall("SELECT * FROM ".tablename('site_page')." WHERE uniacid = :uniacid AND type = '1'".$condition." ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $param, 'id');
	if (!empty($result['list'])) {
		foreach ($result['list'] as $k => &$v) {
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
		}
		unset($v);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename('site_page'). " WHERE uniacid = :uniacid AND type = 1" . $condition, $param);
		$result['pager'] = pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'true'));
	}
	iajax(0, $result);
}
if ($do == 'newslist') {
	$result = array();
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND n.title LIKE :title";
		$param = array(':uniacid' => $_W['uniacid'], ':title' => '%'. trim($_GPC['keyword']) .'%');
	} else {
		$param = array(':uniacid' => $_W['uniacid']);
	}
	$sql = "SELECT n.id, n.title, n.url FROM ". tablename('rule')."AS r,". tablename('news_reply'). " AS n WHERE r.id = n.rid AND r.module IN ('reply', 'news') AND r.uniacid = :uniacid". $condition ." ORDER BY n.displayorder DESC LIMIT ". ($pindex - 1) * $psize . ',' . $psize;
	$result['list'] = pdo_fetchall($sql, $param, 'id');
	if (!empty($result['list'])) {
		foreach ($result['list'] as $key => &$list) {
			if (empty($list['url'])) {
				$list['url'] = './index.php?i=' . $_W['uniacid'] . '&c=entry&id=' . $list['id'] . '&do=detail&m=core';
			}
		}
		$sql = "SELECT COUNT(*) FROM ". tablename('rule')."AS r,". tablename('news_reply'). " AS n WHERE r.id = n.rid AND r.module IN ('reply', 'news') AND r.uniacid = :uniacid ". $condition;
		$total = pdo_fetchcolumn($sql, $param);
		$result['pager'] = pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
	iajax(0, $result);
}
if ($do == 'catelist') {
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND name LIKE :name";
		$param = array(':uniacid' => $_W['uniacid'], ':name' => '%'.trim($_GPC['keyword']).'%');
	} else {
		$param = array(':uniacid' => $_W['uniacid']);
	}
	$category = pdo_fetchall("SELECT id, uniacid, parentid, name FROM ".tablename('site_category')." WHERE uniacid = :uniacid ". $condition." ORDER BY parentid, displayorder DESC, id", $param, 'id');
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$category[$row['parentid']]['children'][$row['id']] = $row;
			unset($category[$index]);
		}
	}
	iajax(0, $category);
}
if ($do == 'page') {
	$result = array();
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$result['list'] = pdo_fetchall("SELECT * FROM ".tablename('site_page')." WHERE uniacid = :uniacid AND type = '1' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $_W['uniacid']), 'id');
	if (!empty($result['list'])) {
		foreach ($result['list'] as $k => &$v) {
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
		}
		unset($v);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('site_page'). ' WHERE uniacid = :uniacid AND type = 1', array(':uniacid' => $_W['uniacid']));
		$result['pager'] = pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'true'));
	}
}
if ($do == 'news') {
	$result = array();
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$sql = "SELECT n.id, n.title FROM ". tablename('rule')."AS r,". tablename('news_reply'). " AS n WHERE r.id = n.rid AND r.module = :news AND r.uniacid = :uniacid ORDER BY n.displayorder DESC LIMIT ". ($pindex - 1) * $psize . ',' . $psize;
	$result['list'] = pdo_fetchall($sql, array(':news' => 'news', ':uniacid' => $_W['uniacid']), 'id');
	if (!empty($result['list'])) {
		$sql = "SELECT COUNT(*) FROM ". tablename('rule')."AS r,". tablename('news_reply'). " AS n WHERE r.id = n.rid AND r.module = :news AND r.uniacid = :uniacid ";
		$total = pdo_fetchcolumn($sql, array(':news' => 'news', ':uniacid' => $_W['uniacid']));
		$result['pager'] = pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
}
if ($do == 'article') {
	$result = array();
	$psize = 10;
	$pindex = max(1, intval($_GPC['page']));
	$result['list'] = pdo_fetchall("SELECT id, title, thumb, description, content, author, incontent, linkurl,  createtime, uniacid FROM ".tablename('site_article')." WHERE uniacid = :uniacid ORDER BY displayorder DESC, id  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $_W['uniacid']), 'id');
	if (!empty($result['list'])) {
		foreach ($result['list'] as $k => &$v) {
			$v['thumb_url'] = tomedia($v['thumb']);
			$v['createtime'] = date('Y-m-d H:i', $v['createtime']);
			$v['name'] = cutstr($v['name'], 10);
		}
		unset($v);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('site_article').' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
		$result['pager'] = pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback'=>'null'));
	}
	$category = pdo_fetchall("SELECT id, uniacid, parentid, name FROM ".tablename('site_category')." WHERE uniacid = :uniacid ORDER BY parentid, displayorder DESC, id", array(':uniacid' => $_W['uniacid']), 'id');
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$category[$row['parentid']]['children'][$row['id']] = $row;
			unset($category[$index]);
		}
	}
}
if ($do == 'entry') {
	$has_permission = array();
	if(permission_account_user_permission_exist()) {
		$has_permission = array(
			'system' => array(),
			'modules' => array()
		);
		$has_permission['system'] = permission_account_user('system');
				$module_permission = permission_account_user_menu($_W['uid'], $_W['uniacid'], 'modules');
		if(!is_error($module_permission) && !empty($module_permission)) {
			$has_permission['modules'] = array_keys($module_permission);
			foreach($module_permission as $row) {
				if($row['permission'] == 'all') {
					$has_permission[$row['type']] = array('all');
				} else {
					$has_permission[$row['type']] = explode('|', $row['permission']);
				}
			}
		}
	}

	$modulemenus = array();
	$modules = uni_modules_app_binding();
	foreach($modules as $module) {
		$m = $module['name'];
		if(empty($has_permission) || (!empty($has_permission) && in_array($m, $has_permission['modules']))) {
			$entries = $module['entries'];
			if(!empty($has_permission[$m]) && $has_permission[$m][0] != 'all') {
				if(!in_array($m.'_home', $has_permission[$m])) {
					unset($entries['home']);
				}
				if(!in_array($m.'_profile', $has_permission[$m])) {
					unset($entries['profile']);
				}
				if(!in_array($m.'_shortcut', $has_permission[$m])) {
					unset($entries['shortcut']);
				}
				if(!empty($entries['cover'])) {
					foreach($entries['cover'] as $k => $row) {
						if(!in_array($m.'_cover_'.$row['do'], $has_permission[$m])) {
							unset($entries['cover'][$k]);
						}
					}
				}
			}

			$module['cover'] = $entries['cover'];
			$module['home'] = $entries['home'];
			$module['profile'] = $entries['profile'];
			$module['shortcut'] = $entries['shortcut'];
			$module['function'] = $entries['function'];
			$modulemenus[$module['type']][$module['name']] = $module;
		}
	}
	$modtypes = module_types();

	$sysmenus = array(
		array('title'=>'微站首页','url'=> murl('home')),
		array('title'=>'个人中心','url'=> rtrim(murl('mc'), '&')),
	);

		if(empty($has_permission) || (!empty($has_permission) && in_array('site_multi_display', $has_permission['system']))) {
		$multi_list = pdo_getall('site_multi', array('uniacid' => $_W['uniacid'], 'status !=' => 0), array('id', 'title'));
		if(!empty($multi_list)) {
			foreach($multi_list as $multi) {
				$multimenus[] = array('title' => $multi['title'], 'url' => murl('home', array('t' => $multi['id'])));
			}
		}
	}
	$linktypes = array(
		'cover' => '封面链接',
		'home' => '微站首页导航',
		'profile'=>'微站个人中心导航',
		'shortcut' => '微站快捷功能导航',
		'function' => '微站独立功能',
	);
}
template('utility/link');