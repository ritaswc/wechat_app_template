<?php
function getConnection()
{
    $dbhost = YOUR_HOST;
    $dbuser = YOUR_USER;
    $dbpass = YOUR_PASS;
    $dbname = MYSQL_DB;
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

    return $dbh;
}

/**
 * 将字符串参数变为数组
 * @param $query
 * @return array array (size=10)
          'm' => string 'content' (length=7)
          'c' => string 'index' (length=5)
          'a' => string 'lists' (length=5)
          'catid' => string '6' (length=1)
          'area' => string '0' (length=1)
          'author' => string '0' (length=1)
          'h' => string '0' (length=1)
          'region' => string '0' (length=1)
          's' => string '1' (length=1)
          'page' => string '1' (length=1)
 */
function convertUrlQuery($query)
{
  $queryParts = explode('&', $query);
  $params = array();
  foreach ($queryParts as $param) {
    $item = explode('=', $param);
    $params[$item[0]] = $item[1];
  }
  return $params;
}
/**
 * 将参数变为字符串
 * @param $array_query
 * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0®ion=0&s=1&page=1'(length=73)
 */
function getUrlQuery($array_query)
{
  $tmp = array();
  foreach($array_query as $k=>$param)
  {
    $tmp[] = $k.'='.$param;
  }
  $params = implode('&',$tmp);
  return $params;
}
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$arr = parse_url($url);
$arr_query = convertUrlQuery($arr['query']);

if(!$arr_query['p']){
    $arr_query['p'] = 1;
}
$arr_query['p'] = (int)$arr_query['p'];


$categorys = array(
    1 => '清纯美女',
    2 => '唯美写真',
    3 => '性感美女',
    4 => '明星美女',
    5 => '丝袜美女',
    6 => '美女模特'
);
$where = '';
if($arr_query['type']){
  $where = 'where category="'.$categorys[$arr_query['type']].'"';
}


$sql = "SELECT id,addtime,image,content,name,category FROM meinv_content ".$where." ORDER BY id ASC LIMIT ".(($arr_query['p'] -1)*10).",10";
try {
    $db = getConnection();
    $db->query('set names utf8;');
    $stmt = $db->prepare($sql);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $db = null;
    echo '{"girls": ' . json_encode($results) . '}';
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}

?>