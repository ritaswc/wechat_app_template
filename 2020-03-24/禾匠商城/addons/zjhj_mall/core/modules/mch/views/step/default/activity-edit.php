<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '挑战编辑';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body"> 
       <form method="post" class="auto-form" return="<?= $urlManager->createUrl(['mch/step/default/activity']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">活动名称</label>
                </div>
                <div class="col-sm-5"> 
                    <input class="form-control"
                           name="name"
                           value="<?= $list->name ?>"
                    >
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">活动时间</label>
                </div>
                <div class="col-sm-5"> 
                    <input class="form-control"
                            id="open_date"
                            name="open_date"
                            value="<?= $list->open_date ?>"
                    >
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">挑战步数</label>
                </div>
                <div class="col-sm-5">
                    <input class="form-control"
                            type="number"
                            step="1"
                            max="100000000"
                            min="1"
                            name="step_num"
                            value="<?= $list->step_num ?>"
                    >
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label required">缴纳金</label>
                </div>

                <div class="col-sm-5">
                    <div class="input-group short-row">
                        <input class="form-control"
                            type="number"
                            step="0.01"
                            max="100000000"
                            min="0.01"
                            name="bail_currency"
                            value="<?= $list->bail_currency ?>"
                        >
                        <span class="input-group-addon">活力币</span>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class="col-form-label">初始奖金池</label>
                </div>

                <div class="col-sm-5">
                    <div class="input-group short-row">
                        <input class="form-control"
                            type="number"
                            step="0.01"
                            max="100000000"
                            min="0.01"
                            name="currency"
                            value="<?= $list->currency ?>"
                        >
                        <span class="input-group-addon">活力币</span>
                    </div>
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

<script>
    (function () {
        $.datetimepicker.setLocale('zh');
        $('#open_date').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#begin_date').val() ? $('#begin_date').val() : false,
                })
            },
            timepicker: false,
        });
    })();
</script>


