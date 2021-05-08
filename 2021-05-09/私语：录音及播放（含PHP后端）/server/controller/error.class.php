<?php
	/**
	* 错误页控制器
	*/
	class Error_Controller {
		
		//模板名称
		public $template = 'error';

		/** 
	     * Error_Controller 入口
	     *  
	     * @param array $var_array GET参数 
	     */  
		public function main($error_status, $error_msg){
			$result['success'] = $error_status;
			$result['msg'] = $error_msg;
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}
?>