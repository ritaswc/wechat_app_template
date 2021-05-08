<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 15:40
 */
defined('YII_ENV') or exit('Access Denied');
/* @var $sms \app\models\SmsSetting */
$urlManager = Yii::$app->urlManager;
$this->title = '短信通知';
$this->params['active_nav_group'] = 1;
?>

<div class="panel">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form method="post" class="auto-form">
            <p>短信设置用于用户下单时，给指定手机号发送短信通知。</p>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">开启短信提醒</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio2" <?= $sms->status == 0 ? 'checked' : null ?>
                               value="0"
                               name="status" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio1" <?= $sms->status == 1 ? 'checked' : null ?>
                               value="1"
                               name="status" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">阿里云AccessKeyId</label>
                </div>
                <div class="col-sm-6">
                    <?php if ($sms->AccessKeyId): ?>
                        <div class="input-hide">
                            <input class="form-control" type="text" name="AccessKeyId"
                                   value="<?= $sms->AccessKeyId ?>">
                            <div class="tip-block">已隐藏AccessKeyId，点击查看或编辑</div>
                        </div>
                    <?php else: ?>
                        <input class="form-control" type="text" name="AccessKeyId"
                               value="<?= $sms->AccessKeyId ?>">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">阿里云AccessKeySecret</label>
                </div>
                <div class="col-sm-6">
                    <?php if ($sms->AccessKeyId): ?>
                        <div class="input-hide">
                            <input class="form-control" type="text" name="AccessKeySecret"
                                   value="<?= $sms->AccessKeySecret ?>">
                            <div class="tip-block">已隐藏AccessKeySecret，点击查看或编辑</div>
                        </div>
                    <?php else: ?>
                        <input class="form-control" type="text" name="AccessKeySecret"
                               value="<?= $sms->AccessKeySecret ?>">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板签名</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="sign"
                           value="<?= $sms->sign ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">接收短信手机号</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="mobile"
                           value="<?= $sms->mobile ?>">
                    <div class="fs-sm text-muted">多个请使用英文逗号<code>,</code>分隔</div>
                </div>
            </div>

            <div>订单下单提醒</div>
            <div class="text-muted fs-sm">例如：模板内容:您有一条新的订单，订单号：89757，请登录商城后台查看。 </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板名称</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="name"
                           value="<?= $sms->name ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板ID</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="tpl"
                           value="<?= $sms->tpl ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板变量</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="msg"
                           value="<?= $sms->msg ?>">
                    <div class="text-muted fs-sm">例如：模板内容: 您有一个新的订单，订单号：${order}，<b class="text-danger">则只需填写order</b></div>
                    <div class="text-danger fs-sm">注意：目前只支持设置订单号</div>
                </div>
            </div>

            <div>订单退款提醒</div>
            <div class="text-muted fs-sm">例如：模板内容:您有一条新的退款订单，请登录商城后台查看。 </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板名称</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="refund[name]"
                           value="<?= $refund['name'] ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板ID</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="refund[tpl]"
                           value="<?= $refund['tpl'] ?>">
                </div>
            </div>

            <div class="form-group row" hidden>
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板变量</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="refund[msg]"
                           value="<?= $refund['msg'] ?>">
                    <div class="text-muted fs-sm">例如：模板内容: 您有一个新的订单，订单号：${order}，则填写order</div>
                    <div class="text-danger fs-sm">注意：目前只支持设置订单号</div>
                </div>
            </div>

            <div>发送手机验证码</div>
            <div class="text-muted fs-sm">例如：模板内容:您的验证码为89757，请勿告知他人。</div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板名称</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="code[name]"
                           value="<?= $code['name'] ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板ID</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="code[tpl]"
                           value="<?= $code['tpl'] ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">模板变量</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="code[msg]"
                           value="<?= $code['msg'] ?>">
                    <div class="text-muted fs-sm">例如：模板内容: 您的验证码为${code}，请勿告知他人。，<b class="text-danger">则只需填写code</b></div>
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
