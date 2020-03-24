## tencentyun-cos-php

SDK for [腾讯云对象存储服务](http://wiki.qcloud.com/wiki/COS%E4%BA%A7%E5%93%81%E4%BB%8B%E7%BB%8D)

## 源码集成

从github下载源码装入到您的程序中，并加载include.php

## 修改配置

修改Qcloud_cos/Conf.php内的APPID、SECRET_ID、SECRET_KEY等信息为您的配置

## 程序示例（sample.php）

``` php
require('./include.php');
use Qcloud_cos\Auth;
use Qcloud_cos\Cosapi;
use Qcloud_cos\CosDb;

Cosapi::setTimeout(180);

//创建文件夹
$bucketName = 'test';
$srcPath = './test.log';
$dstPath = '/sdk/test.log';
$dstFolder = '/sdk/';
$createFolderRet = Cosapi::createFolder($bucketName, $dstFolder);
var_dump($createFolderRet);

//上传文件
$bizAttr = "";
$insertOnly = 0;
$sliceSize = 3 * 1024 * 1024;
$uploadRet = Cosapi::upload($bucketName, $srcPath, $dstPath,$bizAttr,$sliceSize, $insertOnly);
var_dump($uploadRet);

//目录列表
$listnum = 20;
$pattern = "eListBoth";
$order = 0;
$listRet = Cosapi::listFolder($bucketName, $dstFolder,$listnum,$pattern, $order);
var_dump($listRet);

//更新目录信息
$bizAttr = "";
$updateRet = Cosapi::updateFolder($bucketName, $dstFolder, $bizAttr);
var_dump($updateRet);

//更新文件信息
$bizAttr = "";
$authority = "eWPrivateRPublic";
$customer_headers_array = array(
    'Cache-Control' => "no",
    'Content-Type' => "application/pdf",
    'Content-Language' => "ch",
);
$updateRet = Cosapi::update($bucketName, $dstPath, $bizAttr,$authority, $customer_headers_array);
var_dump($updateRet);

//查询目录信息
$statRet = Cosapi::statFolder($bucketName, $dstFolder);
var_dump($statRet);

//查询文件信息
$statRet = Cosapi::stat($bucketName, $dstPath);
var_dump($statRet);

//删除文件
$delRet = Cosapi::delFile($bucketName, $dstPath);
var_dump($delRet);

//删除目录
$delRet = Cosapi::delFolder($bucketName, $dstFolder);
var_dump($delRet);
```