<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('file');

$do   = in_array($_GPC['do'], array('upload', 'delete')) ? $_GPC['do'] : 'upload';
$type = in_array($_GPC['type'], array('image','audio')) ? $_GPC['type'] : 'image';

$result = array('error' => 1, 'message' => '');
if ($do == 'delete') {
	if ($type = 'image') {
		$id = intval($_GPC['id']);
		if (empty($id)) {
			return message($result, '', 'ajax');
		}

		$attachment = table('core_attachment')
			->select(array('attachment', 'uniacid', 'uid'))
			->where(array(
				'id' => $id,
				'uniacid' => $_W['uniacid']
			))
			->get();
		if (empty($attachment)) {
			return message(error(1, '图片不存在或已删除！'), '', 'ajax');
		}

		if (empty($_W['openid']) || (!empty($_W['fans']) && $attachment['uid'] != $_W['fans']['from_user']) || (!empty($_W['member']) && $attachment['uid'] != $_W['member']['uid'])) {
			return message(error(1, '无权删除！'), '', 'ajax');
		}

		$uni_remote_setting = uni_setting_load('remote');
		if (!empty($uni_remote_setting['remote']['type'])) {
			$_W['setting']['remote'] = $uni_remote_setting['remote'];
		}
		if ($_W['setting']['remote']['type']) {
			$result = file_remote_delete($attachment['attachment']);
		} else {
			$result = file_delete($attachment['attachment']);
		}
		if (!is_error($result)) {
			table('core_attachment')
				->where(array(
					'id' => $id,
					'uniacid' => $_W['uniacid']
				))
				->delete();
		}
		if (!is_error($result)) {
			return message(error('0'), '', 'ajax');
		} else {
			return message(error(1, $result['message']), '', 'ajax');
		}

	}
}
if ($do == 'upload') {
	if($type == 'image'){
		$setting = $_W['setting']['upload'][$type];
		$result = array(
			'jsonrpc' => '2.0',
			'id' => 'id',
			'error' => array('code' => 1, 'message'=>''),
		);
		if (empty($_FILES['file']['tmp_name'])) {
			$binaryfile = file_get_contents('php://input', 'r');
			if (!empty($binaryfile)) {
				mkdirs(ATTACHMENT_ROOT . '/temp');
				$tempfilename = random(5);
				$tempfile = ATTACHMENT_ROOT . '/temp/' . $tempfilename;
				if (file_put_contents($tempfile, $binaryfile)) {
					$imagesize = @getimagesize($tempfile);
					$imagesize = explode('/', $imagesize['mime']);
					$_FILES['file'] = array(
						'name' => $tempfilename . '.' . $imagesize[1],
						'tmp_name' => $tempfile,
						'error' => 0,
					);
				}
			}
		}
		if (!empty($_FILES['file']['name'])) {
			if ($_FILES['file']['error'] != 0) {
				$result['error']['message'] = '上传失败，请重试！';
				die(json_encode($result));
			}
			if (!file_is_image($_FILES['file']['tmp_name'])) {
				$result['message'] = '上传失败, 请重试.';
				die(json_encode($result));
			}
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$ext = strtolower($ext);

			$file = file_upload($_FILES['file']);
			if (is_error($file)) {
				$result['error']['message'] = $file['message'];
				die(json_encode($result));
			}

			$pathname = $file['path'];
			$fullname = ATTACHMENT_ROOT . '/' . $pathname;

			$thumb = empty($setting['thumb']) || $ext == 'gif' ? 0 : 1; 			$width = intval($setting['width']); 			if ($thumb == 1 && $width > 0 && (!isset($_GPC['thumb']) || (isset($_GPC['thumb']) && !empty($_GPC['thumb'])))) {
				$thumbnail = file_image_thumb($fullname, '', $width);
				@unlink($fullname);
				if (is_error($thumbnail)) {
					$result['message'] = $thumbnail['message'];
					die(json_encode($result));
				} else {
					$filename = pathinfo($thumbnail, PATHINFO_BASENAME);
					$pathname = $thumbnail;
					$fullname = ATTACHMENT_ROOT .'/'.$pathname;
				}
			}
			$info = array(
				'name' => $_FILES['file']['name'],
				'ext' => $ext,
				'filename' => $pathname,
				'attachment' => $pathname,
				'url' => tomedia($pathname),
				'is_image' => 1,
				'filesize' => filesize($fullname),
			);
			$size = getimagesize($fullname);
			$info['width'] = $size[0];
			$info['height'] = $size[1];
			
			setting_load('remote');
			$uni_remote_setting = uni_setting_load('remote');
			if (!empty($uni_remote_setting['remote']['type'])) {
				$_W['setting']['remote'] = $uni_remote_setting['remote'];
			}
			if (!empty($_W['setting']['remote']['type'])) {
				$remotestatus = file_remote_upload($pathname);
				if (is_error($remotestatus)) {
					$result['message'] = '远程附件上传失败，请检查配置并重新上传';
					file_delete($pathname);
					die(json_encode($result));
				} else {
					file_delete($pathname);
					$info['url'] = tomedia($pathname);
				}
			}

			table('core_attachment')
				->fill(array(
					'uniacid' => $uniacid,
					'uid' => $_W['uid'],
					'filename' => $_FILES['file']['name'],
					'attachment' => $pathname,
					'type' => $type == 'image' ? 1 : 2,
					'createtime' => TIMESTAMP,
				))
				->save();
			$info['id'] = pdo_insertid();
			die(json_encode($info));
		} else {
			$result['error']['message'] = '请选择要上传的图片！';
			die(json_encode($result));
		}
	}
}
