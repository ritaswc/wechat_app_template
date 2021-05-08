<?php
// 接口输出类
class Agent
{
	// 设备类型
	const DEVICE_MOBILE  = 1;
	const DEVICE_DESKTOP = 2;
	const DEVICE_UNKNOWN = -1;
	
	// 浏览器类型
	const BROWSER_TYPE_IPHONE  = 1;
	const BROWSER_TYPE_IPAD    = 2;
	const BROWSER_TYPE_IPOD	   = 3;
	const BROWSER_TYPE_ANDROID = 4;
	const BROWSER_TYPE_XZAPP   = 5;
	const BROWSER_TYPE_UNKNOWN = -1;
	
	// 系统类型
	const OS_TYPE_IOS	  = 1;
	const OS_TYPE_ANDROID = 2;
	const OS_TYPE_UNKNOWN = -1;
	
	// 是否RETINA屏
	const RETINA_TYPE_YES = 1;
	const RETINA_TYPE_NOT = 0;
	
	// 是否IOS6系统
	const IOS6_YES = 1;
	const IOS6_NOT = 0;
	
	// 是否微信打开
	const MICRO_MESSAGE_YES = 1;
	const MICRO_MESSAGE_NOT = 0;
	
	// APP已经安装
	const APP_INSTALLED_YES = 1;
	const APP_INSTALLED_NOT = 0;
	
	// 得到agent完整类型信息
	public static function getDeviceInfo()
	{
		return array(
			'deviceType'  => self::deviceType(),
			'browserType' => self::browserType(),
			'isRetina' 	  => self::isRetina(),
			'osType' 	  => self::osType(),
			'isIos6' 	  => self::isIos6(),
		);
	}
	
	// 浏览器类型
	public static function browserType($agent = '')
	{
		$agent = self::getAgent($agent);
		if (stripos($agent, 'baiduboxapp') !== false) {
			return self::BROWSER_TYPE_XZAPP;
		}

		if (stripos($agent, 'iphone') !== false) {
			return self::BROWSER_TYPE_IPHONE;
		}
		
		if (stripos($agent, 'ipad') !== false) {
			return self::BROWSER_TYPE_IPAD;
		}
		
		if (stripos($agent, 'ipod') !== false) {
			return self::BROWSER_TYPE_IPOD;
		}
		
		if (stripos($agent, 'android') !== false) {
			return self::BROWSER_TYPE_ANDROID;
		}

		return self::BROWSER_TYPE_UNKNOWN;
	}
	
	// 系统类型
	public static function osType($agent = '')
	{
		$agent = self::getAgent($agent);
		$browserType = self::browserType($agent);

		switch ($browserType) {
			case self::BROWSER_TYPE_IPHONE:
			case self::BROWSER_TYPE_IPAD:
			case self::BROWSER_TYPE_IPOD:
				 $osType = self::OS_TYPE_IOS;
				 break;
			case self::BROWSER_TYPE_ANDROID:
				 $osType = self::OS_TYPE_ANDROID;
				 break;
			default:
				 $osType = self::OS_TYPE_UNKNOWN;
		}
		
		return $osType;
	}
	
	// 设备类型
	public static function deviceType()
	{
		if (self::isMobile()) {
			return self::DEVICE_MOBILE;
		} else {
			return self::DEVICE_DESKTOP;
		}
	}
	
	// retina屏
	public static function isRetina($agent = '')
	{
		$agent = self::getAgent($agent);
		$osType = self::osType($agent);
		
		if (($osType == self::OS_TYPE_IOS) && (self::isIos6($agent) != 1)) {
			return self::RETINA_TYPE_YES;
		} else {
			return self::RETINA_TYPE_NOT;
		}
	}
	
	// ios6系统的手机(iphone4, iphone4s)
	public static function isIos6($agent = '')
	{
		$agent = self::getAgent($agent);
		
		if (stripos($agent, 'iPhone OS 6')) {
			return self::IOS6_YES;
		} else {
			return self::IOS6_NOT;
		}
	}
	// 检查是否在微信中打开
	public static function isMicroMessage($agent = '')
	{
		$agent = self::getAgent($agent);
		
		if (stripos($agent, 'MicroMessenger') !== false) {
			return self::MICRO_MESSAGE_YES;
		} else {
			return self::MICRO_MESSAGE_NOT;
		}
	}
	
	// 已安装APP
	public static function isAppInstalled()
	{
		if (isset($_GET['isappinstalled']) && ($_GET['isappinstalled'] == 1)) {
			return self::APP_INSTALLED_YES;
		} else {
			return self::APP_INSTALLED_NOT;
		}
	}
	
	// 是移动设备访问
	public static function isMobile()
	{
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
				return true;
		}
		// 脑残法，判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp',
				'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 
				'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 
				'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'WindowsWechat');
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		// 协议法，因为有可能不准确，放到最后判断
		if (isset($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备, 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			}
		}
		return false;
	}
	
	public static function getAgent($agent = '')
	{
		$agent = empty($agent) ? $_SERVER['HTTP_USER_AGENT'] : $agent;
		return $agent;
	}
}