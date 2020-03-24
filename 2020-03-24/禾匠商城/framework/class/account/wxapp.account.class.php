<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->func('communication');

class WxappAccount extends WeAccount {
	protected $tablename = 'account_wxapp';
	protected $menuFrame = 'wxapp';
	protected $type = ACCOUNT_TYPE_APP_NORMAL;
	protected $typeName = '微信小程序';
	protected $typeTempalte = '-wxapp';
	protected $typeSign = WXAPP_TYPE_SIGN;
	protected $supportVersion = STATUS_ON;

	protected function getAccountInfo($acid) {
		$account = table('account_wxapp')->getAccount($acid);
		$account['encrypt_key'] = $account['key'];

		return $account;
	}

	public function getOauthInfo($code = '') {
		global $_W, $_GPC;
		if (!empty($_GPC['code'])) {
			$code = $_GPC['code'];
		}
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->account['key']}&secret={$this->account['secret']}&js_code={$code}&grant_type=authorization_code";

		return $response = $this->requestApi($url);
	}

	public function getOauthCodeUrl($callback, $state = '') {
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
	}

	public function getOauthUserInfoUrl($callback, $state = '') {
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
	}

	
	public function checkSign() {
		$token = $this->account['token'];
		$signkey = array($token, $_GET['timestamp'], $_GET['nonce']);
		sort($signkey, SORT_STRING);
		$signString = implode($signkey);
		$signString = sha1($signString);

		return $signString == $_GET['signature'];
	}

	
	public function pkcs7Encode($encrypt_data, $iv) {
		$key = base64_decode($_SESSION['session_key']);
		$result = aes_pkcs7_decode($encrypt_data, $key, $iv);
		if (is_error($result)) {
			return error(1, '解密失败');
		}
		$result = json_decode($result, true);
		if (empty($result)) {
			return error(1, '解密失败');
		}
		if ($result['watermark']['appid'] != $this->account['key']) {
			return error(1, '解密失败');
		}
		unset($result['watermark']);

		return $result;
	}

	public function getAccessToken() {
		$cachekey = cache_system_key('accesstoken_key', array('key' => $this->account['key']));
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['token']) && $cache['expire'] > TIMESTAMP) {
			$this->account['access_token'] = $cache;

			return $cache['token'];
		}

		if (empty($this->account['key']) || empty($this->account['secret'])) {
			return error('-1', '未填写小程序的 appid 或 appsecret！');
		}

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->account['key']}&secret={$this->account['secret']}";
		$response = $this->requestApi($url);
		if (is_error($response)) {
			return $response;
		}
		$record = array();
		$record['token'] = $response['access_token'];
		$record['expire'] = TIMESTAMP + $response['expires_in'] - 200;

		$this->account['access_token'] = $record;
		cache_write($cachekey, $record);

		return $record['token'];
	}

	public function getJssdkConfig($url = '') {
		return array();
	}

	
	public function getCodeLimit($path, $width = '430', $option = array()) {
		if (!preg_match('/[0-9a-zA-Z\&\/\:\=\?\-\.\_\~\@]{1,128}/', $path)) {
			return error(1, '路径值不合法');
		}
		$access_token = $this->getAccessToken();
		if (is_error($access_token)) {
			return $access_token;
		}
		$data = array(
			'path' => $path,
			'width' => intval($width),
		);
		if (!empty($option['auto_color'])) {
			$data['auto_color'] = intval($option['auto_color']);
		}
		if (!empty($option['line_color'])) {
			$data['line_color'] = array(
				'r' => $option['line_color']['r'],
				'g' => $option['line_color']['g'],
				'b' => $option['line_color']['b'],
			);
			$data['auto_color'] = false;
		}
		$url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token=' . $access_token;
		$response = $this->requestApi($url, json_encode($data));
		if (is_error($response)) {
			return $response;
		}

		return $response['content'];
	}

	public function getCodeUnlimit($scene, $page = '', $width = '430', $option = array()) {
		if (!preg_match('/[0-9a-zA-Z\!\#\$\&\'\(\)\*\+\,\/\:\;\=\?\@\-\.\_\~]{1,32}/', $scene)) {
			return error(1, '场景值不合法');
		}
		$access_token = $this->getAccessToken();
		if (is_error($access_token)) {
			return $access_token;
		}
		$data = array(
			'scene' => $scene,
			'width' => intval($width),
		);
		if (!empty($page)) {
			$data['page'] = $page;
		}
		if (!empty($option['auto_color'])) {
			$data['auto_color'] = intval($option['auto_color']);
		}

		if (!empty($option['line_color'])) {
			$data['line_color'] = array(
				'r' => $option['line_color']['r'],
				'g' => $option['line_color']['g'],
				'b' => $option['line_color']['b'],
			);
			$data['auto_color'] = false;
		}
		$url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
		$response = $this->requestApi($url, json_encode($data));
		if (is_error($response)) {
			return $response;
		}

		return $response['content'];
	}

	public function getQrcode() {
	}

	protected function requestApi($url, $post = '') {
		$response = ihttp_request($url, $post);
		$result = @json_decode($response['content'], true);
		if (is_error($response)) {
			return error($result['errcode'], "访问公众平台接口失败, 错误详情: {$this->errorCode($result['errcode'])}");
		}
		if (empty($result)) {
			return $response;
		} elseif (!empty($result['errcode'])) {
			return error($result['errcode'], "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function result($errno, $message = '', $data = '') {
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}

	public function getDailyVisitTrend($date) {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend?access_token={$token}";
		$data = array(
			'begin_date' => $date,
			'end_date' => $date,
		);

		$response = $this->requestApi($url, json_encode($data));
		if (is_error($response)) {
			return $response;
		}

		return $response['list'][0];
	}
}