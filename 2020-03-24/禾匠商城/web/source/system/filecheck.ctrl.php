<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('file');
load()->model('cloud');
load()->func('communication');

$dos = array('check');
$do = in_array($do, $dos) ? $do : '';

if ('check' == $do) {
	$filetree = file_tree(IA_ROOT, array('api', 'app', 'framework', 'payment', 'web', 'api.php', 'index.php'));
	$modify = $unknown = $lose = $clouds = array();

	$params = _cloud_build_params();
	$params['method'] = 'application.build4';
	$response = cloud_request('http://api-upgrade.w7.cc/gateway.php', $params);
	$file = IA_ROOT . '/data/application.build';
	$cloud_data = _cloud_shipping_parse($response, $file);

	if (!empty($cloud_data['files'])) {
		foreach ($cloud_data['files'] as $value) {
			$clouds[$value['path']]['path'] = $value['path'];
			$clouds[$value['path']]['checksum'] = $value['checksum'];
		}
		foreach ($filetree as $filename) {
			$file = str_replace(IA_ROOT, '', $filename);
			$ignore_list = array(
					0 === strpos($file, '/data/tpl/'),
					'map.json' == substr($file, -8),
					0 === strpos($file, '/data/logs'),
					0 === strpos($file, '/attachment'),
					'/data/config.php' == $file,
					0 === strpos($file, '/data') &&
					'lock' == substr($file, -4),
					0 === strpos($file, '/app/themes/default'),
					'/framework/version.inc.php' == $file,
			);
			if (in_array(true, $ignore_list)) {
				continue;
			}

			if (preg_match('/\/addons\/([a-zA-Z_0-9\-]+)\/.*/', $file, $match)) {
				$module = IA_ROOT . '/addons/' . $match[1];
				if (file_exists($module . '/map.json')) {
					$maps = file_get_contents($module . '/map.json');
					$maps = json_decode($maps, true);
					if (!empty($maps)) {
						$checksum_found = false;
						foreach ($maps as $map) {
							if (!is_array($map) || empty($map['checksum'])) {
								continue;
							} else {
								$checksum_found = true;
								$clouds['/addons/' . $match[1] . $map['path']] = array('path' => '/addons/' . $match[1] . $map['path'], 'checksum' => $map['checksum']);
							}
						}
						if (empty($checksum_found)) {
							continue;
						}
					}
				} else {
					continue;
				}
			}

			if (preg_match('/\/app\/themes\/([a-zA-Z_0-9\-]+)\/.*/', $file, $match)) {
				$template = IA_ROOT . '/app/themes/' . $match[1];
				if (file_exists($template . '/map.json')) {
					$maps = file_get_contents($template . '/map.json');
					$maps = json_decode($maps, true);
					if (!empty($maps)) {
						$checksum_found = false;
						foreach ($maps as $map) {
							if (!is_array($map) || empty($map['checksum'])) {
								continue;
							} else {
								$checksum_found = true;
								$clouds['/app/themes/' . $match[1] . $map['path']] = array('path' => '/app/themes/' . $match[1] . $map['path'], 'checksum' => $map['checksum']);
							}
						}
						if (empty($checksum_found)) {
							continue;
						}
					}
				} else {
					continue;
				}
			}

			if (!empty($clouds[$file])) {
				if (!is_file($filename) || $clouds[$file]['checksum'] != md5_file($filename)) {
					$modify[] = $file;
				}
			} else {
				$unknown[] = $file;
			}
		}

		foreach ($clouds as $value) {
			$cloud = IA_ROOT . $value['path'];
			if (!in_array($cloud, $filetree)) {
				$cloud = str_replace(IA_ROOT, '', $cloud);
				$lose[] = $cloud;
			}
		}
	}

	$count_unknown = count($unknown);
	$count_lose = count($lose);
	$count_modify = count($modify);
}
template('system/filecheck');