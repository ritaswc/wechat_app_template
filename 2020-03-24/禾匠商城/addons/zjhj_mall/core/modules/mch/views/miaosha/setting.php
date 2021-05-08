<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '秒杀设置';
$this->params['active_nav_group'] = 10;
$this->params['is_book'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/miaosha/setting']) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label">未支付订单取消时间</label>
                    </div>
                    <div class="col-5 col-form-label">
                        <div class="input-group">
                            <input class="form-control" type="number" name="model[unpaid]"
                                   value="<?= $setting->unpaid ?: 1 ?>">
                            <span class="input-group-addon">分钟</span>
                        </div>
                        <div class="text-muted fs-sm">注意：不设置默认一分钟自动取消</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">是否开启分销</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input <?= $setting->is_share == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_share]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting->is_share == 1 ? "checked" : "" ?>
                                type="radio" name="model[is_share]" value="1">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm text-danger">
                            注：必须现在“分销中心=><a href="<?= $urlManager->createUrl(['mch/share/basic']) ?>"
                                            target="_blank">分销设置=>基础设置</a>”中设置，才能使用
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">是否开启短信提醒</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input <?= $setting->is_sms == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_sms]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting->is_sms == 1 ? "checked" : "" ?>
                                type="radio" name="model[is_sms]" value="1">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm text-danger">
                            注：必须现在“商城管理=><a href="<?= $urlManager->createUrl(['mch/store/sms']) ?>"
                                            target="_blank">短信通知</a>”中设置，才能使用
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">是否开启邮件提醒</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input <?= $setting->is_mail == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_mail]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting->is_mail == 1 ? "checked" : "" ?>
                                type="radio" name="model[is_mail]" value="1">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm text-danger">
                            注：必须现在“商城管理=><a href="<?= $urlManager->createUrl(['mch/store/mail']) ?>"
                                            target="_blank">邮件通知</a>”中设置，才能使用
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">是否开启订单打印</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input <?= $setting->is_print == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_print]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting->is_print == 1 ? "checked" : "" ?>
                                type="radio" name="model[is_print]" value="1">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm text-danger">
                            注：必须现在“商城管理=><a href="<?= $urlManager->createUrl(['mch/printer/setting']) ?>"
                                            target="_blank">小票打印=>打印设置</a>”中设置，才能使用
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">是否开启区域允许购买</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input <?= $setting->is_area == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_area]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting->is_area == 1 ? "checked" : "" ?>
                                type="radio" name="model[is_area]" value="1">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm text-danger">
                            注：必须现在“商城管理=><a href="<?= $urlManager->createUrl(['mch/store/territorial-index']) ?>"
                                            target="_blank">区域允许购买</a>”中设置，才能使用
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
