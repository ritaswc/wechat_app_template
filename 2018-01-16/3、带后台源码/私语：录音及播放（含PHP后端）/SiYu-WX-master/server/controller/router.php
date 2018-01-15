<?php
	//获取请求信息
	$request = $_SERVER['QUERY_STRING'];

	//http://loclhost:8080/index.php?homepage&action=init
	//切割请求信息
	$parsed = explode('/', $request);
	//获取请求页面信息
	$page = array_shift($parsed);
	//$page为空跳转默认页面
	if ($page == ''){
		$error = new Error_Controller;
		$error->main(false, "匹配控制器失败，空的请求串。");
		die();
	}

	//找到控制器位置
	$target = SERVER_ROOT . '/controller/' . $page . '.class.php';

	if (file_exists($target)){
		//存在则实例化控制器
		include_once($target);
		$class = ucfirst($page) . '_Controller';
		if (class_exists($class)){
			$controller = new $class;
		}else{
			$error = new Error_Controller;
			$error->main(false, "匹配控制器失败，控制器没有被创建。");
			die();
		}
	}else{
		//不存在则跳转错误页面
		$error = new Error_Controller;
		$error->main(false, "匹配控制器失败，找不到对应控制器。");
		die();
	}

	//初始化控制器
	$controller->main($parsed);
?>