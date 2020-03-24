<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('upload_file');
$do = in_array($do, $dos) ? $do : 'upload_file';
permission_check_account_user('profile_setting');

if ('upload_file' == $do) {
	if (checksubmit('submit')) {
		if (empty($_FILES['file']['tmp_name'])) {
			itoast('请选择文件', url('profile/common/upload_file'), 'error');
		}
		if ($_FILES['file']['type'] != 'text/plain') {
			itoast('文件类型错误', url('profile/common/upload_file'), 'error');
		}
		$file = file_get_contents($_FILES['file']['tmp_name']);
		$file_name = 'MP_verify_' . $file . '.txt';
		if ($_FILES['file']['name'] != $file_name || !preg_match('/^[A-Za-z0-9]+$/', $file)) {
			itoast('上传文件不合法,请重新上传', url('profile/common/upload_file'), 'error');
		}
		file_put_contents(IA_ROOT . '/' . $_FILES['file']['name'], $file);
		itoast('上传成功', url('profile/common/upload_file'), 'success');
	}
}
template('profile/upload_file');
