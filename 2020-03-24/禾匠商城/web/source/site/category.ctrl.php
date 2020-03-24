<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('article');

$do = !empty($do) ? $do : 'display';
$do = in_array($do, array('display', 'post', 'delete', 'change_status')) ? $do : 'display';
permission_check_account_user('platform_site');

if ($do == 'display') {
	$children = array();
	$category = table('site_category')->getBySnake('*', array('uniacid' => $_W['uniacid']), array('parentid' => 'DESC', 'displayorder' => 'DESC', 'id' => 'DESC'))->getall();
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$children[$row['parentid']][] = $row;
			unset($category[$index]);
		}
	}
	template('site/category-display');
} elseif ($do == 'post') {
	$parentid = intval($_GPC['parentid']);
	$id = intval($_GPC['id']);
		$setting = uni_setting($_W['uniacid'], array('default_site'));
	$site_styleid = table('site_multi')->where(array('id' => $setting['default_site']))->getcolumn('styleid');
	if ($site_styleid) {
		$site_template = table('site_styles')
			->searchWithTemplates(array('a.*', 'b.name', 'b.sections'))
			->where(array(
				'a.uniacid' => $_W['uniacid'],
				'a.id' => $site_styleid
			))
			->get();
	}

		$styles = $site_template = table('site_styles')
		->searchWithTemplates(array('a.*', 'b.name as tname', 'b.title'))
		->where(array('a.uniacid' => $_W['uniacid']))
		->getall();

	if (!empty($id)) {
		$category = table('site_category')->getBySnake('*', array('uniacid' => $_W['uniacid'], 'id' => $id))->get();
		if (empty($category)) {
			itoast('分类不存在或已删除', '', 'error');
		}
		if (!empty($category['css'])) {
			$category['css'] = iunserializer($category['css']);
		} else {
			$category['css'] = array();
		}
	} else {
		$category = array(
			'displayorder' => 0,
			'css' => array(),
		);
	}
	if (!empty($parentid)) {
		$parent = table('site_category')->getById($parentid, $_W['uniacid']);
		if (empty($parent)) {
			itoast('抱歉，上级分类不存在或是已经被删除！', url('site/category/display'), 'error');
		}
	}
	$category['style'] = $styles[$category['styleid']];
	$category['style']['tname'] = empty($category['style']['tname'])? 'default' : $category['style']['tname'];
	if (!empty($category['nid'])) {
		$category['nav'] = table('site_nav')->getById($category['nid']);
	} else {
		$category['nav'] = array();
	}
	$multis = table('site_multi')->getAllByUniacid($_W['uniacid']);
	if (checksubmit('submit')) {
		if (empty($_GPC['cname'])) {
			itoast('抱歉，请输入分类名称！', '', '');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['cname'],
			'displayorder' => intval($_GPC['displayorder']),
			'parentid' => intval($parentid),
			'description' => $_GPC['description'],
			'styleid' => intval($_GPC['styleid']),
			'linkurl' => $_GPC['linkurl'],
			'ishomepage' => intval($_GPC['ishomepage']),
			'enabled' => intval($_GPC['enabled']),
			'icontype' => intval($_GPC['icontype']),
			'multiid' => intval($_GPC['multiid'])
		);
		
		if ($data['icontype'] == 1) {
			$data['icon'] = '';
			$data['css'] = serialize(array(
				'icon' => array(
					'font-size' => $_GPC['icon']['size'],
					'color' => $_GPC['icon']['color'],
					'width' => $_GPC['icon']['size'],
					'icon' => empty($_GPC['icon']['icon']) ? 'fa fa-external-link' : $_GPC['icon']['icon'],
				),
			));
		} else {
			$data['css'] = '';
			$data['icon'] = $_GPC['iconfile'];
		}
		
		$isnav = intval($_GPC['isnav']);
		if ($isnav) {
			$nav = array(
				'uniacid' => $_W['uniacid'],
				'categoryid' => $id,
				'displayorder' => $_GPC['displayorder'],
				'name' => $_GPC['cname'],
				'description' => $_GPC['description'],
				'url' => "./index.php?c=site&a=site&cid={$category['id']}&i={$_W['uniacid']}",
				'status' => 1,
				'position' => 1,
				'multiid' => intval($_GPC['multiid']),
			);
			if ($data['icontype'] == 1) {
				$nav['icon'] = '';
				$nav['css'] = serialize(array(
					'icon' => array(
						'font-size' => $_GPC['icon']['size'],
						'color' => $_GPC['icon']['color'],
						'width' => $_GPC['icon']['size'],
						'icon' => empty($_GPC['icon']['icon']) ? 'fa fa-external-link' : $_GPC['icon']['icon'],
					),
					'name' => array(
						'color' => $_GPC['icon']['color'],
					),
				));
			} else {
				$nav['css'] = '';
				$nav['icon'] = $_GPC['iconfile'];
			}
			if ($category['nid']) {
				$nav_exist = table('site_nav')
					->where(array(
						'id' => $category['nid'],
						'uniacid' => $_W['uniacid']
					))
					->get();
			} else {
				$nav_exist = '';
			}
			if (!empty($nav_exist)) {
				table('site_nav')
					->where(array(
						'id' => $category['nid'],
						'uniacid' => $_W['uniacid']
					))
					->fill($nav)
					->save();
			} else {
				table('site_nav')
					->fill($nav)
					->save();
				$nid = pdo_insertid();
				$data['nid'] = $nid;
			}
		} else {
			if ($category['nid']) {
				$data['nid'] = 0;
				table('site_nav')
					->where(array(
						'id' => $category['nid'],
						'uniacid' => $_W['uniacid']
					))
					->delete();
			}
		}
		if (!empty($id)) {
			unset($data['parentid']);
			table('site_category')
				->where(array(
					'id' => $id,
					'uniacid' => $_W['uniacid']
				))
				->fill($data)
				->save();
		} else {
			table('site_category')->fill($data)->save();
			$id = pdo_insertid();
			$nav_url['url'] = "./index.php?c=site&a=site&cid={$id}&i={$_W['uniacid']}";
			table('site_nav')
				->where(array(
					'id' => $data['nid'],
					'uniacid' => $_W['uniacid']
				))
				->fill($nav_url)
				->save();
		}
		itoast('更新分类成功！', url('site/category'), 'success');
	}
	template('site/category-post');
} elseif ($do == 'delete') {
	$owner_info = account_owner($_W['uniacid']);
	if (checksubmit('submit')) {
		if (user_is_founder($_W['uid']) || $_W['uid'] == $owner_info['uid']) {
			foreach ($_GPC['rid'] as $key => $id) {
				$category_delete = article_category_delete($id);
				if (empty($category_delete)) {
					itoast('抱歉，分类不存在或是已经被删除！', referer(), 'error');
				}
			}
			itoast('分类批量删除成功！', referer(), 'success');
		} else {
			itoast('操作失败！', referer(), 'error');
		}
	} else {
		$id = intval($_GPC['id']);
		if (user_is_founder($_W['uid']) || $_W['uid'] == $owner_info['uid']) {
			$category_delete = article_category_delete($id);
			if (empty($category_delete)) {
				itoast('抱歉，分类不存在或是已经被删除！', referer(), 'error');
			}
			itoast('分类删除成功！', referer(), 'success');
		} else {
			itoast('操作失败！', referer(), 'error');
		}
	}
} elseif ($do == 'change_status') {
	$id = intval($_GPC['id']);
	$category_exist = table('site_category')
		->where(array(
			'id' => $id,
			'uniacid' => $_W['uniacid']
		))
		->get();
	if (!empty($category_exist)) {
		$status = $category_exist['enabled'] == 1 ? 0 : 1;
		$result = table('site_category')
			->where(array(
				'id' => $id,
				'uniacid' => $_W['uniacid']
			))
			->fill(array('enabled' => $status))
			->save();
		if ($result) {
			iajax(0, '更改成功！', url('site/category'));
		} else {
			iajax(1, '更改失败！', '');
		}
	} else {
		iajax(-1, '分类不存在！', '');
	}
}