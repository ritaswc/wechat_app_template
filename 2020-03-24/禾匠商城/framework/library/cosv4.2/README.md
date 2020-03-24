cos-php-sdk：php sdk for [腾讯云对象存储服务](https://www.qcloud.com/product/cos.html)
===================================================================================================

### 安装（直接下载源码集成）
直接从[github](https://github.com/tencentyun/cos-php-sdk-v4)下载源码，然后在您的程序中加载cos-php-sdk-v4/include.php就可以了。

### 修改配置
修改cos-php-sdk-v4/qcloudcos/conf.php内的APPID、SECRET_ID、SECRET_KEY为您的配置。

### 示例程序
请参考sample.php

```php
// 包含cos-php-sdk-v4/include.php文件
require('cos-php-sdk-v4/include.php');
use qcloudcos\Cosapi;

// 设置COS所在的区域，对应关系如下：
//     华南  -> gz
//     华中  -> sh
//     华北  -> tj
Cosapi::setRegion('gz');

// 创建文件夹
$ret = Cosapi::createFolder($bucket, $folder);
var_dump($ret);

// 上传文件
$ret = Cosapi::upload($bucket, $src, $dst);
var_dump($ret);

// 目录列表
$ret = Cosapi::listFolder($bucket, $folder);
var_dump($ret);

// 更新目录信息
$bizAttr = "";
$ret = Cosapi::updateFolder($bucket, $folder, $bizAttr);
var_dump($ret);

// 更新文件信息
$bizAttr = '';
$authority = 'eWPrivateRPublic';
$customerHeaders = array(
    'Cache-Control' => 'no',
    'Content-Type' => 'application/pdf',
    'Content-Language' => 'ch',
);
$ret = Cosapi::update($bucket, $dst, $bizAttr, $authority, $customerHeaders);
var_dump($ret);

// 查询目录信息
$ret = Cosapi::statFolder($bucket, $folder);
var_dump($ret);

// 查询文件信息
$ret = Cosapi::stat($bucket, $dst);
var_dump($ret);

// 删除文件
$ret = Cosapi::delFile($bucket, $dst);
var_dump($ret);

// 删除目录
$ret = Cosapi::delFolder($bucket, $folder);
var_dump($ret);

// 复制文件
$ret = Cosapi::copyFile($bucket, '/111.txt', '/111_2.txt');
var_dump($ret);

// 移动文件
$ret = Cosapi::moveFile($bucket, '/111.txt', '/111_3.txt');
var_dump($ret);
```
