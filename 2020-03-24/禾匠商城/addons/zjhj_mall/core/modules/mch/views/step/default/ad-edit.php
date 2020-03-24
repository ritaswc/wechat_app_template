<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '流量主编辑';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body"> 
       <form method="post" class="auto-form" return="<?= $urlManager->createUrl(['mch/step/default/ad']) ?>">

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">广告单元ID</label>
                </div>
                <div class="col-sm-5"> 
                    <input class="form-control"
                           name="unit_id"
                           value="<?= $list->unit_id ?>"
                    >
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">位置</label>
                </div>
                <div class="col-sm-5">
                    <select class="form-control form-add-type" name="type">
                        <option <?= $list->type == 1 ? 'selected' : null ?> value="1">步数宝首页</option>
                        <option <?= $list->type == 2 ? 'selected' : null ?> value="2">挑战底部</option>
                        <option <?= $list->type == 3 ? 'selected' : null ?> value="3">排行榜底部</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">状态</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input name="status"
                            <?= $list->status == 0 ? 'checked' : null ?>
                            value="0" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <label class="radio-label">
                        <input name="status"
                            <?= $list->status == 1 ? 'checked' : null ?>
                            value="1" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right"></div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

