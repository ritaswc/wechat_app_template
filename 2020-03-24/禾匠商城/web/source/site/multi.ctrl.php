<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('site');
load()->model('extension');

$dos = array('display', 'post', 'del', 'default', 'copy', 'switch', 'quickmenu_display', 'quickmenu_post');
$do = in_array($do, $dos) ? $do : 'display';

permission_check_account_user('platform_site_multi');

$setting = uni_setting($_W['uniacid'], 'default_site');
$default_site = intval($setting['default_site']);
$site_multi = table('site_multi')->getById($default_site, $_W['uniacid']);
$default_site_status = $site_multi['status'];
if ($default_site_status != 1) {
	table('site_multi')
		->where(array('id' => $default_site))
		->fill(array('status' => 1))
		->save();
}
if ($do == 'post') {
	if ($_W['isajax'] && $_W['ispost']) {
				$name = safe_gpc_string($_GPC['name']);
		$styles = table('site_styles')
			->searchWithTemplates()
			->where(array(
				'a.uniacid' => $_W['uniacid'],
				'a.name LIKE' => "%{$name}%"))
			->getall();
		iajax(0, $styles, '');
	}
	$id = intval($_GPC['multiid']);

	if (checksubmit('submit')) {
		$bindhost = parse_url($_W['siteroot']);
		if ($bindhost['host'] == safe_gpc_string($_GPC['bindhost'])) {
			itoast('绑定域名有误', referer(), 'error');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => safe_gpc_string($_GPC['title']),
			'styleid' => intval($_GPC['styleid']),
			'status' => intval($_GPC['status']),
			'site_info' => iserializer(array(
				'thumb' => safe_gpc_string($_GPC['thumb']),
				'keyword' => !empty($_GPC['keyword']) ? safe_gpc_string($_GPC['keyword']) : '微官网',
				'description' => safe_gpc_string($_GPC['description']),
				'footer' => htmlspecialchars($_GPC['footer'])
			)),
			'bindhost' => safe_gpc_string($_GPC['bindhost']),
		);
		if (!empty($id)) {
						if ($id == $default_site) {
				$data['status'] = 1;
			}
			table('site_multi')
				->where(array('id' => $id))
				->fill($data)
				->save();
		} else {
			table('site_multi')->fill($data)->save();
			$id = pdo_insertid();
		}

		$cover = array(
			'uniacid' => $_W['uniacid'],
			'title' => $data['title'],
			'keyword' => !empty($_GPC['keyword']) ? safe_gpc_string($_GPC['keyword']) : '微官网',
			'url' => url('home', array('i' => $_W['uniacid'], 't' => $id)),
			'description' => safe_gpc_string($_GPC['description']),
			'thumb' => safe_gpc_string($_GPC['thumb']),
			'module' => 'site',
			'multiid' => $id,
		);
		site_cover($cover);

		itoast('更新站点信息成功！', url('site/multi/display'), 'success');
	}

	if (!empty($id)) {
		$multi = table('site_multi')->getById($id, $_W['uniacid']);
		if (empty($multi)) {
			itoast('微站不存在或已删除', referer(), 'error');
		}
		$multi['site_info'] = iunserializer($multi['site_info']) ? iunserializer($multi['site_info']) : array();
	}


	$temtypes = ext_template_type();
	$temtypes[] = array('name' => 'all', 'title' => '全部');
	$styles = table('site_styles')
		->searchWithTemplates('a.*, b.`id` as `tid`, b.`name` AS `tname`, b.`title`, b.`type`, b.`sections`')
		->where(array('a.uniacid' => $_W['uniacid']))
		->getall('id');
	if (empty($multi)) {
		$multi = array(
			'site_info' => array(),
			'status' => 1,
		);
	}
	$multi['style'] = $styles[$multi['styleid']];
	template('site/post');
}

if ($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$where['uniacid'] = $_W['uniacid'];
	if (!empty($_GPC['keyword'])) {
		$where['title LIKE'] = "%{$_GPC['keyword']}%";
	}
	$templates = uni_templates();
	$multis = table('site_multi')
		->where($where)
		->searchWithPage($pindex, $psize)
		->getall();
	foreach ($multis as &$li) {
		$li['style'] = table('site_styles')->getById($li['styleid'], $_W['uniacid']);
		$li['template'] = table('site_templates')->getById($li['style']['templateid']);
		$li['site_info'] = (array)iunserializer($li['site_info']);
		$li['site_info']['thumb'] = tomedia($li['site_info']['thumb']);
		if (file_exists('../app/themes/'.$li['template']['name'].'/preview.jpg')) {
			$li['preview_thumb'] = $_W['siteroot'].'app/themes/'.$li['template']['name'].'/preview.jpg';
		} else {
			$li['preview_thumb'] = $_W['siteroot'].'web/resource/images/nopic-203.png';
		}
	}
	unset($li);
	$total = table('site_multi')
		->where($where)
		->getcolumn('COUNT(*)');
	$pager = pagination($total, $pindex, $psize);
	template('site/display');
}

if ($do == 'del') {
	$id = intval($_GPC['id']);
	if ($default_site == $id) {
		itoast('您删除的微站是默认微站,删除前先指定其他微站为默认微站', referer(), 'error');
	}
		table('site_nav')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'multiid' => $id
		))
		->delete();
		$rid = table('cover_reply')->where(array('uniacid' => $_W['uniacid'], 'multiid' => $id))->getcolumn('rid');

	uni_delete_rule($rid, 'cover_reply');
		table('site_multi')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'id' => $id
		))
		->delete();
	itoast('删除微站成功', referer(), 'success');
}

if ($do == 'copy') {
	$id = intval($_GPC['multiid']);
	$multi = table('site_multi')->getById($id, $_W['uniacid']);
	if (empty($multi)) {
		itoast('微站不存在或已删除', referer(), 'error');
	}
	$multi['title'] = $multi['title'] . '_' . random(6);
	unset($multi['id']);
	table('site_multi')
		->fill($multi)
		->save();
	$multi_id = pdo_insertid();
	if (!$multi_id) {
		itoast('复制微站出错', '', 'error');
	} else {
				$navs = table('site_nav')
			->getBySnake('*', array('uniacid' => $_W['uniacid'], 'multiid' => $id))
			->getall();
		if (!empty($navs)) {
			foreach ($navs as &$nav) {
				unset($nav['id']);
				$nav['multiid'] = $multi_id;
				table('site_nav')->fill($nav)->save();
			}
			unset($nav);
		}
				$cover = table('cover_reply')
			->searchWithUniacid($_W['uniacid'])
			->searchWithMultiid($id)
			->get();
		if (!empty($cover)) {
			$rule = table('rule')->getById($cover['rid'], $_W['uniacid']);
			$keywords = table('rule_keyword')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'rid' => $cover['rid']
				))
				->getall();
			if (!empty($rule) && !empty($keywords)) {
				$rule['name'] = $multi['title'] . '入口设置';
				unset($rule['id']);
				table('rule')->fill($rule)->save();
				$new_rid = pdo_insertid();
				foreach($keywords as &$keyword) {
					unset($keyword['id']);
					$keyword['rid'] = $new_rid;
					table('rule_keyword')->fill($keyword)->save();
				}
				unset($keyword);
				unset($cover['id']);
				$cover['title'] =  $multi['title'] . '入口设置';
				$cover['multiid'] =  $multi_id;
				$cover['rid'] =  $new_rid;
				table('cover_reply')->fill($cover)->save();
			}
		}
		itoast('复制微站成功', url('site/multi/post', array('multiid' => $multi_id)), 'success');
	}
}

if ($do == 'switch') {
	$id = intval($_GPC['id']);
	$multi_info = table('site_multi')->getById($id, $_W['uniacid']);
	if(empty($multi_info)) {
		itoast('微站不存在或已删除', referer(), 'error');
	}
	$data = array('status' => $multi_info['status'] == 1 ? 0 : 1);
	$result = table('site_multi')
		->where(array(
			'id' => $id,
			'uniacid' => $_W['uniacid']
		))
		->fill($data)
		->save();
	if(!empty($result)) {
		iajax(0, '更新成功！', '');
	}else {
		iajax(-1, '请求失败！', '');
	}
}
if ($do == 'quickmenu_display' && $_W['isajax'] && $_W['ispost'] && $_W['role'] != 'operator') {
	$multiid = intval($_GPC['multiid']);
	if($multiid > 0){
		$page = table('site_page')
			->where(array(
				'multiid' => $multiid,
				'type' => 2
			))
			->get();
	}
	$params = !empty($page['params']) ? $page['params'] : 'null';
	$status = $page['status'] == 1 ? 1 : 0;
	$modules = uni_modules();
	$modules = !empty($modules) ? $modules : 'null';
	iajax(0, array('params' => json_decode($params), 'status' => $status, 'modules' => $modules), '');
}

if ($do == 'quickmenu_post' && $_W['isajax'] && $_W['ispost']) {
	$params = $_GPC['postdata']['params'];
	if (empty($params)) {
		iajax(1, '请您先设计手机端页面.');
	}
	foreach ($params['position'] as &$val) {
		$val = $val == 'true' ? 1 : 0;
	}
	unset($val);
	$html = safe_gpc_html(htmlspecialchars_decode($_GPC['postdata']['html'], ENT_QUOTES));
	$html = preg_replace('/background\-image\:(\s)*url\(\"(.*)\"\)/U', 'background-image: url($2)', $html);
	$data = array(
		'uniacid' => $_W['uniacid'],
		'multiid' => intval($_GPC['multiid']),
		'title' => '快捷菜单',
		'description' => '',
		'status' => intval($_GPC['status']),
		'type' => 2,
		'params' => json_encode($params),
		'html' => $html,
		'createtime' => TIMESTAMP,
	);
	$id = table('site_page')
		->searchWithMultiid(intval($_GPC['multiid']))
		->where(array('type' => 2))
		->getcolumn('id');
	if (!empty($id)) {
		$result = table('site_page')
			->where(array(
				'id' => $id,
				'uniacid' => $_W['uniacid']
			))
			->fill($data)
			->save();
	} else {
		$result = table('site_page')->fill($data)->save();
		$id = pdo_insertid();
	}
	if ($result) {
		iajax(0, '保存成功！', '');
	} else {
		iajax(1, '保存失败！', '');
	}
}