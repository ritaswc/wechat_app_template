<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('communication');

define('QQ_PLATFORM_API_OAUTH_LOGIN_URL', 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=%s&redirect_uri=%s&state=%s');
define('QQ_PLATFORM_API_GET_ACCESS_TOKEN', 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=%s&client_secret=%s&code=%s&redirect_uri=%s');
define('QQ_PLATFORM_API_GET_OPENID', 'https://graph.qq.com/oauth2.0/me?access_token=%s');
define('QQ_PLATFORM_API_GET_USERINFO', 'https://graph.qq.com/user/get_user_info?access_token=%s&oauth_consumer_key=%s&openid=%s');

class Qq extends OAuth2Client {
	private $calback_url;

	public function __construct($ak, $sk, $calback_url = '') {
		global $_W;
		parent::__construct($ak, $sk);
		$this->calback_url = $_W['siteroot'] . 'web/index.php';
		$this->stateParam['from'] = 'qq';
	}

	
	public function showLoginUrl($calback_url = '') {
		$state = $this->stateParam();
		return sprintf(QQ_PLATFORM_API_OAUTH_LOGIN_URL, $this->ak, $this->calback_url, $state);
	}

	public function getAccessToken($state, $code) {
		if (empty($state) || empty($code)) {
			return error(-1, '参数错误');
		}

		$local_state = $this->stateParam();
		if ($state != $local_state) {
			return error(-1, '重新登陆');
		}
		$access_url = sprintf(QQ_PLATFORM_API_GET_ACCESS_TOKEN, $this->ak, $this->sk, $code, urlencode($this->calback_url));
		$response = ihttp_get($access_url);
		if (strexists($response['content'], 'callback') !== false){
			return error(-1, $response['content']);
		}

		parse_str($response['content'], $result);
		return $result;
	}

	public function getOpenid($token) {
		if (empty($token)) {
			return error(-1, '参数错误');
		}
		$openid_url = sprintf(QQ_PLATFORM_API_GET_OPENID, $token);
		$response = ihttp_get($openid_url);
		if (strexists($response['content'], "callback") !== false) {
			$lpos = strpos($response['content'], "(");
			$rpos = strrpos($response['content'], ")");
			$content = substr($response['content'], $lpos + 1, $rpos - $lpos -1);
		}
		$result = json_decode($content, true);
		if (isset($result->error)) {
			return error(-1, $result['content']);
		}
		return $result;
	}

	public function getUserInfo($token, $openid) {
		if (empty($openid) || empty($token)) {
			return error(-1, '参数错误');
		}
		$openid_url = sprintf(QQ_PLATFORM_API_GET_USERINFO, $token, $this->ak, $openid);
		$response = ihttp_get($openid_url);
		$user_info = json_decode($response['content'], true);

		if ($user_info['ret'] != 0) {
			return error(-1, $user_info['ret'] . ',' . $user_info['msg']);
		}
		return $user_info;
	}

	public function getOauthInfo() {
		global $_GPC;
		$getAccessToken = $this->getAccessToken($_GPC['state'], $_GPC['code']);
		if (is_error($getAccessToken)) {
			return error($getAccessToken['errno'], $getAccessToken['message']);
		}
		$oauth['access_token'] = $getAccessToken['access_token'];

		$getOpenId = $this->getOpenid($oauth['access_token']);
		if (is_error($getOpenId['openid'])) {
			return error($getOpenId['errno'], $getOpenId['message']);
		}
		$oauth['openid'] = $getOpenId['openid'];
		return $oauth;
	}

	public function user() {
		$oauth_info = $this->getOauthInfo();
		$openid = $oauth_info['openid'];
		$user_info = $this->getUserInfo($oauth_info['access_token'], $openid);
		if (is_error($user_info)) {
			return $user_info;
		}
		$user = array();
		$profile = array();
		$user['username'] = strip_emoji($user_info['nickname']);
		$user['password'] = '';
		$user['type'] = $this->user_type;
		$user['starttime'] = TIMESTAMP;
		$user['openid'] = $openid;
		$user['register_type'] = USER_REGISTER_TYPE_QQ;

		$profile['avatar'] = $user_info['figureurl_qq_1'];
		$profile['nickname'] = strip_emoji($user_info['nickname']);
		$profile['gender'] = $user_info['gender'] == '女' ? 0 : 1;
		$profile['resideprovince'] = $user_info['province'];
		$profile['residecity'] = $user_info['city'];
		$profile['birthyear'] = $user_info['year'];

		return array(
			'member' => $user,
			'profile' => $profile,
		);
	}

	public function register() {
		return true;
	}

	public function login() {
		load()->model('user');
		$user = $this->user();
		if (is_error($user)) {
			return $user;
		}
		$user_id = pdo_getcolumn('users', array('openid' => $user['member']['openid']), 'uid');
		$user_bind_info = table('users_bind')->getByTypeAndBindsign($user['member']['register_type'], $user['member']['openid']);

		if (!empty($user_id)) {
			return $user_id;
		}

		if (!empty($user_bind_info)) {
			return $user_bind_info['uid'];
		}

		if (!empty($user_id) && empty($user_bind_info)) {
			pdo_insert('users_bind', array('uid' => $user_id, 'bind_sign' => $user['member']['openid'], 'third_type' => $user['member']['register_type'], 'third_nickname' => $user['member']['username']));
			return $user_id;
		}

		return parent::user_register($user);
	}

	public function bind() {
		global $_W;
		$user = $this->user();
		$user_id = pdo_getcolumn('users', array('openid' => $user['member']['openid']), 'uid');
		$user_bind_info = table('users_bind')->getByTypeAndBindsign($user['member']['register_type'], $user['member']['openid']);

		if (!empty($user_id) || !empty($user_bind_info)) {
			return error(-1, '已被其他用户绑定，请更换账号');
		}
		pdo_insert('users_bind', array('uid' => $_W['uid'], 'bind_sign' => $user['member']['openid'], 'third_type' => $user['member']['register_type'], 'third_nickname' => strip_emoji($user['profile']['nickname'])));
		return true;
	}

	public function unbind() {
		global $_GPC, $_W;
		$third_type = intval($_GPC['bind_type']);
		$bind_info = table('users_bind')->getByTypeAndUid($third_type, $_W['uid']);

		if (empty($bind_info)) {
			return error(-1, '已经解除绑定');
		}
		pdo_update('users', array('openid' => ''), array('uid' => $_W['uid']));
		pdo_delete('users_bind', array('uid' => $_W['uid'], 'third_type' => $third_type));
		return error(0, '成功');
	}

	public function isbind() {
		global $_W;
		$bind_info = table('users_bind')->getByTypeAndUid(USER_REGISTER_TYPE_QQ, $_W['uid']);
		return !empty($bind_info['bind_sign']);
	}
}