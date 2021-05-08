<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取线路列表
 */

//包含配置文件
require_once '../inc.Config.php';

//设置HTTP头
header("Content-Type: application/json; charset=utf-8\n");

/* 加载线路数据 */
$rows = $SCAUBus->BusData->getLineList();

/* 把数据分配到相应线路的数组内 */
$line1 = $line2 = $line3 = array();
foreach ($rows as $row) {
	if (stripos($row['line_name'], '1号线') !== false) {
		$line1[] = $row;
	} elseif (stripos($row['line_name'], '2号线') !== false) {
		$line2[] = $row;
	} elseif (stripos($row['line_name'], '3号线') !== false) {
		$line3[] = $row;
	}
}

/* 组装回传数据 */
$data = array();
$data['line1'] = $line1;
$data['line2'] = $line2;
$data['line3'] = $line3;

/* 输出回传数据 */
print json_encode($data);

die();
