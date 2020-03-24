<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '清除缓存';
$this->params['active_nav_group'] = 1;
?>

<div class="panel mb-3">
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label>清除项目</label>
                </div>
                <div class="col-sm-8">
                    <label class="checkbox-label">
                        <input name="data" type="checkbox" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">数据缓存</span>
                    </label>
                    <label class="checkbox-label">
                        <input name="pic" type="checkbox" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">临时图片</span>
                    </label>
                    <label class="checkbox-label">
                        <input name="update" type="checkbox" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">更新缓存</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">提交</a>
                </div>
            </div>
        </form>
    </div>
</div>
