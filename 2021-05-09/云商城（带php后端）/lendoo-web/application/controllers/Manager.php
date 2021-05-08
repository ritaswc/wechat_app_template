<?php

use LeanCloud\User;

class Manager extends BaseController {
	function __construct() {
		parent::__construct();
		$this->load->model('Manager_model', 'manager_model');
		$this->load->helper('acl');
	}
	// 管理员登录
	public function login() {
		// 首先判断是否已经登录
		if (!acl()) {
			$this->load->view('manager/login');
		} else {
			redirect('../dashboard/index');
		}
	}

	// 管理员退出
	public function logout() {
		User::logout();
		redirect('../manager/login');
	}

	// 管理员验证
	public function verify() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		if ($this->manager_model->verify($username, $password)) {
			if (!acl()) {
				$this->echo_json('您没有相应权限', false);
				return;
			}
			// 登录成功
			$this->echo_json('登录成功');
		} else {
			// 给出弹窗提示
			$this->echo_json('用户名或密码错误', false);
		}
	}

	// 管理员修改密码
	public function updatePassword() {
		$oldPassword = $this->input->post('oldPassword');
		$newPassword = $this->input->post('newPassword');
		try {
			User:: getCurrentUser()->updatePassword($oldPassword, $newPassword);
			$this->echo_json('密码修改成功', true);
		} catch (Exception $e) {
			switch ($e->getCode()) {
				case 1:
					$message = '密码不能为空';
					break;
				case 210:
					$message = '旧密码错误';
					break;
				default:
					$message = '密码修改失败:' . $e->getCode();
					break;
			}
			$this->echo_json($message, false);
		}
	}

	// 修改密码
	public function profile() {
		$this->layout->view('manager/profile');
	}

	// 保存资料
	public function save() {
		$data['msg'] = '操作成功';
		$data['level'] = 'info';
		$data['redirect'] = 'profile';
		$this->layout->view('manager/msg', $data);
	}
}
?>