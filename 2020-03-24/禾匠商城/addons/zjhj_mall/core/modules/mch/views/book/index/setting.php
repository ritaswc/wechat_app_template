<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '预约设置';
$this->params['active_nav_group'] = 10;
$this->params['is_book'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/book/index/setting']) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label">是否显示分类</label>
                    </div>
                    <div class="col-9 col-form-label">
                        <label class="radio-label">
                            <input <?= $setting['cat'] == 1 ? 'checked' : '' ?>
                                value="1" name="model[cat]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">显示</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting['cat'] == 0 ? 'checked' : '' ?>
                                value="0" name="model[cat]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">不显示</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label">是否开启分销</label>
                    </div>
                    <div class="col-9 col-form-label">
                        <label class="radio-label">
                            <input <?= $setting['is_share'] == 0 ? 'checked' : '' ?>
                                value="0" name="model[is_share]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting['is_share'] == 1 ? 'checked' : '' ?>
                                value="1" name="model[is_share]" type="radio" class="custom-control-input">
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
                            <input <?= $setting['is_sms'] == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_sms]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting['is_sms'] == 1 ? "checked" : "" ?>
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
                            <input <?= $setting['is_mail'] == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_mail]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting['is_mail'] == 1 ? "checked" : "" ?>
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
                        <label class="col-form-label">支付方式</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="checkbox-label">
                            <input <?= $yyPayment['wechat'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="payment[wechat]" type="checkbox" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">线上支付</span>
                        </label>
                        <label class="checkbox-label">
                            <input <?= $yyPayment['balance'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="payment[balance]" type="checkbox" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">余额支付</span>
                        </label>
                        <div class="fs-sm text-danger">默认支持线上支付；若二个都不勾选，则视为勾选线上支付</div>
                    </div>
                </div>

                <div class="form-group row" hidden>
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">是否开启订单打印</label>
                    </div>
                    <div class="col-sm-6">
                        <label class="radio-label">
                            <input <?= $setting['is_print'] == 0 ? "checked" : "" ?>
                                type="radio" name="model[is_print]" value="0">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting['is_print'] == 1 ? "checked" : "" ?>
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
