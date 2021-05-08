<?php

$message = $this->message;

function render(&$str) {
	$str = "呵呵 {$str} 哈哈";
}

$ret = preg_match('/(?:userapi)(.*)/i', $this->message['content'], $matchs);
if (!$ret) {
	return $this->respText('请输入合适的格式, "userapi+查询内容", 如: "userapi微擎"');
}
$word = $matchs[1];

render($word);

return $this->respText($word);
