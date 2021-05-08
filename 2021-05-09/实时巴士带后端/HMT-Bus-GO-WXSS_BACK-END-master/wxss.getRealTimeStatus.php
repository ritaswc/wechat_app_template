<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取实时校巴状态
 */

//包含配置文件
require_once '../inc.Config.php';

//设置HTTP头
header("Content-Type: application/json; charset=utf-8\n");

/* 组装回传数据 */
$devices = array();
$devices['online'] = $SCAUBus->RealTimeBus->getDevice(1);
$devices['offline'] = $SCAUBus->RealTimeBus->getDevice(0);

/* 输出回传数据 */
print json_encode($devices);

die();
