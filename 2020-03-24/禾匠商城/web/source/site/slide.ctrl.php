<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$do = !empty($do) ? $do : 'display';
$do = in_array($do, array('display', 'post', 'delete')) ? $do : 'display';
permission_check_account_user('platform_site');

if ($do == 'display' && $_W['isajax'] && $_W['ispost']) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$multiid = intval($_GPC['multiid']);
	$where['uniacid'] = $_W['uniacid'];
	if ($multiid > 0) {
		$where['multiid'] = $multiid;
	}
	if (!empty($_GPC['keyword'])) {
		$where['title LIKE'] = "%{$_GPC['keyword']}%";
	}
	$list = table('site_slide')
		->getBySnake('*', $where)
		->searchWithPage($psize, $psize)
		->getall();

	$total = table('site_slide')->where($where)->getcolumn('COUNT(*)');
	$pager = pagination($total, $pindex, $psize);
	iajax(0, $list, '');
}

if ($do == 'post' && $_W['isajax'] && $_W['ispost']) {
	$multiid = intval($_GPC['multiid']);
	
	if (empty($_GPC['slide'])) {
		table('site_slide')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'multiid' => $multiid
			))
			->delete();
	} else {
		foreach ($_GPC['slide'] as $key => $val) {
			if (empty($val['thumb'])){
				iajax(-1, '幻灯图片不可为空', '');
			}
		}
		table('site_slide')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'multiid' => $multiid
			))
			->delete();
		foreach ($_GPC['slide'] as  $value) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'multiid' => $multiid,
				'title' => $value['title'],
				'url' => $value['url'],
				'thumb' => $value['thumb'],
				'displayorder' => intval($value['displayorder']),
			);
			table('site_slide')
				->fill($data)
				->save();
		}
	}
	iajax(0, '幻灯片保存成功！', '');
}