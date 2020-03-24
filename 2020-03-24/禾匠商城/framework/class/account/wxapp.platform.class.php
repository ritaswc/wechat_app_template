<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->classs('weixin.platform');

class WxappPlatform extends WeixinPlatform {
	const JSCODEURL = 'https://api.weixin.qq.com/sns/component/jscode2session?appid=%s&js_code=%s&grant_type=authorization_code&component_appid=%s&component_access_token=%s';
		public $appid;
	protected $appsecret;
	public $encodingaeskey;
	public $token;
	protected $refreshtoken;
	protected $tablename = 'account_wxapp';
	protected $menuFrame = 'wxapp';
	protected $type = ACCOUNT_TYPE_APP_AUTH;
	protected $typeName = '微信小程序';
	protected $typeSign = WXAPP_TYPE_SIGN;
	protected $supportVersion = STATUS_ON;
	protected $typeTempalte = '-wxapp';

	public function __construct($uniaccount = array()) {
		$setting = setting_load('platform');
		$this->appid = $setting['platform']['appid'];
		$this->appsecret = $setting['platform']['appsecret'];
		$this->token = $setting['platform']['token'];
		$this->encodingaeskey = $setting['platform']['encodingaeskey'];
		parent::__construct($uniaccount);
	}

	protected function getAccountInfo($acid) {
		if ('wx570bc396a51b8ff8' == $this->account['key']) {
			$this->account['key'] = $this->appid;
			$this->openPlatformTestCase();
		}
		$account = table('account_wxapp')->getAccount($acid);
		$account['encrypt_key'] = $this->appid;

		return $account;
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
		cache_write('account_oauth_refreshtoken' . $this->account['key'], $response['refresh_token']);

		return $response;
	}

	protected function setAuthRefreshToken($token) {
		$tablename = 'account_wxapp';
		pdo_update($tablename, array('auth_refresh_token' => $token), array('uniacid' => $this->account['uniacid']));
		cache_write(cache_system_key('account_auth_refreshtoken', array('uniacid' => $this->account['uniacid'])), $token);
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

	public function getDailyVisitTrend($date) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend?access_token={$token}";

		$response = $this->requestApi($url, json_encode(array('begin_date' => $date, 'end_date' => $date)));
		if (is_error($response)) {
			return $response;
		}

		return $response['list'][0];
	}
}