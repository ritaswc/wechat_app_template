<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取站点位置
 */

header("Content-Type: application/json; charset=utf-8\n");

require_once '../inc.Config.php';

$stops = $SCAUBus->BusData->getStopLocation();
$bus = $SCAUBus->RealTimeBus->getBusLocation();
$data = array();
$i = 1;

foreach ($bus['online'] as $online) {
	$data[] = array(
		'id' => $i,
		'title' => $online['busNum'] . ' @ ' . $online['line'] . ' [终端在线]',
		'longitude' => $online['position'][0] - 0.000150,
		'latitude' => $online['position'][1] + 0.000070,
		'iconPath' => '/source/map-marker/marker-bus-online.png',
		'width' => 40,
		'height' => 40
	);
	$i++;
}

foreach ($bus['offline'] as $offline) {
	$data[] = array(
		'id' => $i,
		'title' => $offline['busNum'] . ' [终端离线]',
		'longitude' => $offline['position'][0] - 0.000150,
		'latitude' => $offline['position'][1] + 0.000070,
		'iconPath' => '/source/map-marker/marker-bus-offline.png',
		'width' => 40,
		'height' => 40
	);
	$i++;
}

foreach ($stops as $stop) {
	$data[] = array(
		'id' => $stop['stopId'] + 50,
		'title' => $stop['title'],
		'longitude' => $stop['position'][0] - 0.000150,
		'latitude' => $stop['position'][1] + 0.000070,
		'iconPath' => '/source/map-marker/marker-stop.png',
		'width' => 40,
		'height' => 40
	);
}

print json_encode($data);

die();
