<?php

$week = array();
$week[0] = '日';
$week[1] = '一';
$week[2] = '二';
$week[3] = '三';
$week[4] = '四';
$week[5] = '五';
$week[6] = '六';

$reply = '今天是 ' . date('Y年n月j日') . ' 星期' . $week[date('w')];

$url = 'http://hl.zdic.net/j/gl/' . date('Y/n') . '.php';
$response = ihttp_get($url);
if (200 == $response['code'] && !empty($response['content'])) {
	preg_match('[{.*}]', $response['content'], $content);
	$data = json_decode("[{$content[0]}]", true);
	if (is_array($data)) {
		$today = date('Y-m-d');
		foreach ($data as $item) {
			if ($item['date'] == $today) {
				$reply .= "\n==================\n";
				$reply .= $item['nongliDay'];

				$reply .= "\n==================\n";
				$reply .= "宜: \n";
				$reply .= $item['yi'];

				$reply .= "\n==================\n";
				$reply .= "忌: \n";
				$reply .= $item['ji'];
				break;
			}
		}
	}
}

return $this->respText($reply);
