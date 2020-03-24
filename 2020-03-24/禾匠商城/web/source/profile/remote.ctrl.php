<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->func('communication');
load()->model('attachment');
load()->model('miniapp');

$dos = array('display', 'save', 'test_setting', 'upload_remote');
$do = in_array($do, $dos) ? $do : 'display';

permission_check_account_user('profile_setting_remote');

if (!empty($_GPC['version_id'])) {
	$version_id = intval($_GPC['version_id']);
	$version_info = miniapp_version($version_id);
}

$remote = uni_setting_load('remote');
$remote = empty($remote['remote']) ? array() : $remote['remote'];

if ('upload_remote' == $do) {
	if (!empty($remote['type'])) {
		if (empty($_W['setting']['remote']['type'])) {
			iajax(3, '未开启全局远程附件');
		}
		$result = file_dir_remote_upload(ATTACHMENT_ROOT . 'images/' . $_W['uniacid']);
		if (is_error($result)) {
			iajax(1, $result['message']);
		} else {
			iajax(0, '上传成功!');
		}
	} else {
		iajax(1, '请先填写并开启远程附件设置。');
	}
}

if ('display' == $do) {
	$safe_path = safe_gpc_path(IA_ROOT . '/attachment/images/' . $_W['uniacid']);
	if (!empty($safe_path)) {
		$local_attachment = file_dir_exist_image($safe_path);
	} else {
		$local_attachment = array();
	}
}

if ('save' == $do || 'test_setting' == $do) {
	$type_sign = array(ATTACH_FTP => 'ftp', ATTACH_OSS => 'alioss', ATTACH_QINIU => 'qiniu', ATTACH_COS => 'cos');
	$type = intval($_GPC['type']); 	$op_type = intval($_GPC['operate_type']);
	$op_sign = $type_sign[$op_type];
	$op_data = array();
	$post = safe_gpc_array($_GPC[$op_sign]);
	if (!in_array($op_type, array_keys($type_sign))) {
		iajax(-1, '参数有误');
	}
	if (0 != $type && !in_array($type, array_keys($type_sign))) {
		iajax(-1, '附件类型有误');
	}
		switch ($op_type) {
		case ATTACH_QINIU:
			$op_data = array(
				'accesskey' => trim($post['accesskey']),
				'secretkey' => strexists($post['secretkey'], '*') ? $remote['qiniu']['secretkey'] : trim($post['secretkey']),
				'bucket' => trim($post['bucket']),
				'url' => trim(trim($post['url']), '/'),
			);
			if ($op_data['url']) {
				$op_data['url'] = strexists($op_data['url'], 'http') ? $op_data['url'] : 'http://' . $op_data['url'];
			}
			if (empty($op_data['accesskey'])) {
				iajax(-1, '请填写Accesskey');
			}
			if (empty($op_data['secretkey'])) {
				iajax(-1, 'secretkey');
			}
			if (empty($op_data['bucket'])) {
				iajax(-1, '请填写bucket');
			}
			if (empty($op_data['url'])) {
				iajax(-1, '请填写url');
			}
			break;
		case ATTACH_OSS:
			$op_data = array(
				'key' => trim($post['key']),
				'secret' => strexists($post['secret'], '*') ? $remote['alioss']['secret'] : $post['secret'],
				'internal' => intval($post['internal']),
				'bucket' => trim($post['bucket']),
				'url' => trim(trim($post['url']), '/'),
			);
			if (!empty($op_data['url']) && !strexists($op_data['url'], 'http://') && !strexists($op_data['url'], 'https://')) {
				$op_data['url'] = 'http://' . $op_data['url'];
			}
			if (empty($op_data['key'])) {
				iajax(-1, 'Access Key ID不能为空');
			}
			if (empty($op_data['secret'])) {
				iajax(-1, 'Access Key Secret不能为空');
			}
			if (empty($op_data['bucket'])) {
				iajax(-1, 'Bucket不能为空');
			}
			break;
		case ATTACH_COS:
			$op_data = array(
				'appid' => trim($post['appid']),
				'secretid' => trim($post['secretid']),
				'secretkey' => strexists(trim($post['secretkey']), '*') ? $remote['cos']['secretkey'] : trim($post['secretkey']),
				'bucket' => trim($post['bucket']),
				'local' => trim($post['local']),
				'url' => trim(trim($post['url']), '/'),
			);
			$op_data['bucket'] = str_replace("-{$post['appid']}", '', $post['bucket']);
			if (empty($op_data['url'])) {
				$op_data['url'] = sprintf('https://%s-%s.cos%s.myqcloud.com', $op_data['bucket'], $op_data['appid'], $op_data['local']);
			}
			if (empty($op_data['appid'])) {
				iajax(-1, '请填写APPID');
			}
			if (empty($op_data['secretid'])) {
				iajax(-1, '请填写SECRETID');
			}
			if (empty($op_data['secretkey'])) {
				iajax(-1, '请填写SECRETKEY');
			}
			if (empty($op_data['bucket'])) {
				iajax(-1, '请填写BUCKET');
			}
			break;
		case ATTACH_FTP:
			$op_data = array(
				'hostname' => $post['host'] ?: $post['hostname'],
				'port' => empty($post['port']) ? 21 : $post['port'],
				'ssl' => intval($post['ssl']),
				'username' => $post['username'],
				'password' => strexists($post['password'], '*') ? $remote['ftp']['password'] : $post['password'],
				'passive' => intval($post['pasv'] ?: $post['passive']) ,
				'rootdir' => $post['dir'] ?: $post['rootdir'],
				'timeout' => intval($post['overtime'] ?: $post['timeout']),
				'url' => trim(trim($post['url']), '/')
			);
			if (empty($op_data['hostname'])) {
				iajax(-1, 'FTP服务器地址为必填项.');
			}
			if (empty($op_data['username'])) {
				iajax(-1, 'FTP帐号为必填项.');
			}
			if (empty($op_data['password'])) {
				iajax(-1, 'FTP密码为必填项.');
			}
			break;
	}
		if ('test_setting' == $do) {
		$test_type = $op_type;
	} elseif ($type == $op_type) {
		$test_type = $type;
	}
	if ($test_type) {
		switch ($test_type) {
			case ATTACH_QINIU:
				$title = '七牛';
				$auth = attachment_qiniu_auth($op_data['accesskey'], $op_data['secretkey'], $op_data['bucket']);
				if (is_error($auth)) {
					$message = $auth['message']['error'] == 'bad token' ? 'Accesskey或Secretkey填写错误， 请检查后重新提交' : 'bucket填写错误或是bucket所对应的存储区域选择错误，请检查后重新提交';
					iajax(-1, $message);
				}
				break;
			case ATTACH_OSS:
				$title = '阿里云OSS';
				$buckets = attachment_alioss_buctkets($op_data['key'], $op_data['secret']);
				if (is_error($buckets)) {
					iajax(-1, 'Access Key ID 或 OSS-Access Key Secret错误，请重新填写');
				}
				if (empty($buckets[$op_data['bucket']])) {
					iajax(-1, 'Bucket不存在或是已经被删除');
				}
				if (empty($op_data['url'])) {
					$op_data['url'] = 'http://' . $op_data['bucket'] . '.' . $buckets[$op_data['bucket']]['location'] . '.aliyuncs.com';
				}
				$op_data['ossurl'] = $buckets[$op_data['bucket']]['location'] . '.aliyuncs.com';
				$result = attachment_newalioss_auth($op_data['key'], $op_data['secret'], $op_data['bucket'], $op_data['internal']);
				if (is_error($result)) {
					iajax(-1, 'OSS-Access Key ID 或 OSS-Access Key Secret错误，请重新填写');
				}
				break;
			case ATTACH_COS:
				$_W['setting']['remote']['cos'] = array();
				$title = '腾讯cos';
				$auth = attachment_cos_auth($op_data['bucket'], $op_data['appid'], $op_data['secretid'], $op_data['secretkey'], $op_data['local']);
				if (is_error($auth)) {
					iajax(-1, '配置失败，请检查配置：' . $auth['message']);
				}
				break;
			case ATTACH_FTP:
				$title = 'FTP';
				load()->library('ftp');
				$ftp = new Ftp($op_data);
				if (true !== $ftp->connect()) {
					iajax(-1, 'FTP服务器连接失败，请检查配置');
				}
								$filename = 'MicroEngine.ico';
				if (!$ftp->upload(ATTACHMENT_ROOT . 'images/global/' . $filename, $filename)) {
					iajax(-1, '上传图片失败，请检查配置');
				}
				break;
		}
		$response = ihttp_request($op_data['url'] . '/MicroEngine.ico', array(), array('CURLOPT_REFERER' => $_SERVER['SERVER_NAME']));
		if (is_error($response)) {
			iajax(-1, '配置失败，' . $title . '访问url错误');
		}
		if (200 != intval($response['code'])) {
			iajax(-1, '配置失败，' . $title . '访问url错误,请保证bucket为公共读取的');
		}
		$image = getimagesizefromstring($response['content']);
		if (empty($image) || !strexists($image['mime'], 'image')) {
			iajax(-1, '配置失败，' . $title . '访问url错误');
		}
	}
		if ('test_setting' == $do) {
		iajax(0, '配置成功');
	}
	if ('save' == $do) {
		$remote['type'] = $type;
		$remote[$op_sign] = $op_data;
		uni_setting_save('remote', $remote);
		iajax(0, '保存成功', url('profile/remote'));
	}
}

template('profile/remote');