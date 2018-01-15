<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/25
 * Time: 17:26
 */
//$ch = curl_init();
//$timeout = 5;
//curl_setopt ($ch, CURLOPT_URL, 'http://m.china.com.cn/baidu/doc_1_3_1596740.html');
//curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//$file_contents = curl_exec($ch);
//curl_close($ch);
//
////echo $file_contents;
header("Content-type:text/html;charset=utf-8");
set_time_limit(0);
include './phpQuery.php';
////phpQuery::newDocumentFile('http://m.china.com.cn/baidu/doc_1_3_1596740.html');
////phpQuery::newDocumentFile('http://ytsports.cn/news-10662.html?cid=64');
//phpQuery::newDocumentFile('http://sports.qq.com/a/20160714/021723.htm');
//$t = pq('h1')->text();
//$tt=
//$s = mb_convert_encoding($t,'ISO-8859-1','GBK');
//$q = mb_convert_encoding($t,'GBK','utf-8');
//echo $t." ".$s."".$q;
//因为执行时间较长，所以设置运行时间不被限制---编码utf-8        ini_set('max_execution_time', '0');
//ini_set('date.timezone','Asia/Taipei');
//比价网---採集
//phpQuery::$defaultCharset = 'euc-jp';
$http = 'http://m.china.com.cn/baidu/doc_1_3_1596740.html';
@$html = file_get_contents($http);
$charset = mb_detect_encoding($html ,array('UTF-8','GBK','GB2312'));

if('cp936' == $charset){
    $charset='GBK';
}
if("utf-8" != $charset){
    $html = iconv($charset,"UTF-8//IGNORE",$html);
}
phpQuery::newDocumentHTML($html);
//标题
$str=pq('h1')->text();
//主体
$body=pq('h1:parent');
echo $body;
phpQuery::$documents = array();