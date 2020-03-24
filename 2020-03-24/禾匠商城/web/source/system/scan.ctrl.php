<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

set_time_limit(0);

load()->func('file');

$dos = array('post', 'count', 'filter_func', 'filter_code', 'encode', 'display', 'view');
$do = in_array($do, $dos) ? $do : 'post';

if ('post' == $do) {
	$config = iunserializer(cache_read(cache_system_key('scan_config')));
	$list = glob(IA_ROOT . '/*', GLOB_NOSORT);
	$ignore = array('data', 'attachment');
	foreach ($list as $key => $li) {
		if (in_array(basename($li), $ignore)) {
			unset($list[$key]);
		}
	}

	$safe = array(
			'file_type' => 'php|js',
			'code' => 'weidongli|sinaapp|safedog',
			'func' => 'com|system|exec|eval|escapeshell|cmd|passthru|base64_decode|gzuncompress',
			'dir' => '',
	);

	if ($_W['ispost'] && $_W['token'] == safe_gpc_string($_GPC['token'])) {
		if (empty($_GPC['dir'])) {
			itoast('请选择要扫描的目录', referer(), 'success');
		}
		set_time_limit(0);
		foreach ($_GPC['dir'] as $k => $v) {
			if (in_array(basename($v), $ignore)) {
				unset($_GPC['dir'][$k]);
			}
		}
		$info['file_type'] = 'php|js';
		$info['func'] = trim($_GPC['func']) ? trim($_GPC['func']) : 'com|system|exec|eval|escapeshell|cmd|passthru|base64_decode|gzuncompress';
		$info['code'] = trim($_GPC['code']) ? trim($_GPC['code']) : 'weidongli|sinaapp';
		$info['md5_file'] = trim($_GPC['md5_file']);
		$info['dir'] = $_GPC['dir'];
		cache_delete(cache_system_key('scan_file'));
		cache_write(cache_system_key('scan_config'), iserializer($info));

				if (empty($info)) {
			itoast('获取扫描配置失败', url('system/scan'), 'error');
		}
		$info['file_type'] = explode('|', $info['file_type']);
		$list_arr = array();
		foreach ($info['dir'] as $v) {
			if (is_dir($v)) {
				if (!empty($info['file_type'])) {
					foreach ($info['file_type'] as $k) {
						$list_arr = array_merge($list_arr, file_lists($v . '/', 1, $k, 0, 1, 1));
					}
				}
			} else {
				$list_arr = array_merge($list_arr, array(str_replace(IA_ROOT . '/', '', $v) => md5_file($v)));
			}
		}
		unset($list_arr['data/config.php']);
		cache_write(cache_system_key('scan_file'), iserializer($list_arr));
				if (isset($info['func']) && !empty($info['func'])) {
			foreach ($list_arr as $key => $val) {
				$html = file_get_contents(IA_ROOT . '/' . $key);
				if (false != stristr($key, '.php.') || preg_match_all('/[^a-z]?(' . $config['func'] . ')\s*\(/i', $html, $state, PREG_SET_ORDER)) {
					$badfiles[$key]['func'] = $state;
				}
			}
		}
		if (!isset($badfiles)) {
			$badfiles = array();
		}
				if (isset($info['code']) && !empty($info['code'])) {
			foreach ($list_arr as $key => $val) {
				if (!empty($info['code'])) {
					$html = file_get_contents(IA_ROOT . '/' . $key);
					if (false != stristr($key, '.php.') || preg_match_all('/[^a-z]?(' . $info['code'] . ')/i', $html, $state, PREG_SET_ORDER)) {
						$badfiles[$key]['code'] = $state;
					}
				}
				if ('.php' == strtolower(substr($key, -4)) && function_exists('zend_loader_file_encoded') && zend_loader_file_encoded(IA_ROOT . '/' . $key)) {
					$badfiles[$key]['zend'] = 'zend encoded';
				}
				$html = '';
			}
		}
				foreach ($info as $key => $val) {
			if ('.php' == strtolower(substr($key, -4))) {
				$html = file_get_contents(IA_ROOT . '/' . $key);
				$token = token_get_all($html);
				$html = '';
				foreach ($token as $to) {
					if (is_array($to) && T_VARIABLE == $to[0]) {
						$pre = preg_match('/([' . chr(0xb0) . '-' . chr(0xf7) . '])+/', $to[1]);
						if (!empty($pre)) {
							$badfiles[$key]['danger'] = 'danger';
							break;
						}
					}
				}
			}
		}

		cache_write(cache_system_key('scan_badfile'), iserializer($badfiles));
		itoast('扫描完成。。。', url('system/scan', array('do' => 'display')), 'success');
	}
}

if ('display' == $do) {
	$badfiles = iunserializer(cache_read(cache_system_key('scan_badfile')));
	if (empty($badfiles)) {
		itoast('没有找到扫描结果，请重新扫描', url('system/scan'), 'error');
	}
	unset($badfiles['data/config.php']);
	foreach ($badfiles as $k => &$v) {
		$v['func_count'] = 0;
		if (isset($v['func'])) {
			$v['func_count'] = count($v['func']);
			foreach ($v['func'] as $k1 => $v1) {
				$d[$k1] = strtolower($v1[1]);
			}
			$d = array_unique($d);
			$v['func_str'] = implode(', ', $d);
		}
		$v['code_count'] = 0;
		if (isset($v['code'])) {
			$v['code_count'] = count($v['code']);
			foreach ($v['code'] as $k2 => $v2) {
				$d1[$k2] = strtolower($v2[1]);
			}
			$d1 = array_unique($d1);
			$v['code_str'] = implode(', ', $d1);
		}
	}
}

if ('view' == $do) {
	$file = authcode(trim($_GPC['file'], 'DECODE'));
	$file_tmp = $file;
	$file = str_replace('//', '', $file);
	if (empty($file) || !parse_path($file) || preg_match('/data.*config\.php/', $file)) {
		itoast('文件不存在', referer(), 'error');
	}
		$file_arr = explode('/', $file);
	$ignore = array('payment');

	if (is_array($file_arr) && in_array($file_arr[0], $ignore)) {
		itoast('系统不允许查看当前文件', referer(), 'error');
	}
	$file = IA_ROOT . '/' . $file;
	if (!is_file($file)) {
		itoast('文件不存在', referer(), 'error');
	}
	$badfiles = iunserializer(cache_read(cache_system_key('scan_badfile')));
	$info = $badfiles[$file_tmp];
	unset($badfiles);

	if (!empty($info)) {
		$info['func_count'] = 0;
		if (isset($info['func'])) {
			$info['func_count'] = count($info['func']);
			foreach ($info['func'] as $k1 => $v1) {
				$d[$k1] = strtolower($v1[1]);
			}
			$d = array_unique($d);
			$info['func_str'] = implode(', ', $d);
		}
		$info['code_count'] = 0;
		if (isset($info['code'])) {
			$info['code_count'] = count($info['code']);
			foreach ($info['code'] as $k2 => $v2) {
				$d1[$k2] = strtolower($v2[1]);
			}
			$d1 = array_unique($d1);
			$info['code_str'] = implode(', ', $d1);
		}
	}
	$data = file_get_contents($file);
}

template('system/scan');