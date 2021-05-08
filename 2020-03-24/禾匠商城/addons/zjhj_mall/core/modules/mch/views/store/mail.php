<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/12
 * Time: 9:46
 */
defined('YII_ENV') or exit('Access Denied');
/* @var $list \app\models\MailSetting */
$urlManager = Yii::$app->urlManager;
$this->title = '邮件通知';
$this->params['active_nav_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?>（QQ邮箱）</div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">开启邮箱提醒</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio2" <?= $list->status == 0 ? 'checked' : null ?>
                               value="0"
                               name="status" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio1" <?= $list->status == 1 ? 'checked' : null ?>
                               value="1"
                               name="status" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发件人邮箱</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="send_mail"
                           value="<?= $list->send_mail ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">授权码</label>
                </div>
                <div class="col-sm-6">
                    <?php if ($list->send_pwd) : ?>
                        <div class="input-hide">
                            <input class="form-control" type="text" name="send_pwd"
                                   value="<?= $list->send_pwd ?>">
                            <div class="tip-block">已隐藏授权码，点击查看或编辑</div>
                        </div>
                    <?php else : ?>
                        <input class="form-control" type="text" name="send_pwd"
                               value="<?= $list->send_pwd ?>">
                    <?php endif; ?>
                    <div class="fs-sm"><a target="_blank"
                                          href="http://service.mail.qq.com/cgi-bin/help?subtype=1&&no=1001256&&id=28">什么是授权码？</a>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">发件平台名称</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="send_name"
                           value="<?= $list->send_name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">收件人邮箱</label>
                </div>
                <div class="col-sm-6">
                    <input autocomplete="off" class="form-control" type="text" name="receive_mail"
                           value="<?= $list->receive_mail ?>">
                    <div class="text-muted fs-sm">多个请用英文逗号隔开</div>
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