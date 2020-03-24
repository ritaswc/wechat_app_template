<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('setting');
load()->model('attachment');

$dos = array('attachment', 'remote', 'buckets', 'oss', 'cos', 'qiniu', 'ftp', 'upload_remote');
$do = in_array($do, $dos) ? $do : 'global';

if ('upload_remote' == $do) {
	if (!empty($_W['setting']['remote_complete_info']['type'])) {
		$result = file_dir_remote_upload(ATTACHMENT_ROOT . 'images');
		if (is_error($result)) {
			iajax(-1, $result['message']);
		} else {
			if (file_dir_exist_image(ATTACHMENT_ROOT . 'images')) {
				iajax(0, array('status' => 1));
			} else {
				iajax(0, array('status' => 0, 'message' => '完成'));
			}
		}
	} else {
		iajax(-1, '请先填写并开启远程附件设置');
	}
}

if ('global' == $do) {
	if (empty($_W['setting']['upload'])) {
		$upload = $_W['config']['upload'];
	} else {
		$upload = $_W['setting']['upload'];
	}
	$post_max_size = ini_get('post_max_size');
	$post_max_size = $post_max_size > 0 ? bytecount($post_max_size) / 1024 : 0;
	$upload_max_filesize = ini_get('upload_max_filesize');
	if ($_W['ispost']) {
		$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');

		switch ($_GPC['key']) {
			case 'attachment_limit':
				$upload['attachment_limit'] = max(0, intval($_GPC['value'])); 				break;
			case 'image_thumb':
				$upload['image']['thumb'] = empty($_GPC['value']) ? 0 : 1;
				break;
			case 'image_width':
				$upload['image']['width'] = intval($_GPC['value']); 				break;
			case 'image_extentions':
				$upload['image']['extentions'] = array();
				$image_extentions = explode("\n", safe_gpc_string($_GPC['value']));
				foreach ($image_extentions as $item) {
					$item = safe_gpc_string(trim($item));
					if (!empty($item) && !in_array($item, $harmtype) && !in_array($item, $upload['image']['extentions'])) {
						$upload['image']['extentions'][] = $item;
					}
				}
				break;
			case 'image_limit':
				$upload['image']['limit'] = max(0, min(intval($_GPC['value']), $post_max_size)); 				break;
			case 'image_zip_percentage':
				$zip_percentage = intval($_GPC['value']);
				$upload['image']['zip_percentage'] = $zip_percentage;
				if ($zip_percentage <= 0 || $zip_percentage > 100) {
					$upload['image']['zip_percentage'] = 100; 				}
				break;
			case 'audio_extentions':
				$upload['audio']['extentions'] = array();
				$audio_extentions = explode("\n", safe_gpc_string($_GPC['value']));
				foreach ($audio_extentions as $item) {
					$item = safe_gpc_string(trim($item));
					if (!empty($item) && !in_array($item, $harmtype) && !in_array($item, $upload['audio']['extentions'])) {
						$upload['audio']['extentions'][] = $item;
					}
				}
				break;
			case 'audio_limit':
				$upload['audio']['limit'] = max(0, min(intval($_GPC['value']), $post_max_size)); 				break;
		}
		setting_save($upload, 'upload');
		iajax(0, '更新设置成功', url('system/attachment'));
	}

	if (empty($upload['image']['thumb'])) {
		$upload['image']['thumb'] = 0;
	} else {
		$upload['image']['thumb'] = 1;
	}
	$upload['image']['width'] = intval($upload['image']['width']);
	if (empty($upload['image']['width'])) {
		$upload['image']['width'] = 800;
	}
	if (!empty($upload['image']['extentions']) && is_array($upload['image']['extentions'])) {
		$upload['image']['extentions'] = implode("\n", $upload['image']['extentions']);
	}
	if (!empty($upload['audio']['extentions']) && is_array($upload['audio']['extentions'])) {
		$upload['audio']['extentions'] = implode("\n", $upload['audio']['extentions']);
	}
	if (empty($upload['image']['zip_percentage'])) {
		$upload['image']['zip_percentage'] = 100;
	}
}

if ('remote' == $do) {
	$remote = $_W['setting']['remote_complete_info'];
	$remote_urls = array(
		'alioss' => array('old_url' => $remote['alioss']['url']),
		'ftp' => array('old_url' => $remote['ftp']['url']),
		'qiniu' => array('old_url' => $remote['qiniu']['url']),
		'cos' => array('old_url' => $remote['cos']['url']),
	);

	if ($_W['ispost']) {
		$remote = array(
			'type' => intval($_GPC['type']),
			'ftp' => array(
				'ssl' => intval($_GPC['ftp']['ssl']),
				'host' => $_GPC['ftp']['host'],
				'port' => $_GPC['ftp']['port'],
				'username' => $_GPC['ftp']['username'],
				'password' => strexists($_GPC['ftp']['password'], '*') ? $_W['setting']['remote_complete_info']['ftp']['password'] : $_GPC['ftp']['password'],
				'pasv' => intval($_GPC['ftp']['pasv']),
				'dir' => $_GPC['ftp']['dir'],
				'url' => $_GPC['ftp']['url'],
				'overtime' => intval($_GPC['ftp']['overtime']),
			),
			'alioss' => array(
				'key' => $_GPC['alioss']['key'],
				'secret' => strexists($_GPC['alioss']['secret'], '*') ? $_W['setting']['remote_complete_info']['alioss']['secret'] : $_GPC['alioss']['secret'],
				'bucket' => $_GPC['alioss']['bucket'],
				'internal' => $_GPC['alioss']['internal'],
			),
			'qiniu' => array(
				'accesskey' => trim($_GPC['qiniu']['accesskey']),
				'secretkey' => strexists($_GPC['qiniu']['secretkey'], '*') ? $_W['setting']['remote_complete_info']['qiniu']['secretkey'] : trim($_GPC['qiniu']['secretkey']),
				'bucket' => trim($_GPC['qiniu']['bucket']),
				'url' => trim($_GPC['qiniu']['url']),
			),
			'cos' => array(
				'appid' => trim($_GPC['cos']['appid']),
				'secretid' => trim($_GPC['cos']['secretid']),
				'secretkey' => strexists(trim($_GPC['cos']['secretkey']), '*') ? $_W['setting']['remote_complete_info']['cos']['secretkey'] : trim($_GPC['cos']['secretkey']),
				'bucket' => trim($_GPC['cos']['bucket']),
				'local' => trim($_GPC['cos']['local']),
				'url' => trim($_GPC['cos']['url']),
			),
		);
		if (ATTACH_OSS == $remote['type']) {
			if ('' == trim($remote['alioss']['key'])) {
				itoast('阿里云OSS-Access Key ID不能为空', '', '');
			}
			if ('' == trim($remote['alioss']['secret'])) {
				itoast('阿里云OSS-Access Key Secret不能为空', '', '');
			}
			$buckets = attachment_alioss_buctkets($remote['alioss']['key'], $remote['alioss']['secret']);
			if (is_error($buckets)) {
				itoast('OSS-Access Key ID 或 OSS-Access Key Secret错误，请重新填写', '', '');
			}
			list($remote['alioss']['bucket'], $remote['alioss']['url']) = explode('@@', $_GPC['alioss']['bucket']);
			if (empty($buckets[$remote['alioss']['bucket']])) {
				itoast('Bucket不存在或是已经被删除', '', '');
			}
			$remote['alioss']['url'] = 'http://' . $remote['alioss']['bucket'] . '.' . $buckets[$remote['alioss']['bucket']]['location'] . '.aliyuncs.com';
			$remote['alioss']['ossurl'] = $buckets[$remote['alioss']['bucket']]['location'] . '.aliyuncs.com';
			if (!empty($_GPC['custom']['url'])) {
				$url = trim($_GPC['custom']['url'], '/');
				if (!strexists($url, 'http://') && !strexists($url, 'https://')) {
					$url = 'http://' . $url;
				}
				$remote['alioss']['url'] = $url;
			}
			attachment_replace_article_remote_url($remote_urls['alioss']['old_url'], $remote['alioss']['url']);
		} elseif (ATTACH_FTP == $remote['type']) {
			if (empty($remote['ftp']['host'])) {
				itoast('FTP服务器地址为必填项.', '', '');
			}
			if (empty($remote['ftp']['username'])) {
				itoast('FTP帐号为必填项.', '', '');
			}
			if (empty($remote['ftp']['password'])) {
				itoast('FTP密码为必填项.', '', '');
			}
			attachment_replace_article_remote_url($remote_urls['ftp']['old_url'], $_GPC['ftp']['url']);
		} elseif (ATTACH_QINIU == $remote['type']) {
			if (empty($remote['qiniu']['accesskey'])) {
				itoast('请填写Accesskey', referer(), 'info');
			}
			if (empty($remote['qiniu']['secretkey'])) {
				itoast('secretkey', referer(), 'info');
			}
			if (empty($remote['qiniu']['bucket'])) {
				itoast('请填写bucket', referer(), 'info');
			}
			if (empty($remote['qiniu']['url'])) {
				itoast('请填写url', referer(), 'info');
			} else {
				$remote['qiniu']['url'] = strexists($remote['qiniu']['url'], 'http') ? trim($remote['qiniu']['url'], '/') : 'http://' . trim($remote['qiniu']['url'], '/');
			}
			attachment_replace_article_remote_url($remote_urls['qiniu']['old_url'], $remote['qiniu']['url']);
			$auth = attachment_qiniu_auth($remote['qiniu']['accesskey'], $remote['qiniu']['secretkey'], $remote['qiniu']['bucket']);
			if (is_error($auth)) {
				$message = $auth['message']['error'] == 'bad token' ? 'Accesskey或Secretkey填写错误， 请检查后重新提交' : 'bucket填写错误或是bucket所对应的存储区域选择错误，请检查后重新提交';
				itoast($message, referer(), 'info');
			}
		} elseif (ATTACH_COS == $remote['type']) {
			if (empty($remote['cos']['appid'])) {
				itoast('请填写APPID', referer(), 'info');
			}
			if (empty($remote['cos']['secretid'])) {
				itoast('请填写SECRETID', referer(), 'info');
			}
			if (empty($remote['cos']['secretkey'])) {
				itoast('请填写SECRETKEY', referer(), 'info');
			}
			if (empty($remote['cos']['bucket'])) {
				itoast('请填写BUCKET', referer(), 'info');
			}
			$remote['cos']['bucket'] = str_replace("-{$remote['cos']['appid']}", '', trim($remote['cos']['bucket']));

			if (empty($url)) {
				$url = sprintf('https://%s-%s.cos%s.myqcloud.com', $bucket, $appid, $_GPC['local']);
			}
			if (empty($remote['cos']['url'])) {
				$remote['cos']['url'] = sprintf('https://%s-%s.cos%s.myqcloud.com', $remote['cos']['bucket'], $remote['cos']['appid'], $remote['cos']['local']);
			}
			$remote['cos']['url'] = rtrim($remote['cos']['url'], '/');
			$_W['setting']['remote']['cos'] = array();
			attachment_replace_article_remote_url($remote_urls['cos']['old_url'], $remote['cos']['url']);
			$auth = attachment_cos_auth($remote['cos']['bucket'], $remote['cos']['appid'], $remote['cos']['secretid'], $remote['cos']['secretkey'], $remote['cos']['local']);

			if (is_error($auth)) {
				itoast($auth['message'], referer(), 'info');
			}
		}
		$_W['setting']['remote_complete_info']['type'] = $remote['type'];
		$_W['setting']['remote_complete_info']['alioss'] = $remote['alioss'];
		$_W['setting']['remote_complete_info']['ftp'] = $remote['ftp'];
		$_W['setting']['remote_complete_info']['qiniu'] = $remote['qiniu'];
		$_W['setting']['remote_complete_info']['cos'] = $remote['cos'];
		setting_save($_W['setting']['remote_complete_info'], 'remote');
		itoast('远程附件配置信息更新成功！', url('system/attachment/remote'), 'success');
	}
	$bucket_datacenter = attachment_alioss_datacenters();
	$local_attachment = file_dir_exist_image(ATTACHMENT_ROOT . 'images');
}

if ('buckets' == $do) {
	$key = $_GPC['key'];
	$secret = $_GPC['secret'];
	$buckets = attachment_alioss_buctkets($key, $secret);
	if (is_error($buckets)) {
		iajax(-1, '');
	}
	$bucket_datacenter = attachment_alioss_datacenters();
	$bucket = array();
	foreach ($buckets as $key => $value) {
		$value['bucket_key'] = $value['name'] . '@@' . $value['location'];
		$value['loca_name'] = $key . '@@' . $bucket_datacenter[$value['location']];
		$bucket[] = $value;
	}
	iajax(0, $bucket);
}

if ('ftp' == $do) {
	load()->library('ftp');
	$ftp_config = array(
		'hostname' => trim($_GPC['host']),
		'username' => trim($_GPC['username']),
		'password' => strexists($_GPC['password'], '*') ? $_W['setting']['remote_complete_info']['ftp']['password'] : trim($_GPC['password']),
		'port' => intval($_GPC['port']),
		'ssl' => trim($_GPC['ssl']),
		'passive' => trim($_GPC['pasv']),
		'timeout' => intval($_GPC['overtime']),
		'rootdir' => trim($_GPC['dir']),
	);
	$url = trim($_GPC['url']);
	$filename = 'MicroEngine.ico';
	$ftp = new Ftp($ftp_config);
	if (true === $ftp->connect()) {
				if ($ftp->upload(ATTACHMENT_ROOT . 'images/global/' . $filename, $filename)) {
			load()->func('communication');
			$response = ihttp_get($url . '/' . $filename);
			if (is_error($response)) {
				iajax(-1, '配置失败，FTP远程访问url错误');
			}
			if (200 != intval($response['code'])) {
				iajax(-1, '配置失败，FTP远程访问url错误');
			}
			$image = getimagesizefromstring($response['content']);
			if (!empty($image) && strexists($image['mime'], 'image')) {
				iajax(0, '配置成功');
			} else {
				iajax(-1, '配置失败，FTP远程访问url错误');
			}
		} else {
			iajax(-1, '上传图片失败，请检查配置');
		}
	} else {
		iajax(-1, 'FTP服务器连接失败，请检查配置');
	}
}

if ('oss' == $do) {
	load()->model('attachment');
	$key = $_GPC['key'];
	$secret = strexists($_GPC['secret'], '*') ? $_W['setting']['remote_complete_info']['alioss']['secret'] : $_GPC['secret'];
	$bucket = $_GPC['bucket'];
	$buckets = attachment_alioss_buctkets($key, $secret);
	list($bucket, $url) = explode('@@', $_GPC['bucket']);
	$result = attachment_newalioss_auth($key, $secret, $bucket, $_GPC['internal']);
	if (is_error($result)) {
		iajax(-1, 'OSS-Access Key ID 或 OSS-Access Key Secret错误，请重新填写');
	}
	$ossurl = $buckets[$bucket]['location'] . '.aliyuncs.com';
	if (!empty($_GPC['url'])) {
		if (!strexists($_GPC['url'], 'http://') && !strexists($_GPC['url'], 'https://')) {
			$url = 'http://' . trim($_GPC['url']);
		} else {
			$url = trim($_GPC['url']);
		}
		$url = trim($url, '/') . '/';
	} else {
		$url = 'http://' . $bucket . '.' . $buckets[$bucket]['location'] . '.aliyuncs.com/';
	}
	load()->func('communication');
	$filename = 'MicroEngine.ico';
	$response = ihttp_request($url . '/' . $filename, array(), array('CURLOPT_REFERER' => $_SERVER['SERVER_NAME']));
	if (is_error($response)) {
		iajax(-1, '配置失败，阿里云访问url错误');
	}
	if (200 != intval($response['code'])) {
		iajax(-1, '配置失败，阿里云访问url错误,请保证bucket为公共读取的');
	}
	$image = getimagesizefromstring($response['content']);
	if (!empty($image) && strexists($image['mime'], 'image')) {
		iajax(0, '配置成功');
	} else {
		iajax(-1, '配置失败，阿里云访问url错误');
	}
}

if ('qiniu' == $do) {
	load()->model('attachment');
	$_GPC['secretkey'] = strexists($_GPC['secretkey'], '*') ? $_W['setting']['remote_complete_info']['qiniu']['secretkey'] : $_GPC['secretkey'];
	$auth = attachment_qiniu_auth(trim($_GPC['accesskey']), trim($_GPC['secretkey']), trim($_GPC['bucket']));
	if (is_error($auth)) {
		iajax(-1, '配置失败，请检查配置。注：请检查存储区域是否选择的是和bucket对应<br/>的区域', '');
	}
	load()->func('communication');
	$url = $_GPC['url'];
	$url = strexists($url, 'http') ? trim($url, '/') : 'http://' . trim($url, '/');
	$filename = 'MicroEngine.ico';
	$response = ihttp_request($url . '/' . $filename, array(), array('CURLOPT_REFERER' => $_SERVER['SERVER_NAME']));
	if (is_error($response)) {
		iajax(-1, '配置失败，七牛访问url错误');
	}
	if (200 != intval($response['code'])) {
		iajax(-1, '配置失败，七牛访问url错误,请保证bucket为公共读取的');
	}
	$image = getimagesizefromstring($response['content']);
	if (!empty($image) && strexists($image['mime'], 'image')) {
		iajax(0, '配置成功');
	} else {
		iajax(-1, '配置失败，七牛访问url错误');
	}
}

if ('cos' == $do) {
	load()->model('attachment');
	$url = $_GPC['url'];
	$appid = trim($_GPC['appid']);
	$secretid = trim($_GPC['secretid']);
	$secretkey = strexists($_GPC['secretkey'], '*') ? $_W['setting']['remote_complete_info']['cos']['secretkey'] : trim($_GPC['secretkey']);
	$bucket = str_replace("-{$appid}", '', trim($_GPC['bucket']));

	if (empty($url)) {
		$url = sprintf('https://%s-%s.cos%s.myqcloud.com', $bucket, $appid, $_GPC['local']);
	}
	$url = rtrim($url, '/');
	$_W['setting']['remote']['cos'] = array();
	$auth = attachment_cos_auth($bucket, $appid, $secretid, $secretkey, $_GPC['local']);

	if (is_error($auth)) {
		iajax(-1, '配置失败，请检查配置' . $auth['message'], '');
	}
	load()->func('communication');
	$filename = 'MicroEngine.ico';
	$response = ihttp_request($url . '/' . $filename, array(), array('CURLOPT_REFERER' => $_SERVER['SERVER_NAME']));
	if (is_error($response)) {
		iajax(-1, '配置失败，腾讯cos访问url错误');
	}
	if (200 != intval($response['code'])) {
		iajax(-1, '配置失败，腾讯cos访问url错误,请保证bucket为公共读取的');
	}
	$image = getimagesizefromstring($response['content']);
	if (!empty($image) && strexists($image['mime'], 'image')) {
		iajax(0, '配置成功');
	} else {
		iajax(-1, '配置失败，腾讯cos访问url错误');
	}
}

template('system/attachment');