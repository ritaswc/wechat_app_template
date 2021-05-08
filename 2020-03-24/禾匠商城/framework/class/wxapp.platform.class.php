<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


class WxAppPlatform extends WeiXinPlatform {

	const JSCODEURL = 'https://api.weixin.qq.com/sns/component/jscode2session?appid=%s&js_code=%s&grant_type=authorization_code&component_appid=%s&component_access_token=%s';


	function __construct($account = array()) {
	
		parent::__construct($account);
		$this->menuFrame = 'wxapp';
		$this->type = ACCOUNT_TYPE_APP_AUTH;
		$this->typeName =  '小程序';
		$this->typeSign = WEBAPP_TYPE_SIGN;
		
	}

	function fetchAccountInfo() {
		if ($this->uniaccount['key'] == 'wx570bc396a51b8ff8') {
			$this->uniaccount['key'] = $this->appid;
			$this->account = $this->uniaccount;
			$this->openPlatformTestCase();
		}
		$account_table = table('account');
		$account = $account_table->getWxappAccount($this->uniaccount['acid']);
		$account['encrypt_key'] = $this->appid;
		return $account;
	}
 
	function accountDisplayUrl() {
		return url('account/display', array('type' => WXAPP_TYPE_SIGN));
	}

	

	public function getAuthLoginUrl() {
		$preauthcode = $this->getPreauthCode();
		if (is_error($preauthcode)) {
			$authurl = "javascript:alert('{$preauthcode['message']}');";
		} else {
			$authurl = sprintf(ACCOUNT_PLATFORM_API_LOGIN, $this->appid, $preauthcode, urlencode($GLOBALS['_W']['siteroot'] . 'index.php?c=wxapp&a=auth&do=forward'), ACCOUNT_PLATFORM_API_LOGIN_WXAPP);
		}
		return $authurl;
	}


	public function getOauthInfo($code = '') {
		$component_accesstoken = $this->getComponentAccesstoken();
		if (is_error($component_accesstoken)) {
			return $component_accesstoken;
		}
		$apiurl = sprintf(self::JSCODEURL, $this->account['key'], $code, $this->appid, $component_accesstoken);

		$response = $this->request($apiurl);
		if (is_error($response)) {
			return $response;
		}
	
		cache_write(cache_system_key('account_auth_accesstoken', array('key' => $this->account['key'])), $response['refresh_token']);
		return $response;
	}

	protected function setAuthRefreshToken($token) {
		$tablename = 'account_wxapp';
		pdo_update($tablename, array('auth_refresh_token' => $token), array('acid' => $this->account['acid']));
		cache_write(cache_system_key('account_auth_accesstoken', array('key' => $this->account['key'])), $token);
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

	public function result($errno, $message = '', $data = '') {
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}
}