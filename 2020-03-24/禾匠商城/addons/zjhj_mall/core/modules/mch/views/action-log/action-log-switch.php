<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

defined('YII_ENV') or exit('Access Denied');
/* @var $sms \app\models\SmsSetting */
$urlManager = Yii::$app->urlManager;
$this->title = '日志设置';
$this->params['active_nav_group'] = 1;
?>

<div class="panel">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form method="post" class="auto-form">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">日志启用状态</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio2" <?= $option == 'off' ? 'checked' : null ?>
                               value=off
                               name="switch" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio1" <?= $option == 'on' || empty($option) ? 'checked' : null ?>
                               value=on
                               name="switch" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>

        </form>
    </div>
</div>
