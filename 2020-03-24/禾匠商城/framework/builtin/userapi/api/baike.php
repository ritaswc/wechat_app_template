<?php

$message = $this->message;

$ret = preg_match('/(?:百科|定义)(.*)/i', $this->message['content'], $matchs);
if (!$ret) {
	return $this->respText('请输入合适的格式, "百科+查询内容" 或 "定义+查询内容", 如: "百科姚明", "定义自行车"');
}
$word = $matchs[1];

$url = 'https://wapbaike.baidu.com/searchresult?word=%s';
$url = sprintf($url, $word);

$resp = ihttp_get($url);
if (200 == $resp['code'] && $resp['content']) {
	preg_match('|<ul.*id="searchList">.*</ul>|isU', $resp['content'], $content);
	if (!empty($content[0])) {
		$content = preg_replace('/(<em>|<em.*>|<\/em>)/', '', $content[0]);
		if (preg_match_all('|<li>.*<a href="(?<link>.+)">.*<span>(?<title>.+)_百度百科</span>.*<p>(?<description>.+)</p>.*</a>.*</li>|isU', $content, $matchs)) {
			$ds = array();
			foreach ($matchs['title'] as $key => $v) {
				$ds[] = array(
					'title' => strip_tags($v),
					'link' => $matchs['link'][$key],
					'description' => strip_tags($matchs['description'][$key]),
				);
			}
			$news = array();
			$news[] = array('title' => "{$word} 的百科解释如下", 'description' => $ds[0]['description'], 'picurl' => 'http://g.hiphotos.baidu.com/baike/c0%3Dbaike180%2C5%2C5%2C180%2C60/sign=f38225303901213fdb3e468e358e5db4/9358d109b3de9c82afcae8666c81800a18d8bc3eb0356b97.jpg', 'url' => $ds[0]['link']);
			$cnt = min(count($ds), 8);
			for ($i = 0; $i < $cnt; ++$i) {
				$news[] = array(
					'title' => $ds[$i]['title'],
					'description' => $ds[$i]['description'],
					'picurl' => '',
					'url' => $ds[$i]['link'],
				);
			}

			return $this->respNews($news);
		}
	}
}

return $this->respText('没有找到结果, 要不换个词试试?');
