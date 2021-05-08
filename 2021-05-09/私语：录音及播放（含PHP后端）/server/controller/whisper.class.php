<?php
	/**
	* 发布控制器
	*/
	class Whisper_Controller {
		
		//模板名称
		public $template = 'whisper';

		/** 
	     * Whisper_Controller
	     *  
	     * @param array $var_array GET参数 
	     */  
		public function main(array $var_array){

			//找到模型位置
			$target = SERVER_ROOT . '/model/' . $this->template . '.class.php';

			if (file_exists($target)){
				//存在则实例化模型
				include_once($target);
				$class = ucfirst($this->template) . '_Model';
				if (class_exists($class)){
					$model = new $class();
					//传递参数
					$action = array_shift($var_array);
					if (strlen($action) > 0){
						$model->$action(array_shift($var_array));
					}else{
						$error = new Error_Controller;
						$error->main(false, "模型动作错误。");
						die();
					}
				}else{
					$error = new Error_Controller;
					$error->main(false, "匹配模型失败，模型没有被创建。");
					die();
				}
			}else{
				//不存在则跳转错误页面
				$error = new Error_Controller;
				$error->main(false, "匹配模型失败，找不到对应模型。");
				die();
			}
		}
	}
?>