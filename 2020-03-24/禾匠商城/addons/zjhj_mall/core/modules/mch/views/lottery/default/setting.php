<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '抽奖设置';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" return="<?= $urlManager->createUrl(['mch/lottery/default/setting']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">小程序标题</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input class="form-control" name="title" 
                           value="<?= $setting->title ?>">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">赠送中奖码规则</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input <?= $setting->type == 0 ? "checked" : "" ?>
                            type="radio" name="type" value="0">
                        <span class="label-icon"></span>
                        <span class="label-text">分享即送</span>
                    </label>
                    <label class="radio-label">
                        <input <?= $setting->type == 1 ? "checked" : "" ?>
                            type="radio" name="type" value="1">
                        <span class="label-icon"></span>
                        <span class="label-text">被分享人参与抽奖</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">规则说明</label>
                </div>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="15" name="rule"><?= $setting->rule ?></textarea>
                </div>
            </div>



            <div class="form-group row">
                <div class="form-group-label col-3 text-right"></div>
                <div class="col-9">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>
