<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
error_reporting(0);
load()->classs('captcha');
session_start();

$captcha = new Captcha();
$captcha->build(150, 40);
$key = complex_authkey();
$hash = md5(strtolower($captcha->phrase) . $key);
isetcookie('__code', $hash);
$_SESSION['__code'] = $hash;

$captcha->output();