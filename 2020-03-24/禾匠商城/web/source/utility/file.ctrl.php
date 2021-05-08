<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('file');
load()->func('communication');
load()->model('account');
load()->model('material');
load()->model('attachment');
load()->model('mc');
load()->model('module');

if (!in_array($do, array('upload', 'fetch', 'browser', 'delete', 'image', 'module', 'video', 'voice', 'news', 'keyword',
	'networktowechat', 'networktolocal', 'towechat', 'tolocal', 'wechat_upload',
	'group_list', 'add_group', 'change_group', 'del_group', 'move_to_group', ))) {
	exit('Access Denied');
}
$result = array(
	'error' => 1,
	'message' => '',
	'data' => '',
);

error_reporting(0);
$type = $_GPC['upload_type']; $type = in_array($type, array('image', 'audio', 'video')) ? $type : 'image';
$option = array();
$option = array_elements(array('uploadtype', 'global', 'dest_dir'), $_POST);

$option['width'] = intval($option['width']);
$option['global'] = $_GPC['global'];

if (!empty($option['global']) && empty($_W['isfounder'])) {
	$result['message'] = '没有向 global 文件夹上传文件的权限.';
	die(json_encode($result));
}

$dest_dir = $_GPC['dest_dir']; if (preg_match('/^[a-zA-Z0-9_\/]{0,50}$/', $dest_dir, $out)) {
	$dest_dir = trim($dest_dir, '/');
	$pieces = explode('/', $dest_dir);
	if (count($pieces) > 3) {
		$dest_dir = '';
	}
} else {
	$dest_dir = '';
}
$module_upload_dir = '';
if ('' != $dest_dir) {
	$module_upload_dir = sha1($dest_dir);
}

$setting = $_W['setting']['upload'][$type];
$uniacid = intval($_W['uniacid']);

if (isset($_GPC['uniacid'])) {
	$requniacid = intval($_GPC['uniacid']);
	attachment_reset_uniacid($requniacid);
	$uniacid = intval($_W['uniacid']);
}

if (!empty($option['global'])) {
	$setting['folder'] = "{$type}s/global/";
	if (!empty($dest_dir)) {
		$setting['folder'] .= '' . $dest_dir . '/';
	}
} else {
	$setting['folder'] = "{$type}s/{$uniacid}";
	if (empty($dest_dir)) {
		$setting['folder'] .= '/' . date('Y/m/');
	} else {
		$setting['folder'] .= '/' . $dest_dir . '/';
	}
}

if ('fetch' == $do) {
	$url = trim($_GPC['url']);
	$resp = ihttp_get($url);
	if (is_error($resp)) {
		$result['message'] = '提取文件失败, 错误信息: ' . $resp['message'];
		die(json_encode($result));
	}
	if (200 != intval($resp['code'])) {
		$result['message'] = '提取文件失败: 未找到该资源文件.';
		die(json_encode($result));
	}
	$ext = '';
	if ('image' == $type) {
		switch ($resp['headers']['Content-Type']) {
			case 'application/x-jpg':
			case 'image/jpeg':
				$ext = 'jpg';
				break;
			case 'image/png':
				$ext = 'png';
				break;
			case 'image/gif':
				$ext = 'gif';
				break;
			default:
				$result['message'] = '提取资源失败, 资源文件类型错误.';
				die(json_encode($result));
				break;
		}
	} else {
		$result['message'] = '提取资源失败, 仅支持图片提取.';
		die(json_encode($result));
	}

	if (intval($resp['headers']['Content-Length']) > $setting['limit'] * 1024) {
		$result['message'] = '上传的媒体文件过大(' . sizecount($size) . ' > ' . sizecount($setting['limit'] * 1024);
		die(json_encode($result));
	}
	$originname = pathinfo($url, PATHINFO_BASENAME);
	$filename = file_random_name(ATTACHMENT_ROOT . '/' . $setting['folder'], $ext);
	$pathname = $setting['folder'] . $filename;
	$fullname = ATTACHMENT_ROOT . '/' . $pathname;
	if (false == file_put_contents($fullname, $resp['content'])) {
		$result['message'] = '提取失败.';
		die(json_encode($result));
	}
}

if ('upload' == $do) {
	if (empty($_FILES['file']['name'])) {
		$result['message'] = '上传失败, 请选择要上传的文件！';
		die(json_encode($result));
	}
	if ($_FILES['file']['error'] != 0) {
		$result['message'] = '上传失败, 请重试.';
		die(json_encode($result));
	}
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	$ext = strtolower($ext);
	$size = intval($_FILES['file']['size']);
	$originname = $_FILES['file']['name'];
	$filename = file_random_name(ATTACHMENT_ROOT . '/' . $setting['folder'], $ext);

	$file = file_upload($_FILES['file'], $type, $setting['folder'] . $filename, true);

	if (is_error($file)) {
		$result['message'] = $file['message'];
		die(json_encode($result));
	}
	$pathname = $file['path'];
	$fullname = ATTACHMENT_ROOT . '/' . $pathname;
}

if ('fetch' == $do || 'upload' == $do) {
	if ('image' == $type) {
		$thumb = empty($setting['thumb']) || 'gif' == $ext ? 0 : 1;
		$width = intval($setting['width']);
		if (isset($option['thumb'])) {
			$thumb = empty($option['thumb']) ? 0 : 1;
		}
		if (isset($option['width']) && !empty($option['width'])) {
			$width = intval($option['width']);
		}
		if (1 == $thumb && $width > 0) {
			$thumbnail = file_image_thumb($fullname, '', $width);
			@unlink($fullname);
			if (is_error($thumbnail)) {
				$result['message'] = $thumbnail['message'];
				die(json_encode($result));
			} else {
				$filename = pathinfo($thumbnail, PATHINFO_BASENAME);
				$pathname = $thumbnail;
				$fullname = ATTACHMENT_ROOT . '/' . $pathname;
			}
		}
	}

	$info = array(
		'name' => $originname,
		'ext' => $ext,
		'filename' => $pathname,
		'attachment' => $pathname,
		'url' => tomedia($pathname),
		'is_image' => 'image' == $type ? 1 : 0,
		'filesize' => filesize($fullname),
		'group_id' => intval($_GPC['group_id']),
	);
	if ('image' == $type) {
		$size = getimagesize($fullname);
		$info['width'] = $size[0];
		$info['height'] = $size[1];
	} else {
		$size = filesize($fullname);
		$info['size'] = sizecount($size);
	}
	$uni_remote_setting = uni_setting_load('remote');
	if (empty($option['global']) && !empty($uni_remote_setting['remote']['type'])) {
		$_W['setting']['remote'] = $uni_remote_setting['remote'];
	}
	if (!empty($option['global'])) {
		$_W['setting']['remote'] = $_W['setting']['remote_complete_info'];
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
	pdo_insert('core_attachment', array(
		'uniacid' => $uniacid,
		'uid' => $_W['uid'],
		'filename' => safe_gpc_html(htmlspecialchars_decode($originname, ENT_QUOTES)),
		'attachment' => $pathname,
		'type' => 'image' == $type ? 1 : ('audio' == $type || 'voice' == $type ? 2 : 3),
		'createtime' => TIMESTAMP,
		'module_upload_dir' => $module_upload_dir,
		'group_id' => intval($_GPC['group_id']),
	));
	$info['state'] = 'SUCCESS';
	die(json_encode($info));
}

if ('delete' == $do) {
	if (empty($_W['isfounder']) && ACCOUNT_MANAGE_NAME_MANAGER != $_W['role'] && ACCOUNT_MANAGE_NAME_OWNER != $_W['role']) {
		iajax(1, '您没有权限删除文件');
	}
	$id = $_GPC['id'];
	if (!is_array($id)) {
		$id = array(intval($id));
	}
	$id = safe_gpc_array($id);
	$core_attachment_table = table('core_attachment');
	$core_attachment_table->searchWithId($id);
	if (empty($uniacid)) {
		$core_attachment_table->searchWithUid($_W['uid']);
	} else {
		$core_attachment_table->searchWithUniacid($uniacid);
	}
	$attachments = $core_attachment_table->getall();
	$delete_ids = array();
	$uni_remote_setting = uni_setting_load('remote');
	if (!empty($uni_remote_setting['remote']['type'])) {
		$_W['setting']['remote'] = $uni_remote_setting['remote'];
	}
	foreach ($attachments as $media) {
		if (!empty($_W['setting']['remote']['type'])) {
			$status = file_remote_delete($media['attachment']);
		} else {
			$status = file_delete($media['attachment']);
		}
		if (is_error($status)) {
			iajax(1, $status['message']);
			exit;
		}
		$delete_ids[] = $media['id'];
	}

	pdo_delete('core_attachment', array('id' => $delete_ids, 'uniacid' => $uniacid));
	iajax(0, '删除成功');
}

$limit = array();
$limit['temp'] = array(
	'image' => array(
		'ext' => array('jpg', 'logo'),
		'size' => 1024 * 1024,
		'errmsg' => '临时图片只支持jpg/logo格式,大小不超过为1M',
	),
	'voice' => array(
		'ext' => array('amr', 'mp3'),
		'size' => 2048 * 1024,
		'errmsg' => '临时语音只支持amr/mp3格式,大小不超过为2M',
	),
	'video' => array(
		'ext' => array('mp4'),
		'size' => 10240 * 1024,
		'errmsg' => '临时视频只支持mp4格式,大小不超过为10M',
	),
	'thumb' => array(
		'ext' => array('jpg', 'logo'),
		'size' => 64 * 1024,
		'errmsg' => '临时缩略图只支持jpg/logo格式,大小不超过为64K',
	),
);
$limit['perm'] = array(
	'image' => array(
		'ext' => array('bmp', 'png', 'jpeg', 'jpg', 'gif'),
		'size' => 2048 * 1024,
		'max' => 5000,
		'errmsg' => '永久图片只支持bmp/png/jpeg/jpg/gif格式,大小不超过为2M',
	),
	'voice' => array(
		'ext' => array('amr', 'mp3', 'wma', 'wav', 'amr'),
		'size' => 5120 * 1024,
		'max' => 1000,
		'errmsg' => '永久语音只支持mp3/wma/wav/amr格式,大小不超过为5M,长度不超过60秒',
	),
	'video' => array(
		'ext' => array('rm', 'rmvb', 'wmv', 'avi', 'mpg', 'mpeg', 'mp4'),
		'size' => 10240 * 1024 * 2,
		'max' => 1000,
		'errmsg' => '永久视频只支持rm/rmvb/wmv/avi/mpg/mpeg/mp4格式,大小不超过为20M',
	),
	'thumb' => array(
		'ext' => array('bmp', 'png', 'jpeg', 'jpg', 'gif'),
		'size' => 2048 * 1024,
		'max' => 5000,
		'errmsg' => '永久缩略图只支持bmp/png/jpeg/jpg/gif格式,大小不超过为2M',
	),
);

$limit['file_upload'] = array(
	'image' => array(
		'ext' => array('jpg'),
		'size' => 1024 * 1024,
		'max' => -1,
		'errmsg' => '图片只支持jpg格式,大小不超过为1M',
	),
);
if ('wechat_upload' == $do) {
	$type = trim($_GPC['upload_type']);
	$mode = trim($_GPC['mode']);
	if ('image' == $type || 'thumb' == $type) {
		$type = 'image';
	}
	if ('audio' == $type) {
		$type = 'voice';
	}

	$setting['folder'] = "{$type}s/{$_W['uniacid']}" . '/' . date('Y/m/');

	if ('perm' == $mode) {
		$now_count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_attachment') . ' WHERE uniacid = :aid AND model = :model AND type = :type', array(':aid' => $_W['uniacid'], ':model' => $mode, ':type' => $type));
		if ($now_count >= $limit['perm'][$type]['max']) {
			$result['message'] = '文件数量超过限制,请先删除部分文件再上传';
			die(json_encode($result));
		}
	}

	if (empty($mode) || empty($type) || !$_W['acid']) {
		$result['message'] = '上传配置出错';
		die(json_encode($result));
	}

	if (empty($_FILES['file']['name'])) {
		$result['message'] = '上传失败, 请选择要上传的文件！';
		die(json_encode($result));
	}

	if ($_FILES['file']['error'] != 0) {
		$result['message'] = '上传失败, 请重试.';
		die(json_encode($result));
	}

	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	$ext = strtolower($ext);
	$size = intval($_FILES['file']['size']);
	$originname = $_FILES['file']['name'];

	if (!in_array($ext, $limit[$mode][$type]['ext']) || ($size > $limit[$mode][$type]['size'])) {
		$result['message'] = $limit[$mode][$type]['errmsg'];
		die(json_encode($result));
	}

	$filename = file_random_name(ATTACHMENT_ROOT . '/' . $setting['folder'], $ext);

	$file = file_wechat_upload($_FILES['file'], $type, $setting['folder'] . $filename, true);

	if (is_error($file)) {
		$result['message'] = $file['message'];
		die(json_encode($result));
	}

	$pathname = $file['path'];
	$fullname = ATTACHMENT_ROOT . '/' . $pathname;
	$acc = WeAccount::createByUniacid();
	if ('perm' == $mode || 'temp' == $mode) {
		if ('video' != $type) {
			$result = $acc->uploadMediaFixed($pathname, $type);
		} else {
			$result = $acc->uploadVideoFixed($originname, $originname, $pathname);
		}
	}

	if ('perm' == $mode || 'temp' == $mode) {
		if (!empty($content['media_id'])) {
			$result['media_id'] = $content['media_id'];
		}
		if (!empty($content['thumb_media_id'])) {
			$result['media_id'] = $content['thumb_media_id'];
		}
	} elseif ('file_upload' == $mode) {
		$result['media_id'] = $content['url'];
	}

	if ('image' == $type || 'thumb' == $type) {
		$file['path'] = file_image_thumb($fullname, '', 300);
	}

	if (!empty($_W['setting']['remote']['type']) && !empty($file['path'])) {
		$remotestatus = file_remote_upload($file['path']);
		if (is_error($remotestatus)) {
			file_delete($pathname);
			if ('image' == $type || 'thumb' == $type) {
				file_delete($file['path']);
			}
			$result['error'] = 0;
			$result['message'] = '远程附件上传失败，请检查配置并重新上传';
			die(json_encode($result));
		} else {
			file_delete($pathname);
			if ('image' == $type || 'thumb' == $type) {
				file_delete($file['path']);
			}
		}
	}

	$insert = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'uid' => $_W['uid'],
		'filename' => $originname,
		'attachment' => $file['path'],
		'media_id' => $result['media_id'],
		'type' => $type,
		'model' => $mode,
		'createtime' => TIMESTAMP,
		'module_upload_dir' => $module_upload_dir,
		'group_id' => intval($_GPC['group_id']),
	);
	if ('image' == $type || 'thumb' == $type) {
		$size = getimagesize($fullname);
		$insert['width'] = $size[0];
		$insert['height'] = $size[1];
		if ('perm' == $mode) {
			$insert['tag'] = $content['url'];
		}
		if (!empty($insert['tag'])) {
			$insert['attachment'] = $content['url'];
		}
		$result['width'] = $size[0];
		$result['hieght'] = $size[1];
	}
	if ('video' == $type) {
		$insert['tag'] = iserializer(array('title' => $originname, 'url' => ''));
	}

	if ('video' == $type && 'perm' == $mode) {
		if (!is_error($result)) {
			pdo_insert('wechat_attachment', $insert);
		}
	} else {
		pdo_insert('wechat_attachment', $insert);
	}

	$result['type'] = $type;
	$result['url'] = tomedia($file['path']);

	if ('image' == $type || 'thumb' == $type) {
		@unlink($fullname);
	}
	$result['mode'] = $mode;
	die(json_encode($result));
}

$type = $_GPC['type']; $resourceid = intval($_GPC['resource_id']); $uid = intval($_W['uid']);
$acid = intval($_W['acid']);
$url = $_GPC['url'];
$isnetwork_convert = !empty($url);
$islocal = 'local' == $_GPC['local'];
if ('keyword' == $do) {
	$keyword = addslashes($_GPC['keyword']);
	$pindex = max(1, $_GPC['page']);
	$psize = 24;
	$condition = array('uniacid' => $uniacid, 'status' => 1);
	if (!empty($keyword)) {
		$condition['content like'] = '%' . $keyword . '%';
	}
	$keyword_lists = pdo_getslice('rule_keyword', $condition, array($pindex, $psize), $total, array(), 'id');
	$result = array(
		'items' => $keyword_lists,
		'pager' => pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback' => 'null', 'isajax' => 1)),
	);
	iajax(0, $result);
}
if ('module' == $do) {
	$enable_modules = array();
	$is_user_module = isset($_GPC['user_module']) ? intval($_GPC['user_module']) : 0;
	$uid = empty($_GPC['uid']) || !is_numeric($_GPC['uid']) ? $_W['uid'] : intval($_GPC['uid']);
	$module_uniacid = empty($_GPC['module_uniacid']) || !is_numeric($_GPC['module_uniacid']) ? $_W['uniacid'] : intval($_GPC['module_uniacid']);
	$have_cover = 'true' == $_GPC['cover'] ? true : false;
	$account_all_type = uni_account_type();
	$module_type = in_array($_GPC['mtype'], array_keys(uni_account_type_sign())) ? $_GPC['mtype'] : '';
	if ($is_user_module) {
		$installedmodulelist = user_modules($uid);
	} else {
		$installedmodulelist = uni_modules_by_uniacid($module_uniacid);
	}

	$sysmods = module_system();
	foreach ($installedmodulelist as $k => $value) {
		if ('system' == $value['type'] || in_array($value['name'], $sysmods)) {
			unset($installedmodulelist[$k]);
			continue;
		}

		$continue = false;
		foreach ($account_all_type as $account_type) {
			if ($module_type == $account_type['type_sign'] && $value[$account_type['module_support_name']] != $account_type['module_support_value']) {
				$continue = true;
				break;
			}
		}
		if ($continue) {
			unset($installedmodulelist[$k]);
			continue;
		}

		if ($have_cover) {
			$module_entries = module_entries($value['name'], array('cover'));
			if (empty($module_entries)) {
				unset($installedmodulelist[$k]);
				continue;
			}
		}

		$installedmodulelist[$k]['official'] = empty($value['issystem']) && (strexists($value['author'], 'WeEngine Team') || strexists($value['author'], '微擎团队'));
	}
	foreach ($installedmodulelist as $name => $module) {
		if ($module['issystem']) {
			$path = '/framework/builtin/' . $module['name'];
		} else {
			$path = '../addons/' . $module['name'];
		}
		$cion = $path . '/icon-custom.jpg';
		if (!file_exists($cion)) {
			$cion = $path . '/icon.jpg';
			if (!file_exists($cion)) {
				$cion = './resource/images/nopic-small.jpg';
			}
		}
		$module['icon'] = $cion;
		$enable_modules[] = $module;
	}
	$result = array('items' => $enable_modules, 'pager' => '');
	iajax(0, $result);
}

if ('video' == $do || 'voice' == $do) {
	$server = $islocal ? MATERIAL_LOCAL : MATERIAL_WEXIN;
	$page_index = max(1, $_GPC['page']);
	$page_size = 10;
	$material_news_list = material_list($do, $server, array('page_index' => $page_index, 'page_size' => $page_size));
	$material_list = $material_news_list['material_list'];
	$pager = $material_news_list['page'];
	foreach ($material_list as &$item) {
		$item['url'] = tomedia($item['attachment']);
		unset($item['uid']);
	}
	$result = array('items' => $material_list, 'pager' => $pager);
	iajax(0, $result);
}

if ('news' == $do) {
	$server = $islocal ? MATERIAL_LOCAL : MATERIAL_WEXIN;
	$page_index = max(1, $_GPC['page']);
	$page_size = 24;
	$search = addslashes($_GPC['keyword']);
	$material_news_list = material_news_list($server, $search, array('page_index' => $page_index, 'page_size' => $page_size));

	$material_list = array_values($material_news_list['material_list']);
	$pager = $material_news_list['page'];
	$result = array('items' => $material_list, 'pager' => $pager);
	iajax(0, $result);
}
if ('image' == $do) {
	$year = $_GPC['year'];
	$month = $_GPC['month'];
	$page = intval($_GPC['page']);
	$groupid = intval($_GPC['group_id']);
	$page_size = 10;
	$page = max(1, $page);
	$is_local_image = 'local' == $islocal ? true : false;
	if ($is_local_image) {
		$attachment_table = table('core_attachment');
	} else {
		$attachment_table = table('wechat_attachment');
	}
	$attachment_table->searchWithUniacid($uniacid);
	$attachment_table->searchWithUploadDir($module_upload_dir);

	if (empty($uniacid)) {
		$attachment_table->searchWithUid($_W['uid']);
	}
	if ($groupid > 0) {
		$attachment_table->searchWithGroupId($groupid);
	}

	if (0 == $groupid) {
		$attachment_table->searchWithGroupId(-1);
	}

	if ($year || $month) {
		$start_time = strtotime("{$year}-{$month}-01");
		$end_time = strtotime('+1 month', $start_time);
		$attachment_table->searchWithTime($start_time, $end_time);
	}
	if ($islocal) {
		$attachment_table->searchWithType(ATTACH_TYPE_IMAGE);
	} else {
		$attachment_table->searchWithType(ATTACHMENT_IMAGE);
	}
	$attachment_table->searchWithPage($page, $page_size);

	$list = $attachment_table->orderby('createtime', 'desc')->getall();
	$total = $attachment_table->getLastQueryTotal();
	if (!empty($list)) {
		foreach ($list as &$meterial) {
			if ($islocal) {
				if (empty($option['global'])) {
					$meterial['url'] = tomedia($meterial['attachment']);
				} else {
					$meterial['url'] = to_global_media($meterial['attachment']);
				}
				unset($meterial['uid']);
			} else {
				if (!empty($_W['setting']['remote']['type'])) {
					$meterial['attach'] = tomedia($meterial['attachment']);
				} else {
					$meterial['attach'] = tomedia($meterial['attachment'], true);
				}
				$meterial['url'] = $meterial['attach'];
			}
		}
	}

	$pager = pagination($total, $page, $page_size, '', $context = array('before' => 5, 'after' => 4, 'isajax' => $_W['isajax']));
	$result = array('items' => $list, 'pager' => $pager);
	iajax(0, $result);
}

if ('tolocal' == $do || 'towechat' == $do) {
	if (!in_array($type, array('news', 'image', 'video', 'voice'))) {
		iajax(1, '转换类型不正确');

		return;
	}
}

if ('networktolocal' == $do) {
	$type = $_GPC['type'];
	if (!in_array($type, array('image', 'video'))) {
		$type = 'image';
	}

	$material = material_network_to_local($url, $uniacid, $uid, $type);
	if (is_error($material)) {
		iajax(1, $material['message']);

		return;
	}
	iajax(0, $material);
}

if ('tolocal' == $do) {
	if ('news' == $type) {
		$material = material_news_to_local($resourceid);
	} else {
		$material = material_to_local($resourceid, $uniacid, $uid, $type);
	}
	if (is_error($material)) {
		iajax(1, $material['message']);

		return;
	}
	iajax(0, $material);
}

if ('networktowechat' == $do) {
	$type = $_GPC['type'];
	if (!in_array($type, array('image', 'video'))) {
		$type = 'image';
	}
	$url_host = parse_url($url, PHP_URL_HOST);
	$is_ip = preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $url_host);
	if ($is_ip) {
		iajax(1, '网络链接不支持IP地址！');
	}
	$material = material_network_to_wechat($url, $uniacid, $uid, $acid, $type);
	if (is_error($material)) {
		iajax(1, $material['message']);

		return;
	}
	iajax(0, $material);

	return;
}

if ('towechat' == $do) {
	$material = null;
	if ('news' != $type) {
		$material = material_to_wechat($resourceid, $uniacid, $uid, $acid, $type);
	} else {
		$material = material_local_news_upload($resourceid);
		if (!is_error($material)) {
			$material['items'] = $material['news'];
		}
	}
	if (is_error($material)) {
		iajax(1, $material['message']);

		return;
	}
	iajax(0, $material);
}

$is_local_image = 'local' == $islocal ? true : false;

if ('group_list' == $do) {
	$query = table('core_attachment_group')->where('type', $is_local_image ? 0 : 1);
	$query->searchWithUniacidOrUid($uniacid, $_W['uid']);
	$list = $query->getall();
	iajax(0, $list);
}

if ('add_group' == $do) {
	$table = table('core_attachment_group');
	$table->fill(array(
		'uid' => $_W['uid'],
		'uniacid' => $uniacid,
		'name' => trim($_GPC['name']),
		'type' => $is_local_image ? 0 : 1,
	));
	$result = $table->save();
	if (is_error($result)) {
		iajax($result['errno'], $result['message']);
	}
	iajax(0, array('id' => pdo_insertid()));
}

if ('change_group' == $do) {
	$table = table('core_attachment_group');
	$type = $is_local_image ? 0 : 1;
	$name = trim($_GPC['name']);
	$id = intval($_GPC['id']);
	$table->searchWithUniacidOrUid($uniacid, $_W['uid']);
	$updated = $table->where('type', $type)
		->fill('name', $name)
		->where('id', $id)->save();
	iajax($updated ? 0 : 1, $updated ? '更新成功' : '更新失败');
}

if ('del_group' == $do) {

	if ($islocal) {
		if (empty($_W['isfounder']) && ACCOUNT_MANAGE_NAME_MANAGER != $_W['role'] && ACCOUNT_MANAGE_NAME_OWNER != $_W['role']) {
			iajax(1, '您没有权限删除图片组');
		}
	} else {
		$result_permission = permission_check_account_user('platform_material_delete',false);
		if (!$result_permission) {
			iajax(1, '您没有权限删除图片组');
		}
	}

	$table = table('core_attachment_group');
	$type = $is_local_image ? 0 : 1;
	$id = intval($_GPC['group_id']);
	$table->searchWithUniacidOrUid($uniacid, $_W['uid']);
	$deleted = $table->where('type', $type)->where('id', $id)->delete();
	iajax($deleted ? 0 : 1, $deleted ? '删除成功' : '删除失败');
}

if ('move_to_group' == $do) {
	$group_id = intval($_GPC['group_id']);
	$ids = $_GPC['id'];
	$ids = safe_gpc_array($ids);

	if ($is_local_image) {
		$table = table('core_attachment');
	} else {
		$table = table('wechat_attachment');
	}
	$updated = $table->where('id', $ids)->where('uniacid', $uniacid)->fill('group_id', $group_id)->save();

	iajax($updated ? 0 : 1, $updated ? '更新成功' : '更新失败');
}
