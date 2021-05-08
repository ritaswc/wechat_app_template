<?php
/**
 *  HMT Bus GO! (WXSS VER.)
 *
 *  @author CRH380A-2722 <609657831@qq.com>
 *  @copyright 2016-2017 CRH380A-2722
 *  @license MIT
 *	@note 获取线路路径
 */

header("Content-Type: application/json; charset=utf-8\n");

require_once '../inc.Config.php';

$data = array();

if (!Verifier::isNumber(@$_GET['id'])) {
	$data = $SCAUBus->BusData->getPolyLineList();
} else {

	$id = $_GET['id'];

	$polyline = $SCAUBus->BusData->getPolyline($id);

	$points = array();

	foreach ($polyline[0]['poly_path'] as $point) {
		$points[] = array(
			'latitude' => $point[1],
			'longitude' => $point[0]
		);
	}

	$data = array(
		'points' => $points,
		'color' => $polyline[0]['poly_color'],
		'width' => 5
	);

}

print json_encode($data);

die();
