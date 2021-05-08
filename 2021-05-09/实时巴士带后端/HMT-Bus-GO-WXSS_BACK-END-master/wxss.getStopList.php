<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取站点列表
 */

//包含配置文件
require_once '../inc.Config.php';

//设置HTTP头
header("Content-Type: application/json; charset=utf-8\n");

/* 加载站点数据 */
$rows = $SCAUBus->BusData->getStopList();
$total = count($rows);

/* 组装回传数据 */
$data = array();
$data['stopList'] = $rows;

/* 输出回传数据 */
print json_encode($data);

die();
