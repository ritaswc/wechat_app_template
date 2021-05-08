<?php
defined('YII_ENV') or exit('Access Denied');

use app\models\Option;

$version = '1.8.3';
$url_manager = Yii::$app->urlManager;
$active_nav_link = isset($this->params['active_nav_link']) ? $this->params['active_nav_link'] : null;

$logo = Option::get('logo', 0, 'admin', null);
$logo = $logo ? $logo : Yii::$app->request->baseUrl . '/statics/admin/images/logo.png';

$copyright = Option::get('copyright', 0, 'admin');
$copyright = $copyright ? $copyright : '©2017 <a href="http://www.zjhejiang.com" target="_blank">禾匠信息科技</a>';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= !empty($this->title) ? $this->title . ' - ' : null ?><?= Option::get('name', 0, 'admin', '禾匠信息科技') ?></title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_353057_pv3mzjwdrk162yb9.css">
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/statics/admin/css/common.css?v=<?= $version ?>">
    <script>var _csrf = "<?=Yii::$app->request->csrfToken?>";</script>
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/plupload/2.3.4/plupload.full.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/common.js?v=<?= $version ?>"></script>
</head>
<body>

<div style="display: table;background: #fff;padding: 20px 40px;font-size: 20px;margin: 50px auto;border-radius: 5px;box-shadow:1px 2px 5px 1px rgba(0,0,0,0.21);color: #d65453;text-align: center">
    <p>您的账户已到期！</p>
    <p style="font-size: 16px">请联系管理员续期。</p>
</div>
</body>
</html>