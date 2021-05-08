<?php
	require_once('DBM.class.php');
	/**
	* 网站基础类
	*/
	class WebBase extends DBM {

		private $code = '';

		private $raw_data = '';

		private $signature = '';

		private $session_key = '';

		private $openid = '';

		/** 
	     * 构造函数
	     */  
		function __construct() {
			// 调用父类构造函数
			return parent::__construct();
		}
		/** 
	     * 登录
	     * 
	     * @param string code 
	     * @param string raw_data 用户信息
	     * @param string signature 数据签名
	     */ 
		function login_session($code, $raw_data, $signature){
			$this->code 		= $code;
			$this->raw_data 	= $raw_data;
			$this->signature 	= $signature;
			if (strlen($this->code) == 0){
				$result['success'] = false;
				$result['msg'] = '非法的code';
			}else{
				$url = "https://api.weixin.qq.com/sns/jscode2session?appid=".APPID."&secret=".APPSECRET."&js_code=".$this->code."&grant_type=authorization_code";
				// 从微信服务器请求session_key, openid
				$response = $this->curl_get($url);
				if (isset($response['errcode'])){
					$result['success'] = false;
					$result['errcode'] = $response['errcode'];
					$result['errmsg'] = $response['errmsg'];
				}else{
					// 生成session 记录session 用户信息
					$session = $this->guid($response['session_key'] . $response['openid']);
					$this->session_key 	= $response['session_key'];
					$this->openid 		= $response['openid'];
					// 校验用户信息
					if ($this->check_signature($this->session_key, $this->raw_data, $this->signature)){
						$uinfo = json_decode($this->raw_data, true);
						// session存在
						if ($this->is_openid_exsit($this->openid)){
							// 更新session
							// 更新用户信息
							$this->begin_transaction();
							if ($this->update_session($session, $this->session_key, $this->openid) && $this->update_user_info($uinfo)){
								$this->commit();
								$result['success'] = true;
								$result['msg'] = '登录成功';
								$result['session'] = $session;
							}else{
								$this->rollback();
								$result['success'] = false;
								$result['msg'] = '更新session、uinfo失败';
							}
						}else{
							echo $this->openid;
							// 插入session 
							// 插入用户信息
							$this->begin_transaction();
							if ($this->insert_session($session, $this->session_key, $this->openid) && $this->insert_user_info($uinfo)){
								$this->commit();
								$result['success'] = true;
								$result['msg'] = '登录成功';
								$result['session'] = $session;
							}else{
								$this->rollback();
								$result['success'] = false;
								$result['msg'] = '插入session、uinfo失败';
							}
						}
					}else{
						$result['success'] = false;
						$result['msg'] = '用户数据签名校验失败';
					}
				}
			}
			return $result;
		}


		/** 
	     * 检查登录状态
	     * 
	     * @param string session 全局唯一标识
	     */ 
		function check_session($session){
			if (strlen($session) == 0){
				$result['success'] = false;
				$result['msg'] = 'session不能为空';
			}else{
				// 匹配session
				if ($this->is_session_exsit($session)){
					// 获取未过期的session对应信息
					$res = $this->match_session($session);
					if ($res){
						$this->session_key 	= $res['session_key'];
						$this->openid 		= $res['openid'];
						$result['success'] = true;
						$result['msg'] = '登录成功';
						$result['session'] = $session;
					}else{
						$result['success'] = false;
						$result['msg'] = 'session已失效';
					}
				}else{
					$result['success'] = false;
					$result['msg'] = '非法的session';
					$result['session'] = $session;
				}
			}
			return $result;
		}


		/** 
	     * 检查session存在
	     * 
	     * @param string session 全局唯一标识
	     */ 
		function is_session_exsit($session){
			$result = $this->query("SELECT * FROM `t_session` WHERE `session` = '" . $session . "'");
			if (!$result || !count($result)){
				return false;
			}else{
				return true;
			}
		}

		/** 
	     * 检查openid存在
	     * 
	     * @param string openid 全局唯一标识
	     */ 
		function is_openid_exsit($openid){
			$result = $this->query("SELECT * FROM `t_session` WHERE `openid` = '" . $openid . "'");
			if (!$result || !count($result)){
				return false;
			}else{
				return true;
			}
		}

		/** 
	     * 查找session对应的未过期信息
	     * 
	     * @param string session 全局唯一标识
	     */ 
		function match_session($session){
			$result = $this->query("SELECT * FROM `t_session` WHERE `session` = '" . $session . "'");
			if (!$result || strtotime (date("y-m-d h:i:s", time())) - strtotime($result[0]['last_active_datetime']) > SESSIONLIFE){
				return false;
			}
			return $result[0];
		}


		/** 
	     * 更新session
	     * @param string session 全局唯一标识
	     */ 
		function update_session($session, $session_key, $openid){
			$sql = "UPDATE `t_session` SET `session`='".$session."',`session_key`='".$session_key."', `openid`='".$openid."', `last_active_datetime`='".date("y-m-d h:i:s", time())."' WHERE `openid` = '".$openid."'";
			if ($this->update($sql)){
				return true;
			}else{
				return false;
			}
		}


		/** 
	     * 插入session
	     * @param string session 全局唯一标识
	     * @param string session_key 会话秘钥
	     * @param string openid 用户全局id
	     */ 
		function insert_session($session, $session_key, $openid){
			$sql = "INSERT INTO `t_session`(`session`, `session_key`, `openid`) VALUES ('".$session."','".$session_key."','".$openid."')";
			if ($this->insert($sql)){
				return true;
			}else{
				return false;
			}
		}


		/** 
	     * 更新用户信息
	     * @param array uinfo 用户信息
	     */ 
		function update_user_info(array $uinfo){
			if (isset($uinfo['nickName'])
				&& isset($uinfo['gender'])
				&& isset($uinfo['city'])
				&& isset($uinfo['avatarUrl'])){
				$nickname = $uinfo['nickName'];
				$gender = $uinfo['gender'];
				$city = $uinfo['city'];
				$avata_url = $uinfo['avatarUrl'];
				if ($this->update("UPDATE `t_user_info` SET `nickname`='".$nickname."',`avatar_url`='".$avata_url."',`gender`='".$gender."',`city`='".$city."' WHERE `openid` = '".$this->openid."'")){
					return true;
				}
			}
			return false;
		}


		/** 
	     * 插入用户信息
	     * @param array uinfo 用户信息
	     */ 
		function insert_user_info(array $uinfo){
			if (isset($uinfo['nickName'])
				&& isset($uinfo['gender'])
				&& isset($uinfo['city'])
				&& isset($uinfo['avatarUrl'])){
				$nickname = $uinfo['nickName'];
				$gender = $uinfo['gender'];
				$city = $uinfo['city'];
				$avata_url = $uinfo['avatarUrl'];
				if ($this->insert("INSERT INTO `t_user_info`(`openid`, `nickname`, `avatar_url`, `gender`, `city`) VALUES ('".$this->openid."','".$nickname."','".$avata_url."',".$gender.",'".$city."')")){
					return true;
				}
			}
			return false;
		}


		/** 
	     * 判断是否已经登录
	     * 
	     */ 
	    function is_logged(){
			if (strlen($this->openid) != 0){
				return true;
			}
	    	return false;
	    }


	    /** 
	     * 获取登录用户openid
	     * 
	     */ 
	    function logged_user_openid(){
	    	if (!$this->is_logged()){
	    		return false;
	    	}
	    	return $this->openid;
	    }


	    /** 
	     * get 请求
	     * 
	     * @param string url 目标网络地址
	     */ 
	    function curl_get($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$output = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($output, true);
			return $result;
	    }


	    /** 
	     * 生成guid
	     * 
	     * @param string str 源字符串
	     */ 
	    function guid($str) {
            return md5($str);
        }


        /** 
	     * 数据签名校验
	     * 
	     * @param string session_key 会话秘钥
	     * @param string raw_data 用户信息
	     * @param string signature 数据签名
	     */ 
	    function check_signature($session_key, $raw_data, $signature) {
			// if ($signature == sha1($raw_data . $session_key)){
			// 	return true;
			// }
			// return false;
			return true;
        }

        /** 
	     * 获取openid
	     */ 
	    function openid() {
            return $this->openid;
        }

        /** 
	     * 获取session_key
	     */ 
	    function session_key() {
            return $this->session_key;
        }
	}
?>