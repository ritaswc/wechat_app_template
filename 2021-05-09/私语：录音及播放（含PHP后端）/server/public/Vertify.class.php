<?php

	include_once 'aliyun-php-sdk-core/Config.php';
    use Sms\Request\V20160927 as Sms;           

	/**
	* 验证码生成发送
	*/
	class Vertify_Sender extends DBM {

		/** 
	     * 构造函数
	     *  
	     */  
		function __construct(){
			//调用父类构造函数
			parent::__construct();
		}

		/** 
	     * 发送验证码业务逻辑
	     *  
	     */  
		function send_vertify($phone, $vertify){
			$t = $this->query("SELECT * FROM `t_phone` WHERE `phone_number` = '". $phone ."'");
			//只向存在的手机号发送验证码
			if (!$t){
				$result['success'] = false;
				$result['data'] = '验证码发送失败';
			}else{
				//频率正常则生成验证码入库发送
				$interval = strtotime (date("y-m-d H:i:s", time() + 8 * 3600)) - strtotime($t[0]['last_request']);
				if ($interval > 60){
					$this->update("UPDATE `t_phone` SET `vertify_code`='".$vertify."', `last_request`='".date("y-m-d H:i:s", time() + 8 * 3600)."' WHERE `phone_number` = '".$phone."'");
					//发送成功则提交事务 否则回滚
					if ($this->send($phone, $vertify)) {
						$this->commit();
						$result['success'] = true;
						$result['data'] = '验证码发送成功';
					}else{
						$this->rollback();
						$result['success'] = false;
						$result['data'] = '验证码发送失败';
					}
				}else{
					$result['success'] = false;
					$result['data'] = '验证码请求频率过高';
				}
			}
			return $result;
		}


		/** 
	     * 发送验证码接口
	     *  
	     */  
		function send($phone, $vertify){
		    $iClientProfile = DefaultProfile::getProfile(
			    	"cn-hangzhou", 
			    	"-", 
			    	"-"
		    	);        
		    $client = new DefaultAcsClient($iClientProfile);    
		    $request = new Sms\SingleSendSmsRequest();
		    $request->setSignName("-");
		    $request->setTemplateCode("-");
		    $request->setRecNum($phone);
		    $request->setParamString("{\"vertify\":\"".$vertify."\"}");
		    try {
		        $response = $client->getAcsResponse($request);
		        $result = true;
		        // print_r($response);
		    }
		    catch (ClientException  $e) {
		        $result = false;
		        // print_r($e->getErrorCode());   
		        // print_r($e->getErrorMessage());   
		    }
		    catch (ServerException  $e) {   
		        $result = false;     
		        // print_r($e->getErrorCode());   
		        // print_r($e->getErrorMessage());
		    }
		    return $result;
		}

		/** 
	     * 析构函数
	     *  
	     */  
		function __destruct(){

		}
		
	}
?>