<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25
 * Time: 15:42
 */
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '日志详情';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">日志标题：<?= $detail['title'] ?></div>
            <div class="col-md-3">日志类型：<?= $detail['action_type'] ?></div>
            <div class="col-md-3">操作时间：<?= date('Y-m-d H:i:s', $detail['addtime']) ?></div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <?= $detail['result'] ?>
            </div>
        </div>
    </div>
</div>
<script>

</script>

