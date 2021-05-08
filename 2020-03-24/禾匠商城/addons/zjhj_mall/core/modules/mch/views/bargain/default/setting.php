<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/10
 * Time: 13:52
 */

$urlManager = Yii::$app->urlManager;
$this->title = '基础设置';
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off"
              action="<?=$urlManager->createUrl(['mch/bargain/default/setting-save'])?>"
              return="<?= $urlManager->createUrl(['mch/bargain/default/setting']) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label">是否开启分销</label>
                    </div>
                    <div class="col-9 col-form-label">
                        <label class="radio-label">
                            <input <?= $setting['is_share'] == 0 ? 'checked' : 'checked' ?>
                                value="0" name="model[is_share]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting['is_share'] == 1 ? 'checked' : null ?>
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
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">活动规则</label>
                    </div>
                    <div class="col-sm-6">
                        <textarea class="form-control short-row" name="model[content]"><?=$setting['content']?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">分享标题</label>
                    </div>
                    <div class="col-sm-6">
                        <textarea class="form-control short-row" name="model[share_title]" style="min-height: 100px;;"><?=$setting['share_title']?></textarea>
                        <div>多个标题请换行，多个标题随机选一个标题显示</div>
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
