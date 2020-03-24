<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
class System extends OAuth2Client {
	private $calback_url;

	public function __construct($ak, $sk) {
		parent::__construct($ak, $sk);
		$this->stateParam['from'] = 'system';
	}

	public function showLoginUrl($calback_url = '') {
		return '';
	}

	public function user() {
		global $_GPC, $_W;
		$username = trim($_GPC['username']);
		$refused_login_limit = $_W['setting']['copyright']['refused_login_limit'];
		pdo_delete('users_failed_login', array('lastupdate <' => TIMESTAMP - $refused_login_limit * 60));
		$failed = pdo_get('users_failed_login', array('username' => $username));
		if ($failed['count'] >= 5) {
			return error('-1', "输入密码错误次数超过5次，请在{$refused_login_limit}分钟后再登录");
		}
		if (!empty($_W['setting']['copyright']['verifycode'])) {
			$verify = trim($_GPC['verify']);
			if (empty($verify)) {
				return error('-1', '请输入验证码');
			}
			$result = checkcaptcha($verify);
			if (empty($result)) {
				return error('-1', '输入验证码错误');
			}
		}
		if (empty($username)) {
			return error('-1', '请输入要登录的用户名');
		}
		$member['username'] = $username;
		$member['password'] = $_GPC['password'];
		$member['type'] = $this->user_type;
		if (empty($member['password'])) {
			return error('-1', '请输入密码');
		}

		return $member;
	}

	public function register() {
		global $_GPC;
		load()->model('user');
		$member = array();
		$profile = array();
		$member['username'] = trim($_GPC['username']);
		$member['owner_uid'] = intval($_GPC['owner_uid']);
		$member['password'] = $_GPC['password'];

		if (!preg_match(REGULAR_USERNAME, $member['username'])) {
			return error(-1, '必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
		}

		if (user_check(array('username' => $member['username']))) {
			return error(-1, '非常抱歉，此用户名已经被注册，你需要更换注册名称！');
		}

		if (!empty($_W['setting']['register']['code'])) {
			if (!checkcaptcha($_GPC['code'])) {
				return error(-1, '你输入的验证码不正确, 请重新输入.');
			}
		}
		if (istrlen($member['password']) < 8) {
			return error(-1, '必须输入密码，且密码长度不得低于8位。');
		}

		$extendfields = $this->systemFields();
		if (!empty($extendfields)) {
			$fields = array_keys($extendfields);
			if (in_array('birthyear', $fields)) {
				$extendfields[] = array('field' => 'birthmonth', 'title' => '出生生日', 'required' => $extendfields['birthyear']['required']);
				$extendfields[] = array('field' => 'birthday', 'title' => '出生生日', 'required' => $extendfields['birthyear']['required']);
				$_GPC['birthyear'] = $_GPC['birth']['year'];
				$_GPC['birthmonth'] = $_GPC['birth']['month'];
				$_GPC['birthday'] = $_GPC['birth']['day'];
			}
			if (in_array('resideprovince', $fields)) {
				$extendfields[] = array('field' => 'residecity', 'title' => '居住地址', 'required' => $extendfields['resideprovince']['required']);
				$extendfields[] = array('field' => 'residedist', 'title' => '居住地址', 'required' => $extendfields['resideprovince']['required']);
				$_GPC['resideprovince'] = $_GPC['reside']['province'];
				$_GPC['residecity'] = $_GPC['reside']['city'];
				$_GPC['residedist'] = $_GPC['reside']['district'];
			}
			foreach ($extendfields as $row) {
				if (!empty($row['required']) && empty($_GPC[$row['field']])) {
					return error(-1, '“' . $row['title'] . '”此项为必填项，请返回填写完整！');
				}
				if ($row['field'] == 'mobile') {
					$mobile = safe_gpc_int($_GPC['mobile']);
					if (!preg_match(REGULAR_MOBILE, $mobile)) {
						return error(-1, '手机号格式不正确');
					}
					$mobile_exists = table('users_profile')->getByMobile($mobile);
					if (!empty($mobile_exists)) {
						return error(-1, '手机号已存在');
					}
				}
				$profile[$row['field']] = $_GPC[$row['field']];
			}
		}

		$register = array(
			'member' => $member,
			'profile' => $profile,
		);

		return parent::user_register($register);
	}

	public function systemFields() {
		return table('core_profile_fields')->getAvailableAndShowableFields();
	}

	public function login() {
		return $this->user();
	}

	public function bind() {
		return true;
	}

	public function unbind() {
		return true;
	}

	public function isbind() {
		return true;
	}
}