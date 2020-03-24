<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$_URLTYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
define('CLOUD_GATEWAY_URL', $_URLTYPE.$_SERVER['HTTP_HOST']);
define('CLOUD_GATEWAY_URL_NORMAL', $_URLTYPE.$_SERVER['HTTP_HOST']);
function cloud_client_define() {
	return array(
		'/framework/function/communication.func.php',
		'/framework/model/cloud.mod.php',
		'/web/source/cloud/upgrade.ctrl.php',
		'/web/source/cloud/process.ctrl.php',
		'/web/source/cloud/dock.ctrl.php',
		'/web/themes/default/cloud/upgrade.html',
		'/web/themes/default/cloud/process.html'
	);
}


function _cloud_build_params($must_authorization_host = true) {
	global $_W;
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['family'] = IMS_FAMILY;
	$pars['version'] = IMS_VERSION;
	$pars['release'] = IMS_RELEASE_DATE;
	$pars['host'] = trim(preg_replace('/http(s)?:\\/\\//', '', trim($_W['siteroot'], '/')));
        $pars['key'] = $_W['setting']['site']['key'];
        $pars['token'] = $_W['setting']['site']['token'];
        $pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	$clients = cloud_client_define();
	$string = '';
	foreach($clients as $cli) {
		$string .= md5_file(IA_ROOT . $cli);
	}
	$pars['client'] = md5($string);
	return $pars;
}

function _cloud_shipping_parse($dat, $file) {
/*	if (is_error($dat)) {
		return error(-1, '网络传输故障，详情： ' . (strpos($dat['message'], 'Connection reset by peer') ? '云服务瞬时访问过大而导致网络传输中断，请稍后重试。' : $dat['message']));
	}
	$tmp = iunserializer($dat['content']);
	if (is_array($tmp) && is_error($tmp)) {
		if ($tmp['errno'] == '-2') {
			file_put_contents(IA_ROOT . '/framework/version.inc.php', str_replace("'x'", "'v'", file_get_contents(IA_ROOT . '/framework/version.inc.php')));
		}
		return $tmp;
	}
	if ($dat['content'] == 'patching') {
		return error(-1, '补丁程序正在更新中，请稍后再试！');
	}
	if ($dat['content'] == 'frequent') {
		return error(-1, '更新操作太频繁，请稍后再试！');
	}
	if ($dat['content'] == 'blacklist') {
		return error(-1, '抱歉，您的站点已被列入云服务黑名单，云服务一切业务已被禁止，请联系客服！');
	}
	$data = @file_get_contents($file);
	@unlink($file);
	$ret = @iunserializer($data);
	$ret = iunserializer($ret['data']);
	if (is_array($ret) && is_error($ret)) {
		if ($ret['errno'] == '-2') {
			file_put_contents(IA_ROOT . '/framework/version.inc.php', str_replace("'x'", "'v'", file_get_contents(IA_ROOT . '/framework/version.inc.php')));
		}
		if ($ret['errno'] == '-3') { 			return array(
				'errno' => $ret['errno'],
				'message' => $ret['message'],
				'cloud_id' => $ret['data'],
			);
		}
	}
	if (!is_error($ret) && is_array($ret)) {
		if (!empty($ret) && $ret['state'] == 'fatal') {
			return error($ret['errorno'], '发生错误: ' . $ret['message']);
		}
		return $ret;
	} else {
		return error($ret['errno'], "发生错误: {$ret['message']}");
	}*/
}

function cloud_request($url, $post = '', $extra = array(), $timeout = 60) {
	global $_W;
	load()->func('communication');
	if (!empty($_W['setting']['cloudip']['ip']) && empty($extra['ip'])) {
		$extra['ip'] = $_W['setting']['cloudip']['ip'];
	}
	if (strexists($url, 's.w7.cc')) {
		$extra = array();
	}

	$response = ihttp_request($url, $post, $extra, $timeout);
	if (is_error($response)) {
		setting_save(array(), 'cloudip');
	}
	return $response;
}

function cloud_api($method, $data = array(), $extra = array(), $timeout = 60) {
	$cache_key = cache_system_key('cloud_api_method', array('method' => $method));
	$cache = cache_load($cache_key);
	if (!empty($cache) && $cache['expire'] > time()) {
		return $cache;
	}
	$api_url = 'http://api.qiuma.wang/%s';
	$must_authorization_host = !in_array($method, array('module/setting/index', 'module/setting/save'));
	$pars = _cloud_build_params($must_authorization_host);
	if ($method != 'site/token/index') {
		$pars['token'] = cloud_build_transtoken();
	}
	$data = array_merge($pars, $data);
	if ($GLOBALS['_W']['config']['setting']['development'] == 3) {
		$extra['CURLOPT_USERAGENT'] = 'development';
	}
	$response = ihttp_request(sprintf($api_url, $method), $data, $extra, $timeout);
	$file = IA_ROOT . '/data/' . (!empty($data['file']) ? $data['file'] : $data['method']);
	$ret = _cloud_shipping_parse($response, $file);
	cache_write($cache_key, $ret, 300);
	return $ret;
}

function cloud_prepare() {
	global $_W;
	setting_load();
/*	if(empty($_W['setting']['site']['key']) || empty($_W['setting']['site']['token'])) {
		return error('-1', '站点注册信息丢失, 请通过"重置站点ID和通信密钥"重新获取 !');
	}
	*/
	return true;
}

function cloud_build() {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.build3';
	$dat = cloud_request(HTTP_X_FOR .'api.qiuma.wang/api.php', $pars);
	$file = IA_ROOT . '/data/application.build';
	$ret = _cloud_shipping_parse($dat, $file);

	if (is_error($ret)) {
		return $ret;
	}

	if($ret['state'] == 'warning') {
		$ret['files'] = cloud_client_define();
		unset($ret['schemas']);
		unset($ret['scripts']);
	} else {
		$patch_path = sprintf('%s/data/patch/upgrade/%s', IA_ROOT, date('Ymd'));
		if (!is_dir($patch_path)) {
			mkdirs($patch_path);
		}

		$files = $files_allowed = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = IA_ROOT . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files_allowed[] = $file['path'];
				}

				$entry = $patch_path . $file['path'];
				if (!is_file($entry)) {
					$entry = IA_ROOT . $file['path'];
				}
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		if (!empty($ret['files'])) {
			cloud_bakup_files($ret['files']);
		} else {
			if (!empty($files_allowed)) {
				foreach ($files_allowed as $file) {
					$dir = pathinfo(IA_ROOT . $file, PATHINFO_DIRNAME);
					if (!is_dir($dir)) {
						mkdirs($dir);
					}
					file_put_contents(IA_ROOT . $file, file_get_contents($patch_path . $file));
				}
				rmdirs($patch_path);
			}
		}
		$schemas = array();
		if(!empty($ret['schemas'])) {
			load()->func('db');
			foreach($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if(empty($local)) {
					$schemas[] = $remote;
				} else {
					$sqls = db_table_fix_sql($local, $remote);
					if(!empty($sqls)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['schemas'] = $schemas;
	}

	if($ret['family'] == 'x' && IMS_FAMILY == 'v') {
		load()->model('setting');
		setting_upgrade_version('x', IMS_VERSION, IMS_RELEASE_DATE);
		message('您已经购买了商业授权版本, 系统将转换为商业版, 并重新运行自动更新程序.', 'refresh');
	}
	$ret['upgrade'] = false;
	if(!empty($ret['files']) || !empty($ret['schemas']) || !empty($ret['scripts'])) {
		$ret['upgrade'] = true;
	}
	$upgrade = array();
	$upgrade['upgrade'] = $ret['upgrade'];
	$upgrade['data'] = $ret;
	$upgrade['lastupdate'] = TIMESTAMP;
	cache_write(cache_system_key('upgrade'), $upgrade);

	return $ret;
}

function cloud_schema() {
	$pars['method'] = 'application.schema';
	$pars['file'] = 'application.schema';
	$ret = cloud_api('site/schema/index', $pars);
	
	if(!is_error($ret)) {
		$schemas = array();
		if(!empty($ret['schemas'])) {
			load()->func('db');
			foreach($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if(empty($local)) {
					$schemas[] = $remote;
				} else {
					$diffs = db_schema_compare($local, $remote);
					if(!empty($diffs)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['schemas'] = $schemas;
	}
	return $ret;
}

function cloud_download($path, $type = '') {
	global $_W;
	$pars = _cloud_build_params();
	$pars['method'] = 'application.shipping';
	$pars['path'] = $path;
	$pars['type'] = $type;
	$pars['gz'] = function_exists('gzcompress') && function_exists('gzuncompress') ? 'true' : 'false';
	$pars['download'] = 'true';
	$headers = array('content-type' => 'application/x-www-form-urlencoded');
	$_URLTYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	$dat = cloud_request($_URLTYPE.$_SERVER['HTTP_HOST'], $pars, $headers, 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	if($dat['content'] == 'success') {
		return true;
	}
	$ret = @json_decode($dat['content'], true);
	if (isset($ret['error'])) {
		return error(1, $ret['error']);
	}
	if(is_error($ret)) {
		return $ret;
	} else {
		$post = $dat['content'];
		$data = base64_decode($post);
		if (base64_encode($data) !== $post) {
			$data = $post;
		}
		$ret = iunserializer($data);
		$gz = function_exists('gzcompress') && function_exists('gzuncompress');
		$file = base64_decode($ret['file']);
		if($gz) {
			$file = gzuncompress($file);
		}
		$_W['setting']['site']['token'] = authcode(cache_load(cache_system_key('cloud_transtoken')), 'DECODE');
		$string = (md5($file) . $ret['path'] . $_W['setting']['site']['token']);
		if(!empty($_W['setting']['site']['token']) && md5($string) === $ret['sign']) {
			$error_file_list = array();
			if (!cloud_file_permission_pass($error_file_list)) {
				return error(-1, '请修复下列文件读写权限 : ' . implode('; ', $error_file_list));
			}
						if ($type == 'module' || $type == 'theme') {
				$patch_path = IA_ROOT;
			} else {
				$patch_path = sprintf('%s/data/patch/upgrade/%s', IA_ROOT, date('Ymd'));
			}
			$path = $patch_path . $ret['path'];
			load()->func('file');
			@mkdirs(dirname($path));
			if (file_put_contents($path, $file)) {
				return true;
			} else {
				return error(-1, '写入失败，请检查是否有写入权限或是否磁盘已满！');
			}
		}
		return error(-1, '写入失败！');
	}
}

function cloud_m_prepare($name) {
	$ret = cloud_api('module/check', array(
		'method' => 'module.check',
		'module' => $name,
	));
	if (is_error($ret)) {
		return $ret;
	}
	return true;
}


function cloud_m_build($modulename, $type = '') {
	$type = in_array($type, array('uninstall', 'upgrade', 'install')) ? $type : '';
	if (empty($modulename)) {
		return array();
	}
	if ($type == 'upgrade') {
		$module_info = cloud_m_info($modulename);
		if (is_error($module_info)) {
			return $module_info['message'];
		}
		$module = array('version' => $module_info['version']['version']);
	} else {
		$module = table('modules')->getByName($modulename);
	}
	$pars['module'] = $modulename;
	$pars['type'] = $type;
	if (!empty($module)) {
		$pars['module_version'] = $module['version'];
	}
	
	$pars['file'] = 'module.build';
	$ret = cloud_api('module/build', $pars);

	if (!is_error($ret)) {
		$dir = IA_ROOT . '/addons/' . $modulename;
		$files = array();
		if (!empty($ret['files'])) {
			foreach ($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if (!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/' . $modulename . $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		$schemas = array();
		if (!empty($ret['schemas'])) {
			load()->func('db');
			foreach ($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if (empty($local)) {
					$schemas[] = $remote;
				} else {
					$diffs = db_table_fix_sql($local, $remote);
					if (!empty($diffs)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['upgrade'] = true;
		$ret['type'] = 'module';
		$ret['schemas'] = $schemas;
				if (empty($module)) {
			$ret['install'] = 1;
		}
	}
	return $ret;
}


function cloud_m_query($module = array(), $page = 1) {
	$pars['method'] = 'module.query';
	if (empty($module)) {
		$module = cloud_extra_module();
	}
	if (!is_array($module)) {
		$module = array($module);
	}
	$pars['page'] = max(1, intval($page));
	$pars['module'] = base64_encode(iserializer($module));
	$ret = cloud_api('module/query', $pars);
	if (isset($ret['error'])) {
		return error(1, $ret['error']);
	}
	if (!is_error($ret)) {
		$pirate_apps = $ret['pirate_apps'];
		unset($ret['pirate_apps']);
		$support_names = array('app', 'wxapp', 'webapp', 'system_welcome', 'android', 'ios', 'xzapp', 'aliapp', 'baiduapp', 'toutiaoapp');
		$record_module = array();
		foreach ($ret['data'] as $modulename => &$info) {
			if ($info['is_record']) {
				$record_module[] = $info['name'];
			}
			if (empty($info['site_branch'])) {
				continue;
			}
			foreach ($support_names as $support) {
				if (in_array($support, $info['site_branch']['bought']) && !empty($info['site_branch']["{$support}_support"]) && $info['site_branch']["{$support}_support"] == 2) {
					$info['site_branch']["{$support}_support"] = 2;
				} else {
					$info['site_branch']["{$support}_support"] = 1;
				}
			}
		}
		$ret['pirate_apps'] = $pirate_apps;
		if (!empty($record_module)) {
			table('modules')->where(array('name IN' => $record_module))->fill(array('cloud_record' => STATUS_ON))->save();
		}
	}
	return $ret;
}

function cloud_m_info($name) {
	$pars['method'] = 'module.info';
	$pars['module'] = $name;
	$ret = cloud_api('module/info', $pars);
	return $ret;
}


function cloud_m_upgradeinfo($modulename) {
	load()->model('module');

	$module = module_fetch($modulename);

	$pars['method'] = 'module.info';
	$pars['module'] = $modulename;
	$pars['curversion'] = $module['version'];
	$pars['isupgrade'] = 1;
	$ret = cloud_api('module/info', $pars);

	if (empty($ret)) {
		return array();
	}
	if (is_error($ret)) {
		return $ret;
	}
	if (version_compare($ret['version']['version'], $module['version'], '>')) {
		$ret['upgrade'] = true;
	}

	$ret['site_branch'] = $ret['branches'][$ret['version']['branch_id']];
	$ret['from'] = 'cloud';
	foreach ($ret['branches'] as &$branch) {
		if ($branch['displayorder'] > $ret['site_branch']['displayorder'] || ($branch['displayorder'] == $ret['site_branch']['displayorder'] && $ret['site_branch']['id'] < intval($branch['id']))) {
			$ret['new_branch'] = true;
		}
		$branch['id'] = intval($branch['id']);
		$branch['version']['description'] = preg_replace('/\n/', '<br/>', $branch['version']['description']);
		$branch['displayorder'] = intval($branch['displayorder']);
		$branch['day'] = intval(date('d', $branch['version']['createtime']));
		$branch['month'] = date('Y.m', $branch['version']['createtime']);
		$branch['hour'] = date('H:i', $branch['version']['createtime']);
	}
	unset($branch);
	return $ret;
}

function cloud_t_prepare($name) {
	$pars['method'] = 'theme.check';
	$pars['theme'] = $name;
	$dat = cloud_api('theme/check', $pars);
	if (is_error($dat)) {
		return $dat;
	}

	return true;
}


function cloud_t_query() {
	$pars['method'] = 'theme.query';
	$pars['theme'] = cloud_extra_theme();
	$ret = cloud_api('theme/query', $pars);
	return $ret;
}

function cloud_t_info($name) {
	$pars['method'] = 'theme.info';
	$pars['theme'] = $name;
	$ret = cloud_api('theme/info', $pars);
	return $ret;
}

function cloud_t_build($name) {
	if (empty($name)) {
		return array();
	}
	$theme = table('site_templates')->getByName(trim($name));
	$pars['method'] = 'theme.build';
	$pars['theme'] = $name;
	if(!empty($theme)) {
		$pars['themeversion'] = $theme['version'];
	}
	$ret = cloud_api('theme/build', $pars);
	if(!is_error($ret)) {
		$dir = IA_ROOT . '/app/themes/' . $name;
		$files = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/'. $name . $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		$ret['upgrade'] = true;
		$ret['type'] = 'theme';
				if(empty($theme)) {
			$ret['install'] = 1;
		}
	}
	return $ret;
}


function cloud_t_upgradeinfo($name) {
	if (empty($name)) {
		return array();
	}
	$theme = table('site_templates')->getByName(trim($name));
	if (empty($theme)) {
		return array();
	}
	return cloud_api('theme/upgrade', array(
		'method' => 'theme.upgrade',
		'theme' => $theme['name'],
		'version' => $theme['version'],
		'isupgrade' => 1,
	));
}


function cloud_sms_send($mobile, $content, $postdata = array(), $custom_sign = '', $use_system_balance = false) {
	global $_W;
	if(!preg_match('/^1\d{10}$/', $mobile) || empty($content)) {
		return error(1, '发送短信失败, 原因: 手机号错误或内容为空.');
	}

		$row = pdo_get('uni_settings' , array('uniacid' => $uniacid), array('notify'));
		$row['notify'] = @iunserializer($row['notify']);

		$config = $row['notify']['sms'];
		$balance = intval($config['balance']);

                $sign = $config['signature'];
        if(empty($sign)) {
                $sign = '短信宝';
	}
        //判断剩余条数
	if ($balance < 1) {
                return error(-1, '短信发送失败, 原因:余额不足');
        }
        //短信宝账号
        $smsbao_info=pdo_get('uni_settings' , array('uniacid' => $_W['uniacid']), array('copyright'));
        $sms_param['u']=$_W['setting']['copyright']['sms_name'];
        $sms_param['p']=md5($_W['setting']['copyright']['sms_password']);
        $sms_param['m']=$mobile;
        $sms_param['c']='【'.$sign.'】'.$content;
        $response = file_get_contents("http://api.smsbao.com/sms?".http_build_query($sms_param));
        if (trim($response)!='0') {
                return error($response, '短信发送失败, 原因:'.$response);
	}
 
        if (trim($response)=='0') {
			$row['notify']['sms']['balance'] = $row['notify']['sms']['balance'] - 1;
			if ($row['notify']['sms']['balance'] < 0) {
				$row['notify']['sms']['balance'] = 0;
			}
			pdo_update('uni_settings', array('notify' => iserializer($row['notify'])), array('uniacid' => $uniacid));
			uni_setting_save('notify', $row['notify']);
	}
	return true;
}


function cloud_sms_info() {
        global $_W;
 
        $data=[];
        //返回短信的剩余条数以及签名
        $sms_name=$_W['setting']['copyright']['sms_name'];
        $sms_password=md5($_W['setting']['copyright']['sms_password']);
        $res=file_get_contents("http://api.smsbao.com/query?u={$sms_name}&p={$sms_password}");
        $retArr = split("\n", $res);
        $balanceArr = split(",", $retArr[1]);
        $data['sms_count']=$retArr[0] == 0 ? $balanceArr[1]:0;
        return $data;
}


function cloud_extra_account() {
	$data = array();
	$data['accounts'] = pdo_fetchall("SELECT name, account, original FROM ".tablename('account_wechats') . " GROUP BY account");
	return serialize($data);
}


function cloud_extra_module() {
	load()->model('module');
	$module_support_type = array_keys(module_support_type());
	$installed = table('modules')->getInstalled();
	$recycle = table('modules_recycle')->where('type', 2)->getall('name');

	$result = array();
	foreach($installed as $install_module) {
		if ($install_module['cloud_record']) {
			continue;
		}
		$result[$install_module['name']] = array(
			'name' => $install_module['name'],
			'version' => $install_module['version'],
		);
		foreach ($module_support_type as $support) {
			$type = str_replace('_support', '', $support);
			if ($install_module[$support] == 2) {
				$result[$install_module['name']]['support'][$type]['is_install'] = 2;
			}
		}
	}
	foreach($recycle as $recycle_module) {
		if (empty($result[$recycle_module['name']])) {
			$result[$recycle_module['name']] = array(
				'name' => $recycle_module['name'],
			);
		}
		foreach ($module_support_type as $support) {
			$type = str_replace('_support', '', $support);
			if ($recycle_module[$support]) {
				$result[$recycle_module['name']]['support'][$type] = array(
					'is_recycle' => 2
				);
			}
		}
	}
	return $result;
}


function cloud_extra_theme() {
	$sql = 'SELECT `name` FROM ' . tablename('site_templates') . ' WHERE `name` <> :name';
	$themes = pdo_fetchall($sql, array(':name' => 'default'), 'name');
	if (!empty($themes)) {
		return base64_encode(iserializer(array_keys($themes)));
	} else {
		return '';
	}
}


function cloud_extra_webtheme() {
	$sql = 'SELECT `name` FROM ' . tablename('webtheme_templates') . ' WHERE `name` <> :name';
	$themes = pdo_fetchall($sql, array(':name' => 'default'), 'name');
	if (!empty($themes)) {
		return base64_encode(iserializer(array_keys($themes)));
	} else {
		return '';
	}
}

function cloud_module_setting($acid, $module) {
	$pars = array(
		'acid' => $acid,
		'module_name' => $module['name'],
		'module_version' => $module['version'],
	);
	return cloud_api('module/setting/index', $pars);
}

function cloud_module_setting_save($acid, $module_name, $setting) {
	$pars = array(
		'acid' => $acid,
		'module_name' => $module_name,
		'setting' => $setting,
	);
	return cloud_api('module/setting/save', $pars);
}

function cloud_module_list($title, $support_type, $page = 1, $per_page = 20) {
	$pars = array(
		'title' => $title,
		'support_type' => $support_type,
		'page' => $page,
		'per_page' => $per_page,
	);
	return cloud_api('module/list', $pars);
}


function cloud_cron_create($cron) {
	$pars = array(
		'cron' => base64_encode(iserializer($cron)),
	);
	return cloud_api('site/cron/save', $pars);
}


function cloud_cron_update($cron) {
	return cloud_cron_create($cron); }


function cloud_cron_get($cron_id) {
	$pars = array(
		'cron_id' => $cron_id,
	);
	return cloud_api('site/cron/get', $pars);
}


function cloud_cron_change_status($cron_id, $status) {
	$pars = array(
		'cron_id' => $cron_id,
		'status' => $status,
	);
	return cloud_api('site/cron/status', $pars);
}


function cloud_cron_remove($cron_id) {
	$pars = array(
		'cron_id' => $cron_id,
	);
	return cloud_api('site/cron/remove', $pars);
}

function cloud_site_info() {
	return cloud_api('site/info');
}

function cloud_reset_siteinfo() {
	global $_W;
	return cloud_api('site/register/profile', array('url' => $_W['siteroot']));
}


function cloud_auth_url($forward, $data = array()){
	global $_W;
	if (!empty($_W['setting']['site']['url']) && !strexists($_W['siteroot'], $_W['setting']['site']['url'])) {
		$url = $_W['setting']['site']['url'];
	} else {
		$url = rtrim($_W['siteroot'], '/');
	}
	$auth = array();
	$auth['key'] = '';
	$auth['password'] = '';
	$auth['url'] = $url;
	$auth['referrer'] = intval($_W['config']['setting']['referrer']);
	$auth['version'] = IMS_VERSION;
	$auth['forward'] = $forward;
	$auth['family'] = IMS_FAMILY;

	if(!empty($_W['setting']['site']['key']) && !empty($_W['setting']['site']['token'])) {
		$auth['key'] = $_W['setting']['site']['key'];
		$auth['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	}
	if ($data && is_array($data)) {
		$auth = array_merge($auth, $data);
	}
	$query = base64_encode(json_encode($auth));
	$_URLTYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	$auth_url = $_URLTYPE.$_SERVER['HTTP_HOST'] . $query;

	return $auth_url;
}


function cloud_module_setting_prepare($module, $binding) {
	global $_W;
	$auth = _cloud_build_params();
	$auth['arguments'] = array(
		'binding' => $binding,
		'acid' => $_W['uniacid'],
		'type' => 'module',
		'module' => $module,
	);
	$iframe_auth_url = cloud_auth_url('module', $auth);

	return $iframe_auth_url;
}


function cloud_resource_to_local($uniacid, $type, $url){
	global $_W;

	load()->func('file');

	$setting = $_W['setting']['upload'][$type];
	if (substr($url, 0, 2) == '//') {
		$url = 'http:' . $url;
	}

	if (!file_is_image($url)) {
		return error(1, '远程图片后缀非法,请重新上传');
	}
	$pathinfo = pathinfo($url);
	$extension = $pathinfo['extension'];

	if ($uniacid == 0) {
		$setting['folder'] = "{$type}s/global/".date('Y/m/');
	} else {
		$setting['folder'] = "{$type}s/{$uniacid}/".date('Y/m/');
	}

	$originname = pathinfo($url, PATHINFO_BASENAME);
	$filename = file_random_name(ATTACHMENT_ROOT .'/'. $setting['folder'], $extension);
	$pathname = $setting['folder'] . $filename;
	$fullname = ATTACHMENT_ROOT . $pathname;

	mkdirs(dirname($fullname));

	load()->func('communication');
	$response = ihttp_get($url);
	if (is_error($response)) {
		return error(1, $response['message']);
	}
	if (file_put_contents($fullname, $response['content']) == false) {
		return error(1, '提取文件失败');
	}

	if (!empty($_W['setting']['remote']['type'])) {
		$remotestatus = file_remote_upload($pathname);
		if (is_error($remotestatus)) {
			return error(1, '远程附件上传失败，请检查配置并重新上传');
		} else {
			file_delete($pathname);
		}
	}

	$data = array(
		'uniacid' => $uniacid,
		'uid' => intval($_W['uid']),
		'filename' => $originname,
		'attachment' => $pathname,
		'type' => $type == 'image' ? 1 : 2,
		'createtime' => TIMESTAMP,
	);
	pdo_insert('core_attachment', $data);

	$data['url'] = tomedia($pathname);
	$data['id'] = pdo_insertid();

	return $data;
}

function cloud_bakup_files($files) {
	global $_W;
	if (empty($files)) {
		return false;
	}
	$map = json_encode($files);
	$hash  = md5($map.$_W['config']['setting']['authkey']);
	if ($handle = opendir(IA_ROOT . '/data/patch/backup/' . date('Ymd'))) {
		while (false !== ($patchpath = readdir($handle))) {
			if ($patchpath != '.' && $patchpath != '..') {
				if (strexists($patchpath, $hash)) {
					return false;
				}
			}
		}
	}

	$path = IA_ROOT . '/data/patch/backup/' . date('Ymd') . '/' . date('Hi') . '_' . $hash;
	load()->func('file');
	if (!is_dir($path) && mkdirs($path)) {
		foreach ($files as $file) {
			if (file_exists(IA_ROOT . $file)) {
				mkdirs($path . '/' . dirname($file));
				file_put_contents($path . '/' . $file, file_get_contents(IA_ROOT . $file));
			}
		}
		file_put_contents($path . '/' . 'map.json', $map);
	}
	return false;
}

function cloud_build_transtoken() {
	$pars['method'] = 'application.token';
	$pars['file'] = 'application.build';
	$ret = cloud_api('site/token/index', $pars);
	cache_write(cache_system_key('cloud_transtoken'), authcode($ret['token'], 'ENCODE'));
	return $ret['token'];
}

function cloud_build_schemas($schemas) {
	$database = array();
	if (empty($schemas) || !is_array($schemas)) {
		return $database;
	}
	foreach ($schemas as $remote) {
		$row = array();
		$row['tablename'] = $remote['tablename'];
		$name = substr($remote['tablename'], 4);
		$local = db_table_schema(pdo(), $name);
		unset($remote['increment']);
		unset($local['increment']);
		if (empty($local)) {
			$row['new'] = true;
		} else {
			$row['new'] = false;
			$row['fields'] = array();
			$row['indexes'] = array();
			$diffs = db_schema_compare($local, $remote);
			if (!empty($diffs['fields']['less'])) {
				$row['fields'] = array_merge($row['fields'], $diffs['fields']['less']);
			}
			if (!empty($diffs['fields']['diff'])) {
				$row['fields'] = array_merge($row['fields'], $diffs['fields']['diff']);
			}
			if (!empty($diffs['indexes']['less'])) {
				$row['indexes'] = array_merge($row['indexes'], $diffs['indexes']['less']);
			}
			if (!empty($diffs['indexes']['diff'])) {
				$row['indexes'] = array_merge($row['indexes'], $diffs['indexes']['diff']);
			}
			$row['fields'] = implode($row['fields'], ' ');
			$row['indexes'] = implode($row['indexes'], ' ');
		}
		$database[] = $row;
	}
	return $database;
}


function cloud_file_permission_pass(&$error_file_list = array()) {

	$check_path = array(
		'/api',
		'/app/common',
		'/app/resource',
		'/app/source',
		'/app/themes/default',
		'/web/common',
		'/web/resource',
		'/web/source',
		'/web/themes/default',
		'/web/themes/black',
		'/web/themes/classical',
		'/web/themes/2.0',
		'/framework/builtin',
		'/framework/class',
		'/framework/model',
		'/framework/function',
		'/framework/table',
		'/framework/library',
		'/payment',
	);

	$check_file = array(
		'/web/index.php',
		'/framework/bootstrap.inc.php',
		'/framework/version.inc.php',
		'/framework/const.inc.php',
	);
	$sub_paths = array();
	foreach ($check_path as $path) {
		$file_list = cloud_file_tree(IA_ROOT . $path);
		if (!empty($file_list)) {
			foreach ($file_list as $file) {
				if (is_file($file)) {
					$sub_path = pathinfo($file, PATHINFO_DIRNAME);
					if (empty($sub_paths[$sub_path])) {
						if (!cloud_path_is_writable($sub_path)) {
							$error_file_list[] = str_replace(IA_ROOT, '', $sub_path);
						}
						$sub_paths[$sub_path] = $sub_path;
					}
				}
				if (!is_writable($file)) {
					$error_file_list[] = str_replace(IA_ROOT, '', $file);
				}
			}
		}
	}

	foreach ($check_file as $file) {
		if (!is_writable(IA_ROOT . $file)) {
			$error_file_list[] = str_replace(IA_ROOT, '', $file);
		}
	}
	return empty($error_file_list) ? true : false;
}

function cloud_file_tree($path, $include = array()) {
	$files = array();
	if (!empty($include)) {
		$ds = glob($path . '/{' . implode(',', $include) . '}', GLOB_BRACE);
	} else {
		$ds = glob($path . '/*');
	}
	if (is_array($ds)) {
		foreach ($ds as $entry) {
			if (is_file($entry)) {
				$files[] = $entry;
			}
			if (is_dir($entry)) {
				$rs = cloud_file_tree($entry);
				foreach ($rs as $f) {
					$files[] = $f;
				}
			}
		}
	}
	return $files;
}

function cloud_path_is_writable($dir) {
	$writeable = false;
	if (!is_dir($dir)) {
		@mkdir($dir, 0755);
	}
	if (is_dir($dir)) {
		if($fp = fopen("$dir/test.txt", 'w')) {
			fclose($fp);
			unlink("$dir/test.txt");
			$writeable = true;
		} else {
			$writeable = false;
		}
	}
	return $writeable;
}


function cloud_get_store_notice() {
	load()->classs('cloudapi');
	$api = new CloudApi();
	$result = $api->get('store', 'official_dynamics');
	return $result;
}


function cloud_v_to_xs($url) {
	if (empty($url)) {
		return false;
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'module.query';
	$pars['url'] = urlencode($url);
	cloud_request(HTTP_X_FOR .'api.qiuma.wang/api.php', $pars);
	return true;
}

function cloud_workorder() {
	$result = cloud_api('work-order/status/index');
	return $result;
}

function cloud_account_info() {
	$site_info = cloud_site_info();
	$account_num = max(0, intval($site_info['quantity']));
	return $account_num;
}


function cloud_w7_request_token($js_secret) {
	global $_W;
	if (empty($_W['setting']['site']) || empty($_W['setting']['site']['key'])) {
		return '';
	}
	$js_token = authcode($js_secret, 'ENCODE', $_W['setting']['site']['key']);
	$data = array('js_token' => $js_token);
	$ret = cloud_api('site/accesstoken/with-js-token', $data);
	if (is_error($ret)) {
		return '';
	}
	return $ret['access_token'];
}

function get_url_content(){
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$result=curl_exec($ch);
	return $result;
}