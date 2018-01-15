<?php

	/**
	* 发布逻辑模型
	*/
	class Whisper_Model extends WebBase {

		/** 
	     * 构造函数
	     *  
	     */  
		function __construct(){
			//调用父类构造函数
			$result = parent::__construct();
			if (!$result){
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				die();
			}
		}

		function whisper($data){

			//遍历请求参数
			$parsed = explode('&', $data);
			$POST = array();
			foreach ($parsed as $argument)  {  
			    list($variable , $value) = explode('=' ,$argument);  
			    $POST[$variable] = $value;  
			}

			if (isset($POST['session']) 
				&& isset($POST['tag'])
				&& isset($POST['duration'])
				&& isset($_FILES['whisper'])){

				$session 	= $POST['session'];
				$duration 	= $POST['duration'];
				$tag 		= urldecode($POST['tag']);

				if (strlen($tag) == 0){
					$tag = date("y-m-d", time());
				}

				// 验证身份
				$result = $this->check_session($session);

				if ($result['success']){
					$result = array();

					// 获取文件基本信息
					$media		= $_FILES['whisper'];
					$filename 	= $media['name'];
					$size 		= $media['size'];
					$tmp_name 	= $media['tmp_name'];
					$error 		= $media['error'];

					$suffix = '.' . explode('.', $filename)[1];

					// 过滤大文件
					if ($size > 1024 * 1024 * 10){
						$result['success'] = false;
						$result['msg'] = '文件大于10M';
					}else{
						$md5 = md5(file_get_contents($media["tmp_name"]));
						$target = SERVER_ROOT . UPLOADTARGET . $md5 . $suffix;
						// 记录入库 保存文件
						$this->begin_transaction();
						if($this->insert("INSERT INTO `t_whisper`(`poster_openid`, `duration`, `tag`, `src`) VALUES ('".$this->openid()."',".$duration.",'".$tag."','".UPLOADTARGET . $md5 . $suffix."')") 
							&& move_uploaded_file($tmp_name, $target)){
							$this->commit();
							$result['success'] = true;
							$result['msg'] = '上传成功';
							$result['src'] = UPLOADTARGET . $md5 . $suffix;
						}else{
							$this->rollback();
							$result['success'] = false;
							$result['msg'] = '上传失败';
						}
					}
				}
			}else {
				$result['success'] = false;
				$result['msg'] = '参数错误';
			}
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}

		function delete($data){
			$POST = json_decode(file_get_contents("php://input"), true);

			if (isset($POST['session']) 
				&& isset($POST['id'])){

				$session 	= $POST['session'];
				$id 		= $POST['id'];

				if (strlen($id) == 0){
					$id = '-1';
				}

				// 验证身份
				$result = $this->check_session($session);

				if ($result['success']){
					$result = array();
					if($this->update("UPDATE `t_whisper` SET `is_valid`= false WHERE `id` = '".$id."'")){
						$this->commit();
						$result['success'] = true;
						$result['msg'] = '删除成功';
					}else{
						$this->rollback();
						$result['success'] = false;
						$result['msg'] = '删除失败';
					}
				}
			}else {
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