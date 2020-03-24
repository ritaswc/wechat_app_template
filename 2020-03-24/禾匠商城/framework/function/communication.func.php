<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
			if (function_exists('curl_init') && function_exists('curl_exec') && $timeout > 0) {
		$ch = ihttp_build_curl($url, $post, $extra, $timeout);
		if (is_error($ch)) {
			return $ch;
		}
		$data = curl_exec($ch);
		$status = curl_getinfo($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if ($errno || empty($data)) {
			return error($errno, $error);
		} else {
			return ihttp_response_parse($data);
		}
	}
	$urlset = ihttp_parse_url($url, true);
	if (!empty($urlset['ip'])) {
		$urlset['host'] = $urlset['ip'];
	}

	$body = ihttp_build_httpbody($url, $post, $extra);

	if ('https' == $urlset['scheme']) {
		$fp = ihttp_socketopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
	} else {
		$fp = ihttp_socketopen($urlset['host'], $urlset['port'], $errno, $error);
	}
	stream_set_blocking($fp, $timeout > 0 ? true : false);
	stream_set_timeout($fp, ini_get('default_socket_timeout'));
	if (!$fp) {
		return error(1, $error);
	} else {
		fwrite($fp, $body);
		$content = '';
		if ($timeout > 0) {
			while (!feof($fp)) {
				$content .= fgets($fp, 512);
			}
		}
		fclose($fp);

		return ihttp_response_parse($content, true);
	}
}


function ihttp_get($url) {
	return ihttp_request($url);
}


function ihttp_post($url, $data) {
	$headers = array('Content-Type' => 'application/x-www-form-urlencoded');

	return ihttp_request($url, $data, $headers);
}


function ihttp_multi_request($urls, $posts = array(), $extra = array(), $timeout = 60) {
	if (!is_array($urls)) {
		return error(1, '请使用ihttp_request函数');
	}
	$curl_multi = curl_multi_init();
	$curl_client = $response = array();

	foreach ($urls as $i => $url) {
		if (isset($posts[$i]) && is_array($posts[$i])) {
			$post = $posts[$i];
		} else {
			$post = $posts;
		}
		if (!empty($url)) {
			$curl = ihttp_build_curl($url, $post, $extra, $timeout);
			if (is_error($curl)) {
				continue;
			}
			if (CURLM_OK === curl_multi_add_handle($curl_multi, $curl)) {
								$curl_client[] = $curl;
			}
		}
	}
	if (!empty($curl_client)) {
		$active = null;
		do {
			$mrc = curl_multi_exec($curl_multi, $active);
		} while (CURLM_CALL_MULTI_PERFORM == $mrc);

		while ($active && CURLM_OK == $mrc) {
			do {
				$mrc = curl_multi_exec($curl_multi, $active);
			} while (CURLM_CALL_MULTI_PERFORM == $mrc);
		}
	}

	foreach ($curl_client as $i => $curl) {
		$response[$i] = curl_multi_getcontent($curl);
		curl_multi_remove_handle($curl_multi, $curl);
	}
	curl_multi_close($curl_multi);

	return $response;
}

function ihttp_socketopen($hostname, $port = 80, &$errno, &$errstr, $timeout = 15) {
	$fp = '';
	if (function_exists('fsockopen')) {
		$fp = @fsockopen($hostname, $port, $errno, $errstr, $timeout);
	} elseif (function_exists('pfsockopen')) {
		$fp = @pfsockopen($hostname, $port, $errno, $errstr, $timeout);
	} elseif (function_exists('stream_socket_client')) {
		$fp = @stream_socket_client($hostname . ':' . $port, $errno, $errstr, $timeout);
	}

	return $fp;
}


function ihttp_response_parse($data, $chunked = false) {
	$rlt = array();

	$pos = strpos($data, "\r\n\r\n");
	$split1[0] = substr($data, 0, $pos);
	$split1[1] = substr($data, $pos + 4, strlen($data));

	$split2 = explode("\r\n", $split1[0], 2);
	preg_match('/^(\S+) (\S+) (.*)$/', $split2[0], $matches);
	$rlt['code'] = !empty($matches[2]) ? $matches[2] : 200;
	$rlt['status'] = !empty($matches[3]) ? $matches[3] : 'OK';
	$rlt['responseline'] = !empty($split2[0]) ? $split2[0] : '';
	$header = explode("\r\n", $split2[1]);
	$isgzip = false;
	$ischunk = false;
	foreach ($header as $v) {
		$pos = strpos($v, ':');
		$key = substr($v, 0, $pos);
		$value = trim(substr($v, $pos + 1));
		if (isset($rlt['headers'][$key]) && is_array($rlt['headers'][$key])) {
			$rlt['headers'][$key][] = $value;
		} elseif (!empty($rlt['headers'][$key])) {
			$temp = $rlt['headers'][$key];
			unset($rlt['headers'][$key]);
			$rlt['headers'][$key][] = $temp;
			$rlt['headers'][$key][] = $value;
		} else {
			$rlt['headers'][$key] = $value;
		}
		if (!$isgzip && 'content-encoding' == strtolower($key) && 'gzip' == strtolower($value)) {
			$isgzip = true;
		}
		if (!$ischunk && 'transfer-encoding' == strtolower($key) && 'chunked' == strtolower($value)) {
			$ischunk = true;
		}
	}
	if ($chunked && $ischunk) {
		$rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
	} else {
		$rlt['content'] = $split1[1];
	}
	if ($isgzip && function_exists('gzdecode')) {
		$rlt['content'] = gzdecode($rlt['content']);
	}

	$rlt['meta'] = $data;
	if ('100' == $rlt['code']) {
		return ihttp_response_parse($rlt['content']);
	}

	return $rlt;
}

function ihttp_response_parse_unchunk($str = null) {
	if (!is_string($str) or strlen($str) < 1) {
		return false;
	}
	$eol = "\r\n";
	$add = strlen($eol);
	$tmp = $str;
	$str = '';
	do {
		$tmp = ltrim($tmp);
		$pos = strpos($tmp, $eol);
		if (false === $pos) {
			return false;
		}
		$len = hexdec(substr($tmp, 0, $pos));
		if (!is_numeric($len) or $len < 0) {
			return false;
		}
		$str .= substr($tmp, ($pos + $add), $len);
		$tmp = substr($tmp, ($len + $pos + $add));
		$check = trim($tmp);
	} while (!empty($check));
	unset($tmp);

	return $str;
}


function ihttp_parse_url($url, $set_default_port = false) {
	if (empty($url)) {
		return error(1);
	}
	$urlset = parse_url($url);
	if (!empty($urlset['scheme']) && !in_array($urlset['scheme'], array('http', 'https'))) {
		return error(1, '只能使用 http 及 https 协议');
	}
	if (empty($urlset['path'])) {
		$urlset['path'] = '/';
	}
	if (!empty($urlset['query'])) {
		$urlset['query'] = "?{$urlset['query']}";
	}
	if (strexists($url, 'https://') && !extension_loaded('openssl')) {
		if (!extension_loaded('openssl')) {
			return error(1, '请开启您PHP环境的openssl', '');
		}
	}
	if (empty($urlset['host'])) {
		$current_url = parse_url($GLOBALS['_W']['siteroot']);
		$urlset['host'] = $current_url['host'];
		$urlset['scheme'] = $current_url['scheme'];
		$urlset['path'] = $current_url['path'] . 'web/' . str_replace('./', '', $urlset['path']);
		$urlset['ip'] = '127.0.0.1';
	} elseif (!ihttp_allow_host($urlset['host'])) {
		return error(1, 'host 非法');
	}

	if ($set_default_port && empty($urlset['port'])) {
		$urlset['port'] = 'https' == $urlset['scheme'] ? '443' : '80';
	}

	return $urlset;
}


function ihttp_allow_host($host) {
	global $_W;
	if (strexists($host, '@')) {
		return false;
	}
	$pattern = '/^(10|172|192|127)/';
	if (preg_match($pattern, $host) && isset($_W['setting']['ip_white_list'])) {
		$ip_white_list = $_W['setting']['ip_white_list'];
		if ($ip_white_list && isset($ip_white_list[$host]) && !$ip_white_list[$host]['status']) {
			return false;
		}
	}

	return true;
}


function ihttp_build_curl($url, $post, $extra, $timeout) {
	if (!function_exists('curl_init') || !function_exists('curl_exec')) {
		return error(1, 'curl扩展未开启');
	}

	$urlset = ihttp_parse_url($url);
	if (is_error($urlset)) {
		return $urlset;
	}

	if (!empty($urlset['ip'])) {
		$extra['ip'] = $urlset['ip'];
	}

	$ch = curl_init();
	if (!empty($extra['ip'])) {
		$extra['Host'] = $urlset['host'];
		$urlset['host'] = $extra['ip'];
		unset($extra['ip']);
	}
	curl_setopt($ch, CURLOPT_URL, $urlset['scheme'] . '://' . $urlset['host'] . (empty($urlset['port']) || '80' == $urlset['port'] ? '' : ':' . $urlset['port']) . $urlset['path'] . (!empty($urlset['query']) ? $urlset['query'] : ''));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	if ($post) {
		if (is_array($post)) {
			$filepost = false;
						foreach ($post as $name => &$value) {
				if (version_compare(phpversion(), '5.5') >= 0 && is_string($value) && '@' == substr($value, 0, 1)) {
					$post[$name] = new CURLFile(ltrim($value, '@'));
				}
				if ((is_string($value) && '@' == substr($value, 0, 1)) || (class_exists('CURLFile') && $value instanceof CURLFile)) {
					$filepost = true;
				}
			}
			if (!$filepost) {
				$post = http_build_query($post);
			}
		}
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if (!empty($GLOBALS['_W']['config']['setting']['proxy'])) {
		$urls = parse_url($GLOBALS['_W']['config']['setting']['proxy']['host']);
		if (!empty($urls['host'])) {
			curl_setopt($ch, CURLOPT_PROXY, "{$urls['host']}:{$urls['port']}");
			$proxytype = 'CURLPROXY_' . strtoupper($urls['scheme']);
			if (!empty($urls['scheme']) && defined($proxytype)) {
				curl_setopt($ch, CURLOPT_PROXYTYPE, constant($proxytype));
			} else {
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			}
			if (!empty($GLOBALS['_W']['config']['setting']['proxy']['auth'])) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['_W']['config']['setting']['proxy']['auth']);
			}
		}
	}
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSLVERSION, 1);
	if (defined('CURL_SSLVERSION_TLSv1')) {
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
	if (!empty($extra) && is_array($extra)) {
		$headers = array();
		foreach ($extra as $opt => $value) {
			if (strexists($opt, 'CURLOPT_')) {
				curl_setopt($ch, constant($opt), $value);
			} elseif (is_numeric($opt)) {
				curl_setopt($ch, $opt, $value);
			} else {
				$headers[] = "{$opt}: {$value}";
			}
		}
		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
	}

	return $ch;
}

function ihttp_build_httpbody($url, $post, $extra) {
	$urlset = ihttp_parse_url($url, true);
	if (is_error($urlset)) {
		return $urlset;
	}

	if (!empty($urlset['ip'])) {
		$extra['ip'] = $urlset['ip'];
	}

	$body = '';
	if (!empty($post) && is_array($post)) {
		$filepost = false;
		$boundary = random(40);
		foreach ($post as $name => &$value) {
			if ((is_string($value) && '@' == substr($value, 0, 1)) && file_exists(ltrim($value, '@'))) {
				$filepost = true;
				$file = ltrim($value, '@');

				$body .= "--$boundary\r\n";
				$body .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . basename($file) . '"; Content-Type: application/octet-stream' . "\r\n\r\n";
				$body .= file_get_contents($file) . "\r\n";
			} else {
				$body .= "--$boundary\r\n";
				$body .= 'Content-Disposition: form-data; name="' . $name . '"' . "\r\n\r\n";
				$body .= $value . "\r\n";
			}
		}
		if (!$filepost) {
			$body = http_build_query($post, '', '&');
		} else {
			$body .= "--$boundary\r\n";
		}
	}

	$method = empty($post) ? 'GET' : 'POST';
	$fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
	$fdata .= "Accept: */*\r\n";
	$fdata .= "Accept-Language: zh-cn\r\n";
	if ('POST' == $method) {
		$fdata .= empty($filepost) ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data; boundary=$boundary\r\n";
	}
	$fdata .= "Host: {$urlset['host']}\r\n";
	$fdata .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1\r\n";
	if (function_exists('gzdecode')) {
		$fdata .= "Accept-Encoding: gzip, deflate\r\n";
	}
	$fdata .= "Connection: close\r\n";
	if (!empty($extra) && is_array($extra)) {
		foreach ($extra as $opt => $value) {
			if (!strexists($opt, 'CURLOPT_')) {
				$fdata .= "{$opt}: {$value}\r\n";
			}
		}
	}
	if ($body) {
		$fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
	} else {
		$fdata .= "\r\n";
	}

	return $fdata;
}


function ihttp_email($to, $subject, $body, $global = false) {
	static $mailer;
	set_time_limit(0);

	if (empty($mailer)) {
		if (!class_exists('PHPMailer')) {
			load()->library('phpmailer');
		}
		$mailer = new PHPMailer();
		global $_W;
		$config = $GLOBALS['_W']['setting']['mail'];
		if (!$global) {
			$row = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']), array('notify'));
			$row['notify'] = @iunserializer($row['notify']);
			if (!empty($row['notify']) && !empty($row['notify']['mail'])) {
				$config = $row['notify']['mail'];
			}
		}

		$config['charset'] = 'utf-8';
		if ($config['smtp']['type'] == '163') {
			$config['smtp']['server'] = 'smtp.163.com';
			$config['smtp']['port'] = 25;
		} elseif ($config['smtp']['type'] == 'qq') {
			$config['smtp']['server'] = 'ssl://smtp.qq.com';
			$config['smtp']['port'] = 465;
		} else {
			if (!empty($config['smtp']['authmode'])) {
				$config['smtp']['server'] = 'ssl://' . $config['smtp']['server'];
			}
		}

		if (!empty($config['smtp']['authmode'])) {
			if (!extension_loaded('openssl')) {
				return error(1, '请开启 php_openssl 扩展！');
			}
		}
		$mailer->signature = $config['signature'];
		$mailer->isSMTP();
		$mailer->CharSet = $config['charset'];
		$mailer->Host = $config['smtp']['server'];
		$mailer->Port = $config['smtp']['port'];
		$mailer->SMTPAuth = true;
		$mailer->Username = $config['username'];
		$mailer->Password = $config['password'];
		!empty($config['smtp']['authmode']) && $mailer->SMTPSecure = 'ssl';

		$mailer->From = $config['username'];
		$mailer->FromName = $config['sender'];
		$mailer->isHTML(true);
	}
	if ($body) {
		if (is_array($body)) {
			$newbody = '';
			foreach ($body as $value) {
				if ('@' == substr($value, 0, 1)) {
					if (!is_file($file = ltrim($value, '@'))) {
						return error(1, $file . ' 附件不存在或非文件！');
					}
					$mailer->addAttachment($file);
				} else {
					$newbody .= $value . '\n';
				}
			}
			$body = $newbody;
		} else {
			if ('@' == substr($body, 0, 1)) {
				$mailer->addAttachment(ltrim($body, '@'));
				$body = '';
			}
		}
	}
	if (!empty($mailer->signature)) {
		$body .= htmlspecialchars_decode($mailer->signature);
	}
	$mailer->Subject = $subject;
	$mailer->Body = $body;
	$mailer->addAddress($to);
	$result = $mailer->send();

	$mailer->clearAddresses();
	$mailer->clearReplyTos();

	if ($result) {
		return true;
	} else {
		return error(1, $mailer->ErrorInfo);
	}
}
