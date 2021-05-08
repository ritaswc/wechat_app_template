<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 11:26
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '操作教程';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <span>点击查看</span><a target="_blank" href="<?=$handle['url']?>">操作教程文档链接</a>
    </div>
</div>