<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(0);


ob_start();
define('IA_ROOT', str_replace("\\",'/', dirname(__FILE__)));
define('APP_URL', 'http://v2.addons.we7.cc/web/');
define('APP_STORE_URL', 'http://v2.addons.we7.cc/web');
define('APP_STORE_API', 'http://v2.addons.we7.cc/api.php');
if($_GET['res']) {
	$res = $_GET['res'];
	$reses = tpl_resources();
	if(array_key_exists($res, $reses)) {
		if($res == 'css') {
			header('content-type:text/css');
		} else {
			header('content-type:image/png');
		}
		echo base64_decode($reses[$res]);
		exit();
	}
}

$actions = array('license', 'env', 'db', 'cloud', 'upgrade', 'finish');
$action = !empty($_GET['step']) ? $_GET['step'] : $_COOKIE['action'];
$action = in_array($action, $actions) ? $action : 'license';
$ispost = strtolower($_SERVER['REQUEST_METHOD']) == 'post';

if(file_exists(IA_ROOT . '/data/install.lock') && $action != 'finish' && $action != 'cloud' && $action != 'upgrade') {
	header('location: ./index.php');
	exit;
}
header('content-type: text/html; charset=utf-8');
if($action == 'license') {
	if($ispost) {
		setcookie('action', 'env');
		header('location: ?refresh');
		exit;
	}
	tpl_install_license();
}
if($action == 'env') {
	if($ispost) {
		setcookie('action', $_POST['do'] == 'continue' ? 'db' : 'license');
		header('location: ?refresh');
		exit;
	}
	$ret = array();
	$ret['server']['os']['value'] = php_uname();
	if(PHP_SHLIB_SUFFIX == 'dll') {
		$ret['server']['os']['remark'] = '建议使用 Linux 系统以提升程序性能';
		$ret['server']['os']['class'] = 'warning';
	}
	$ret['server']['sapi']['value'] = $_SERVER['SERVER_SOFTWARE'];
	if(PHP_SAPI == 'isapi') {
		$ret['server']['sapi']['remark'] = '建议使用 Apache 或 Nginx 以提升程序性能';
		$ret['server']['sapi']['class'] = 'warning';
	}
	$ret['server']['php']['value'] = PHP_VERSION;
	$ret['server']['dir']['value'] = IA_ROOT;
	if(function_exists('disk_free_space')) {
		$ret['server']['disk']['value'] = floor(disk_free_space(IA_ROOT) / (1024*1024)).'M';
	} else {
		$ret['server']['disk']['value'] = 'unknow';
	}
	$ret['server']['upload']['value'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow';

	$ret['php']['version']['value'] = PHP_VERSION;
	$ret['php']['version']['class'] = 'success';
	if(version_compare(PHP_VERSION, '5.3.0') == -1) {
		$ret['php']['version']['class'] = 'danger';
		$ret['php']['version']['failed'] = true;
		$ret['php']['version']['remark'] = 'PHP版本必须为 5.3.0 以上. <a href="http://bbs.we7.cc/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58062">详情</a>';
	}

	$ret['php']['pdo']['ok'] = extension_loaded('pdo') && extension_loaded('pdo_mysql');
	if($ret['php']['pdo']['ok']) {
		$ret['php']['pdo']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['pdo']['class'] = 'success';
	} else {
		$ret['php']['pdo']['failed'] = true;
		$ret['php']['pdo']['value'] = '<span class="glyphicon glyphicon-remove text-warning"></span>';
		$ret['php']['pdo']['class'] = 'warning';
		$ret['php']['pdo']['remark'] = '您的PHP环境不支持PDO, 请开启此扩展. <a target="_blank" href="http://bbs.we7.cc/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58074">详情</a>';
	}

	$ret['php']['fopen']['ok'] = @ini_get('allow_url_fopen') && function_exists('fsockopen');
	if($ret['php']['fopen']['ok']) {
		$ret['php']['fopen']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
	} else {
		$ret['php']['fopen']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
	}

	$ret['php']['curl']['ok'] = extension_loaded('curl') && function_exists('curl_init');
	if($ret['php']['curl']['ok']) {
		$ret['php']['curl']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['curl']['class'] = 'success';
	} else {
		$ret['php']['curl']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['curl']['class'] = 'danger';
		$ret['php']['curl']['remark'] = '您的PHP环境不支持cURL, 也不支持 allow_url_fopen, 系统无法正常运行. <a target="_blank" href="http://bbs.we7.cc/thread-26119-1-1.html">详情</a>';
		$ret['php']['curl']['failed'] = true;
	}

	$ret['php']['ssl']['ok'] = extension_loaded('openssl');
	if($ret['php']['ssl']['ok']) {
		$ret['php']['ssl']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['ssl']['class'] = 'success';
	} else {
		$ret['php']['ssl']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['ssl']['class'] = 'danger';
		$ret['php']['ssl']['failed'] = true;
		$ret['php']['ssl']['remark'] = '没有启用OpenSSL, 将无法访问公众平台的接口, 系统无法正常运行. <a target="_blank" href="http://bbs.we7.cc/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58109">详情</a>';
	}

	$ret['php']['gd']['ok'] = extension_loaded('gd');
	if($ret['php']['gd']['ok']) {
		$ret['php']['gd']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['gd']['class'] = 'success';
	} else {
		$ret['php']['gd']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['gd']['class'] = 'danger';
		$ret['php']['gd']['failed'] = true;
		$ret['php']['gd']['remark'] = '没有启用GD, 将无法正常上传和压缩图片, 系统无法正常运行. <a target="_blank" href="http://bbs.we7.cc/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58110">详情</a>';
	}

	$ret['php']['dom']['ok'] = class_exists('DOMDocument');
	if($ret['php']['dom']['ok']) {
		$ret['php']['dom']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['dom']['class'] = 'success';
	} else {
		$ret['php']['dom']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['dom']['class'] = 'danger';
		$ret['php']['dom']['failed'] = true;
		$ret['php']['dom']['remark'] = '没有启用DOMDocument, 将无法正常安装使用模块, 系统无法正常运行. <a target="_blank" href="http://bbs.we7.cc/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58111">详情</a>';
	}

	$ret['php']['session']['ok'] = ini_get('session.auto_start');
	if($ret['php']['session']['ok'] == 0 || strtolower($ret['php']['session']['ok']) == 'off') {
		$ret['php']['session']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['session']['class'] = 'success';
	} else {
		$ret['php']['session']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['session']['class'] = 'danger';
		$ret['php']['session']['failed'] = true;
		$ret['php']['session']['remark'] = '系统session.auto_start开启, 将无法正常注册会员, 系统无法正常运行. <a target="_blank" href="http://bbs.we7.cc/forum.php?mod=redirect&goto=findpost&ptid=3564&pid=58111">详情</a>';
	}

	$ret['php']['asp_tags']['ok'] = ini_get('asp_tags');
	if(empty($ret['php']['asp_tags']['ok']) || strtolower($ret['php']['asp_tags']['ok']) == 'off') {
		$ret['php']['asp_tags']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['php']['asp_tags']['class'] = 'success';
	} else {
		$ret['php']['asp_tags']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['php']['asp_tags']['class'] = 'danger';
		$ret['php']['asp_tags']['failed'] = true;
		$ret['php']['asp_tags']['remark'] = '请禁用可以使用ASP 风格的标志，配置php.ini中asp_tags = Off';
	}

	$ret['write']['root']['ok'] = local_writeable(IA_ROOT . '/');
	if($ret['write']['root']['ok']) {
		$ret['write']['root']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['write']['root']['class'] = 'success';
	} else {
		$ret['write']['root']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['write']['root']['class'] = 'danger';
		$ret['write']['root']['failed'] = true;
		$ret['write']['root']['remark'] = '本地目录无法写入, 将无法使用自动更新功能, 系统无法正常运行.  <a href="http://bbs.we7.cc/">详情</a>';
	}
	$ret['write']['data']['ok'] = local_writeable(IA_ROOT . '/data');
	if($ret['write']['data']['ok']) {
		$ret['write']['data']['value'] = '<span class="glyphicon glyphicon-ok text-success"></span>';
		$ret['write']['data']['class'] = 'success';
	} else {
		$ret['write']['data']['value'] = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		$ret['write']['data']['class'] = 'danger';
		$ret['write']['data']['failed'] = true;
		$ret['write']['data']['remark'] = 'data目录无法写入, 将无法写入配置文件, 系统无法正常安装. ';
	}

	$ret['continue'] = true;
	foreach($ret['php'] as $opt) {
		if($opt['failed']) {
			$ret['continue'] = false;
			break;
		}
	}
	if($ret['write']['failed']) {
		$ret['continue'] = false;
	}
	tpl_install_env($ret);
}
if($action == 'db') {
	if($ispost) {
		if($_POST['do'] != 'continue') {
			setcookie('action', 'env');
			header('location: ?refresh');
			exit();
		}
		$family = $_POST['family'] == 'x' ? 'x' : 'v';
		$db = $_POST['db'];
		$user = $_POST['user'];
		try {
			$pieces = explode(':', $db['server']);
			$db['server'] = $pieces[0];
			$db['port'] = !empty($pieces[1]) ? $pieces[1] : '3306';
			$link = new PDO("mysql:host={$db['server']};port={$db['port']}", $db['username'], $db['password']); 	// dns可以没有dbname
			$link->exec("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");
			$link->exec("SET sql_mode=''");
			if ($link->errorCode() != '00000') {
				$errorInfo = $link->errorInfo();
				$error = $errorInfo[2];
			} else {
				$statement = $link->query("SHOW DATABASES LIKE '{$db['name']}';");
				$fetch = $statement->fetch();
				if (empty($fetch)){
					if (substr($link->getAttribute(PDO::ATTR_SERVER_VERSION), 0, 3) > '4.1') {
						$link->query("CREATE DATABASE IF NOT EXISTS `{$db['name']}` DEFAULT CHARACTER SET utf8");
					} else {
						$link->query("CREATE DATABASE IF NOT EXISTS `{$db['name']}`");
					}
				}
				$statement = $link->query("SHOW DATABASES LIKE '{$db['name']}';");
				$fetch = $statement->fetch();
				if (empty($fetch)) {
					$error .= "数据库不存在且创建数据库失败. <br />";
				}
				if ($link->errorCode() != '00000') {
					$errorInfo = $link->errorInfo();
					$error .= $errorInfo[2];
				}
			}
		} catch (PDOException $e) {
			$error = $e->getMessage();
			if (strpos($error, 'Access denied for user') !== false) {
				$error = '您的数据库访问用户名或是密码错误. <br />';
			} else {
				$error = iconv('gbk', 'utf8', $error);
			}
		}
		if(empty($error)) {
			$link->exec("USE {$db['name']}");
			$statement = $link->query("SHOW TABLES LIKE '{$db['prefix']}%';");
			if ($statement->fetch()) {
				$error = '您的数据库不为空，请重新建立数据库或是清空该数据库或更改表前缀！';
			}
		}
		if(empty($error)) {
			$config = local_config();
			$cookiepre = local_salt(4) . '_';
			$authkey = local_salt(8);
			$config = str_replace(array(
				'{db-server}', '{db-username}', '{db-password}', '{db-port}', '{db-name}', '{db-tablepre}', '{cookiepre}', '{authkey}', '{attachdir}'
			), array(
				$db['server'], $db['username'], $db['password'], $db['port'], $db['name'], $db['prefix'], $cookiepre, $authkey, 'attachment'
			), $config);
			$verfile = IA_ROOT . '/framework/version.inc.php';
			$dbfile = IA_ROOT . '/data/db.php';

			if($_POST['type'] == 'remote') {
				$link = NULL;
				$ins = remote_install();
				if(empty($ins)) {
					die('<script type="text/javascript">alert("连接不到服务器, 请稍后重试！");history.back();</script>');
				}
				if($ins == 'error') {
					die('<script type="text/javascript">alert("版本错误，请确认是否为微擎最新版安装文件！");history.back();</script>');
				}

				$link = new PDO("mysql:dbname={$db['name']};host={$db['server']};port={$db['port']}", $db['username'], $db['password']);
				$link->exec("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");
				$link->exec("SET sql_mode=''");

				$tmpfile = IA_ROOT . '/we7source.tmp';
				file_put_contents($tmpfile, $ins);

				$zip = new ZipArchive;
				$res = $zip->open($tmpfile);

				if ($res === TRUE) {
					$zip->extractTo(IA_ROOT);
					$zip->close();
				} else {
					die('<script type="text/javascript">alert("安装失败，请确认当前目录是否有写入权限！");history.back();</script>');
				}
				unlink($tmpfile);
			}

			if(file_exists(IA_ROOT . '/index.php') && is_dir(IA_ROOT . '/web') && file_exists($verfile) && file_exists($dbfile)) {
				$dat = require $dbfile;
				if(empty($dat) || !is_array($dat)) {
					die('<script type="text/javascript">alert("安装包不正确, 数据安装脚本缺失.");history.back();</script>');
				}
				foreach($dat['schemas'] as $schema) {
					$sql = local_create_sql($schema);
					local_run($sql);
				}
				foreach($dat['datas'] as $data) {
					local_run($data);
				}
			} else {
				die('<script type="text/javascript">alert("你正在使用本地安装, 但未下载完整安装包, 请从微擎官网下载完整安装包后重试.");history.back();</script>');
			}

			$salt = local_salt(8);
			$password = sha1("{$user['password']}-{$salt}-{$authkey}");
			$link->exec("INSERT INTO {$db['prefix']}users (username, password, salt, joindate, groupid) VALUES('{$user['username']}', '{$password}', '{$salt}', '" . time() . "', 1)");
			local_mkdirs(IA_ROOT . '/data');
			file_put_contents(IA_ROOT . '/data/config.php', $config);
			touch(IA_ROOT . '/data/install.lock');
			setcookie('action', 'finish');
			header('location: ?refresh');
			exit();
		}
	}
	tpl_install_db($error);

}
if($action == 'finish') {
	setcookie('action', '', -10);
	$dbfile = IA_ROOT . '/data/db.php';
	@unlink($dbfile);
	define('IN_SYS', true);
	require IA_ROOT . '/framework/bootstrap.inc.php';
	require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
	$_W['uid'] = $_W['isfounder'] = 1;
	load()->web('common');
	load()->web('template');
	load()->model('setting');
	load()->model('cache');

	cache_build_frame_menu();
	cache_build_setting();
	cache_build_users_struct();
	cache_build_module_subscribe_type();
	tpl_install_finish();
}

function local_writeable($dir) {
	$writeable = 0;
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = fopen("$dir/test.txt", 'w')) {
			fclose($fp);
			unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function local_salt($length = 8) {
	$result = '';
	while(strlen($result) < $length) {
		$result .= sha1(uniqid('', true));
	}
	return substr($result, 0, $length);
}

function local_config() {
	$cfg = <<<EOF
<?php
defined('IN_IA') or exit('Access Denied');

\$config = array();

\$config['db']['master']['host'] = '{db-server}';
\$config['db']['master']['username'] = '{db-username}';
\$config['db']['master']['password'] = '{db-password}';
\$config['db']['master']['port'] = '{db-port}';
\$config['db']['master']['database'] = '{db-name}';
\$config['db']['master']['charset'] = 'utf8';
\$config['db']['master']['pconnect'] = 0;
\$config['db']['master']['tablepre'] = '{db-tablepre}';

\$config['db']['slave_status'] = false;
\$config['db']['slave']['1']['host'] = '';
\$config['db']['slave']['1']['username'] = '';
\$config['db']['slave']['1']['password'] = '';
\$config['db']['slave']['1']['port'] = '3307';
\$config['db']['slave']['1']['database'] = '';
\$config['db']['slave']['1']['charset'] = 'utf8';
\$config['db']['slave']['1']['pconnect'] = 0;
\$config['db']['slave']['1']['tablepre'] = 'ims_';
\$config['db']['slave']['1']['weight'] = 0;

\$config['db']['common']['slave_except_table'] = array('core_sessions');

// --------------------------  CONFIG COOKIE  --------------------------- //
\$config['cookie']['pre'] = '{cookiepre}';
\$config['cookie']['domain'] = '';
\$config['cookie']['path'] = '/';

// --------------------------  CONFIG SETTING  --------------------------- //
\$config['setting']['charset'] = 'utf-8';
\$config['setting']['cache'] = 'mysql';
\$config['setting']['timezone'] = 'Asia/Shanghai';
\$config['setting']['memory_limit'] = '256M';
\$config['setting']['filemode'] = 0644;
\$config['setting']['authkey'] = '{authkey}';
\$config['setting']['founder'] = '1';
\$config['setting']['development'] = 0;
\$config['setting']['referrer'] = 0;

// --------------------------  CONFIG UPLOAD  --------------------------- //
\$config['upload']['image']['extentions'] = array('gif', 'jpg', 'jpeg', 'png');
\$config['upload']['image']['limit'] = 5000;
\$config['upload']['attachdir'] = '{attachdir}';
\$config['upload']['audio']['extentions'] = array('mp3');
\$config['upload']['audio']['limit'] = 5000;

// --------------------------  CONFIG MEMCACHE  --------------------------- //
\$config['setting']['memcache']['server'] = '';
\$config['setting']['memcache']['port'] = 11211;
\$config['setting']['memcache']['pconnect'] = 1;
\$config['setting']['memcache']['timeout'] = 30;
\$config['setting']['memcache']['session'] = 1;

// --------------------------  CONFIG PROXY  --------------------------- //
\$config['setting']['proxy']['host'] = '';
\$config['setting']['proxy']['auth'] = '';
EOF;
	return trim($cfg);
}

function local_mkdirs($path) {
	if(!is_dir($path)) {
		local_mkdirs(dirname($path));
		mkdir($path);
	}
	return is_dir($path);
}

function local_run($sql) {
	global $link, $db;

	if(!isset($sql) || empty($sql)) return;

	$sql = str_replace("\r", "\n", str_replace(' ims_', ' '.$db['prefix'], $sql));
	$sql = str_replace("\r", "\n", str_replace(' `ims_', ' `'.$db['prefix'], $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			$link->exec($query);
			if($link->errorCode() != '00000') {
				$errorInfo = $link->errorInfo();
				echo $errorInfo[0] . ": " . $errorInfo[2] . "<br />";
				exit($query);
			}
		}
	}
}

function local_create_sql($schema) {
	$pieces = explode('_', $schema['charset']);
	$charset = $pieces[0];
	$engine = $schema['engine'];
	$sql = "CREATE TABLE IF NOT EXISTS `{$schema['tablename']}` (\n";
	foreach ($schema['fields'] as $value) {
		if(!empty($value['length'])) {
			$length = "({$value['length']})";
		} else {
			$length = '';
		}

		$signed  = empty($value['signed']) ? ' unsigned' : '';
		if(empty($value['null'])) {
			$null = ' NOT NULL';
		} else {
			$null = '';
		}
		if(isset($value['default'])) {
			$default = " DEFAULT '" . $value['default'] . "'";
		} else {
			$default = '';
		}
		if($value['increment']) {
			$increment = ' AUTO_INCREMENT';
		} else {
			$increment = '';
		}

		$sql .= "`{$value['name']}` {$value['type']}{$length}{$signed}{$null}{$default}{$increment},\n";
	}
	foreach ($schema['indexes'] as $value) {
		$fields = implode('`,`', $value['fields']);
		if($value['type'] == 'index') {
			$sql .= "KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'unique') {
			$sql .= "UNIQUE KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'primary') {
			$sql .= "PRIMARY KEY (`{$fields}`),\n";
		}
	}
	$sql = rtrim($sql);
	$sql = rtrim($sql, ',');

	$sql .= "\n) ENGINE=$engine DEFAULT CHARSET=$charset;\n\n";
	return $sql;
}

function remote_install() {
	global $family;
	$token = '';
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['version'] = '1.0';
	$pars['type'] = 'install';
	$pars['method'] = 'application.install';
	$url = 'http://v2.addons.we7.cc/gateway.php';
	$urlset = parse_url($url);
	$cloudip = gethostbyname($urlset['host']);
	$headers[] = "Host: {$urlset['host']}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlset['scheme'] . '://' . $cloudip . $urlset['path']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pars, '', '&'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$content = curl_exec($ch);
	curl_close($ch);

	if (empty($content)) {
		return showerror(-1, '获取安装信息失败，可能是由于网络不稳定，请重试。');
	}

	return $content;
}

function tpl_frame() {
	global $action, $actions;
	$action = $_COOKIE['action'];
	$step = array_search($action, $actions);
	$steps = array();
	for($i = 0; $i <= $step; $i++) {
		if($i == $step) {
			$steps[$i] = ' list-group-item-info';
		} else {
			$steps[$i] = ' list-group-item-success';
		}
	}
	$progress = $step * 20 + 20;
	$content = ob_get_contents();
	ob_clean();
	$tpl = <<<EOF
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>安装系统 - 微擎 - 公众平台自助开源引擎</title>
		<link rel="stylesheet" href="//cdn.w7.cc/web/resource/css/wechat/bootstrap.css">
		<style>
			html,body{font-size:13px;font-family:"Microsoft YaHei UI", "微软雅黑", "宋体";}
			.pager li.previous a{margin-right:10px;}
			.header a{color:#FFF;}
			.header a:hover{color:#428bca;}
			.footer{padding:10px;}
			.footer a,.footer{color:#eee;font-size:14px;line-height:25px;}
		</style>
		<!--[if lt IE 9]>
		  <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body style="background-color:#28b0e4;">
		<div class="container" style="width:1200px;">
			<div class="header" style="margin:15px auto;">
				<ul class="nav nav-pills pull-right" role="tablist">
					<li role="presentation" class="active"><a href="javascript:;">安装微擎系统</a></li>
				</ul>
				<img src="?res=logo" />
			</div>
			<div class="row well" style="margin:auto 0;">
				<div class="col-xs-2" style="padding:0; width:14%;">
					<div class="progress" title="安装进度">
						<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="{$progress}" aria-valuemin="0" aria-valuemax="100" style="width: {$progress}%;">
							{$progress}%
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							安装步骤
						</div>
						<ul class="list-group">
							<a href="javascript:;" class="list-group-item{$steps[0]}"><span class="glyphicon glyphicon-copyright-mark"></span> &nbsp; 许可协议</a>
							<a href="javascript:;" class="list-group-item{$steps[1]}"><span class="glyphicon glyphicon-eye-open"></span> &nbsp; 环境监测</a>
							<a href="javascript:;" class="list-group-item{$steps[2]}"><span class="glyphicon glyphicon-cog"></span> &nbsp; 参数配置</a>
							<a href="javascript:;" class="list-group-item{$steps[3]}"><span class="glyphicon glyphicon-ok"></span> &nbsp; 成功</a>
						</ul>
					</div>
				</div>
				<div class="col-xs-10">
					{$content}
				</div>
			</div>
			<div class="footer" style="margin:15px auto;">
				<div class="text-center">
					<a href="http://www.we7.cc">关于微擎</a> &nbsp; &nbsp; <a href="http://bbs.we7.cc">微擎帮助</a> &nbsp; &nbsp; <a href="http://www.we7.cc">购买授权</a>
				</div>
				<div class="text-center">
					Powered by <a href="http://www.we7.cc"><b>微擎</b></a> v0.8 &copy; 2014 <a href="http://www.we7.cc">www.we7.cc</a>
				</div>
			</div>
		</div>
		<script src="//cdn.w7.cc/web/resource/js/lib/jquery-1.11.1.min.js"></script>
		<script src="//cdn.w7.cc/web/resource/js/lib/bootstrap.min.js"></script>
	</body>
</html>
EOF;
	echo trim($tpl);
}

function tpl_install_license() {
	echo <<<EOF
		<div class="panel panel-default">
			<div class="panel-heading">阅读许可协议</div>
			<div class="panel-body" style="overflow-y:scroll;max-height:400px;line-height:20px;">
				<h3>版权所有 (c)2014，微擎团队保留所有权利。 </h3>
				<p>
					感谢您选择微擎 - 微信公众平台自助开源引擎（以下简称WE7，WE7基于 PHP + MySQL的技术开发，全部源码开放。 <br />
					为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：
				</p>
				<p>
					<strong>一、本授权协议适用且仅适用于微擎系统(We7, MicroEngine. 以下简称微擎)任何版本，微擎官方对本授权协议的最终解释权。</strong>
				</p>
				<p>
					<strong>二、协议许可的权利 </strong>
					<ol>
						<li>您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。</li>
						<li>您可以在协议规定的约束和限制范围内修改微擎源代码或界面风格以适应您的网站要求。</li>
						<li>您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。</li>
						<li>获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。</li>
					</ol>
				</p>
				<p>
					<strong>三、协议规定的约束和限制 </strong>
					<ol>
						<li>未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目的或实现盈利的网站）。</li>
						<li>未经官方许可，不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。</li>
						<li>未经官方许可，禁止在微擎的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
						<li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
					</ol>
				</p>
				<p>
					<strong>四、有限担保和免责声明 </strong>
					<ol>
						<li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
						<li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
						<li>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装  WE7，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</li>
						<li>如果本软件带有其它软件的整合API示范例子包，这些文件版权不属于本软件官方，并且这些文件是没经过授权发布的，请参考相关软件的使用许可合法的使用。</li>
					</ol>
				</p>
			</div>
		</div>
		<form class="form-inline" role="form" method="post">
			<ul class="pager">
				<li class="pull-left" style="display:block;padding:5px 10px 5px 0;">
					<div class="checkbox">
						<label>
							<input type="checkbox"> 我已经阅读并同意此协议
						</label>
					</div>
				</li>
				<li class="previous"><a href="javascript:;" onclick="if(jQuery(':checkbox:checked').length == 1){jQuery('form')[0].submit();}else{alert('您必须同意软件许可协议才能安装！')};">继续 <span class="glyphicon glyphicon-chevron-right"></span></a></li>
			</ul>
		</form>
EOF;
	tpl_frame();
}

function tpl_install_env($ret = array()) {
	if(empty($ret['continue'])) {
		$continue = '<li class="previous disabled"><a href="javascript:;">请先解决环境问题后继续</a></li>';
	} else {
		$continue = '<li class="previous"><a href="javascript:;" onclick="$(\'#do\').val(\'continue\');$(\'form\')[0].submit();">继续 <span class="glyphicon glyphicon-chevron-right"></span></a></li>';
	}
	echo <<<EOF
		<div class="panel panel-default">
			<div class="panel-heading">服务器信息</div>
			<table class="table table-striped">
				<tr>
					<th style="width:150px;">参数</th>
					<th>值</th>
					<th></th>
				</tr>
				<tr class="{$ret['server']['os']['class']}">
					<td>服务器操作系统</td>
					<td>{$ret['server']['os']['value']}</td>
					<td>{$ret['server']['os']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['sapi']['class']}">
					<td>Web服务器环境</td>
					<td>{$ret['server']['sapi']['value']}</td>
					<td>{$ret['server']['sapi']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['php']['class']}">
					<td>PHP版本</td>
					<td>{$ret['server']['php']['value']}</td>
					<td>{$ret['server']['php']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['dir']['class']}">
					<td>程序安装目录</td>
					<td>{$ret['server']['dir']['value']}</td>
					<td>{$ret['server']['dir']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['disk']['class']}">
					<td>磁盘空间</td>
					<td>{$ret['server']['disk']['value']}</td>
					<td>{$ret['server']['disk']['remark']}</td>
				</tr>
				<tr class="{$ret['server']['upload']['class']}">
					<td>上传限制</td>
					<td>{$ret['server']['upload']['value']}</td>
					<td>{$ret['server']['upload']['remark']}</td>
				</tr>
			</table>
		</div>

		<div class="alert alert-info">PHP环境要求必须满足下列所有条件，否则系统或系统部份功能将无法使用。</div>
		<div class="panel panel-default">
			<div class="panel-heading">PHP环境要求</div>
			<table class="table table-striped">
				<tr>
					<th style="width:150px;">选项</th>
					<th style="width:180px;">要求</th>
					<th style="width:50px;">状态</th>
					<th>说明及帮助</th>
				</tr>
				<tr class="{$ret['php']['version']['class']}">
					<td>PHP版本</td>
					<td>5.3或者5.3以上</td>
					<td>{$ret['php']['version']['value']}</td>
					<td>{$ret['php']['version']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['curl']['class']}">
					<td>cURL</td>
					<td>支持</td>
					<td>{$ret['php']['curl']['value']}</td>
					<td>{$ret['php']['curl']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['pdo']['class']}">
					<td>PDO</td>
					<td>支持</td>
					<td>{$ret['php']['pdo']['value']}</td>
					<td>{$ret['php']['pdo']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['ssl']['class']}">
					<td>openSSL</td>
					<td>支持</td>
					<td>{$ret['php']['ssl']['value']}</td>
					<td>{$ret['php']['ssl']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['gd']['class']}">
					<td>GD2</td>
					<td>支持</td>
					<td>{$ret['php']['gd']['value']}</td>
					<td>{$ret['php']['gd']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['dom']['class']}">
					<td>DOM</td>
					<td>支持</td>
					<td>{$ret['php']['dom']['value']}</td>
					<td>{$ret['php']['dom']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['session']['class']}">
					<td>session.auto_start</td>
					<td>关闭</td>
					<td>{$ret['php']['session']['value']}</td>
					<td>{$ret['php']['session']['remark']}</td>
				</tr>
				<tr class="{$ret['php']['asp_tags']['class']}">
					<td>asp_tags</td>
					<td>关闭</td>
					<td>{$ret['php']['asp_tags']['value']}</td>
					<td>{$ret['php']['asp_tags']['remark']}</td>
				</tr>
			</table>
		</div>

		<div class="alert alert-info">系统要求微擎整个安装目录必须可写, 才能使用微擎所有功能。</div>
		<div class="panel panel-default">
			<div class="panel-heading">目录权限监测</div>
			<table class="table table-striped">
				<tr>
					<th style="width:150px;">目录</th>
					<th style="width:180px;">要求</th>
					<th style="width:50px;">状态</th>
					<th>说明及帮助</th>
				</tr>
				<tr class="{$ret['write']['root']['class']}">
					<td>/</td>
					<td>整目录可写</td>
					<td>{$ret['write']['root']['value']}</td>
					<td>{$ret['write']['root']['remark']}</td>
				</tr>
				<tr class="{$ret['write']['data']['class']}">
					<td>/</td>
					<td>data目录可写</td>
					<td>{$ret['write']['data']['value']}</td>
					<td>{$ret['write']['data']['remark']}</td>
				</tr>
			</table>
		</div>
		<form class="form-inline" role="form" method="post">
			<input type="hidden" name="do" id="do" />
			<ul class="pager">
				<li class="previous"><a href="javascript:;" onclick="$('#do').val('back');$('form')[0].submit();"><span class="glyphicon glyphicon-chevron-left"></span> 返回</a></li>
				{$continue}
			</ul>
		</form>
EOF;
	tpl_frame();
}

function tpl_install_db($error = '') {
	if(!empty($error)) {
		$message = '<div class="alert alert-danger">发生错误: ' . $error . '</div>';
	}
	$insTypes = array();
	if(file_exists(IA_ROOT . '/index.php') && is_dir(IA_ROOT . '/app') && is_dir(IA_ROOT . '/web')) {
		$insTypes['local'] = ' checked="checked"';
	} else {
		$insTypes['remote'] = ' checked="checked"';
	}
	if (!empty($_POST['type'])) {
		$insTypes = array();
		$insTypes[$_POST['type']] = ' checked="checked"';
	}
	$disabled = empty($insTypes['local']) ? ' disabled="disabled"' : '';
	echo <<<EOF
	{$message}
	<form class="form-horizontal" method="post" role="form">
		<div class="panel panel-default">
			<div class="panel-heading">安装选项</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">安装方式</label>
					<div class="col-sm-10">
						<label class="radio-inline">
							<input type="radio" name="type" value="local"{$insTypes['local']}{$disabled}> 离线安装
						</label>
						
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">数据库选项</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库主机</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[server]" value="127.0.0.1">
						<a style="color:red;" >特别注意：<br>如果是win系统，请填"127.0.0.1"，<br>如果是linux系统，请填"localhost"</a>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库用户</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[username]" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库密码</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[password]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">表前缀</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[prefix]" value="ims_" readonly="readonly" onclick="javascript:alert('禁止修改表前缀！')">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">数据库名称</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="db[name]" value="">
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">管理选项</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">管理员账号</label>
					<div class="col-sm-4">
						<input class="form-control" type="username" name="user[username]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">管理员密码</label>
					<div class="col-sm-4">
						<input class="form-control" type="password" name="user[password]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">确认密码</label>
					<div class="col-sm-4">
						<input class="form-control" type="password"">
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="do" id="do" />
		<ul class="pager">
			<li class="previous"><a href="javascript:;" onclick="$('#do').val('back');$('form')[0].submit();"><span class="glyphicon glyphicon-chevron-left"></span> 返回</a></li>
			<li class="previous"><a href="javascript:;" onclick="if(check(this)){jQuery('#do').val('continue');if($('input[name=type]:checked').val() == 'remote'){alert('在线安装时，安装程序会下载精简版快速完成安装，完成后请务必注册云服务更新到完整版。')}$('form')[0].submit();}">继续 <span class="glyphicon glyphicon-chevron-right"></span></a></li>
		</ul>
	</form>
	<script>
		var lock = false;
		function check(obj) {
			if(lock) {
				return;
			}
			$('.form-control').parent().parent().removeClass('has-error');
			var error = false;
			$('.form-control').each(function(){
				if($(this).val() == '') {
					$(this).parent().parent().addClass('has-error');
					this.focus();
					error = true;
				}
			});
			if(error) {
				alert('请检查未填项');
				return false;
			}
			if($(':password').eq(0).val() != $(':password').eq(1).val()) {
				$(':password').parent().parent().addClass('has-error');
				alert('确认密码不正确.');
				return false;
			}
			lock = true;
			$(obj).parent().addClass('disabled');
			$(obj).html('正在执行安装');
			return true;
		}
	</script>
EOF;
	tpl_frame();
}

function tpl_install_finish() {
	$modules = get_store_module();
	$themes = get_store_theme();
	echo <<<EOF
	<div class="page-header"><h3>安装完成</h3></div>
	<div class="alert alert-success">
		恭喜您!已成功安装“微擎 - 公众平台自助开源引擎”系统，您现在可以: <a target="_blank" class="btn btn-success" href="./web/index.php">访问网站首页</a>
	</div>

EOF;
	tpl_frame();
}

function tpl_resources() {
	static $res = array(
		'logo' => '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',
	);
	return $res;
}

function showerror($errno, $message = '') {
	return array(
		'errno' => $errno,
		'error' => $message,
	);
}

function get_store_module() {
	load()->func('communication');
	$response = ihttp_request(APP_STORE_API, array('controller' => 'store', 'action' => 'api', 'do' => 'module'));
	$response = json_decode($response['content'], true);

	$modules = '';
	foreach ($response['message'] as $key => $module) {
		if ($key % 3 < 1) {
			$modules .= '</tr><tr>';
		}
		$module['detail_link'] = APP_STORE_URL . trim($module['detail_link'], '.');
		$modules .= '<td>';
		$modules .= '<div class="col-sm-4">';
		$modules .= '<a href="' . $module['detail_link'] . '" title="查看详情" target="_blank">';
		$modules .= '<img src="' . $module['logo']. '"' . ' width="50" height="50" ' . $module['title'] . '" /></a>';
		$modules .= '</div>';
		$modules .= '<div class="col-sm-8">';
		$modules .= '<p><a href="' . $module['detail_link'] .'" title="查看详情" target="_blank">' . $module['title'] . '</a></p>';
		$modules .= '<p>安装量：<span class="text-danger">' . $module['purchases'] . '</span></p>';
		$modules .= '</div>';
		$modules .= '</td>';
	}
	$modules = substr($modules, 5) . '</tr>';

	return $modules;
}

function get_store_theme() {
	load()->func('communication');
	$response = ihttp_request(APP_STORE_API, array('controller' => 'store', 'action' => 'api', 'do' => 'theme'));
	$response = json_decode($response['content'], true);

	$themes = '<tr><td colspan="' . count($response['message']) . '">';
	$themes .= '<div class="form-group">';
	foreach ($response['message'] as $key => $theme) {
		$theme['detail_link'] = APP_STORE_URL . trim($theme['detail_link'], '.');
		$themes .= '<div class="col-sm-2" style="padding-left: 7px;margin-right: 25px;">';
		$themes .= '<a href="' . $theme['detail_link'] .'" title="查看详情" target="_blank" /><img src="' . $theme['logo']. '" /></a>';
		$themes .= '<p></p><p class="text-right">';
		$themes .= '<a href="' . $theme['detail_link']. '" title="查看详情" target="_blank">'  . $theme['title'] . '</a></p>';
		$themes .= '</div>';
	}
	$themes .= '</div>';

	return $themes;
}
