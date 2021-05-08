<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
load()->func('communication');

define('Wechat_PLATFORM_API_OAUTH_LOGIN_URL', 'https://open.weixin.qq.com/connect/qrconnect?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_login&state=%s#wechat_redirect');
define('Wechat_PLATFORM_API_GET_ACCESS_TOKEN', 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code');
define('Wechat_PLATFORM_API_GET_USERINFO', 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN');
class Wechat extends OAuth2Client {
	private $calback_url;

	public function __construct($ak, $sk, $calback_url = '') {
		global $_W;
		parent::__construct($ak, $sk);
		$this->calback_url = $_W['siteroot'] . 'web/index.php';
		$this->stateParam['from'] = 'wechat';
	}

	public function showLoginUrl($calback_url = '') {
		$redirect_uri = urlencode($this->calback_url);
		$state = $this->stateParam();
		return sprintf(Wechat_PLATFORM_API_OAUTH_LOGIN_URL, $this->ak, $redirect_uri, $state);
	}

	public function getUserInfo($token, $openid) {
		if (empty($openid) || empty($token)) {
			return error(-1, '参数错误');
		}
		$user_info_url = sprintf(Wechat_PLATFORM_API_GET_USERINFO, $token, $openid);
		$response = $this->requestApi($user_info_url);
		return $response;
	}

	public function getOauthInfo() {
		global $_GPC, $_W;
		$state = $_GPC['state'];
		$code = $_GPC['code'];
		if (empty($state) || empty($code)) {
			return error(-1, '参数错误');
		}
		$local_state = $this->stateParam();
		if ($state != $local_state) {
			return error(-1, '重新登陆');
		}
		$access_url = sprintf(Wechat_PLATFORM_API_GET_ACCESS_TOKEN, $this->ak, $this->sk, $code, urlencode($this->calback_url));
		$response = $this->requestApi($access_url);
		return $response;
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
		$user['openid'] = $user_info['openid'];
		$user['register_type'] = USER_REGISTER_TYPE_WECHAT;

		$profile['avatar'] = $user_info['headimgurl'];
		$profile['nickname'] = $user_info['nickname'];
		$profile['gender'] = $user_info['sex'];
		$profile['resideprovince'] = $user_info['province'];
		$profile['residecity'] = $user_info['city'];
		$profile['birthyear'] = '';
		return array(
			'member' => $user,
			'profile' => $profile,
			'unionid' => empty($user_info['unionid']) ? '' : $user_info['unionid'],
		);
	}

	protected function requestApi($url, $post = '') {
		$response = ihttp_request($url, $post);

		$result = @json_decode($response['content'], true);
		if(is_error($response)) {
			return error($result['errcode'], "访问公众平台接口失败, 错误详情: {$result['errmsg']}");
		}
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error($result['errcode'], "访问公众平台接口失败, 错误: {$result['errmsg']}");
		}
		return $result;
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
			if (!empty($user['unionid'])) {
				pdo_insert('users_bind', array('uid' => $user_id, 'bind_sign' => $user['unionid'], 'third_type' => USER_REGISTER_TYPE_OPEN_WECHAT, 'third_nickname' => ''));
			}
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
		if (!empty($user['unionid'])) {
			pdo_insert('users_bind', array('uid' => $_W['uid'], 'bind_sign' => $user['unionid'], 'third_type' => USER_REGISTER_TYPE_OPEN_WECHAT, 'third_nickname' => ''));
		}
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
		if ($third_type == USER_REGISTER_TYPE_WECHAT) {
			pdo_delete('users_bind', array('uid' => $_W['uid'], 'third_type' => USER_REGISTER_TYPE_OPEN_WECHAT));
		}

		return error(0, '成功');
	}

	public function isbind() {
		global $_W;
		$bind_info = table('users_bind')->getByTypeAndUid(array(USER_REGISTER_TYPE_WECHAT, USER_REGISTER_TYPE_OPEN_WECHAT), $_W['uid']);
		return !empty($bind_info['bind_sign']);
	}
}