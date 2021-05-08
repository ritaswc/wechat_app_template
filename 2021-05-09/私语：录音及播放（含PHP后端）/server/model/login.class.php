<?php

	/**
	* 登录逻辑模型
	*/
	class Login_Model extends WebBase {

		/** 
	     * 构造函数
	     */  
		function __construct(){
			//调用父类构造函数
			$result = parent::__construct();
			if (!$result){
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				die();
			}
		}

		/** 
	     * 登录
	     *
	     */  
		function login(){
			// $result = $this->login_session("001WJUys0o1gac1FJVys0231zs0WJUyJ", '{"nickName":"白蛙","gender":1,"language":"zh_CN","city":"Suqian","province":"Jiangsu","country":"CN","avatarUrl":"http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83erL6iaEl9ge6xHH3Z7ex1r1pGZSkeOkicWXAHMeKQXvPf6ibSzb1srpaZz0j0hAVgesMUial66zpfcPww/0"}', "5b1d2a5cdc6ff9b6a2e7ec18ef0265b4a6a8e2fa");
			$POST = json_decode(file_get_contents("php://input"), true);
			if (isset($POST['code']) 
				&& isset($POST['raw_data']) 
				&& isset($POST['signature'])){
				$code		 = $POST['code'];
				$raw_data	 = $POST['raw_data'];
				$signature	 = $POST['signature'];
				$result = $this->login_session($code, $raw_data, $signature);
			} else {
				$result['success'] = false;
				$result['msg'] = '参数错误';
			}
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}

		/** 
	     * 检查登录状态过期
	     *
	     */  
		function check(){
			$POST = json_decode(file_get_contents("php://input"), true);
			if (isset($POST['session'])){
				$session 	= $POST['session'];
				$result = $this->check_session($session);
			} else {
				$result['success'] = false;
				$result['msg'] = '参数错误';
			}
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
		
		/** 
	     * 析构函数
	     *  
	     */  
		function __destruct(){

		}
		
	}
?>