<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
set_time_limit(0);

load()->func('file');
load()->model('cloud');
load()->func('db');
load()->model('system');
$dos = array('backup', 'restore', 'trim', 'optimize', 'run', 'scrap_table', 'delete_scrap_table', 'load_scrap_table_data', 'change_module_stutas');
$do = in_array($do, $dos) ? $do : 'backup';

if ('backup' == $do) {
	if ($_GPC['status']) {
		if (empty($_W['setting']['copyright']['status'])) {
			itoast('为了保证备份数据完整请关闭站点后再进行此操作', url('system/site'), 'error');
		}
		$sql = "SHOW TABLE STATUS LIKE '{$_W['config']['db']['tablepre']}%'";
		$tables = pdo_fetchall($sql);
		if (empty($tables)) {
			itoast('数据已经备份完成', url('system/database/'), 'success');
		}
		$series = max(1, intval($_GPC['series']));
		if (!empty($_GPC['volume_suffix']) && !preg_match('/[^0-9A-Za-z-_]/', $_GPC['volume_suffix'])) {
			$volume_suffix = $_GPC['volume_suffix'];
		} else {
			$volume_suffix = random(10);
		}
		if (!empty($_GPC['folder_suffix']) && !preg_match('/[^0-9A-Za-z-_]/', $_GPC['folder_suffix'])) {
			$folder_suffix = $_GPC['folder_suffix'];
		} else {
			$folder_suffix = TIMESTAMP . '_' . random(8);
		}
		$bakdir = IA_ROOT . '/data/backup/' . $folder_suffix;
		if (trim($_GPC['start'])) {
			$result = mkdirs($bakdir);
		}
		$size = 300;
		$volumn = 1024 * 1024 * 2;
		$dump = '';
		if (empty($_GPC['last_table'])) {
			$last_table = '';
			$catch = true;
		} else {
			$last_table = $_GPC['last_table'];
			$catch = false;
		}
		foreach ($tables as $table) {
			$table = array_shift($table);
			if (!empty($last_table) && $table == $last_table) {
				$catch = true;
			}
			if (!$catch) {
				continue;
			}
			if (!empty($dump)) {
				$dump .= "\n\n";
			}
			if ($table != $last_table) {
				$row = db_table_schemas($table);
				$dump .= $row;
			}
			$index = 0;
			if (!empty($_GPC['index'])) {
				$index = intval($_GPC['index']);
				$_GPC['index'] = 0;
			}
						while (true) {
				$start = $index * $size;
				$result = db_table_insert_sql($table, $start, $size);
				if (!empty($result)) {
					$dump .= $result['data'];
					if (strlen($dump) > $volumn) {
						$bakfile = $bakdir . "/volume-{$volume_suffix}-{$series}.sql";
						$dump .= "\n\n";
						file_put_contents($bakfile, $dump);
						++$series;
						++$index;
						$current = array(
							'last_table' => $table,
							'index' => $index,
							'series' => $series,
							'volume_suffix' => $volume_suffix,
							'folder_suffix' => $folder_suffix,
							'status' => 1,
						);
						$current_series = $series - 1;
						message('正在导出数据, 请不要关闭浏览器, 当前第 ' . $current_series . ' 卷.', url('system/database/backup/', $current), 'info');
					}
				}

				if (empty($result) || count($result['result']) < $size) {
					break;
				}
				++$index;
			}
		}
		$bakfile = $bakdir . "/volume-{$volume_suffix}-{$series}.sql";
		$dump .= "\n\n----WeEngine MySQL Dump End";
		file_put_contents($bakfile, $dump);
		itoast('数据已经备份完成', url('system/database/'), 'success');
	}
}
if ('restore' == $do) {
		$reduction = system_database_backup();
		if (!empty($_GPC['restore_dirname'])) {
		$restore_dirname = $_GPC['restore_dirname'];
		$restore_dirname_list = array_keys($reduction);
		if (!in_array($restore_dirname, $restore_dirname_list)) {
			itoast('非法访问', '', 'error');
			exit;
		}

		$volume_list = $reduction[$restore_dirname]['volume_list'];
		if (empty($_GPC['restore_volume_name'])) {
			$restore_volume_name = $volume_list[0];
		} else {
			$restore_volume_name = $_GPC['restore_volume_name'];
		}
		$restore_volume_sizes = max(1, intval($_GPC['restore_volume_sizes']));
		if ($reduction[$restore_dirname]['volume'] < $restore_volume_sizes) {
			itoast('成功恢复数据备份. 可能还需要你更新缓存.', url('system/database/restore'), 'success');
			exit;
		}
		$volume_sizes = $restore_volume_sizes;
		system_database_volume_restore($restore_volume_name);
		$next_restore_volume_name = system_database_volume_next($restore_volume_name);
		++$restore_volume_sizes ;
		$restore = array(
				'restore_volume_name' => $next_restore_volume_name,
				'restore_volume_sizes' => $restore_volume_sizes,
				'restore_dirname' => $restore_dirname,
		);
		message('正在恢复数据备份, 请不要关闭浏览器, 当前第 ' . $volume_sizes . ' 卷.', url('system/database/restore', $restore), 'success');
	}
		if ($_GPC['delete_dirname']) {
		$delete_dirname = $_GPC['delete_dirname'];
		if (!empty($reduction[$delete_dirname]) && system_database_backup_delete($delete_dirname)) {
			itoast('删除备份成功.', url('system/database/restore'), 'success');
		}
	}
}

if ('trim' == $do) {
	if ($_W['ispost']) {
		$type = $_GPC['type'];
		$data = $_GPC['data'];
		$table = $_GPC['table'];
		if ('field' == $type && pdo_fieldexists($table, $data)) {
			$sql = "ALTER TABLE `$table` DROP `$data`";
			if (false !== pdo_query($sql, $params)) {
				iajax(0, '操作成功');
			}
		} elseif ('index' == $type && pdo_indexexists($table, $data)) {
			$sql = "ALTER TABLE `$table` DROP INDEX `$data`";
			if (false !== pdo_query($sql, $params)) {
				iajax(0, '操作成功');
			}
		}
		exit();
	}

	$r = cloud_prepare();
	if (is_error($r)) {
		itoast($r['message'], url('cloud/profile'), 'error');
	}

	$upgrade = cloud_schema();
	$schemas = $upgrade['schemas'];
	

	if (!empty($schemas)) {
		foreach ($schemas as $key => $value) {
			$tablename = substr($value['tablename'], 4);
			$struct = db_table_schema(pdo(), $tablename);
			if (!empty($struct)) {
				$temp = db_schema_compare($schemas[$key], $struct);
				if (!empty($temp['fields']['less'])) {
					$diff[$tablename]['name'] = $value['tablename'];
					foreach ($temp['fields']['less'] as $key => $fields_value) {
						$diff[$tablename]['fields'][] = $fields_value;
					}
				}
				if (!empty($temp['indexes']['less'])) {
					$diff[$tablename]['name'] = $value['tablename'];
					foreach ($temp['indexes']['less'] as $key => $indexes_value) {
						$diff[$tablename]['indexes'][] = $indexes_value;
					}
				}
			}
		}
	}
}
if ('optimize' == $do) {
	$optimize_table = array();
	$sql = "SHOW TABLE STATUS LIKE '{$_W['config']['db']['tablepre']}%'";
	$tables = pdo_fetchall($sql);
	foreach ($tables as $tableinfo) {
		if ('InnoDB' == $tableinfo['Engine']) {
			continue;
		}
		if (!empty($tableinfo) && !empty($tableinfo['Data_free'])) {
			$row = array(
				'title' => $tableinfo['Name'],
				'type' => $tableinfo['Engine'],
				'rows' => $tableinfo['Rows'],
				'data' => sizecount($tableinfo['Data_length']),
				'index' => sizecount($tableinfo['Index_length']),
				'free' => sizecount($tableinfo['Data_free']),
			);
			$optimize_table[$row['title']] = $row;
		}
	}

	if ($_W['ispost']) {
		foreach ($_GPC['select'] as $tablename) {
			if (!empty($optimize_table[$tablename])) {
				$sql = "OPTIMIZE TABLE {$tablename}";
				pdo_fetch($sql);
			}
		}
		itoast('数据表优化成功.', 'refresh', 'success');
	}
}
if ('run' == $do) {
	if (!DEVELOPMENT) {
		itoast('请先开启开发模式后再使用此功能', referer(), 'info');
	}
	if ($_W['ispost']) {
		$sql = $_POST['sql'];
		pdo_run($sql);
		itoast('查询执行成功.', 'refresh', 'success');
	}
}

if (in_array($do, array('scrap_table', 'delete_scrap_table', 'load_scrap_table_data'))) {
	$installed_modules = table('modules')->where('issystem !=', MODULE_SYSTEM)->getall('name');
}

if ('scrap_table' == $do) {
	$module_status = safe_gpc_int($_GPC['module_status']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$modules_cloud_table = table('modules_cloud');
	if (!empty($module_status) && in_array($module_status, array(MODULE_CLOUD_UNINSTALL_NORMAL, MODULE_CLOUD_UNINSTALL_CHECKED, MODULE_CLOUD_UNINSTALL_IGNORE))) {
		$modules_cloud_table->where(array('module_status' => $module_status));
	}
	$modules_cloud_table->searchWithUninstall(MODULE_CLOUD_UNINSTALL);
	$modules_cloud_table->searchWithPage($pindex, $psize);
	$module_cloud = $modules_cloud_table->getall('name');
	$total = $modules_cloud_table->getLastQueryTotal();
	$pager = pagination($total, $pindex, $psize);

	if (empty($module_cloud)) {
		$module_upgrade = module_upgrade_info();
		cache_build_uninstalled_module();
	}
	$uninstalled_modules = array_diff(array_keys($module_cloud), $installed_modules);
	foreach ($module_cloud as $module_key => $module_value) {
		if (!in_array($module_key, $uninstalled_modules)) {
			unset($module_cloud[$module_key]);
			continue;
		}
		$module_cloud[$module_key] = array('logo' => $module_value['logo'], 'title' => $module_value['title'], 'name' => $module_value['name'], 'module_status' => $module_value['module_status']);
	}
}

if ('delete_scrap_table' == $do) {
	$module_name = safe_gpc_string($_GPC['module_name']);
	$tables = safe_gpc_string($_GPC['tables']);
	if (!empty($installed_modules[$module_name])) {
		iajax(-1, '模块已安装并使用，不可删除！');
	}
	if (!is_array($tables)) {
		iajax(-1, '要删除的表数据错误！');
	}
	$drop_table = array();
	foreach ($tables as $table) {
		if (pdo_tableexists(ltrim($table, 'ims_'))) {
			$drop_table[] = '`' . $table . '`';
		}
	}
	if (empty($drop_table)) {
		iajax(0, '系统已不存在这些表，无需删除！');
	}
	$result = pdo_run('DROP TABLE ' . implode(',', $drop_table) . ';');
	if ($result) {
		table('modules_cloud')
			->where(array('name' => $module_name))
			->fill(array('module_status' => MODULE_CLOUD_UNINSTALL_CHECKED))
			->save();
		iajax(0, '删除成功!');
	} else {
		iajax(-1, '删除失败!');
	}
}

if ('load_scrap_table_data' == $do) {
	$module_name = safe_gpc_string($_GPC['module_name']);
	if (!empty($installed_modules[$module_name])) {
		iajax(0, '');
	}
	$buildinfo = cloud_m_build($module_name);
	if (is_error($buildinfo) || (empty($buildinfo['sql']) && empty($buildinfo['scripts']))) {
		iajax(0, '');
	}
		if (!empty($buildinfo['sql']) || !empty($buildinfo['scripts'])) {
		if (!empty($buildinfo['sql'])) {
			preg_match_all('/\`ims_[a-zA-Z0-9\-\_]{1,50}\`/', $buildinfo['sql'], $tables);
		}
		if (!empty($buildinfo['scripts'])) {
			preg_match_all('/\`ims_[a-zA-Z0-9\-\_]{1,50}\`/', base64_decode($buildinfo['scripts']), $tables);
		}
		$tables = array_map(function ($item) {
			return trim($item, '`');
		}, $tables[0]);
		foreach ($tables as $key => $value) {
			$value = substr($value, 4);
			if (!pdo_tableexists($value)) {
				unset($tables[$key]);
			}
		}
	}
	if (empty($tables)) {
		table('modules_cloud')
			->where(array('name' => $module_name))
			->fill(array('module_status' => MODULE_CLOUD_UNINSTALL_CHECKED))
			->save();
	}
	iajax(0, !empty($tables) ? $tables : '');
}

if ('change_module_stutas' == $do) {
	$module_status = safe_gpc_int($_GPC['module_status']);
	$module_name = safe_gpc_string($_GPC['module_name']);
	if (!in_array($module_status, array(MODULE_CLOUD_UNINSTALL_NORMAL, MODULE_CLOUD_UNINSTALL_CHECKED, MODULE_CLOUD_UNINSTALL_IGNORE))) {
		iajax('-1', '参数错误');
	}
	if ($_W['isajax'] && $_W['ispost']) {
		table('modules_cloud')
			->where(array('name' => $module_name))
			->fill(array('module_status' => $module_status))
			->save();
	}
	iajax(0, '修改成功');
}

template('system/database');
