<?php

$message = $this->message;

$ret = preg_match('/(.+)天气/i', $this->message['content'], $matchs);
if (!$ret) {
	return $this->respText('请输入合适的格式, 城市+天气, 例如: 北京天气');
}
$city = $matchs[1];
$response = array();

$url = 'https://way.jd.com/he/freeweather?city=%s&appkey=e61ea08206439db9cb30910865faad7c';
$obj = weather_http_request($url, $city);

$data = $city . '今日天气：' . $obj['cond']['txt'] . PHP_EOL .
				'空气质量：' . $obj['aqi']['city']['qlty'] . PHP_EOL .
				'pm25：' . $obj['aqi']['city']['pm25'] . PHP_EOL .
				'温度：' . $obj['now']['tmp'] . '摄氏度。' . PHP_EOL .
				'湿度：' . $obj['now']['hum'] . PHP_EOL .
				'能见度：' . $obj['now']['vis'] . PHP_EOL .
				'降水量：' . $obj['now']['pcpn'] . PHP_EOL .
				'感冒指数：' . $obj['now']['fl'] . PHP_EOL .
				'风级：' . $obj['now']['wind']['sc'] . PHP_EOL .
				'风向：' . $obj['now']['wind']['dir'] . PHP_EOL;

$response = $this->respText($data);

return $response;

function weather_http_request($url, $city) {
	$url = sprintf($url, $city);
	$resp = ihttp_get($url);
	if (200 == $resp['code'] && $resp['content']) {
		$obj = json_decode($resp['content'], true);

		return $obj['result']['HeWeather5'][0];
	}

	return '';
}
