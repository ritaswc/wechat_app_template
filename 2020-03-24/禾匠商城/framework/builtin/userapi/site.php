<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class UserApiModuleSite extends WeModuleSite {
	public $tablename = 'userapi_reply';

	private $predefines = array(
		'weather.php' => array(
			'title' => '城市天气',
			'description' => '"城市名+天气", 如: "北京天气"',
			'keywords' => array(
				array('3', '^.+天气$'),
			),
		),
		'baike.php' => array(
			'title' => '百度百科',
			'description' => '"百科+查询内容" 或 "定义+查询内容", 如: "百科姚明", "定义自行车"',
			'keywords' => array(
				array('3', '^百科.+$'),
				array('3', '^定义.+$'),
			),
		),
		'translate.php' => array(
			'title' => '即时翻译',
			'description' => '"@查询内容(中文或英文)"',
			'keywords' => array(
				array('3', '^@.+$'),
			),
		),
		'calendar.php' => array(
			'title' => '今日老黄历',
			'description' => '"日历", "万年历", "黄历"或"几号"',
			'keywords' => array(
				array('1', '日历'),
				array('1', '万年历'),
				array('1', '黄历'),
				array('1', '几号'),
			),
		),
		'news.php' => array(
			'title' => '看新闻',
			'description' => '"新闻"',
			'keywords' => array(
				array('1', '新闻'),
			),
		),
		'express.php' => array(
			'title' => '快递查询',
			'description' => '"快递+单号", 如: "申通1200041125"',
			'keywords' => array(
				array('3', '^(申通|圆通|中通|汇通|韵达|顺丰|EMS) *[a-z0-9]{1,}$'),
			),
		),
	);
}
