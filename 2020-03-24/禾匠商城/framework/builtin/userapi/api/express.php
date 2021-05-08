<?php

$matchs = array();
$ret = preg_match('/^(?P<express>申通|圆通|中通|汇通|韵达|顺丰|ems|天天|宅急送|邮政|德邦|全峰) *(?P<sn>[a-z\d]{1,})$/i', $this->message['content'], $matchs);
if (!$ret) {
	return $this->respText('请输入合适的格式, 快递公司+空格+单号(当前仅支持申通,圆通,中通,汇通,韵达,顺丰,EMS,天天,宅急送,邮政,德邦,全峰), 例如: 申通 2309381801');
}
$express = $matchs['express'];
$sn = $matchs['sn'];
$mappings = array(
	'申通' => 'shentong',
	'圆通' => 'yuantong',
	'中通' => 'zhongtong',
	'汇通' => 'huitongkuaidi',
	'韵达' => 'yunda',
	'顺丰' => 'shunfeng',
	'ems' => 'ems',
	'天天' => 'tiantian',
	'宅急送' => 'zhaijisong',
	'邮政' => 'youzhengguonei',
	'德邦' => 'debangwuliu',
	'全峰' => 'quanfengkuaidi',
);
$images = array(
	'shentong' => 'http://cdn.kuaidi100.com/images/all/st_logo.gif',
	'yuantong' => 'http://cdn.kuaidi100.com/images/all/yt_logo.gif',
	'zhongtong' => 'http://cdn.kuaidi100.com/images/all/zt_logo.gif',
	'huitongkuaidi' => 'http://cdn.kuaidi100.com/images/all/htky_logo.gif',
	'yunda' => 'http://cdn.kuaidi100.com/images/all/yd_logo.gif',
	'shunfeng' => 'http://cdn.kuaidi100.com/images/all/sf_logo.gif',
	'ems' => 'http://cdn.kuaidi100.com/images/all/ems_logo.gif',
	'tiantian' => 'http://cdn.kuaidi100.com/images/all/tt_logo.gif',
	'zhaijisong' => 'http://cdn.kuaidi100.com/images/all/zjs_logo.gif',
	'youzhengguonei' => 'http://cdn.kuaidi100.com/images/all/yzgn_logo.gif',
	'debangwuliu' => 'http://cdn.kuaidi100.com/images/all/dbwl_logo.gif',
	'quanfengkuaidi' => 'http://cdn.kuaidi100.com/images/all/qfkd_logo.gif',
);
$code = $mappings[$express];
$rand = rand();
$url = "http://wap.kuaidi100.com/wap_result.jsp?rand={$rand}&id={$code}&fromWeb=null&&postid={$sn}";
$dat = ihttp_get($url);
$msg = '';
if (!empty($dat) && !empty($dat['content'])) {
	$reply = $dat['content'];
	preg_match('/查询结果如下所示.+/', $reply, $matchs);
	$reply = $matchs[0];

	preg_match_all('/&middot;(.*?)<br \/>(.*?)<\/p>/', $reply, $matchs);
	$traces = '';
	for ($i = 0; $i < count($matchs[0]); ++$i) {
		$traces .= $matchs[1][$i] . '-' . $matchs[2][$i] . PHP_EOL;
	}
	$replys = array();
	$replys[] = array(
		'title' => '已经为你查到相关快递记录:',
		'picurl' => $images[$code],
		'description' => $traces,
		'url' => 'http://m.kuaidi100.com/index_all.html?type=' . $code . '&postid=' . $sn,
	);

	return $this->respNews($replys);
}

return $this->respText('没有查找到相关的数据' . $msg . '. 请重新发送或检查您的输入格式, 正确格式为: 快递公司+空格+单号, 例如: 申通 2309381801');
