<?php

$message = $this->message;

$ret = preg_match('/@(.+)/', $this->message['content'], $matchs);
if (!$ret) {
	return $this->respText('请输入合适的格式, @查询内容(中文或英文)');
}
$word = $matchs[1];

$url = 'http://dict.youdao.com/search?q=%s';
$url = sprintf($url, $word);

$resp = ihttp_get($url);
if (200 == $resp['code'] && $resp['content']) {
	if (preg_match('/(?P<block><h2 class="wordbook-js">.+<\/h2>)/s', $resp['content'], $block) && preg_match('/<div class="trans-container">.*?(?P<trans><ul>.+?<\/ul>).*?<\/div>/s', $resp['content'], $trans)) {
		$block = $block['block'];
		if (preg_match('/<span class="keyword">(?P<keyword>.+?)<\/span>/', $block, $keyword)) {
			$keyword = $keyword['keyword'];
			$rs = array();
			$ds = array();
			if (preg_match_all('/<span class="(pronounce|phonetic)">(?P<tic>.+?)<\/span>/s', $block, $tics)) {
				foreach ($tics['tic'] as $line) {
					$line = trim(strip_tags($line));
					if ($line) {
						$rs[] = preg_replace('/\s+/', ' ', $line);
					}
				}
			}
			$trans = $trans['trans'];
			if (preg_match_all('/<p class="wordGroup">(?P<line1>.+?)<\/p>|<li>(?P<line2>.+?)<\/li>/s', $trans, $lines)) {
				foreach ($lines['line1'] as $line) {
					$line = trim(strip_tags($line));
					if ($line) {
						$ds[] = $line;
					}
				}
				foreach ($lines['line2'] as $line) {
					$line = trim(strip_tags($line));
					if ($line) {
						$ds[] = $line;
					}
				}
			}

			$reply = "{$keyword}\n==================\n";
			if ($rs) {
				$reply .= "发音:\n";
				foreach ($rs as $row) {
					$reply .= "{$row}\n";
				}
				$reply .= "==================\n";
			}
			if ($rs) {
				$ds = preg_replace("/\s+/", '', $ds);
				foreach ($ds as $row) {
					$reply .= "{$row}\n";
				}
			}

			return $this->respText($reply);
		}
	}
}

return $this->respText('没有找到结果, 要不换个词试试?');
