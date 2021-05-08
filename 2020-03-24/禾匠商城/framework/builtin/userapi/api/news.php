<?php

$url = 'https://way.jd.com/jisuapi/get?channel=头条&num=10&start=0&appkey=e61ea08206439db9cb30910865faad7c';
$resp = ihttp_get($url);
if (200 == $resp['code'] && $resp['content']) {
	$obj = json_decode($resp['content'], true);
	if (empty($obj['result']) || empty($obj['result']['result']) || empty($obj['result']['result']['num'])) {
		return $this->respText('没有找到结果, 要不过一会再试试?');
	}
	$num = $obj['result']['result']['num'];
	$data = $obj['result']['result']['list'];
	$sum = 0;
	for ($i = 0; $i < $num; ++$i) {
		if (empty($data[$i]['pic']) || $sum >= 8) {
			continue;
		}
		$news[] = array(
			'title' => strval($data[$i]['title']),
			'picurl' => strval($data[$i]['pic']),
			'url' => strval($data[$i]['url']),
		);
		++$sum;
	}

	return $this->respNews($news);
}

return $this->respText('没有找到结果, 要不过一会再试试?');
