<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
$id = intval($_W['cron']['extra']);
$data = table('mc_mass_record')->where(array('uniacid' => $_W['uniacid'], 'id' => $id))->get();
if (empty($data)) {
	$this->addCronLog($id, -1100, '未找到群发的设置信息');
}
$acc = WeAccount::createByUniacid();
if (is_error($acc)) {
	$this->addCronLog($id, -1101, '创建公众号操作对象失败');
}

$status = $acc->fansSendAll($data['group'], $data['msgtype'], $data['media_id']);
if (is_error($status)) {
	table('mc_mass_record')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'id' => $id
		))
		->fill(array(
			'status' => 2,
			'finalsendtime' => TIMESTAMP
		))
		->save();
	$this->addCronLog($id, -1102, $status['message']);
}
table('mc_mass_record')
	->where(array(
		'uniacid' => $_W['uniacid'],
		'id' => $id
	))
	->fill(array(
		'status' => 0,
		'finalsendtime' => TIMESTAMP
	))
	->save();
table('core_cron')->where(array('uniacid' => $_W['uniacid'], 'id' => $_W['cron']['id']))->delete();
$this->addCronLog($id, 0, 'success');
