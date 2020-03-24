<?php
defined('IN_IA') or exit('Access Denied');
load()->func('file');


function material_sync($material, $exist_material, $type) {
	global $_W;
	$material = empty($material) ? array() : $material;
	foreach ($material as $news) {
		$attachid = '';
		$material_exist = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'media_id' => $news['media_id']));
		if (empty($material_exist)) {
			$material_data = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'media_id' => $news['media_id'],
				'type' => $type,
				'model' => 'perm',
				'createtime' => $news['update_time']
			);
			if ($type == 'image') {
				$material_data['filename'] = $news['name'];
				$material_data['attachment'] = $news['url'];
			}
			if ($type == 'voice') {
				$material_data['filename'] = $news['name'];
			}
			if ($type == 'video') {
				$material_data['tag'] = iserializer(array('title' => $news['name']));
			}
			pdo_insert('wechat_attachment', $material_data);
			$attachid = pdo_insertid();
		} else {
			if ($type == 'image') {
				$material_data = array(
					'createtime' => $news['update_time'],
					'attachment' => $news['url'],
					'filename' => $news['name']
				);
				pdo_update('wechat_attachment', $material_data, array('uniacid' => $_W['uniacid'], 'media_id' => $news['media_id']));
			}
			if ($type == 'voice') {
				$material_data = array(
					'createtime' => $news['update_time'],
					'filename' => $news['name']
				);
				pdo_update('wechat_attachment', $material_data, array('uniacid' => $_W['uniacid'], 'media_id' => $news['media_id']));
			}
			if ($type == 'video') {
				$tag = empty($material_exist['tag']) ? array() : iunserializer($material_exist['tag']);
				$material_data = array(
					'createtime' => $news['update_time'],
					'tag' => iserializer(array('title' => $news['name'], 'url' => $tag['url']))
				);
				pdo_update('wechat_attachment', $material_data, array('uniacid' => $_W['uniacid'], 'media_id' => $news['media_id']));
			}
			$exist_material[] = $material_exist['id'];
		}
		if ($type == 'news') {
			$attachid = empty($attachid) ? $material_exist['id'] : $attachid;
			pdo_delete('wechat_news', array('uniacid' =>$_W['uniacid'], 'attach_id' => $attachid));
			foreach ($news['content']['news_item'] as $key => $new) {
				$new_data = array(
					'uniacid' => $_W['uniacid'],
					'attach_id' => $attachid,
					'thumb_media_id' => $new['thumb_media_id'],
					'thumb_url' => $new['thumb_url'],
					'title' => $new['title'],
					'author' => $new['author'],
					'digest' => $new['digest'],
					'content' => $new['content'],
					'content_source_url' => $new['content_source_url'],
					'show_cover_pic' => $new['show_cover_pic'],
					'url' => $new['url'],
					'displayorder' => $key,
				);
				pdo_insert('wechat_news', $new_data);
			}
			pdo_update('wechat_attachment', array('createtime' => $news['update_time']), array('media_id' => $news['media_id']));
		}
	}
	return $exist_material;
}


function material_news_set($data, $attach_id) {
	global $_W;
	$attach_id = intval($attach_id);
	foreach ($data as $key => $news) {
		if (empty($news['title']) ||
			(!empty($news['thumb']) && !parse_path($news['thumb'])) ||
			(!empty($news['url']) && !parse_path($news['url'])) ||
			(!empty($news['content_source_url']) && !parse_path($news['content_source_url']))
		) {
			return error('-1', '参数有误');
		}
		if (!material_url_check($news['content_source_url']) || !material_url_check($news['url']) || !material_url_check($news['thumb'])) {
			return error('-3', '提交链接参数不合法');
		}
		$post_news[] = array(
			'id' => intval($news['id']),
			'uniacid' => $_W['uniacid'],
			'thumb_url' => $news['thumb'],
			'title' => addslashes($news['title']),
			'author' => addslashes($news['author']),
			'digest' => addslashes($news['digest']),
			'content' => safe_gpc_html(htmlspecialchars_decode($news['content'])),
			'url' => $news['url'],
			'show_cover_pic' => intval($news['show_cover_pic']),
			'displayorder' 		=> intval($key),
			'thumb_media_id' => addslashes($news['media_id']),
			'content_source_url' => $news['content_source_url'],
		);
	}
	if (!empty($attach_id)){
		$wechat_attachment = pdo_get('wechat_attachment', array(
			'id' => $attach_id,
			'uniacid' => $_W['uniacid']
		));
		if (empty($wechat_attachment)){
			return error('-2', '编辑素材不存在');
		}
		$wechat_attachment['model'] = 'local';
		pdo_update('wechat_attachment', $wechat_attachment, array('id' => $attach_id, 'uniacid' => $_W['uniacid']));
		pdo_delete('wechat_news', array('attach_id' => $attach_id, 'uniacid' => $_W['uniacid']));
		foreach ($post_news as $id => $news) {
			$news['attach_id'] = $attach_id;
			unset($news['id']);
			pdo_insert('wechat_news', $news);
		}
		cache_delete(cache_system_key('material_reply', array('attach_id' => $attach_id)));
	} else {
		$wechat_attachment = array(
			'uniacid' => $_W['uniacid'],
			'acid' => $_W['acid'],
			'media_id' => '',
			'type' => 'news',
			'model' => 'local',
			'createtime' => TIMESTAMP
		);
		pdo_insert('wechat_attachment', $wechat_attachment);
		$attach_id = pdo_insertid();
		foreach ($post_news as $key => $news) {
			$news['attach_id'] = $attach_id;
			pdo_insert('wechat_news', $news);
		}
	}
	return $attach_id;
}


function material_get($attach_id) {
	if (empty($attach_id)) {
		return error(1, "素材id参数不能为空");
	}
	if (is_numeric($attach_id)) {
		$material = table('wechat_attachment')->getById($attach_id);
	} else {
		$media_id = trim($attach_id);
		$material = table('wechat_attachment')->getByMediaId($media_id);
	}
	if (!empty($material)) {
		if ($material['type'] == 'news') {
			$news = table('wechat_news')->getAllByAttachId($material['id']);
			if (!empty($news)) {
				foreach ($news as &$news_row) {
					$news_row['content_source_url'] = $news_row['content_source_url'];
					$news_row['thumb_url'] = tomedia($news_row['thumb_url']);
					preg_match_all('/src=[\'\"]?([^\'\"]*)[\'\"]?/i', $news_row['content'], $match);
					if (!empty($match[1])) {
						foreach ($match[1] as $val) {
							if ((strexists($val, 'http://') || strexists($val, 'https://')) && (strexists($val, 'mmbiz.qlogo.cn') || strexists($val, 'mmbiz.qpic.cn'))) {
								$news_row['content'] = str_replace($val, tomedia($val), $news_row['content']);
							}
						}
					}
					$news_row['content'] = str_replace('data-src', 'src', $news_row['content']);
				}
				unset($news_row);
			} else {
				return error('1', '素材不存在');
			}
			$material['news'] = $news;
		} elseif ($material['type'] == 'image') {
			$material['url'] = $material['attachment'];
			$material['attachment'] = tomedia($material['attachment']);

		}
		return $material;
	} else {
		return error('1', "素材不存在");
	}
}


function material_build_reply($attach_id) {
	if (empty($attach_id)) {
		return error(1, "素材id参数不能为空");
	}
	$cachekey = cache_system_key('material_reply', array('attach_id' => $attach_id));
	$reply = cache_load($cachekey);
	if (!empty($reply)) {
		return $reply;
	}
	$reply_material = material_get($attach_id);
	$reply = array();
	if ($reply_material['type'] == 'news') {
		if (!empty($reply_material['news'])) {
			foreach ($reply_material['news'] as $material) {
				$reply[] = array(
					'title' => $material['title'],
					'description' => $material['digest'],
					'picurl' => $material['thumb_url'],
					'url' => !empty($material['content_source_url']) ? $material['content_source_url'] : $material['url'],
				);
			}
		}
	}
	cache_write($cachekey, $reply, CACHE_EXPIRE_MIDDLE);
	return $reply;
}


function material_strip_wechat_image_proxy($content) {
	global $_W;
	$match_wechat = array();
	$content = htmlspecialchars_decode($content);
	preg_match_all ('/<img.*src=[\'"](.*)[\'"].*\/?>/iU', $content, $match_wechat);
	if (!empty($match_wechat[1])) {
		foreach ($match_wechat[1] as $val) {
			$wechat_thumb_url = urldecode(str_replace($_W['siteroot'] . 'web/index.php?c=utility&a=wxcode&do=image&attach=', '', $val));
			$content = str_replace($val, $wechat_thumb_url, $content);
		}
	}
	return $content;
}


function material_get_image_url($content) {
	global $_W;
	$content = htmlspecialchars_decode ($content);
	$match = array ();
	$images = array ();
	preg_match_all ('/<img.*src=[\'"](.*\.(?:png|jpg|jpeg|jpe|gif))[\'"].*\/?>/iU', $content, $match);
	if (!empty($match[1])) {
		foreach ($match[1] as $val) {
			if ((strexists ($val, 'http://') || strexists ($val, 'https://')) && !strexists ($val, 'mmbiz.qlogo.cn') && !strexists ($val, 'mmbiz.qpic.cn')) {
				$images[] = $val;
			} else {
				if (strexists ($val, './attachment/images/')) {
					$images[] = tomedia ($val);
				}
			}
		}
	}
	return $images;
}


function material_parse_content($content) {
	global $_W;
	$content = material_strip_wechat_image_proxy($content);
	$images = material_get_image_url($content);
	if (!empty($images)) {
		foreach ($images as $image) {
			$thumb = file_remote_attach_fetch(tomedia($image), 1024, 'material/images');
			if(is_error($thumb)) {
				return $thumb;
			}
			$thumb = ATTACHMENT_ROOT . $thumb;
			$account_api = WeAccount::createByUniacid();
			$result = $account_api->uploadNewsThumb($thumb);
			if (is_error($result)) {
				return $result;
			} else {
				$content = str_replace($image, $result, $content);
			}
		}
	}
	return $content;
}

function material_local_news_upload($attach_id) {
	global $_W;
	$account_api = WeAccount::createByUniacid();
	$material = material_get($attach_id);
	if (is_error($material)){
		return error('-1', '获取素材文件失败');
	}
	$change_media_id = 0;
	foreach ($material['news'] as $news) {
		if (empty($news['content'])){
			return error('-6', '素材内容不能为空');
		}
		$news['content'] = material_parse_content($news['content']);
		if (!empty($news['content_source_url'])) {
			$news['content_source_url'] = safe_gpc_url($news['content_source_url'], false, $_W['siteroot'] . 'app/' . $news['content_source_url']);
		}
		if (is_error($news['content'])) {
			return error('-2', $news['content']['message']);
		}
		if (empty($news['thumb_media_id'])) {
			if (empty($news['thumb_url'])){
				return error('-7', '图文封面不能为空');
			}else{
				$result = material_local_upload_by_url($news['thumb_url']);
				if (is_error($result)){
					return error('-3', $result['message']);
				}
				$news['thumb_media_id'] = $result['media_id'];
				$news['thumb_url'] = $result['url'];
			}
		}
		pdo_update('wechat_news', $news, array('id' => $news['id']));

		$articles['articles'][] = $news;

		if (!empty($material['media_id'])) {
			$edit_attachment['media_id'] = $material['media_id'];
			$edit_attachment['index'] = $news['displayorder'];
			$edit_attachment['articles'] = $news;
			$result = $account_api->editMaterialNews($edit_attachment);
			if (is_error($result)) {
				if ($result['errno'] == 40114) { 					$change_media_id = $material['media_id'];
					break;
				} else {
					return error('-4', $result['message']);
				}
			}
		}
	}

	if (empty($material['media_id']) || $change_media_id) {
		$media_id = $account_api->addMatrialNews($articles);
		if (is_error($media_id)) {
			return error('-5', $media_id, '');
		}
		$material_info = $account_api->getMaterial($media_id, false);
		if (!empty($material_info['news_item'])) {
			foreach ($material_info['news_item'] as $key => $info) {
				pdo_update('wechat_news', array('url' => $info['url']), array('uniacid' => $_W['uniacid'], 'attach_id' => $material['id'], 'displayorder' => $key));
			}
		}
		pdo_update('wechat_attachment', array(
			'media_id' => $media_id,
			'model' => 'perm'
		), array(
			'uniacid' => $_W['uniacid'],
			'id' => $attach_id
		));
		if ($change_media_id) {
			$account_api->delMaterial($change_media_id);
		}
	} else {
		pdo_update('wechat_attachment', array('model' => 'perm'), array('uniacid' => $_W['uniacid'], 'id' => $attach_id));
	}
	return $material;
}

function material_local_upload_by_url($url, $type='images') {
	global $_W;
	$account_api = WeAccount::createByUniacid();
	if (! empty($_W['setting']['remote']['type'])) {
		$remote_file_url = tomedia($url);
		$filepath = file_remote_attach_fetch($remote_file_url,0,'');
		if(is_error($filepath)) {
			return $filepath;
		}
		$filepath = ATTACHMENT_ROOT . $filepath;
	} else {
		if (strexists(parse_url($url, PHP_URL_PATH), '/attachment/')) {
			$url = substr(parse_url($url, PHP_URL_PATH), strpos(parse_url($url, PHP_URL_PATH), '/attachment/') + strlen('/attachment/'));
		}
		$filepath = ATTACHMENT_ROOT . $url;
	}
	$filesize = filesize($filepath);
	$filesize = sizecount($filesize, true);
	if ($filesize > 10 && $type == 'videos') {
		return error(-1, '要转换的微信素材视频不能超过10M');
	}
	return $account_api->uploadMediaFixed($filepath, $type);
}


function material_local_upload($material_id){
	global $_W;
	$type_arr = array('1' => 'images', '2' => 'voices', '3' => 'videos');
	$material = pdo_get('core_attachment', array('uniacid' => $_W['uniacid'], 'id' => $material_id));
	if (empty($material)) {
		return error('-1', '同步素材不存在或已删除');
	}
	return material_local_upload_by_url($material['attachment'], $type_arr[$material['type']]);
}


function material_upload_limit() {
	global $_W;
	$default = 5 * 1024 * 1024;
	$upload_limit = array(
		'num' => '30',
		'image' => $default,
		'voice' => $default,
		'video' => $default
	);
	$upload = $_W['setting']['upload'];
	if (isset($upload['image']['limit']) && (bytecount($upload['image']['limit'].'kb')>0)){
		$upload_limit['image'] = bytecount($upload['image']['limit'].'kb');
	}
	if (isset($upload['image']['limit']) && (bytecount($upload['audio']['limit'].'kb')>0)){
		$upload_limit['voice'] = $upload_limit['video'] = bytecount($upload['audio']['limit'].'kb');
	}
	return $upload_limit;
}


function material_news_delete($material_id){
	global $_W;
	$permission = permission_account_user_menu($_W['uid'], $_W['uniacid'], 'system');
	if (is_error($permission)) {
		return error(-1, $permission['message']);
	}
	if (empty($_W['isfounder']) && !empty($permission) && !in_array('platform_material', $permission) && !in_array('all', $permission)) {
		return error('-1', '您没有权限删除该文件');
	}
	$material_id = intval($material_id);
	$material = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $material_id));
	if (empty($material)){
		return error('-2', '素材文件不存在或已删除');
	}
	if (!empty($material['media_id'])){
		$account_api = WeAccount::createByUniacid();
		$result = $account_api->delMaterial($material['media_id']);
	}
	if (is_error($result)){
		return $result;
	}
	pdo_delete('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $material_id));
	pdo_delete('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $material_id));
	return $result;
}


function material_delete($material_id, $location){
	global $_W;
	if (empty($_W['isfounder']) && !permission_check_account_user('platform_material_delete')) {
		return error('-1', '您没有权限删除该文件');
	}
	$material_id = intval($material_id);
	$table = $location == 'wechat' ? 'wechat_attachment' : 'core_attachment';
	$material = pdo_get($table, array('id' => $material_id));
	if (empty($material)){
		return error('-2', '素材文件不存在或已删除');
	}
	if ($location == 'wechat' && !empty($material['media_id'])){
		$account_api = WeAccount::createByUniacid();
		$result = $account_api->delMaterial($material['media_id']);
	} else {
						if (!empty($material['uniacid'])) {
			$role = permission_account_user_role($_W['uid'], $material['uniacid']);
			if (in_array($role, array(ACCOUNT_MANAGE_NAME_OPERATOR, ACCOUNT_MANAGE_NAME_MANAGER)) && $_W['uid'] != $material['uid']) {
				return error('-1', '您没有权限删除该文件');
			}
		} elseif ($_W['uid'] != $material['uid']) {
			return error('-1', '您没有权限删除该文件');
		}
		if (!empty($_W['setting']['remote']['type'])) {
			$result = file_remote_delete($material['attachment']);
						if (file_exists(IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/' . $material['attachment'])) {
				$result = file_delete($material['attachment']);
			}
		} else {
			$result = file_delete($material['attachment']);
		}
	}
	if (is_error($result)) {
		return error('-3', '删除文件操作发生错误');
	}
	pdo_delete($table, array('id' => $material_id, 'uniacid' => $_W['uniacid']));
	return $result;
}


function material_url_check($url) {
	if (empty($url)){
		return true;
	} else {
		$pattern ="/^((https|http|tel):\/\/|\.\/index.php)[^\s]+/i";
		return preg_match($pattern, trim($url));
	}
}

function material_news_list($server = '', $search ='', $page = array('page_index' => 1, 'page_size' => 24)) {
	global $_W;
	$wechat_news_table = table('wechat_news');
	$wechat_attachment_table = table('wechat_attachment');
	$material_list = array();
	if (empty($search)) {
		$wechat_attachment_table->searchWithUniacid($_W['uniacid']);
		$wechat_attachment_table->searchWithType('news');
		if (!empty($server) && in_array($server, array('local', 'perm'))) {
			$wechat_attachment_table->searchWithModel($server);
		}
		$wechat_attachment_table->searchWithPage($page['page_index'], $page['page_size']);
		$news_list = $wechat_attachment_table->orderby('createtime DESC')->getall();
		$total = $wechat_attachment_table->getLastQueryTotal();

		if (! empty($news_list)) {
			foreach ($news_list as $news) {
				$news['items'] = $wechat_news_table->getAllByAttachId($news['id']);
				$material_list[$news['id']] = $news;
			}
		}
	} else {
		$wechat_news_table->searchKeyword("%$search%");
		$wechat_news_table->searchWithUniacid($_W['uniacid']);
		$search_attach_id = $wechat_news_table->getall();

		if (!empty($search_attach_id)) {
			foreach ($search_attach_id as $news) {
				if (isset($material_list[$news['attach_id']]) && !empty($material_list[$news['attach_id']])) {
					continue;
				}
				$wechat_attachment = $wechat_attachment_table->getById($news['attach_id']);
				if (empty($wechat_attachment)) {
					continue;
				}
				$material_list[$news['attach_id']] = $wechat_attachment;
				$material_list[$news['attach_id']]['items'] = $wechat_news_table->getAllByAttachId($news['attach_id']);
			}
		}
	}

		foreach ($material_list as $key => &$news) {
		if (isset($news['items']) && is_array($news['items'])) {
			if (empty($news['items'][0])) {
				$news['items'] = array_values($news['items']);
			}
			foreach ($news['items'] as &$item) {
				$item['thumb_url'] = tomedia($item['thumb_url']);
			}
		}
	}
	unset($news_list);
	$pager = pagination($total, $page['page_index'], $page['page_size'],'',$context = array('before' => 5, 'after' => 4, 'isajax' => $_W['isajax']));
	$material_news = array('material_list' => $material_list, 'page' => $pager);
	return $material_news;
}

function material_list($type = '', $server = '', $page = array('page_index' => 1, 'page_size' => 24)) {
	global $_W;
	$tables = array(MATERIAL_LOCAL => 'core_attachment', MATERIAL_WEXIN => 'wechat_attachment');
	$conditions['uniacid'] = $_W['uniacid'];
		$table = $tables[$server];
		switch ($type) {
			case 'voice' :
				$conditions['type'] = $server == MATERIAL_LOCAL ? ATTACH_TYPE_VOICE : 'voice';
				break;
			case 'video' :
				$conditions['type'] = $server == MATERIAL_LOCAL ? ATTACH_TYPE_VEDIO : 'video';
				break;
			default :
				$conditions['type'] = $server == MATERIAL_LOCAL ? ATTACH_TYPE_IMAGE : 'image';
				break;
		}
		if ($server == 'local') {
			$material_list = pdo_getslice($table, $conditions, array($page['page_index'], $page['page_size']), $total, array(), '', 'createtime DESC');
		} else {
			$conditions['model'] = MATERIAL_WEXIN;
			$material_list = pdo_getslice($table, $conditions, array($page['page_index'], $page['page_size']), $total, array(), '', 'createtime DESC');
			if ($type == 'video'){
				foreach ($material_list as &$row) {
					$row['tag'] = $row['tag'] == '' ? array() : iunserializer($row['tag']);
					if (empty($row['filename'])) {
						$row['filename'] = $row['tag']['title'];
					}
				}
				unset($row);
			}
		}
		$pager = pagination($total, $page['page_index'], $page['page_size'],'',$context = array('before' => 5, 'after' => 4, 'isajax' => $_W['isajax']));
		$material_news = array('material_list' => $material_list, 'page' => $pager);
		return $material_news;
}



function material_news_to_local($attach_id) {
		$material = material_get($attach_id);
	if(is_error($material)) {
		return $material;
	}
	$attach_id = material_news_set($material['news'],$attach_id);
	if(is_error($attach_id)) {
		return $attach_id;
	}
	$material['items'] = $material['news'];	return $material;
}


function material_to_local($resourceid, $uniacid, $uid, $type = 'image') {
	$material = material_get($resourceid);
	if(is_error($material)) {
		return $material;
	}
	return material_network_image_to_local($material['url'], $uniacid, $uid);
}


function material_network_image_to_local($url, $uniacid, $uid) {
	return material_network_to_local($url, $uniacid, $uid, 'image');
}



function material_network_to_local($url, $uniacid, $uid, $type = 'image') {
	$path = file_remote_attach_fetch($url); 	if(is_error($path)) {
		return $path;
	}
	$filename = pathinfo($path,PATHINFO_FILENAME);
	$data = array('uniacid' => $uniacid, 'uid' => $uid,
		'filename' => $filename,
		'attachment' => $path,
		'type' => $type == 'image' ? ATTACH_TYPE_IMAGE : ($type == 'audio'||$type == 'voice' ? ATTACH_TYPE_VOICE : ATTACH_TYPE_VEDIO),
		'createtime'=>TIMESTAMP
	);
	pdo_insert('core_attachment', $data);
	$id = pdo_insertid();
	$data['id'] = $id;
	$data['url'] = tomedia($path);
	return $data;
}



function material_to_wechat($attach_id, $uniacid, $uid, $acid, $type = 'image') {
	$result = material_local_upload($attach_id); 	if (is_error($result)) {
		return $result;
	}
	$tag = $result['url'];
	if($type == 'video') {
		$tag = serialize(array('title'=>'网络视频','description'=>'网络视频'));
	}
	$data = array('uniacid' => $uniacid, 'uid' => $uid, 'acid' => $acid,
		'media_id' => $result['media_id'],
		'attachment' => $result['url'],
		'type' => $type,
		'tag' => $tag,
		'model' => 'perm',
		'createtime'=>TIMESTAMP
	);
	pdo_insert('wechat_attachment', $data);
	$id = pdo_insertid();
	$data['url'] = tomedia($result['url']);
	$data['id'] = $id;
	return $data;
}



function material_network_image_to_wechat($url, $uniacid, $uid, $acid) {
	return material_network_to_wechat($url, $uniacid, $uid, $acid, 'image');
}


function material_network_to_wechat($url, $uniacid, $uid, $acid, $type = 'image') {
	$local = material_network_to_local($url, $uniacid, $uid, $type); 	if (is_error($local)) {
		return $local;
	}
	return material_to_wechat($local['id'], $uniacid, $uid, $acid, $type);
}
