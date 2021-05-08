<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '好物圈设置';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div style="background-color: #fce9e6;width: 100%;border-color: #edd7d4;color: #e55640;border-radius: 2px;padding: 15px;margin-bottom: 20px;">
            【微信好物圈】是由微信提供的订单和商品管理工具，用户可以通过好物圈，统一查看并管理所有小程序的已购订单和购物车商品。
            <div>1、打开微信“发现”-“小程序”</div>
            <div>2、点击搜索框，即可看到好物圈入口</div>
        </div>
        <form class="form auto-form" method="post" autocomplete="off" return="<?= $urlManager->createUrl(['mch/gwd/setting/index']) ?>">

            <div class="form-group row">
                <div class="form-group-label col-sm-3 text-right">
                    <label class="col-form-label">小程序端好物圈开关</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input <?= $setting->status == 0 ? "checked" : "" ?>
                            type="radio" name="status" value="0">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <label class="radio-label">
                        <input <?= $setting->status == 1 ? "checked" : "" ?>
                            type="radio" name="status" value="1">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
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
