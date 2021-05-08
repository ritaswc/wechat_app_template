<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取线路详细信息
 */

//包含配置文件
require_once '../inc.Config.php';

//设置HTTP头
header("Content-Type: application/json; charset=utf-8\n");

if (!isset($_GET['id']) || !Verifier::isNumber($_GET['id'])) {
	throw new Error('传入线路ID有误');
}

/* 加载线路数据 */
$id = $_GET['id'];
$line = $SCAUBus->BusData->getLineDetail($id);
$stops = $SCAUBus->RealTimeBus->getDataByLineId($id);
$totalStops = count($stops);

/* 组装回传数据 */
$data = array();
$data['lineInfo'] = $line;
$data['totalStops'] = $totalStops;
$data['stops'] = $stops;

/* 输出数据 */
print json_encode($data);

die();
