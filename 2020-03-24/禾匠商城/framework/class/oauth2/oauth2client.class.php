<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
abstract class OAuth2Client {
	protected $ak;
	protected $sk;
	protected $login_type;
	protected $user_type = USER_TYPE_COMMON;
	protected $stateParam = array(
		'state' => '',
		'from' => '',
		'mode' => '',
	);

	public function __construct($ak, $sk) {
		$this->ak = $ak;
		$this->sk = $sk;
	}

	public function stateParam() {
		global $_W;
		$this->stateParam['state'] = $_W['token'];
		if (!empty($_W['user'])) {
			$this->stateParam['mode'] = 'bind';
		} else {
			$this->stateParam['mode'] = 'login';
		}
		return base64_encode(http_build_query($this->stateParam, '', '&'));
	}

	public function getLoginType($login_type) {
		$this->login_type = $login_type;
	}

	public function setUserType($user_type) {
		$this->user_type = $user_type;
		return $this;
	}

	public static function supportLoginType(){
		return array('system', 'qq', 'wechat', 'mobile');
	}

	public static function supportThirdLoginType() {
		return array('qq', 'wechat');
	}

	public static function supportBindTypeInfo($type = '') {
		$data = array(
			'qq' => array(
				'type' => 'qq',
				'title' => 'QQ',
			),
			'wechat' => array(
				'type' => 'wechat',
				'title' => '微信',
			),
			'mobile' => array(
				'type' => 'mobile',
				'title' => '手机号',
			),
		);
		if (!empty($type)) {
			return $data[$type];
		} else {
			return $data;
		}
	}

	
	public static function supportThirdLoginBindType() {
		return array('qq', 'wechat');
	}

	public static function supportThirdMode() {
		return array('bind', 'login');
	}

	public static function supportParams($state) {
		$state = urldecode($state);
		$param = array();
		if (!empty($state)) {
			$state = base64_decode($state);
			parse_str($state, $third_param);
			$modes = self::supportThirdMode();
			$types = self::supportThirdLoginType();

			if (in_array($third_param['mode'],$modes) && in_array($third_param['from'],$types)) {
				return $third_param;
			}
		}
		return $param;
	}

	public static function create($type, $appid = '', $appsecret = '') {
		$types = self::supportLoginType();
		if (in_array($type, $types)) {
			load()->classs('oauth2/' . $type);
			$type_name = ucfirst($type);
			$obj = new $type_name($appid, $appsecret);
			$obj->getLoginType($type);
			return $obj;
		}

		return null;
	}

	abstract public function showLoginUrl($calback_url = '');

	abstract public function user();

	abstract public function login();

	abstract public function bind();

	abstract public function unbind();

	abstract public function isbind();

	abstract public function register();

	public function user_register($register) {
		global $_W;
		load()->model('user');

		if (is_error($register)) {
			return $register;
		}
		$member = $register['member'];
		$profile = $register['profile'];

		$member['type'] = $this->user_type;
		if ($member['type'] == USER_TYPE_CLERK) {
			$member['status'] = !empty($_W['setting']['register']['clerk']['verify']) ? 1 : 2;
		} else {
			$member['status'] = !empty($_W['setting']['register']['verify']) ? 1 : 2;
		}
		$member['remark'] = '';
		$member['groupid'] = intval($_W['setting']['register']['groupid']);
		if (empty($member['groupid'])) {
			$member['groupid'] = pdo_fetchcolumn('SELECT id FROM '.tablename('users_group').' ORDER BY id ASC LIMIT 1');
			$member['groupid'] = intval($member['groupid']);
		}
		$group = user_group_detail_info($member['groupid']);

		$timelimit = intval($group['timelimit']);
		if($timelimit > 0) {
			$member['endtime'] = strtotime($timelimit . ' days');
		}
		$member['starttime'] = TIMESTAMP;

		$user_id = user_register($member, $this->stateParam['from']);
		if (in_array($member['register_type'], array(USER_REGISTER_TYPE_QQ, USER_REGISTER_TYPE_WECHAT))) {
			pdo_update('users', array('username' => $member['username'] . $user_id . rand(100,999)), array('uid' => $user_id));
		}
		if($user_id > 0) {
			unset($member['password']);
			$member['uid'] = $user_id;
			if (!empty($profile)) {
				$profile['uid'] = $user_id;
				$profile['createtime'] = TIMESTAMP;
				pdo_insert('users_profile', $profile);
			}
			if (in_array($member['register_type'], array(USER_REGISTER_TYPE_QQ, USER_REGISTER_TYPE_WECHAT, USER_REGISTER_TYPE_MOBILE))) {
				pdo_insert('users_bind', array('uid' => $user_id, 'bind_sign' => $member['openid'], 'third_type' => $member['register_type'], 'third_nickname' => $member['username']));
			}
			if (in_array($member['register_type'], array(USER_REGISTER_TYPE_QQ, USER_REGISTER_TYPE_WECHAT))) {
				return $user_id;
			}

			$message = '注册成功';
			if ($member['status'] == USER_STATUS_CHECK) {
				$message .= '，请等待管理员审核！';
			} elseif ($member['type'] != USER_TYPE_CLERK) {
				$message .= '，请重新登录！';
			}
			return array(
				'errno' => 0,
				'message' => $message,
				'uid' => $user_id,
			);
		}

		return error(-1, '增加用户失败，请稍候重试或联系网站管理员解决！');
	}
}