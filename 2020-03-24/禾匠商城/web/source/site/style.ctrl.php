<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('extension');
load()->model('module');
load()->func('file');

$dos = array('default', 'designer', 'module', 'template', 'copy', 'build', 'del');
$do = in_array($do, $dos) ? $do : 'template';
permission_check_account_user('platform_site_style');

$site_multi_table = table('site_multi');
$site_styles_table = table('site_styles');
$site_styles_vars_table = table('site_styles_vars');
$site_templates_table = table('site_templates');

if ('template' == $do) {
	$setting = uni_setting($_W['uniacid'], array('default_site'));
	$site_multi_info = $site_multi_table->getById($setting['default_site']);
	$setting['styleid'] = $site_multi_info['styleid'];
	$site_styles_table->searchWithTemplates();
	$site_styles_table->where('a.uniacid', $_W['uniacid']);
	if (!empty($_GPC['keyword'])) {
		$keyword = safe_gpc_string($_GPC['keyword']);
		$site_styles_table->where('a.name LIKE', "%$keyword%");
	}
	$styles = $site_styles_table->getall();
	$templates = uni_templates();

		$stylesResult = array();
	foreach ($templates as $k => $v) {
		if (!empty($keyword)) {
			if (false !== strpos($v['title'], $keyword)) {
				$stylesResult[$k] = array(
					'templateid' => $v['id'],
					'name' => $v['name'],
					'title' => $v['title'],
					'type' => $v['type'],
				);
			}
		} else {
			$stylesResult[$k] = array(
				'templateid' => $v['id'],
				'name' => $v['name'],
				'title' => $v['title'],
				'type' => $v['type'],
			);
			foreach ($styles as $style_val) {
				if ($v['id'] == $style_val['templateid']) {
					unset($stylesResult[$k]);
				}
			}
		}
	}
	$templates_id = array_keys($templates);
	foreach ($styles as $v) {
				if (!in_array($v['templateid'], $templates_id)) {
			continue;
		}
		$stylesResult[] = array(
			'styleid' => $v['id'],
			'templateid' => $v['templateid'],
			'name' => $templates[$v['templateid']]['name'],
			'title' => $v['name'],
			'type' => $templates[$v['templateid']]['type'],
		);
	}
	if (!empty($_GPC['type']) && 'all' != $_GPC['type']) {
		$tmp = array();
		foreach ($stylesResult as $k => $v) {
			if ($v['type'] == $_GPC['type']) {
				$tmp[] = $v;
			}
		}
		$stylesResult = $tmp;
	}
	array_multisort($stylesResult, SORT_DESC);
	$temtypes = ext_template_type();
	template('site/tpl-display');
}

if ('default' == $do) {
	$setting = uni_setting($_W['uniacid'], array('default_site'));
	$multi = $site_multi_table->getById($setting['default_site']);
	if (empty($multi)) {
		itoast('您的默认微站找不到,请联系网站管理员');
	}
	$styleid = intval($_GPC['styleid']);
	$style = $site_styles_table->getById($styleid);
	if (empty($style)) {
		itoast('抱歉，风格不存在或是您无权限使用！');
	}
	$templateid = $style['templateid'];
	$template = array();
	$templates = uni_templates();
	if (!empty($templates)) {
		foreach ($templates as $row) {
			if ($row['id'] == $templateid) {
				$template = $row;
				break;
			}
		}
	}
	if (empty($template)) {
		itoast('抱歉，模板不存在或是您无权限使用！');
	}
	$site_multi_table->fill(array('styleid' => $styleid))->where(array('uniacid' => $_W['uniacid'], 'id' => $setting['default_site']))->save();
	$styles = $site_styles_vars_table->getAllByStyleid($styleid);
	$styles_tmp = array_keys($styles);
	$templatedata = ext_template_manifest($template['name']);
	if (empty($styles)) {
		if (!empty($templatedata['settings'])) {
			foreach ($templatedata['settings'] as $list) {
				table('site_styles_vars')
					->fill(array(
						'variable' => $list['key'],
						'content' => $list['value'],
						'description' => $list['desc'],
						'templateid' => $templateid,
						'styleid' => $styleid,
						'uniacid' => $_W['uniacid']
					))
					->save();
			}
		}
	} else {
		if (!empty($templatedata['settings'])) {
			foreach ($templatedata['settings'] as $list) {
				if (!in_array($list['key'], $styles_tmp)) {
					table('site_styles_vars')
						->fill(array(
							'content' => $list['value'],
							'templateid' => $templateid,
							'styleid' => $styleid,
							'variable' => $list['key'],
							'description' => $list['desc'],
							'uniacid' => $_W['uniacid'],
						))
						->save();
				}
			}
		}
	}
	itoast('默认模板更新成功！', url('site/style/template'), 'success');
}

if ('designer' == $do) {
	$styleid = intval($_GPC['styleid']);
	$style = $site_styles_table->getById($styleid);
	if (empty($style)) {
		itoast('抱歉，风格不存在或是已经被删除！', '', 'error');
	}
	$templateid = $style['templateid'];
	$template = $site_templates_table->getById($templateid);
	if (empty($template)) {
		itoast('抱歉，模板不存在或是已经被删除！', '', 'error');
	}
	$styles = $site_styles_vars_table->getAllByStyleid($styleid);
	$systemtags = array(
		'imgdir',
		'indexbgcolor',
		'indexbgimg',
		'indexbgextra',
		'fontfamily',
		'fontsize',
		'fontcolor',
		'fontnavcolor',
		'linkcolor',
		'css',
	);
	if (checksubmit('submit')) {
		if (!empty($_GPC['style'])) {
			$_GPC['style'] = safe_gpc_array($_GPC['style']);
			foreach ($_GPC['style'] as $variable => $value) {
				if (!in_array($variable, $systemtags)) {
					continue;
				}
				$value = safe_gpc_html(htmlspecialchars_decode($value, ENT_QUOTES));
				if ('imgdir' == $variable) {
					$value = safe_gpc_path($value);
				}
				if (!empty($styles[$variable])) {
					if ($styles[$variable]['content'] != $value) {
						table('site_styles_vars')
							->where(array(
								'styleid' => $styleid,
								'variable' => $variable,
							))
							->fill(array('content' => $value))
							->save();
					}
					unset($styles[$variable]);
				} elseif (!empty($value)) {
					table('site_styles_vars')
						->fill(array(
							'content' => $value,
							'templateid' => $templateid,
							'styleid' => $styleid,
							'variable' => $variable,
							'uniacid' => $_W['uniacid'],
						))
						->save();
				}
			}
		}
		if (!empty($_GPC['custom']['name'])) {
			$_GPC['custom']['name'] = safe_gpc_array($_GPC['custom']['name']);
			foreach ($_GPC['custom']['name'] as $i => $variable) {
				if (preg_match('/[^0-9A-Za-z-_]/', $variable)) {
					continue;
				}
				$value = safe_gpc_string($_GPC['custom']['value'][$i]);
				$desc = safe_gpc_string($_GPC['custom']['desc'][$i]);
				if (!empty($value)) {
					if (!empty($styles[$variable])) {
						if ($styles[$variable] != $value) {
							table('site_styles_vars')
								->where(array(
									'templateid' => $templateid,
									'variable' => $variable,
									'uniacid' => $_W['uniacid'],
									'styleid' => $styleid,
								))
								->fill(array(
									'content' => $value,
									'description' => $desc
								))
								->save();
						}
						unset($styles[$variable]);
					} else {
						table('site_styles_vars')
							->fill(array(
								'content' => $value,
								'templateid' => $templateid,
								'styleid' => $styleid,
								'variable' => $variable,
								'description' => $desc,
								'uniacid' => $_W['uniacid'],
							))
							->save();
					}
				}
			}
		}

		if (!empty($styles)) {
			$stylekeys = array_keys($styles);
			foreach ($stylekeys as $value) {
				table('site_styles_vars')
					->where(array(
						'uniacid' => $_W['uniacid'],
						'styleid' => $styleid,
						'variable' => $value
					))
					->delete();
			}
		}
		$site_styles_table->fill(array('name' => safe_gpc_string($_GPC['name'])))->where(array('id' => $styleid, 'uniacid' => $_W['uniacid']))->save();
		itoast('更新风格成功！', url('site/style'), 'success');
	}

	template('site/tpl-post');
}

if ('module' == $do) {
		if (empty($_W['isfounder'])) {
		itoast('您无权进行该操作！', '', '');
	}
	$setting = uni_setting($_W['uniacid'], array('default_site'));
	$multi_info = $site_multi_table->getById($setting['default_site']);
	$styleid = !empty($multi_info) ? $multi_info['styleid'] : 0;
	$style_info = $site_styles_table->getById($styleid);
	$templateid = !empty($style_info) ? $style_info['templateid'] : 0;
	$ts = uni_templates();
	$currentTemplate = !empty($ts[$templateid]) ? $ts[$templateid]['name'] : 'default';
	$modules = uni_modules();
	$path = IA_ROOT . '/addons';
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($modulepath = readdir($handle))) {
				if ('.' != $modulepath && '..' != $modulepath && !empty($modules[$modulepath])) {
					if (is_dir($path . '/' . $modulepath . '/template/mobile')) {
						if ($handle1 = opendir($path . '/' . $modulepath . '/template/mobile')) {
							while (false !== ($mobilepath = readdir($handle1))) {
								if ('.' != $mobilepath && '..' != $mobilepath && strexists($mobilepath, '.html')) {
									$templates[$modulepath][] = $mobilepath;
								}
							}
						}
					}
				}
			}
		}
	}
	template('site/style');
}

if ('build' == $do) {
	$templateid = intval($_GPC['styleid']);
	$template = array();
	$templates = uni_templates();
	if (!empty($templates)) {
		foreach ($templates as $row) {
			if ($row['id'] == $templateid) {
				$template = $row;
				break;
			}
		}
	}
	if (empty($template)) {
		itoast('抱歉，模板不存在或是您无权限使用！', '', 'error');
	}
	list($templatetitle) = explode('_', $template['title']);
	$newstyle = array(
		'uniacid' => $_W['uniacid'],
		'name' => $templatetitle . '_' . random(4),
		'templateid' => $template['id'],
	);
	table('site_styles')
		->fill($newstyle)
		->save();
	$id = pdo_insertid();

	$templatedata = ext_template_manifest($template['name']);
	if (!empty($templatedata['settings'])) {
		foreach ($templatedata['settings'] as $style_var) {
			if (!empty($style_var['key']) && !empty($style_var['desc'])) {
				table('site_styles_vars')
					->fill(array(
						'content' => $style_var['value'],
						'templateid' => $templateid,
						'styleid' => $id,
						'variable' => $style_var['key'],
						'uniacid' => $_W['uniacid'],
						'description' => $style_var['desc'],
					))
					->save();
			}
		}
	}
	itoast('风格创建成功，进入“设计风格”界面。', url('site/style/designer', array('templateid' => $template['id'], 'styleid' => $id)), 'success');
}

if ('copy' == $do) {
	$styleid = intval($_GPC['styleid']);

	$style = $site_styles_table->getById($styleid);
	if (empty($style)) {
		itoast('抱歉，风格不存在或是已经被删除！', '', 'error');
	}
	$templateid = $style['templateid'];
	$template = $site_templates_table->getById($templateid);
	if (empty($template)) {
		itoast('抱歉，模板不存在或是已经被删除！', '', 'error');
	}

	list($name) = explode('_', $style['name']);
	$newstyle = array(
		'uniacid' => $_W['uniacid'],
		'name' => $name . '_' . random(4),
		'templateid' => $style['templateid'],
	);
	table('site_styles')
		->fill($newstyle)
		->save();
	$id = pdo_insertid();

	$styles = $site_styles_vars_table->getAllByStyleid($styleid);
	if (!empty($styles)) {
		foreach ($styles as $data) {
			$data['styleid'] = $id;
			table('site_styles_vars')
				->fill($data)
				->save();
		}
	}
	itoast('风格复制成功，进入“设计风格”界面。', url('site/style/designer', array('templateid' => $style['templateid'], 'styleid' => $id)), 'success');
}

if ('del' == $do) {
	$styleid = intval($_GPC['styleid']);
	table('site_styles_vars')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'styleid' => $styleid
		))
		->delete();
	table('site_styles')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'id' => $styleid
		))
		->delete();
	itoast('删除风格成功。', referer(), 'success');
}