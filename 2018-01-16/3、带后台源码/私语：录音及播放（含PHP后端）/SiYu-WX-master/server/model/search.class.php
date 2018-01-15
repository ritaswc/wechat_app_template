<?php

	/**
	* 搜索逻辑模型
	*/
	class Search_Model extends WebBase {

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
	     * 搜索
	     *
	     */  
		function search(){
			$POST = json_decode(file_get_contents("php://input"), true);
			if (isset($POST['session']) 
				&& isset($POST['key']) 
				&& isset($POST['index']) 
				&& isset($POST['number'])){
				$session = $POST['session'];
				$key	 = $POST['key'];
				$index	 = $POST['index'];
				$number	 = $POST['number'];

				// 验证身份
				$result = $this->check_session($session);
				if ($result['success']){
					$result = array();
					if (strlen($key) == 0){
						$sql = "SELECT `id`, `duration`, `tag`, `src`, `post_datetime` FROM `t_whisper` WHERE `is_valid` is true AND `poster_openid` = '".$this->openid()."' ORDER BY `post_datetime` DESC LIMIT ".$index.",".$number;
					}else{
						$sql = "SELECT `id`, `duration`, `tag`, `src`, `post_datetime` FROM `t_whisper` WHERE `is_valid` is true AND `poster_openid` = '".$this->openid()."' AND `tag` LIKE '%".$key."%' OR MONTH(`post_datetime`) = '".$key."' ORDER BY `post_datetime` DESC LIMIT ".$index.",".$number;
					}
					$t = $this->query($sql);
					if ($t !== false){
						$result['success'] = true;
						$result['msg'] = '获取成功';
						$result['number'] = count($t);
						$result['data'] = !count($t) ? array() : $t;
					}else{
						$result['success'] = false;
						$result['msg'] = '获取失败';
					}
				}
			}else {
				$result['success'] = false;
				$result['msg'] = '参数错误';
			}
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}

		/** 
	     * 查看
	     *
	     */  
		function view(){
			$POST = json_decode(file_get_contents("php://input"), true);
			if (isset($POST['session']) 
				&& isset($POST['index']) 
				&& isset($POST['number'])){
				$session = $POST['session'];
				$index	 = $POST['index'];
				$number	 = $POST['number'];

				// 验证身份
				$result = $this->check_session($session);
				if ($result['success']){
					$result = array();
					$sql = "SELECT `id`, `duration`, `tag`, `src`, `post_datetime` FROM `t_whisper` WHERE `is_valid` is true AND `poster_openid` = '".$this->openid()."' ORDER BY `post_datetime` DESC LIMIT ".$index.",".$number;
					$t = $this->query($sql);
					if ($t !== false){
						$result['success'] = true;
						$result['msg'] = '获取成功';
						$result['number'] = count($t);
						$result['data'] = !count($t) ? array() : $t;
					}else{
						$result['success'] = false;
						$result['msg'] = '获取失败';
					}
				}
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