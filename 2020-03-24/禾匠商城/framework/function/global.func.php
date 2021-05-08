<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function ver_compare($version1, $version2) {
	$version1 = str_replace('.', '', $version1);
	$version2 = str_replace('.', '', $version2);
	$oldLength = istrlen($version1);
	$newLength = istrlen($version2);
	if (is_numeric($version1) && is_numeric($version2)) {
		if ($oldLength > $newLength) {
			$version2 .= str_repeat('0', $oldLength - $newLength);
		}
		if ($newLength > $oldLength) {
			$version1 .= str_repeat('0', $newLength - $oldLength);
		}
		$version1 = intval($version1);
		$version2 = intval($version2);
	}

	return version_compare($version1, $version2);
}

function iget_headers($url, $format = 0) {
	$result = @get_headers($url, $format);
	if (empty($result)) {
		stream_context_set_default(array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
			),
		));
		$result = get_headers($url, $format);
	}

	return $result;
}

function igetimagesize($filename, $imageinfo = array()) {
	$result = @getimagesize($filename, $imageinfo);
	if (empty($result)) {
		$file_content = ihttp_request($filename);
		$content = $file_content['content'];
		$result = getimagesize('data://image/jpeg;base64,' . base64_encode($content), $imageinfo);
	}

	return $result;
}


function istripslashes($var) {
	if (is_array($var)) {
		foreach ($var as $key => $value) {
			$var[stripslashes($key)] = istripslashes($value);
		}
	} else {
		$var = stripslashes($var);
	}

	return $var;
}


function ihtmlspecialchars($var) {
	if (is_array($var)) {
		foreach ($var as $key => $value) {
			$var[htmlspecialchars($key)] = ihtmlspecialchars($value);
		}
	} else {
		$var = str_replace('&amp;', '&', htmlspecialchars($var, ENT_QUOTES));
	}

	return $var;
}


function isetcookie($key, $value, $expire = 0, $httponly = false) {
	global $_W;
	$expire = 0 != $expire ? (TIMESTAMP + $expire) : 0;
	$secure = 443 == $_SERVER['SERVER_PORT'] ? 1 : 0;

	return setcookie($_W['config']['cookie']['pre'] . $key, $value, $expire, $_W['config']['cookie']['path'], $_W['config']['cookie']['domain'], $secure, $httponly);
}


function igetcookie($key) {
	global $_W;
	$key = $_W['config']['cookie']['pre'] . $key;

	return $_COOKIE[$key];
}


function getip() {
	static $ip = '';
	if (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
		$ip = $_SERVER['HTTP_CDN_SRC_IP'];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] as $xip) {
			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
				$ip = $xip;
				break;
			}
		}
	}
	if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip)) {
		return $ip;
	} else {
		return '127.0.0.1';
	}
}


function token($specialadd = '') {
	global $_W;
	if (!defined('IN_MOBILE')) {
		$key = complex_authkey();

		return substr(md5($key . $specialadd), 8, 8);
	} else {
		if (!empty($_SESSION['token'])) {
			$count = count($_SESSION['token']) - 5;
			asort($_SESSION['token']);
			foreach ($_SESSION['token'] as $k => $v) {
				if (TIMESTAMP - $v > 300 || $count > 0) {
					unset($_SESSION['token'][$k]);
					--$count;
				}
			}
		}
		$key = substr(random(20), 0, 4);
		$_SESSION['token'][$key] = TIMESTAMP;

		return $key;
	}
}


function random($length, $numeric = false) {
	$seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
	if ($numeric) {
		$hash = '';
	} else {
		$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
		--$length;
	}
	$max = strlen($seed) - 1;
	for ($i = 0; $i < $length; ++$i) {
		$hash .= $seed[mt_rand(0, $max)];
	}

	return $hash;
}


function checksubmit($var = 'submit', $allowget = false) {
	global $_W, $_GPC;
	if (empty($_GPC[$var])) {
		return false;
	}
	if (defined('IN_SYS')) {
		if ($allowget || (($_W['ispost'] && !empty($_W['token']) && $_W['token'] == $_GPC['token']) && (empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", '\\1', $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", '\\1', $_SERVER['HTTP_HOST'])))) {
			return true;
		}
	} else {
		if (empty($_W['isajax']) && empty($_SESSION['token'][$_GPC['token']])) {
			exit('<script type="text/javascript">history.go(-1);</script>');
		} else {
			unset($_SESSION['token'][$_GPC['token']]);
		}

		return true;
	}

	return false;
}

function complex_authkey() {
	global $_W;
	$key = (array) $_W['setting']['site'];
	$key['authkey'] = $_W['config']['setting']['authkey'];

	return implode('', $key);
}
function checkcaptcha($code) {
	global $_W, $_GPC;
	session_start();
	$key = complex_authkey();
	$codehash = md5(strtolower($code) . $key);
	if (!empty($_GPC['__code']) && $codehash == $_SESSION['__code']) {
		$return = true;
	} else {
		$return = false;
	}
	$_SESSION['__code'] = '';
	isetcookie('__code', '');

	return $return;
}


function tablename($table) {
	if (empty($GLOBALS['_W']['config']['db']['master'])) {
		return "`{$GLOBALS['_W']['config']['db']['tablepre']}{$table}`";
	}

	return "`{$GLOBALS['_W']['config']['db']['master']['tablepre']}{$table}`";
}


function array_elements($keys, $src, $default = false) {
	$return = array();
	if (!is_array($keys)) {
		$keys = array($keys);
	}
	foreach ($keys as $key) {
		if (isset($src[$key])) {
			$return[$key] = $src[$key];
		} else {
			$return[$key] = $default;
		}
	}

	return $return;
}


function iarray_sort($array, $keys, $type = 'asc') {
	$keysvalue = $new_array = array();
	foreach ($array as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	}
	if ('asc' == $type) {
		asort($keysvalue);
	} else {
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k => $v) {
		$new_array[$k] = $array[$k];
	}

	return $new_array;
}


function range_limit($num, $downline, $upline, $returnNear = true) {
	$num = intval($num);
	$downline = intval($downline);
	$upline = intval($upline);
	if ($num < $downline) {
		return empty($returnNear) ? false : $downline;
	} elseif ($num > $upline) {
		return empty($returnNear) ? false : $upline;
	} else {
		return empty($returnNear) ? true : $num;
	}
}


function ijson_encode($value, $options = 0) {
	if (empty($value)) {
		return false;
	}
	if (version_compare(PHP_VERSION, '5.4.0', '<') && JSON_UNESCAPED_UNICODE == $options) {
		$str = json_encode($value);
		$json_str = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($matchs) {
			return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
		}, $str);
	} else {
		$json_str = json_encode($value, $options);
	}

	return addslashes($json_str);
}


function iserializer($value) {
	return serialize($value);
}


function iunserializer($value) {
	if (empty($value)) {
		return array();
	}
	if (!is_serialized($value)) {
		return $value;
	}
	if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
		$result = unserialize($value, array('allowed_classes' => false));
	} else {
		if (preg_match('/[oc]:[^:]*\d+:/i', $value)) {
			return array();
		}
		$result = unserialize($value);
	}
	if (false === $result) {
		$temp = preg_replace_callback('!s:(\d+):"(.*?)";!s', function ($matchs) {
			return 's:' . strlen($matchs[2]) . ':"' . $matchs[2] . '";';
		}, $value);

		return unserialize($temp);
	} else {
		return $result;
	}
}


function is_base64($str) {
	if (!is_string($str)) {
		return false;
	}

	return $str == base64_encode(base64_decode($str));
}


function is_serialized($data, $strict = true) {
	if (!is_string($data)) {
		return false;
	}
	$data = trim($data);
	if ('N;' == $data) {
		return true;
	}
	if (strlen($data) < 4) {
		return false;
	}
	if (':' !== $data[1]) {
		return false;
	}
	if ($strict) {
		$lastc = substr($data, -1);
		if (';' !== $lastc && '}' !== $lastc) {
			return false;
		}
	} else {
		$semicolon = strpos($data, ';');
		$brace = strpos($data, '}');
				if (false === $semicolon && false === $brace) {
			return false;
		}
				if (false !== $semicolon && $semicolon < 3) {
			return false;
		}
		if (false !== $brace && $brace < 4) {
			return false;
		}
	}
	$token = $data[0];
	switch ($token) {
		case 's':
			if ($strict) {
				if ('"' !== substr($data, -2, 1)) {
					return false;
				}
			} elseif (false === strpos($data, '"')) {
				return false;
			}
						case 'a':
			return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
		case 'O':
			return false;
		case 'b':
		case 'i':
		case 'd':
			$end = $strict ? '$' : '';

			return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
	}

	return false;
}


function wurl($segment, $params = array(), $contain_domain = false) {
	global $_W;
	list($controller, $action, $do) = explode('/', $segment);
	if ($contain_domain) {
		$url = $_W['siteroot'] . 'web/index.php?';
	} else {
		$url = './index.php?';
	}
	if (!empty($controller)) {
		$url .= "c={$controller}&";
	}
	if (!empty($action)) {
		$url .= "a={$action}&";
	}
	if (!empty($do)) {
		$url .= "do={$do}&";
	}
	if (!empty($params)) {
		$queryString = http_build_query($params, '', '&');
		$url .= $queryString;
	}

	return $url;
}

if (!function_exists('murl')) {
	
	function murl($segment, $params = array(), $noredirect = true, $addhost = false) {
		global $_W;
		list($controller, $action, $do) = explode('/', $segment);
		if (!empty($addhost)) {
			$url = $_W['siteroot'] . 'app/';
		} else {
			$url = './';
		}
		$str = '';
		if (uni_is_multi_acid()) {
			$str .= "&j={$_W['acid']}";
		}
		if (!empty($_W['account']) && $_W['account']['type'] == ACCOUNT_TYPE_WEBAPP_NORMAL) {
			$str .= '&a=webapp';
		}
		if (!empty($_W['account']) && $_W['account']['type'] == ACCOUNT_TYPE_PHONEAPP_NORMAL) {
			$str .= '&a=phoneapp';
		}
		$url .= "index.php?i={$_W['uniacid']}{$str}&";
		if (!empty($controller)) {
			$url .= "c={$controller}&";
		}
		if (!empty($action)) {
			$url .= "a={$action}&";
		}
		if (!empty($do)) {
			$url .= "do={$do}&";
		}
		if (!empty($params)) {
			$queryString = http_build_query($params, '', '&');
			$url .= $queryString;
			if (false === $noredirect) {
								$url .= '&wxref=mp.weixin.qq.com#wechat_redirect';
			}
		}

		return $url;
	}
}


function pagination($total, $pageIndex, $pageSize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '', 'callbackfuncname' => '')) {
	global $_W;
	$pdata = array(
		'tcount' => 0,
		'tpage' => 0,
		'cindex' => 0,
		'findex' => 0,
		'pindex' => 0,
		'nindex' => 0,
		'lindex' => 0,
		'options' => '',
	);
	if (empty($context['before'])) {
		$context['before'] = 5;
	}
	if (empty($context['after'])) {
		$context['after'] = 4;
	}

	if ($context['ajaxcallback']) {
		$context['isajax'] = true;
	}

	if ($context['callbackfuncname']) {
		$callbackfunc = $context['callbackfuncname'];
	}

	$pdata['tcount'] = $total;
	$pdata['tpage'] = (empty($pageSize) || $pageSize < 0) ? 1 : ceil($total / $pageSize);
	if ($pdata['tpage'] <= 1) {
		return '';
	}
	$cindex = $pageIndex;
	$cindex = min($cindex, $pdata['tpage']);
	$cindex = max($cindex, 1);
	$pdata['cindex'] = $cindex;
	$pdata['findex'] = 1;
	$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
	$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
	$pdata['lindex'] = $pdata['tpage'];

	if ($context['isajax']) {
		if (empty($url)) {
			$url = $_W['script_name'] . '?' . http_build_query($_GET);
		}
		$pdata['faa'] = 'href="javascript:;" page="' . $pdata['findex'] . '" ' . ($callbackfunc ? 'ng-click="' . $callbackfunc . '(\'' . $url . '\', \'' . $pdata['findex'] . '\', this);"' : '');
		$pdata['paa'] = 'href="javascript:;" page="' . $pdata['pindex'] . '" ' . ($callbackfunc ? 'ng-click="' . $callbackfunc . '(\'' . $url . '\', \'' . $pdata['pindex'] . '\', this);"' : '');
		$pdata['naa'] = 'href="javascript:;" page="' . $pdata['nindex'] . '" ' . ($callbackfunc ? 'ng-click="' . $callbackfunc . '(\'' . $url . '\', \'' . $pdata['nindex'] . '\', this);"' : '');
		$pdata['laa'] = 'href="javascript:;" page="' . $pdata['lindex'] . '" ' . ($callbackfunc ? 'ng-click="' . $callbackfunc . '(\'' . $url . '\', \'' . $pdata['lindex'] . '\', this);"' : '');
	} else {
		if ($url) {
			$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
			$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
			$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
			$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
		} else {
			$_GET['page'] = $pdata['findex'];
			$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['pindex'];
			$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['nindex'];
			$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['lindex'];
			$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
		}
	}

	$html = '<div><ul class="pagination pagination-centered">';
	$html .= "<li><a {$pdata['faa']} class=\"pager-nav\">首页</a></li>";
	empty($callbackfunc) && $html .= "<li><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一页</a></li>";

		if (!$context['before'] && 0 != $context['before']) {
		$context['before'] = 5;
	}
	if (!$context['after'] && 0 != $context['after']) {
		$context['after'] = 4;
	}

	if (0 != $context['after'] && 0 != $context['before']) {
		$range = array();
		$range['start'] = max(1, $pdata['cindex'] - $context['before']);
		$range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
		if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
			$range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
			$range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
		}
		for ($i = $range['start']; $i <= $range['end']; ++$i) {
			if ($context['isajax']) {
				$aa = 'href="javascript:;" page="' . $i . '" ' . ($callbackfunc ? 'ng-click="' . $callbackfunc . '(\'' . $url . '\', \'' . $i . '\', this);"' : '');
			} else {
				if ($url) {
					$aa = 'href="?' . str_replace('*', $i, $url) . '"';
				} else {
					$_GET['page'] = $i;
					$aa = 'href="?' . http_build_query($_GET) . '"';
				}
			}
			if (!empty($context['isajax'])) {
				$html .= ($i == $pdata['cindex'] ? '<li class="active">' : '<li>') . "<a {$aa}>" . $i . '</a></li>';
			} else {
				$html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
			}
		}
	}

	if ($pdata['cindex'] < $pdata['tpage']) {
		empty($callbackfunc) && $html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
		$html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
	}
	$html .= '</ul></div>';

	return $html;
}


function tomedia($src, $local_path = false, $is_cahce = false) {
	global $_W;
	$src = trim($src);
	if (empty($src)) {
		return '';
	}
	if ($is_cahce) {
		$src .= '?v=' . time();
	}

	if (strexists($src, 'c=utility&a=wxcode&do=image&attach=')) {
		return $src;
	}

	$t = strtolower($src);
	if (strexists($t, 'https://mmbiz.qlogo.cn') || strexists($t, 'http://mmbiz.qpic.cn')) {
		$url = url('utility/wxcode/image', array('attach' => $src));

		return $_W['siteroot'] . 'web' . ltrim($url, '.');
	}

	if ('//' == substr($src, 0, 2)) {
		return 'http:' . $src;
	}
	if (('http://' == substr($src, 0, 7)) || ('https://' == substr($src, 0, 8))) {
		return $src;
	}

	if (strexists($src, 'addons/')) {
		return $_W['siteroot'] . substr($src, strpos($src, 'addons/'));
	}
		if (strexists($src, $_W['siteroot']) && !strexists($src, '/addons/')) {
		$urls = parse_url($src);
		$src = $t = substr($urls['path'], strpos($urls['path'], 'images'));
	}
	$uni_remote_setting = uni_setting_load('remote');
		if ($local_path ||
		empty($_W['setting']['remote']['type']) && (empty($_W['uniacid']) || !empty($_W['uniacid']) && empty($uni_remote_setting['remote']['type'])) ||
		file_exists(IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/' . $src)) {
		$src = $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' . $src;
	} else {
		$src = $_W['attachurl_remote'] . $src;
	}

	return $src;
}


function to_global_media($src) {
	global $_W;
	$lower_src = strtolower($src);
	if (('http://' == substr($lower_src, 0, 7)) || ('https://' == substr($lower_src, 0, 8)) || ('//' == substr($lower_src, 0, 2))) {
		return $src;
	}
	$remote = setting_load('remote');
	$remote = empty($remote) ? array() : $remote['remote'];
	if (empty($remote['type']) || file_exists(IA_ROOT . '/' . $_W['config']['upload']['attachdir'] . '/' . $src)) {
		$src = $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/' . $src;
	} else {
		if (ATTACH_FTP == $remote['type']) {
			$attach_url = $remote['ftp']['url'] . '/';
		} elseif (ATTACH_OSS == $remote['type']) {
			$attach_url = $remote['alioss']['url'] . '/';
		} elseif (ATTACH_QINIU == $remote['type']) {
			$attach_url = $remote['qiniu']['url'] . '/';
		} elseif (ATTACH_COS == $remote['type']) {
			$attach_url = $remote['cos']['url'] . '/';
		}
		$src = $attach_url . $src;
	}

	return $src;
}


function error($errno, $message = '') {
	return array(
		'errno' => $errno,
		'message' => $message,
	);
}


function is_error($data) {
	if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && 0 == $data['errno'])) {
		return false;
	} else {
		return true;
	}
}


function detect_sensitive_word($string) {
	$setting = setting_load('sensitive_words');
	if (empty($setting['sensitive_words'])) {
		return false;
	}
	$sensitive_words = $setting['sensitive_words'];
	$blacklist = '/' . implode('|', $sensitive_words) . '/';
	if (preg_match($blacklist, $string, $matches)) {
		return $matches[0];
	}

	return false;
}

function referer($default = '') {
	global $_GPC, $_W;
	$_W['referer'] = !empty($_GPC['referer']) ? $_GPC['referer'] : $_SERVER['HTTP_REFERER'];
	$_W['referer'] = '?' == substr($_W['referer'], -1) ? substr($_W['referer'], 0, -1) : $_W['referer'];

	if (strpos($_W['referer'], 'member.php?act=login')) {
		$_W['referer'] = $default;
	}
	$_W['referer'] = $_W['referer'];
	$_W['referer'] = str_replace('&amp;', '&', $_W['referer']);
	$reurl = parse_url($_W['referer']);

	if (!empty($reurl['host']) && !in_array($reurl['host'], array($_SERVER['HTTP_HOST'], 'www.' . $_SERVER['HTTP_HOST'])) && !in_array($_SERVER['HTTP_HOST'], array($reurl['host'], 'www.' . $reurl['host']))) {
		$_W['referer'] = $_W['siteroot'];
	} elseif (empty($reurl['host'])) {
		$_W['referer'] = $_W['siteroot'] . './' . $_W['referer'];
	}

	return strip_tags($_W['referer']);
}


function strexists($string, $find) {
	return !(false === strpos($string, $find));
}


function cutstr($string, $length, $havedot = false, $charset = '') {
	global $_W;
	if (empty($charset)) {
		$charset = $_W['charset'];
	}
	if ('gbk' == strtolower($charset)) {
		$charset = 'gbk';
	} else {
		$charset = 'utf8';
	}
	if (istrlen($string, $charset) <= $length) {
		return $string;
	}
	if (function_exists('mb_strcut')) {
		$string = mb_substr($string, 0, $length, $charset);
	} else {
		$pre = '{%';
		$end = '%}';
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

		$strcut = '';
		$strlen = strlen($string);

		if ('utf8' == $charset) {
			$n = $tn = $noc = 0;
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if (9 == $t || 10 == $t || (32 <= $t && $t <= 126)) {
					$tn = 1;
					++$n;
					++$noc;
				} elseif (194 <= $t && $t <= 223) {
					$tn = 2;
					$n += 2;
					++$noc;
				} elseif (224 <= $t && $t <= 239) {
					$tn = 3;
					$n += 3;
					++$noc;
				} elseif (240 <= $t && $t <= 247) {
					$tn = 4;
					$n += 4;
					++$noc;
				} elseif (248 <= $t && $t <= 251) {
					$tn = 5;
					$n += 5;
					++$noc;
				} elseif (252 == $t || 253 == $t) {
					$tn = 6;
					$n += 6;
					++$noc;
				} else {
					++$n;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		} else {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if ($t > 127) {
					$tn = 2;
					$n += 2;
					++$noc;
				} else {
					$tn = 1;
					++$n;
					++$noc;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		}
		$string = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	}

	if ($havedot) {
		$string = $string . '...';
	}

	return $string;
}


function istrlen($string, $charset = '') {
	global $_W;
	if (empty($charset)) {
		$charset = $_W['charset'];
	}
	if ('gbk' == strtolower($charset)) {
		$charset = 'gbk';
	} else {
		$charset = 'utf8';
	}
	if (function_exists('mb_strlen') && extension_loaded('mbstring')) {
		return mb_strlen($string, $charset);
	} else {
		$n = $noc = 0;
		$strlen = strlen($string);

		if ('utf8' == $charset) {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if (9 == $t || 10 == $t || (32 <= $t && $t <= 126)) {
					++$n;
					++$noc;
				} elseif (194 <= $t && $t <= 223) {
					$n += 2;
					++$noc;
				} elseif (224 <= $t && $t <= 239) {
					$n += 3;
					++$noc;
				} elseif (240 <= $t && $t <= 247) {
					$n += 4;
					++$noc;
				} elseif (248 <= $t && $t <= 251) {
					$n += 5;
					++$noc;
				} elseif (252 == $t || 253 == $t) {
					$n += 6;
					++$noc;
				} else {
					++$n;
				}
			}
		} else {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if ($t > 127) {
					$n += 2;
					++$noc;
				} else {
					++$n;
					++$noc;
				}
			}
		}

		return $noc;
	}
}


function emotion($message = '', $size = '24px') {
	$emotions = array(
		'/::)', '/::~', '/::B', '/::|', '/:8-)', '/::<', '/::$', '/::X', '/::Z', "/::'(",
		'/::-|', '/::@', '/::P', '/::D', '/::O', '/::(', '/::+', '/:--b', '/::Q', '/::T',
		'/:,@P', '/:,@-D', '/::d', '/:,@o', '/::g', '/:|-)', '/::!', '/::L', '/::>', '/::,@',
		'/:,@f', '/::-S', '/:?', '/:,@x', '/:,@@', '/::8', '/:,@!', '/:!!!', '/:xx', '/:bye',
		'/:wipe', '/:dig', '/:handclap', '/:&-(', '/:B-)', '/:<@', '/:@>', '/::-O', '/:>-|',
		'/:P-(', "/::'|", '/:X-)', '/::*', '/:@x', '/:8*', '/:pd', '/:<W>', '/:beer', '/:basketb',
		'/:oo', '/:coffee', '/:eat', '/:pig', '/:rose', '/:fade', '/:showlove', '/:heart',
		'/:break', '/:cake', '/:li', '/:bome', '/:kn', '/:footb', '/:ladybug', '/:shit', '/:moon',
		'/:sun', '/:gift', '/:hug', '/:strong', '/:weak', '/:share', '/:v', '/:@)', '/:jj', '/:@@',
		'/:bad', '/:lvu', '/:no', '/:ok', '/:love', '/:<L>', '/:jump', '/:shake', '/:<O>', '/:circle',
		'/:kotow', '/:turn', '/:skip', '/:oY', '/:#-0', '/:hiphot', '/:kiss', '/:<&', '/:&>',
	);
	foreach ($emotions as $index => $emotion) {
		$message = str_replace($emotion, '<img style="width:' . $size . ';vertical-align:middle;" src="http://res.mail.qq.com/zh_CN/images/mo/DEFAULT2/' . $index . '.gif" />', $message);
	}

	return $message;
}


function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5('' != $key ? $key : $GLOBALS['_W']['config']['setting']['authkey']);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ('DECODE' == $operation ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);

	$string = 'DECODE' == $operation ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for ($i = 0; $i <= 255; ++$i) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; ++$i) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; ++$i) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if ('DECODE' == $operation) {
		if ((0 == substr($result, 0, 10) || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}


function sizecount($size, $unit = false) {
	if ($size >= 1073741824) {
		$size = round($size / 1073741824 * 100) / 100 . ' GB';
	} elseif ($size >= 1048576) {
		$size = round($size / 1048576 * 100) / 100 . ' MB';
	} elseif ($size >= 1024) {
		$size = round($size / 1024 * 100) / 100 . ' KB';
	} else {
		$size = $size . ' Bytes';
	}
	if ($unit) {
		$size = preg_replace('/[^0-9\.]/', '', $size);
	}

	return $size;
}


function bytecount($str) {
	if ('b' == strtolower($str[strlen($str) - 1])) {
		$str = substr($str, 0, -1);
	}
	if ('k' == strtolower($str[strlen($str) - 1])) {
		return floatval($str) * 1024;
	}
	if ('m' == strtolower($str[strlen($str) - 1])) {
		return floatval($str) * 1048576;
	}
	if ('g' == strtolower($str[strlen($str) - 1])) {
		return floatval($str) * 1073741824;
	}
}


function array2xml($arr, $level = 1) {
	$s = 1 == $level ? '<xml>' : '';
	foreach ($arr as $tagname => $value) {
		if (is_numeric($tagname)) {
			$tagname = $value['TagName'];
			unset($value['TagName']);
		}
		if (!is_array($value)) {
			$s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
		} else {
			$s .= "<{$tagname}>" . array2xml($value, $level + 1) . "</{$tagname}>";
		}
	}
	$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);

	return 1 == $level ? $s . '</xml>' : $s;
}

function xml2array($xml) {
	if (empty($xml)) {
		return array();
	}
	$result = array();
	$xmlobj = isimplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	if ($xmlobj instanceof SimpleXMLElement) {
		$result = json_decode(json_encode($xmlobj), true);
		if (is_array($result)) {
			return $result;
		} else {
			return '';
		}
	} else {
		return $result;
	}
}

function scriptname() {
	global $_W;
	$_W['script_name'] = basename($_SERVER['SCRIPT_FILENAME']);
	if (basename($_SERVER['SCRIPT_NAME']) === $_W['script_name']) {
		$_W['script_name'] = $_SERVER['SCRIPT_NAME'];
	} else {
		if (basename($_SERVER['PHP_SELF']) === $_W['script_name']) {
			$_W['script_name'] = $_SERVER['PHP_SELF'];
		} else {
			if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $_W['script_name']) {
				$_W['script_name'] = $_SERVER['ORIG_SCRIPT_NAME'];
			} else {
				if (false !== ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName))) {
					$_W['script_name'] = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $_W['script_name'];
				} else {
					if (isset($_SERVER['DOCUMENT_ROOT']) && 0 === strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT'])) {
						$_W['script_name'] = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
					} else {
						$_W['script_name'] = 'unknown';
					}
				}
			}
		}
	}

	return $_W['script_name'];
}


function utf8_bytes($cp) {
	if ($cp > 0x10000) {
				return	chr(0xF0 | (($cp & 0x1C0000) >> 18)) .
			chr(0x80 | (($cp & 0x3F000) >> 12)) .
			chr(0x80 | (($cp & 0xFC0) >> 6)) .
			chr(0x80 | ($cp & 0x3F));
	} elseif ($cp > 0x800) {
				return	chr(0xE0 | (($cp & 0xF000) >> 12)) .
			chr(0x80 | (($cp & 0xFC0) >> 6)) .
			chr(0x80 | ($cp & 0x3F));
	} elseif ($cp > 0x80) {
				return	chr(0xC0 | (($cp & 0x7C0) >> 6)) .
			chr(0x80 | ($cp & 0x3F));
	} else {
				return chr($cp);
	}
}

function media2local($media_id, $all = false) {
	global $_W;
	load()->model('material');
	$data = material_get($media_id);
	if (!is_error($data)) {
		$data['attachment'] = tomedia($data['attachment'], true);
		if (!$all) {
			return $data['attachment'];
		}

		return $data;
	} else {
		return '';
	}
}

function aes_decode($message, $encodingaeskey = '', $appid = '') {
	$key = base64_decode($encodingaeskey . '=');

	$ciphertext_dec = base64_decode($message);
	$iv = substr($key, 0, 16);
		$decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
	$block_size = 32;

	$pad = ord(substr($decrypted, -1));
	if ($pad < 1 || $pad > 32) {
		$pad = 0;
	}
	$result = substr($decrypted, 0, (strlen($decrypted) - $pad));
	if (strlen($result) < 16) {
		return '';
	}
	$content = substr($result, 16, strlen($result));
	$len_list = unpack('N', substr($content, 0, 4));
	$contentlen = $len_list[1];
	$content = substr($content, 4, $contentlen);
	$from_appid = substr($content, $xml_len + 4);
	if (!empty($appid) && $appid != $from_appid) {
		return '';
	}

	return $content;
}

function aes_encode($message, $encodingaeskey = '', $appid = '') {
	$key = base64_decode($encodingaeskey . '=');
	$text = random(16) . pack('N', strlen($message)) . $message . $appid;

	$iv = substr($key, 0, 16);

	$block_size = 32;
	$text_length = strlen($text);
		$amount_to_pad = $block_size - ($text_length % $block_size);
	if (0 == $amount_to_pad) {
		$amount_to_pad = $block_size;
	}
		$pad_chr = chr($amount_to_pad);
	$tmp = '';
	for ($index = 0; $index < $amount_to_pad; ++$index) {
		$tmp .= $pad_chr;
	}
	$text = $text . $tmp;
		$encrypted = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
		$encrypt_msg = base64_encode($encrypted);

	return $encrypt_msg;
}


function aes_pkcs7_decode($encrypt_data, $key, $iv = false) {
	load()->library('pkcs7');
	$encrypt_data = base64_decode($encrypt_data);
	if (!empty($iv)) {
		$iv = base64_decode($iv);
	}
	$pc = new Prpcrypt($key);
	$result = $pc->decrypt($encrypt_data, $iv);
	if (0 != $result[0]) {
		return error($result[0], '解密失败');
	}

	return $result[1];
}


function isimplexml_load_string($string, $class_name = 'SimpleXMLElement', $options = 0, $ns = '', $is_prefix = false) {
	libxml_disable_entity_loader(true);
	if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $string)) {
		return false;
	}
	$string = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f\\x7f]/', '', $string); 	return simplexml_load_string($string, $class_name, $options, $ns, $is_prefix);
}

function ihtml_entity_decode($str) {
	$str = str_replace('&nbsp;', '#nbsp;', $str);

	return str_replace('#nbsp;', '&nbsp;', html_entity_decode(urldecode($str)));
}

function iarray_change_key_case($array, $case = CASE_LOWER) {
	if (!is_array($array) || empty($array)) {
		return array();
	}
	$array = array_change_key_case($array, $case);
	foreach ($array as $key => $value) {
		if (empty($value) && is_array($value)) {
			$array[$key] = '';
		}
		if (!empty($value) && is_array($value)) {
			$array[$key] = iarray_change_key_case($value, $case);
		}
	}

	return $array;
}


function parse_path($path) {
	$danger_char = array('../', '{php', '<?php', '<%', '<?', '..\\', '\\\\', '\\', '..\\\\', '%00', '\0', '\r');
	foreach ($danger_char as $char) {
		if (strexists($path, $char)) {
			return false;
		}
	}

	return $path;
}


function dir_size($dir) {
	$size = 0;
	if (is_dir($dir)) {
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle))) {
			if ('.' != $entry && '..' != $entry) {
				if (is_dir("{$dir}/{$entry}")) {
					$size += dir_size("{$dir}/{$entry}");
				} else {
					$size += filesize("{$dir}/{$entry}");
				}
			}
		}
		closedir($handle);
	}

	return $size;
}


function get_first_pinyin($str) {
	static $pinyin;
	$first_char = '';
	$str = trim($str);
	if (empty($str)) {
		return $first_char;
	}
	if (empty($pinyin)) {
		load()->library('pinyin');
		$pinyin = new Pinyin_Pinyin();
	}
	$first_char = $pinyin->get_first_char($str);

	return $first_char;
}


function strip_emoji($nickname) {
	$clean_text = '';
		$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	$clean_text = preg_replace($regexEmoticons, '_', $nickname);
		$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	$clean_text = preg_replace($regexSymbols, '_', $clean_text);
		$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	$clean_text = preg_replace($regexTransport, '_', $clean_text);
		$regexMisc = '/[\x{2600}-\x{26FF}]/u';
	$clean_text = preg_replace($regexMisc, '_', $clean_text);
		$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
	$clean_text = preg_replace($regexDingbats, '_', $clean_text);

	$clean_text = str_replace("'", '', $clean_text);
	$clean_text = str_replace('"', '', $clean_text);
	$clean_text = str_replace('“', '', $clean_text);
	$clean_text = str_replace('゛', '', $clean_text);
	$search = array(' ', '　', "\n", "\r", "\t");
	$replace = array('', '', '', '', '');

	return str_replace($search, $replace, $clean_text);
}


function emoji_unicode_decode($string) {
	preg_match_all('/\[U\+(\\w{4,})\]/i', $string, $match);
	if (!empty($match[1])) {
		foreach ($match[1] as $emojiUSB) {
			$string = str_ireplace("[U+{$emojiUSB}]", utf8_bytes(hexdec($emojiUSB)), $string);
		}
	}

	return $string;
}

function emoji_unicode_encode($string) {
	$ranges = array(
		'\\\\ud83c[\\\\udf00-\\\\udfff]', 		'\\\\ud83d[\\\\udc00-\\\\ude4f]', 		'\\\\ud83d[\\\\ude80-\\\\udeff]',  	);
	preg_match_all('/' . implode('|', $ranges) . '/i', $string, $match);
	print_r($match);
	exit;
}


if (!function_exists('starts_with')) {
	function starts_with($haystack, $needles) {
		foreach ((array) $needles as $needle) {
			if ('' != $needle && substr($haystack, 0, strlen($needle)) === (string) $needle) {
				return true;
			}
		}

		return false;
	}
}


function icall_user_func($callback) {
	if (function_exists($callback)) {
		$args = func_get_args();
		switch (func_num_args()) {
			case 1:
				return call_user_func($callback);
				break;
			case 2:
				return call_user_func($callback, $args[1]);
				break;
			case 3:
				return call_user_func($callback, $args[1], $args[2]);
				break;
			case 4:
				return call_user_func($callback, $args[1], $args[2], $args[3]);
				break;
			case 5:
				return call_user_func($callback, $args[1], $args[2], $args[3], $args[4]);
				break;
		}
	}

	return '';
}

load()->func('safe');
load()->func('system');