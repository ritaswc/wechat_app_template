<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


class WeixinAccount extends WeAccount {
	protected $tablename = 'account_wechats';
	protected $menuFrame = 'account';
	protected $type = ACCOUNT_TYPE_OFFCIAL_NORMAL;
	protected $typeName = '公众号';
	protected $typeSign = ACCOUNT_TYPE_SIGN;
	
	public $types = array(
		'view', 'click', 'scancode_push',
		'scancode_waitmsg', 'pic_sysphoto', 'pic_photo_or_album',
		'pic_weixin', 'location_select', 'media_id', 'view_limited',
	);

	protected function getAccountInfo($acid) {
		$account = table('account_wechats')->getById($acid);
		$account['encrypt_key'] = $account['key'];

		return $account;
	}

	
	public function checkSign() {
		$token = $this->account['token'];
		$signkey = array($token, $_GET['timestamp'], $_GET['nonce']);
		sort($signkey, SORT_STRING);
		$signString = implode($signkey);
		$signString = sha1($signString);

		return $signString == $_GET['signature'];
	}

	
	public function checkSignature($encrypt_msg) {
		$str = $this->buildSignature($encrypt_msg);

		return $str == $_GET['msg_signature'];
	}

	public function local_checkSignature($packet) {
		$token = $this->account['token'];
		$array = array($packet['Encrypt'], $token, $packet['TimeStamp'], $packet['Nonce']);
		sort($array, SORT_STRING);
		$str = implode($array);
		$str = sha1($str);

		return $str == $packet['MsgSignature'];
	}

	
	public function local_decryptMsg($postData) {
		$token = $this->account['token'];
		$encodingaeskey = $this->account['encodingaeskey'];
		$appid = $this->account['encrypt_key'];

		if (43 != strlen($encodingaeskey)) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40004 \n,错误描述为: " . $this->encryptErrorCode('40004'));
		}
		$key = base64_decode($encodingaeskey . '=');
				$packet = $this->local_xmlExtract($postData);
		if (is_error($packet)) {
			return error(-1, $packet['message']);
		}
				$istrue = $this->local_checkSignature($packet);
		if (!$istrue) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40001 \n,错误描述为: " . $this->encryptErrorCode('40001'));
		}
				$ciphertext_dec = base64_decode($packet['Encrypt']);
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
		$xml_len = $len_list[1];
		$xml_content = substr($content, 4, $xml_len);
		$from_appid = substr($content, $xml_len + 4);
		if ($from_appid != $appid) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40005 \n,错误描述为: " . $this->encryptErrorCode('40005'));
		}

		return $xml_content;
	}

	
	public function buildSignature($encrypt_msg) {
		$token = $this->account['token'];
		$array = array($encrypt_msg, $token, $_GET['timestamp'], $_GET['nonce']);
		sort($array, SORT_STRING);
		$str = implode($array);
		$str = sha1($str);

		return $str;
	}

	
	public function encryptMsg($text) {
		$token = $this->account['token'];
		$encodingaeskey = $this->account['encodingaeskey'];
		$appid = $this->account['encrypt_key'];

		$key = base64_decode($encodingaeskey . '=');
		$text = random(16) . pack('N', strlen($text)) . $text . $appid;
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
				$signature = $this->buildSignature($encrypt_msg);

		return array($signature, $encrypt_msg);
	}

	
	public function decryptMsg($postData) {
		$token = $this->account['token'];
		$encodingaeskey = $this->account['encodingaeskey'];
		$appid = $this->account['encrypt_key'];
		$key = base64_decode($encodingaeskey . '=');

		if (43 != strlen($encodingaeskey)) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40004 \n,错误描述为: " . $this->encryptErrorCode('40004'));
		}
				$packet = $this->xmlExtract($postData);
		if (is_error($packet)) {
			return error(-1, $packet['message']);
		}
				$istrue = $this->checkSignature($packet['encrypt']);
		if (!$istrue) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40001 \n,错误描述为: " . $this->encryptErrorCode('40001'));
		}
				$ciphertext_dec = base64_decode($packet['encrypt']);
		$iv = substr($key, 0, 16);
				$decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

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
		$xml_len = $len_list[1];
		$xml_content = substr($content, 4, $xml_len);
		$from_appid = substr($content, $xml_len + 4);
		if ($from_appid != $appid) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40005 \n,错误描述为: " . $this->encryptErrorCode('40005'));
		}

		return $xml_content;
	}

	
	public function xmlDetract($data) {
				$xml['Encrypt'] = $data[1];
		$xml['MsgSignature'] = $data[0];
		$xml['TimeStamp'] = $_GET['timestamp'];
		$xml['Nonce'] = $_GET['nonce'];

		return array2xml($xml);
	}

	
	public function xmlExtract($message) {
		$packet = array();
		if (!empty($message)) {
			$obj = isimplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
			if ($obj instanceof SimpleXMLElement) {
				$packet['encrypt'] = strval($obj->Encrypt);
				$packet['to'] = strval($obj->ToUserName);
			}
		}
		if (!empty($packet['encrypt'])) {
			return $packet;
		} else {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40002 \n,错误描述为: " . $this->encryptErrorCode('40002'));
		}
	}

	public function local_xmlExtract($message) {
		$packet = array();
		if (!empty($message)) {
			$obj = isimplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
			if ($obj instanceof SimpleXMLElement) {
				$packet['Encrypt'] = strval($obj->Encrypt);
				$packet['MsgSignature'] = strval($obj->MsgSignature);
				$packet['TimeStamp'] = strval($obj->TimeStamp);
				$packet['Nonce'] = strval($obj->Nonce);
			}
		}
		if (!empty($packet)) {
			return $packet;
		} else {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40002 \n,错误描述为: " . $this->encryptErrorCode('40002'));
		}
	}

	public function queryAvailableMessages() {
		$messages = array('text', 'image', 'voice', 'video', 'location', 'link', 'subscribe', 'unsubscribe');

		if (!empty($this->account['key']) && !empty($this->account['secret'])) {
			$level = intval($this->account['level']);
			if ($level > 1) {
				$messages[] = 'click';
				$messages[] = 'view';
			}
			if ($level > 2) {
				$messages[] = 'qr';
				$messages[] = 'trace';
			}
		}

		return $messages;
	}

	public function queryAvailablePackets() {
		$packets = array('text', 'music', 'news');
		if (!empty($this->account['key']) && !empty($this->account['secret'])) {
			if (intval($this->account['level']) > 1) {
				$packets[] = 'image';
				$packets[] = 'voice';
				$packets[] = 'video';
			}
		}

		return $packets;
	}

	
	public function isMenuSupported() {
		return	!empty($this->account['key']) &&
				!empty($this->account['secret']) &&
				(intval($this->account['level']) > 1);
	}

	public function menuCreate($menu) {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
		if (!empty($menu['matchrule'])) {
			$url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token={$token}";
		}
		$data = urldecode(json_encode($menu));
		$response = ihttp_post($url, $data);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result['menuid'];
	}

	
	public function menuBuild($data_array, $is_conditional = false) {
		$menu = array();
		if (empty($data_array) || empty($data_array['button']) || !is_array($data_array)) {
			return $menu;
		}
		foreach ($data_array['button'] as $button) {
			$temp = array();
			$temp['name'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $button['name']);
			$temp['name'] = urlencode($temp['name']);
			if (empty($button['sub_button'])) {
				$temp['type'] = $button['type'];
				if ('view' == $button['type']) {
					$temp['url'] = urlencode($button['url']);
				} elseif ('click' == $button['type']) {
					if (!empty($button['media_id']) && empty($button['key'])) {
						$temp['media_id'] = urlencode($button['media_id']);
						$temp['type'] = 'media_id';
					} elseif (empty($button['media_id']) && !empty($button['key'])) {
						$temp['type'] = 'click';
						$temp['key'] = urlencode($button['key']);
					}
				} elseif ('media_id' == $button['type'] || 'view_limited' == $button['type']) {
					$temp['media_id'] = urlencode($button['media_id']);
				} elseif ('miniprogram' == $button['type']) {
					$temp['appid'] = trim($button['appid']);
					$temp['pagepath'] = urlencode($button['pagepath']);
					$temp['url'] = urlencode($button['url']);
				} else {
					$temp['key'] = urlencode($button['key']);
				}
			} else {
				foreach ($button['sub_button'] as $sub_button) {
					$sub_temp = array();
					$sub_temp['name'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $sub_button['name']);
					$sub_temp['name'] = urlencode($sub_temp['name']);
					$sub_temp['type'] = $sub_button['type'];
					if ('view' == $sub_button['type']) {
						$sub_temp['url'] = urlencode($sub_button['url']);
					} elseif ('click' == $sub_button['type']) {
						if (!empty($sub_button['media_id']) && empty($sub_button['key'])) {
							$sub_temp['media_id'] = urlencode($sub_button['media_id']);
							$sub_temp['type'] = 'media_id';
						} elseif (empty($sub_button['media_id']) && !empty($sub_button['key'])) {
							$sub_temp['type'] = 'click';
							$sub_temp['key'] = urlencode($sub_button['key']);
						}
					} elseif ('media_id' == $sub_button['type'] || 'view_limited' == $sub_button['type']) {
						$sub_temp['media_id'] = urlencode($sub_button['media_id']);
					} elseif ('miniprogram' == $sub_button['type']) {
						$sub_temp['appid'] = trim($sub_button['appid']);
						$sub_temp['pagepath'] = urlencode($sub_button['pagepath']);
						$sub_temp['url'] = urlencode($sub_button['url']);
					} else {
						$sub_temp['key'] = urlencode($sub_button['key']);
					}
					$temp['sub_button'][] = $sub_temp;
				}
			}
			$menu['button'][] = $temp;
		}

		if (empty($is_conditional) || empty($data_array['matchrule']) || !is_array($data_array['matchrule'])) {
			return $menu;
		}

		if ($data_array['matchrule']['sex'] > 0) {
			$menu['matchrule']['sex'] = $data_array['matchrule']['sex'];
		}
		if ($data_array['matchrule']['group_id'] != -1) {
			$menu['matchrule']['tag_id'] = $data_array['matchrule']['group_id'];
		}
		if ($data_array['matchrule']['client_platform_type'] > 0) {
			$menu['matchrule']['client_platform_type'] = $data_array['matchrule']['client_platform_type'];
		}
		if (!empty($data_array['matchrule']['province'])) {
			$menu['matchrule']['country'] = urlencode('中国');
			$menu['matchrule']['province'] = urlencode($data_array['matchrule']['province']);
			if (!empty($data_array['matchrule']['city'])) {
				$menu['matchrule']['city'] = urlencode($data_array['matchrule']['city']);
			}
		}
		if (!empty($data_array['matchrule']['language'])) {
			$inarray = 0;
			$languages = menu_languages();
			foreach ($languages as $key => $value) {
				if (in_array($data_array['matchrule']['language'], $value, true)) {
					$inarray = 1;
					break;
				}
			}
			if (1 === $inarray) {
				$menu['matchrule']['language'] = $data_array['matchrule']['language'];
			}
		}

		return $menu;
	}

	public function menuDelete($menuid = 0) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		if ($menuid > 0) {
			$url = "https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token={$token}";
			$data = array(
				'menuid' => $menuid,
			);
			$response = ihttp_post($url, json_encode($data));
		} else {
			$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$token}";
			$response = ihttp_get($url);
		}
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return true;
	}

	public function menuModify($menu) {
		return $this->menuCreate($menu);
	}

	public function menuCurrentQuery() {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token={$token}";

		return $this->requestApi($url);
	}

	public function menuQuery() {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$token}";
		$response = ihttp_get($url);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
				if (!empty($result['errcode']) && '46003' != $result['errcode']) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function fansQueryInfo($uniid, $isOpen = true) {
		if ($isOpen) {
			$openid = $uniid;
		} else {
			exit('error');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
		$response = ihttp_get($url);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		preg_match('/city":"(.*)","province":"(.*)","country":"(.*)"/U', $response['content'], $reg_arr);
		$city = htmlentities(bin2hex($reg_arr[1]));
		$province = htmlentities(bin2hex($reg_arr[2]));
		$country = htmlentities(bin2hex($reg_arr[3]));
		$response['content'] = str_replace('"city":"' . $reg_arr[1] . '","province":"' . $reg_arr[2] . '","country":"' . $reg_arr[3] . '"', '"city":"' . $city . '","province":"' . $province . '","country":"' . $country . '"', $response['content']);
		$result = @json_decode($response['content'], true);
		$result['city'] = hex2bin(html_entity_decode($result['city']));
		$result['province'] = hex2bin(html_entity_decode($result['province']));
		$result['country'] = hex2bin(html_entity_decode($result['country']));
		$result['headimgurl'] = str_replace('http:', 'https:', $result['headimgurl']);
		unset($result['remark'], $result['subscribe_scene'], $result['qr_scene'], $result['qr_scene_str']);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error($result['errcode'], "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function fansBatchQueryInfo($data) {
		if (empty($data)) {
			return error(-1, '粉丝openid错误');
		}
		foreach ($data as $da) {
			$post[] = array(
				'openid' => trim($da),
				'lang' => 'zh-CN',
			);
		}
		$data = array();
		$data['user_list'] = $post;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$token}";
		$response = ihttp_post($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result['user_info_list'];
	}

	public function fansAll($startopenid = '') {
		global $_GPC;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $token;
		if (!empty($_GPC['next_openid'])) {
			$startopenid = $_GPC['next_openid'];
		}
		if (!empty($startopenid)) {
			$url .= '&next_openid=' . $startopenid;
		}
		$response = ihttp_get($url);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}
		$return = array();
		$return['total'] = $result['total'];
		$return['fans'] = $result['data']['openid'];
		$return['next'] = $result['next_openid'];

		return $return;
	}

	public function queryBarCodeActions() {
		return array('barCodeCreateDisposable', 'barCodeCreateFixed');
	}

	public function barCodeCreateDisposable($barcode) {
		$barcode['expire_seconds'] = empty($barcode['expire_seconds']) ? 2592000 : $barcode['expire_seconds'];
		if (empty($barcode['action_info']['scene']['scene_id']) || empty($barcode['action_name'])) {
			return error('1', 'Invalid params');
		}
		$token = $this->getAccessToken();
		$response = ihttp_request('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $token, json_encode($barcode));
		if (is_error($response)) {
			return $response;
		}
		$content = @json_decode($response['content'], true);

		if (empty($content)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		}
		if (!empty($content['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$content['errcode']}, 错误信息: {$content['errmsg']},错误详情：{$this->errorCode($content['errcode'])}");
		}

		return $content;
	}

	public function barCodeCreateFixed($barcode) {
		if ('QR_LIMIT_SCENE' == $barcode['action_name'] && empty($barcode['action_info']['scene']['scene_id'])) {
			return error('1', '场景值错误');
		}
		if ('QR_LIMIT_STR_SCENE' == $barcode['action_name'] && empty($barcode['action_info']['scene']['scene_str'])) {
			return error('1', '场景字符串错误');
		}
		$token = $this->getAccessToken();
		$response = ihttp_request('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $token, json_encode($barcode));
		if (is_error($response)) {
			return $response;
		}
		$content = @json_decode($response['content'], true);
		if (empty($content)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		}
		if (!empty($content['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$content['errcode']}, 错误信息: {$content['errmsg']},错误详情：{$this->errorCode($content['errcode'])}");
		}

		return $content;
	}

		private function encryptErrorCode($code) {
		$errors = array(
			'40001' => '签名验证错误',
			'40002' => 'xml解析失败',
			'40003' => 'sha加密生成签名失败',
			'40004' => 'encodingAesKey 非法',
			'40005' => 'appid 校验错误',
			'40006' => 'aes 加密失败',
			'40007' => 'aes 解密失败',
			'40008' => '解密后得到的buffer非法',
			'40009' => 'base64加密失败',
			'40010' => 'base64解密失败',
			'40011' => '生成xml失败',
		);
		if ($errors[$code]) {
			return $errors[$code];
		} else {
			return '未知错误';
		}
	}

	
	public function changeSend($send) {
		if (empty($send)) {
			return error(-1, 'Invalid params');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$sendapi = 'https://api.weixin.qq.com/pay/delivernotify?access_token=' . $token;
		$response = ihttp_request($sendapi, json_encode($send));
		$response = json_decode($response['content'], true);
		if (empty($response)) {
			return error(-1, '发货失败，请检查您的公众号权限或是公众号AppId和公众号AppSecret！');
		}
		if (!empty($response['errcode'])) {
			return error(-1, $response['errmsg']);
		}

		return true;
	}

	
	public function getAccessToken() {
		$cachekey = cache_system_key('accesstoken', array('uniacid' => $this->account['uniacid']));
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['token']) && $cache['expire'] > TIMESTAMP) {
			$this->account['access_token'] = $cache;

			return $cache['token'];
		}
		if (empty($this->account['key']) || empty($this->account['secret'])) {
			return error('-1', '未填写公众号的 appid 或 appsecret！');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->account['key']}&secret={$this->account['secret']}";
		$content = ihttp_get($url);
		if (is_error($content)) {
			return error('-1', '获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message']);
		}
		if (empty($content['content'])) {
			return error('-1', 'AccessToken获取失败，请检查appid和appsecret的值是否与微信公众平台一致！');
		}
		$token = @json_decode($content['content'], true);

		if ('40164' == $token['errcode']) {
			return error(-1, $this->errorCode($token['errcode'], $token['errmsg']));
		}
		if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			return error('-1', '获取微信公众号授权失败！错误代码:' . $token['errcode'] . '，错误信息:' . $this->errorCode($token['errcode']));
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'] - 200;
		$this->account['access_token'] = $record;
		cache_write($cachekey, $record);

		return $record['token'];
	}

	public function getVailableAccessToken() {
		$accounts = pdo_fetchall('SELECT `key`, `secret`, `acid` FROM ' . tablename('account_wechats') . ' WHERE uniacid = :uniacid ORDER BY `level` DESC ', array(':uniacid' => $GLOBALS['_W']['uniacid']));
		if (empty($accounts)) {
			return error(-1, 'no permission');
		}
		foreach ($accounts as $account) {
			if (empty($account['key']) || empty($account['secret'])) {
				continue;
			}
			$acid = $account['acid'];
			break;
		}
		$account = WeAccount::create($acid);

		return $account->getAccessToken();
	}

	public function fetch_token() {
		return $this->getAccessToken();
	}

	public function fetch_available_token() {
		return $this->getVailableAccessToken();
	}

	public function clearAccessToken() {
		$access_token = $this->getAccessToken();
		if (is_error($access_token)) {
			return $access_token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $access_token;
		$response = $this->requestApi($url);
		if (is_error($response) && '40001' == $response['errno']) {
			cache_delete(cache_system_key('accesstoken', array('uniacid' => $this->account['uniacid'])));
		}

		return true;
	}

	
	public function getJsApiTicket() {
		$cachekey = cache_system_key('jsticket', array('uniacid' => $this->account['uniacid']));
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['ticket']) && $cache['expire'] > TIMESTAMP) {
			return $cache['ticket'];
		}
		$access_token = $this->getAccessToken();
		if (is_error($access_token)) {
			return $access_token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
		$content = ihttp_get($url);
		if (is_error($content)) {
			return error(-1, '调用接口获取微信公众号 jsapi_ticket 失败, 错误信息: ' . $this->errorCode($content['message']));
		}
		$result = @json_decode($content['content'], true);
		if (empty($result) || 0 != intval(($result['errcode'])) || 'ok' != $result['errmsg']) {
			return error(-1, '获取微信公众号 jsapi_ticket 结果错误, 错误信息: ' . $this->errorCode($result['errcode'], $result['errmsg']));
		}
		$record = array();
		$record['ticket'] = $result['ticket'];
		$record['expire'] = TIMESTAMP + $result['expires_in'] - 200;
		$this->account['jsapi_ticket'] = $record;
		cache_write($cachekey, $record);

		return $record['ticket'];
	}

	
	public function getJssdkConfig($url = '') {
		global $_W;
		$jsapiTicket = $this->getJsApiTicket();
		if (is_error($jsapiTicket)) {
			$jsapiTicket = $jsapiTicket['message'];
		}
		$nonceStr = random(16);
		$timestamp = TIMESTAMP;
		$url = empty($url) ? $_W['siteurl'] : $url;
		$string1 = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
		$signature = sha1($string1);
		$config = array(
			'appId' => $this->account['key'],
			'nonceStr' => $nonceStr,
			'timestamp' => "$timestamp",
			'signature' => $signature,
		);
		if (DEVELOPMENT) {
			$config['url'] = $url;
			$config['string1'] = $string1;
			$config['name'] = $this->account['name'];
		}

		return $config;
	}

	
	public function long2short($longurl) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
		$send = array();
		$send['action'] = 'long2short';
		$send['long_url'] = $longurl;
		$response = ihttp_request($url, json_encode($send));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$this->errorCode($response['message'])}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function fetchChatLog($params = array()) {
		if (empty($params['starttime']) || empty($params['endtime'])) {
			return error(-1, '没有要查询的时间段');
		}
		$starttmp = date('Y-m-d', $params['starttime']);
		$endtmp = date('Y-m-d', $params['endtime']);
		if ($starttmp != $endtmp) {
			return error(-1, '时间范围有误，微信公众平台不支持跨日查询');
		}
		if (empty($params['openid'])) {
			return error(-1, '没有要查询的openid');
		}
		if (empty($params['pagesize'])) {
			$params['pagesize'] = 50;
		}
		if (empty($params['pageindex'])) {
			$params['pageindex'] = 1;
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/customservice/msgrecord/getrecord?access_token={$token}";
		$response = ihttp_request($url, json_encode($params));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function isTagSupported() {
		return (!empty($this->account['key']) &&
		!empty($this->account['secret']) || ACCOUNT_OAUTH_LOGIN == $this->account['type']) &&
		(intval($this->account['level']) > ACCOUNT_SERVICE);
	}

	
	public function fansTagAdd($tagname) {
		if (empty($tagname)) {
			return error(-1, '请填写标签名称');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token={$token}";
				$data = stripslashes(ijson_encode(array('tag' => array('name' => $tagname)), JSON_UNESCAPED_UNICODE));
		cache_delete(cache_system_key('account_tags', array('uniacid' => $this->account['uniacid'])));

		return $this->requestApi($url, $data);
	}

	
	public function fansTagFetchAll() {
		$cachekey = cache_system_key('account_tags', array('uniacid' => $this->account['uniacid']));
		$cache = cache_load($cachekey);
		if (!empty($cache['tags']) && $cache['expire'] > TIMESTAMP) {
			return $cache['tags'];
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token={$token}";
		$tags = $this->requestApi($url);
		if (!is_error($tags)) {
			cache_write($cachekey, array('tags' => $tags, 'expire' => TIMESTAMP + 3600));
		}
		return $tags;
	}

	
	public function fansTagEdit($tagid, $tagname) {
		if (empty($tagid) || empty($tagname)) {
			return error(-1, '标签信息错误');
		}
		if (in_array($tagid, array(1, 2))) {
			return error(-1, '微信平台默认标签，不能修改');
		}

		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/update?access_token={$token}";
		$data = stripslashes(ijson_encode(array('tag' => array('id' => $tagid, 'name' => $tagname)), JSON_UNESCAPED_UNICODE));
		$result = $this->requestApi($url, $data);
		if (is_error($result)) {
			return $result;
		}
		cache_delete(cache_system_key('account_tags', array('uniacid' => $this->account['uniacid'])));

		return true;
	}

	
	public function fansTagDelete($tagid) {
		$tagid = intval($tagid);
		if (empty($tagid)) {
			return error(-1, '标签id错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/delete?access_token={$token}";
		$data = json_encode(array('tag' => array('id' => $tagid)));
		$result = $this->requestApi($url, $data);
		if (is_error($result)) {
			return $result;
		}
		cache_delete(cache_system_key('account_tags', array('uniacid' => $this->account['uniacid'])));

		return true;
	}

	
	public function fansTagGetUserlist($tagid, $next_openid = '') {
		$tagid = intval($tagid);
		$next_openid = (string) $next_openid;
		if (empty($tagid)) {
			return error(-1, '标签id错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=' . $token;
		$data = array(
			'tagid' => $tagid,
		);
		if (!empty($next_openid)) {
			$data['next_openid'] = $next_openid;
		}
		$data = json_encode($data);

		return $this->requestApi($url, $data);
	}

	
	public function fansTagTagging($openid, $tagids) {
		$openid = (string) $openid;
		$tagids = (array) $tagids;
		if (empty($openid)) {
			return error(-1, '没有填写用户openid');
		}
		if (empty($tagids)) {
			return error(-1, '没有填写标签');
		}
		if (count($tagids) > 3) {
			return error(-1, '最多3个标签');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
				$fetch_result = $this->fansTagFetchOwnTags($openid);
		if (is_error($fetch_result)) {
			return $fetch_result;
		}
		foreach ($fetch_result['tagid_list'] as $del_tagid) {
			$this->fansTagBatchUntagging($openid, $del_tagid);
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token={$token}";
		foreach ($tagids as $tagid) {
			$data = array(
				'openid_list' => $openid,
				'tagid' => $tagid,
			);
			$data = json_encode($data);
			$result = $this->requestApi($url, $data);
			if (is_error($result)) {
				return $result;
			}
		}

		return true;
	}

	
	public function fansTagBatchTagging($openid_list, $tagid) {
		$openid_list = (array) $openid_list;
		$tagid = (int) $tagid;
		if (empty($openid_list)) {
			return error(-1, '没有填写用户openid列表');
		}
		if (empty($tagid)) {
			return error(-1, '没有填写tagid');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token={$token}";
		$data = array(
			'openid_list' => $openid_list,
			'tagid' => $tagid,
		);
		$data = json_encode($data);
		$result = $this->requestApi($url, $data);
		if (is_error($result)) {
			return $result;
		}

		return true;
	}

	
	public function fansTagBatchUntagging($openid_list, $tagid) {
		$openid_list = (array) $openid_list;
		$tagid = (int) $tagid;
		if (empty($openid_list)) {
			return error(-1, '没有填写用户openid列表');
		}
		if (empty($tagid)) {
			return error(-1, '没有填写tagid');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token={$token}";
		$data = array(
			'openid_list' => $openid_list,
			'tagid' => $tagid,
		);
		$data = json_encode($data);
		$result = $this->requestApi($url, $data);
		if (is_error($result)) {
			return $result;
		}

		return true;
	}

	
	public function fansTagFetchOwnTags($openid) {
		$openid = (string) $openid;
		if (empty($openid)) {
			return error(-1, '没有填写用户openid');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token={$token}";
		$data = json_encode(array('openid' => $openid));

		return $this->requestApi($url, $data);
	}

	
	public function fansSendAll($group, $msgtype, $media_id) {
		$types = array('text' => 'text', 'basic' => 'text', 'image' => 'image', 'news' => 'mpnews', 'voice' => 'voice', 'video' => 'mpvideo', 'wxcard' => 'wxcard');
		if (empty($types[$msgtype])) {
			return error(-1, '消息类型不合法');
		}
		$is_to_all = false;
		if ($group == -1) {
			$is_to_all = true;
		}
		$send_conent = ('text' == $types[$msgtype]) ? array('content' => $media_id) : array('media_id' => $media_id);
		$data = array(
				'filter' => array(
						'is_to_all' => $is_to_all,
						'tag_id' => $group,
				),
				'msgtype' => $types[$msgtype],
				$types[$msgtype] => $send_conent,
		);
		if ('wxcard' == $types[$msgtype]) {
			unset($data['wxcard']['media_id']);
			$data['wxcard']['card_id'] = $media_id;
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$token}";
		$data = urldecode(json_encode($data, JSON_UNESCAPED_UNICODE));
		$response = ihttp_request($url, $data);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function fansSendPreview($wxname, $content, $msgtype) {
		$types = array('text' => 'text', 'image' => 'image', 'news' => 'mpnews', 'voice' => 'voice', 'video' => 'mpvideo', 'wxcard' => 'wxcard');
		if (empty($types[$msgtype])) {
			return error(-1, '群发类型不合法');
		}
		$msgtype = $types[$msgtype];
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=' . $token;
		$send = array(
				'towxname' => $wxname,
				'msgtype' => $msgtype,
		);
		if ('text' == $msgtype) {
			$send[$msgtype] = array(
					'content' => $content,
			);
		} elseif ('wxcard' == $msgtype) {
			$send[$msgtype] = array(
					'card_id' => $content,
			);
		} else {
			$send[$msgtype] = array(
					'media_id' => $content,
			);
		}

		$response = ihttp_request($url, json_encode($send, JSON_UNESCAPED_UNICODE));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function sendCustomNotice($data) {
		if (empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return true;
	}

	
	public function sendTplNotice($touser, $template_id, $postdata, $url = '', $topcolor = '#FF683F', $miniprogram = array('appid' => '', 'pagepath' => '')) {
		if (empty($this->account['key']) || ACCOUNT_SERVICE_VERIFY != $this->account['level']) {
			return error(-1, '你的公众号没有发送模板消息的权限');
		}
		if (empty($touser)) {
			return error(-1, '参数错误,粉丝openid不能为空');
		}
		if (empty($template_id)) {
			return error(-1, '参数错误,模板标示不能为空');
		}
		if (empty($postdata) || !is_array($postdata)) {
			return error(-1, '参数错误,请根据模板规则完善消息内容');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		
		$data = array();
		if (!empty($miniprogram['appid']) && !empty($miniprogram['pagepath'])) {
			$data['miniprogram'] = $miniprogram;
		}
		$data['touser'] = $touser;
		$data['template_id'] = trim($template_id);
		$data['url'] = trim($url);
		$data['topcolor'] = trim($topcolor);
		$data['data'] = $postdata;
		$data = json_encode($data);
		$post_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
		$response = ihttp_request($post_url, $data);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->errorCode($result['errcode'])}");
		}

		return true;
	}

	
	public function uploadMedia($path, $type = 'image') {
		if (empty($path)) {
			return error(-1, '参数错误');
		}
		if (in_array(substr(ltrim($path, '/'), 0, 6), array('images', 'videos', 'audios', 'thumb'))) {
			$path = ATTACHMENT_ROOT . ltrim($path, '/');
		}
		if (!file_exists($path)) {
			return error(1, '文件不存在');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}";
		$data = array(
			'media' => '@' . $path,
		);

		return $this->requestApi($url, $data);
	}

	
	public function uploadMediaFixed($path, $type = 'images') {
		global $_W;
		if (empty($path)) {
			return error(-1, '参数错误');
		}
		if (in_array(substr(ltrim($path, '/'), 0, 6), array('images', 'videos', 'audios', 'thumb', 'voices'))) {
			$path = ATTACHMENT_ROOT . ltrim($path, '/');
		}
		if (!file_exists($path)) {
			return error(1, '文件不存在');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$token}&type={$type}";
		$data = array(
			'media' => '@' . $path,
		);

		if ('videos' == $type) {
			$video_filename = ltrim($path, ATTACHMENT_ROOT);
			$material = $material = pdo_get('core_attachment', array('uniacid' => $_W['uniacid'], 'attachment' => $video_filename));
		}
		$filename = pathinfo($path, PATHINFO_FILENAME);
		$description = array(
			'title' => 'videos' == $type ? $material['filename'] : $filename,
			'introduction' => $filename,
		);
		$data['description'] = urldecode(json_encode($description));

		return $this->requestApi($url, $data);
	}

	
	public function editMaterialNews($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token={$token}";
		$response = $this->requestApi($url, stripslashes(ijson_encode($data, JSON_UNESCAPED_UNICODE)));
		if (is_error($response)) {
			return $response;
		}

		return true;
	}

	
	public function uploadNewsThumb($thumb) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		if (!file_exists($thumb)) {
			return error(1, '文件不存在');
		}
		$data = array(
			'media' => '@' . $thumb,
		);
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token={$token}";
		$response = $this->requestApi($url, $data);
		if (is_error($response)) {
			return $response;
		} else {
			return $response['url'];
		}
	}

	public function uploadVideoFixed($title, $description, $path) {
		if (empty($path) || empty($title) || empty($description)) {
			return error(-1, '参数错误');
		}
		if (in_array(substr(ltrim($path, '/'), 0, 6), array('images', 'videos', 'audios'))) {
			$path = ATTACHMENT_ROOT . ltrim($path, '/');
		}
		if (!file_exists($path)) {
			return error(1, '文件不存在');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$token}&type=videos";
		$data = array(
			'media' => '@' . $path,
			'description' => stripslashes(ijson_encode(array('title' => $title, 'introduction' => $description), JSON_UNESCAPED_UNICODE)),
		);

		return $this->requestApi($url, $data);
	}

	
	public function uploadVideo($data) {
		if (empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://file.api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}, 错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function uploadNews($data) {
		if (empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function addMatrialNews($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token={$token}";
		$data = stripslashes(urldecode(ijson_encode($data, JSON_UNESCAPED_UNICODE)));
		$response = $this->requestApi($url, $data);
		if (is_error($response)) {
			return $response;
		}

		return $response['media_id'];
	}

	
	public function batchGetMaterial($type = 'news', $offset = 0, $count = 20) {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $token;
		$data = array(
			'type' => $type,
			'offset' => intval($offset),
			'count' => $count,
		);

		return $this->requestApi($url, json_encode($data));
	}

	
	public function getMaterial($media_id, $savefile = true) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=' . $token;
		$data = array(
			'media_id' => trim($media_id),
		);
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (!empty($result['errcode'])) {
			return error(-1, "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}
		if (empty($response['headers']['Content-disposition'])) {
			$response = json_decode($response['content'], true);
			if (!empty($response['down_url'])) {
				if (empty($savefile)) {
					return $response;
				}
				$response = ihttp_get($response['down_url']);
								$response['headers']['Content-disposition'] = $response['headers']['Content-Disposition'];
			} elseif (!empty($response['news_item'])) {
				return $response;
			}
		}
		if ($savefile && !empty($response['headers']['Content-disposition']) && strexists($response['headers']['Content-disposition'], 'filename=')) {
			global $_W;
			preg_match('/filename=\"?([^"]*)/', $response['headers']['Content-disposition'], $match);
			$pathinfo = pathinfo($match[1]);
			$filename = $_W['uniacid'] . '/' . date('Y/m/');
			if (in_array(strtolower($pathinfo['extension']), array('mp4'))) {
				$filename = 'videos/' . $filename;
			} elseif (in_array(strtolower($pathinfo['extension']), array('amr', 'mp3', 'wma', 'wmv'))) {
				$filename = 'audios/' . $filename;
			} else {
				$filename = 'images/' . $filename;
			}
			$filename .= file_random_name($filename, $pathinfo['extension']);
			load()->func('file');
			file_write($filename, $response['content']);
			file_remote_upload($filename);

			return $filename;
		} else {
			return $response['content'];
		}

		return $result;
	}

	
	public function downloadMedia($media_id, $savefile = true) {
		$mediatypes = array('image', 'voice', 'thumb');
		$media_id = is_array($media_id) ? $media_id['media_id'] : $media_id;
		if (empty($media_id)) {
			return error(-1, '微信下载媒体资源参数错误');
		}

		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$token}&media_id={$media_id}";
		$response = ihttp_get($url);

		if (empty($response['headers']['Content-disposition'])) {
			$response = json_decode($response['content'], true);
			if (!empty($response['video_url'])) {
				$response = ihttp_get($response['video_url']);
								$response['headers']['Content-disposition'] = $response['headers']['Content-Disposition'];
			}
		}
		if ($savefile && !empty($response['headers']['Content-disposition']) && strexists($response['headers']['Content-disposition'], 'filename=')) {
			global $_W;
			preg_match('/filename=\"?([^"]*)/', $response['headers']['Content-disposition'], $match);
			$filename = $_W['uniacid'] . '/' . date('Y/m/') . $match[1];
			$pathinfo = pathinfo($filename);
			if (in_array(strtolower($pathinfo['extension']), array('mp4'))) {
				$filename = 'videos/' . $filename;
			} elseif (in_array(strtolower($pathinfo['extension']), array('amr', 'mp3', 'wma', 'wmv'))) {
				$filename = 'audios/' . $filename;
			} else {
				$filename = 'images/' . $filename;
			}
			load()->func('file');
			file_write($filename, $response['content']);
			file_remote_upload($filename);

			return $filename;
		} else {
			return $response['content'];
		}
	}

	
	public function getMaterialCount() {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=' . $token;

		return $this->requestApi($url);
	}

	public function delMaterial($media_id) {
		$media_id = trim($media_id);
		if (empty($media_id)) {
			return error(-1, '素材media_id错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=' . $token;
		$data = array(
			'media_id' => trim($media_id),
		);
		$response = $this->requestApi($url, json_encode($data));
		if (is_error($response)) {
			return $response;
		}

		return true;
	}

	
	public function changeOrderStatus($send) {
		if (empty($send)) {
			return error(-1, '参数错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$sendapi = 'https://api.weixin.qq.com/pay/delivernotify?access_token=' . $token;
		$response = ihttp_request($sendapi, json_encode($send));
		$response = json_decode($response['content'], true);
		if (empty($response)) {
			return error(-1, '发货失败，请检查您的公众号权限或是公众号AppId和公众号AppSecret！');
		}
		if (!empty($response['errcode'])) {
			return error(-1, $response['errmsg']);
		}

		return $response;
	}

	public function getOauthUserInfo($accesstoken, $openid) {
		$apiurl = "https://api.weixin.qq.com/sns/userinfo?access_token={$accesstoken}&openid={$openid}&lang=zh_CN";
		$response = $this->requestApi($apiurl);
		unset($response['remark'], $response['subscribe_scene'], $response['qr_scene'], $response['qr_scene_str']);

		return $response;
	}

	public function getOauthInfo($code = '') {
		global $_W, $_GPC;
		if (!empty($_GPC['code'])) {
			$code = $_GPC['code'];
		}
		if (empty($code)) {
			$oauth_url = uni_account_oauth_host();
			$url = $oauth_url . "app/index.php?{$_SERVER['QUERY_STRING']}";
			$forward = $this->getOauthCodeUrl(urlencode($url));
			header('Location: ' . $forward);
			exit;
		}
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->account['key']}&secret={$this->account['secret']}&code={$code}&grant_type=authorization_code";

		return $this->requestApi($url);
	}

	public function getOauthAccessToken() {
		$cachekey = cache_system_key('oauthaccesstoken', array('acid' => $this->account['acid']));
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['token']) && $cache['expire'] > TIMESTAMP) {
			return $cache['token'];
		}
		$token = $this->getOauthInfo();
		if (is_error($token)) {
			return error(1);
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'] - 200;
		cache_write($cachekey, $record);

		return $token['access_token'];
	}

	
	public function getShareAddressConfig() {
		global $_W;
		static $current_url;
		if (empty($current_url)) {
			$current_url = $_W['siteurl'];
		}
		$token = $this->getOauthAccessToken();
		if (is_error($token)) {
			return false;
		}
		$package = array(
			'appid' => $this->account['key'],
			'url' => $current_url,
			'timestamp' => strval(TIMESTAMP),
			'noncestr' => strval(random(8, true)),
			'accesstoken' => $token,
		);
		ksort($package, SORT_STRING);
		$signstring = array();
		foreach ($package as $k => $v) {
			$signstring[] = "{$k}={$v}";
		}
		$signstring = strtolower(sha1(trim(implode('&', $signstring))));
		$shareaddress_config = array(
			'appId' => $this->account['key'],
			'scope' => 'jsapi_address',
			'signType' => 'sha1',
			'addrSign' => $signstring,
			'timeStamp' => $package['timestamp'],
			'nonceStr' => $package['noncestr'],
		);

		return $shareaddress_config;
	}

	public function getOauthCodeUrl($callback, $state = '') {
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
	}

	public function getOauthUserInfoUrl($callback, $state = '') {
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
	}

	public function getFansStat() {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/datacube/getusersummary?access_token={$token}";
		$data = array(
			'begin_date' => date('Y-m-d', strtotime('-7 days')),
			'end_date' => date('Y-m-d', strtotime('-1 days')),
		);
		$summary_response = $this->requestApi($url, json_encode($data));
		if (is_error($summary_response)) {
			return $summary_response;
		}

		$url = "https://api.weixin.qq.com/datacube/getusercumulate?access_token={$token}";
		$cumulate_response = $this->requestApi($url, json_encode($data));
		if (is_error($cumulate_response)) {
			return $cumulate_response;
		}

		$result = array();
		if (!empty($summary_response['list'])) {
			foreach ($summary_response['list'] as $row) {
				$key = str_replace('-', '', $row['ref_date']);
				$result[$key]['new'] = intval($result[$key]['new']) + $row['new_user'];
				$result[$key]['cancel'] = intval($result[$key]['cancel']) + $row['cancel_user'];
			}
		}
		if (!empty($cumulate_response['list'])) {
			foreach ($cumulate_response['list'] as $row) {
				$key = str_replace('-', '', $row['ref_date']);
				$result[$key]['cumulate'] = $row['cumulate_user'];
			}
		}

		return $result;
	}

	
	public function getComment($msg_data_id, $index, $type = 0, $begin = 0, $count = 50) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/comment/list?access_token={$token}";
		$data = array(
			'msg_data_id' => $msg_data_id,
			'index' => $index,
			'begin' => $begin,
			'count' => $count,
			'type' => $type,
		);

		return $this->requestApi($url, json_encode($data));
	}

	
	public function commentReply($msg_data_id, $user_comment_id, $content, $index = 0) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/comment/reply/add?access_token={$token}";
		$data = array(
			'msg_data_id' => $msg_data_id,
			'user_comment_id' => $user_comment_id,
			'content' => $content,
			'index' => $index,
		);

		return $this->requestApi($url, stripslashes(ijson_encode($data, JSON_UNESCAPED_UNICODE)));
	}

	
	public function commentMark($msg_data_id, $user_comment_id, $comment_type, $index = 0) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		if (1 != $comment_type) {
			$url = "https://api.weixin.qq.com/cgi-bin/comment/markelect?access_token={$token}";
		} else {
			$url = "https://api.weixin.qq.com/cgi-bin/comment/unmarkelect?access_token={$token}";
		}

		$data = array(
			'msg_data_id' => $msg_data_id,
			'user_comment_id' => $user_comment_id,
			'index' => $index,
		);

		return $this->requestApi($url, json_encode($data));
	}

	
	public function commentDelete($msg_data_id, $user_comment_id, $index = 0) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/comment/delete?access_token={$token}";

		$data = array(
			'msg_data_id' => $msg_data_id,
			'user_comment_id' => $user_comment_id,
			'index' => $index,
		);

		return $this->requestApi($url, json_encode($data));
	}

	
	public function commentReplyDelete($msg_data_id, $user_comment_id, $index = 0) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/comment/reply/delete?access_token={$token}";
		$data = array(
			'msg_data_id' => $msg_data_id,
			'user_comment_id' => $user_comment_id,
			'index' => $index,
		);

		return $this->requestApi($url, json_encode($data));
	}

	
	public function commentSwitch($msg_data_id, $need_open_comment, $index = 0) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		if (1 == $need_open_comment) {
			$url = "https://api.weixin.qq.com/cgi-bin/comment/close?access_token={$token}";
		} else {
			$url = "https://api.weixin.qq.com/cgi-bin/comment/open?access_token={$token}";
		}

		$data = array(
			'msg_data_id' => $msg_data_id,
			'index' => $index,
		);

		return $this->requestApi($url, json_encode($data));
	}

	protected function requestApi($url, $post = '') {
		$response = ihttp_request($url, $post);

		$result = @json_decode($response['content'], true);
		if (is_error($response)) {
			return error($result['errcode'], "访问公众平台接口失败, 错误详情: {$this->errorCode($result['errcode'])}");
		}
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error($result['errcode'], "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function getMaterialSupport() {
		return array(
			'mass' => array('basic' => false, 'news' => false, 'image' => false, 'voice' => false, 'video' => false),
			'chats' => array('basic' => false, 'news' => false, 'image' => false, 'music' => false, 'voice' => false, 'video' => false),
		);
	}
}