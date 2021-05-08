<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取发车时刻表
 */

//包含配置文件
require_once '../inc.Config.php';

//设置HTTP头
header("Content-Type: application/json; charset=utf-8\n");

if (!isset($_GET['id']) || !Verifier::isNumber($_GET['id'])) {
	throw new Error('传入站点ID有误');
} elseif (!Verifier::isNumber($_GET['type'])) {
	throw new Error('传入时刻表类型有误');
}

/* 加载时刻表 */
$id = $_GET['id'];
$type = $_GET['type'];

$line = $SCAUBus->BusData->getLineDetail($id);
$timetable = array();
foreach ($SCAUBus->BusData->getTimetableByLineId($id, $type) as $time) {
	$timetable[] = $time['table_time'];
}

$data = array(
	'line_name' => $line[0]['line_name'],
	'line_start' => $line[0]['line_start'],
	'line_end' => $line[0]['line_end'],
	'timetable' => $timetable
);

/* 输出回传数据 */
print json_encode($data);

die();
