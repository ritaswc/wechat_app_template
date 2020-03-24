<?php

require('./include.php');

use qcloudcos\Cosapi;

$bucket = 'testbucket';
$src = './111.txt';
$dst = '/testfolder/111.txt';
$folder = '/testfolder';

Cosapi::setTimeout(180);

// 设置COS所在的区域，对应关系如下：
//     华南  -> gz
//     华中  -> sh
//     华北  -> tj
Cosapi::setRegion('gz');

// Create folder in bucket.
$ret = Cosapi::createFolder($bucket, $folder);
var_dump($ret);

// Upload file into bucket.
$ret = Cosapi::upload($bucket, $src, $dst);
var_dump($ret);

// List folder.
$ret = Cosapi::listFolder($bucket, $folder);
var_dump($ret);

// Update folder information.
$bizAttr = "";
$ret = Cosapi::updateFolder($bucket, $folder, $bizAttr);
var_dump($ret);

// Update file information.
$bizAttr = '';
$authority = 'eWPrivateRPublic';
$customerHeaders = array(
    'Cache-Control' => 'no',
    'Content-Type' => 'application/pdf',
    'Content-Language' => 'ch',
);
$ret = Cosapi::update($bucket, $dst, $bizAttr,$authority, $customerHeaders);
var_dump($ret);

// Stat folder.
$ret = Cosapi::statFolder($bucket, $folder);
var_dump($ret);

// Stat file.
$ret = Cosapi::stat($bucket, $dst);
var_dump($ret);

// Copy file.
$ret = Cosapi::copyFile($bucket, $dst, $dst . '_copy');
var_dump($ret);

// Move file.
$ret = Cosapi::moveFile($bucket, $dst, $dst . '_move');
var_dump($ret);

// Delete file.
$ret = Cosapi::delFile($bucket, $dst . '_copy');
var_dump($ret);
$ret = Cosapi::delFile($bucket, $dst . '_move');
var_dump($ret);

// Delete folder.
$ret = Cosapi::delFolder($bucket, $folder);
var_dump($ret);
