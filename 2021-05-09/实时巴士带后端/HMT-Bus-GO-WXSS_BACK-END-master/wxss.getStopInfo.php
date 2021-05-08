<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取站点详情
 */

//包含配置文件
require_once '../inc.Config.php';

//设置HTTP头
header("Content-Type: application/json; charset=utf-8\n");

if (!isset($_GET['id']) || !Verifier::isNumber($_GET['id'])) {
	throw new Error('传入站点ID有误');
}

/* 加载站点数据 */
$id = $_GET['id'];
$stop = $SCAUBus->BusData->getStopDetail($id);
$lineList = $SCAUBus->BusData->getLineByStopId($id);
$totalLine = count($lineList);

/* 组装回传数据 */
$data = array();
$data['stopInfo'] = $stop;
$data['totalLine'] = $totalLine;
$data['lineList'] = $lineList;

/* 输出回传数据 */
print json_encode($data);

die();
