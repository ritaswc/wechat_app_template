<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function code_verify($uniacid, $receiver, $code) {
	$receiver = safe_gpc_string($receiver);
	if (empty($receiver) || !is_numeric($code)) {
		return false;
	}
	$data = table('uni_verifycode')->where(array(
		'uniacid' => intval($uniacid),
		'receiver' => $receiver,
		'verifycode' => $code,
		'createtime >' => (TIMESTAMP - 1800)
	))->get();

	return !empty($data);
}


function utility_image_rename($image_source_url, $image_destination_url) {
	global $_W;
	load()->func('file');
	$image_source_url = str_replace(array("\0","%00","\r"),'',$image_source_url);
	if (empty($image_source_url) || !parse_path($image_source_url)) {
		return false;
	}
	if (!strexists($image_source_url, $_W['siteroot']) || $_W['setting']['remote']['type'] > 0) {
		$img_local_path = file_remote_attach_fetch($image_source_url);
		if (is_error($img_local_path)) {
			return false;
		}
		$img_source_path = ATTACHMENT_ROOT . $img_local_path;
	} else {
		$img_local_path = substr($image_source_url, strlen($_W['siteroot']));
		$img_path_params = explode('/', $img_local_path);
		if ($img_path_params[0] != 'attachment') {
			return false;
		}
		$img_source_path = IA_ROOT . '/' . $img_local_path;
	}
	if (!file_is_image($img_source_path)) {
		return false;
	}
	$result = copy($img_source_path, $image_destination_url);
	return $result;
}


function utility_smscode_verify($uniacid, $receiver, $verifycode = '') {
	$table = table('uni_verifycode');
	$verify_info = $table->getByReceiverVerifycode($uniacid, $receiver, $verifycode);

	if (empty($verify_info)) {
		$table->updateFailedCount($receiver);
		return error(-1, '短信验证码不正确');
	} else if ($verify_info['createtime'] + 120 < TIMESTAMP) {
		return error(-2, '短信验证码已过期，请重新获取');
	} else {
		return error(0, '短信验证码正确');
	}

}