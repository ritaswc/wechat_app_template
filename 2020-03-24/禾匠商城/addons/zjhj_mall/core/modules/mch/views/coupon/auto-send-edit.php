<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 10:18
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '自动发放优惠券编辑';
$this->params['active_nav_group'] = 7;
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl(['mch/coupon/auto-send']);
}
$events = [
    1 => '页面转发',
    2 => '购买并付款',
];
?>
<!--<link href="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet">-->
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" return="<?= $returnUrl ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label required">触发事件</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control" name="event">
                            <?php foreach ($events as $i => $v) : ?>
                                <option
                                    value="<?= $i ?>" <?= $model->event == $i ? 'selected' : null ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label required">发放的优惠券</label>
                    </div>
                    <div class="col-9">
                        <select class="form-control" name="coupon_id">
                            <?php foreach ($coupon_list as $coupon) : ?>
                                <option
                                    value="<?= $coupon->id ?>" <?= $model->coupon_id == $coupon->id ? 'selected' : null ?>>
                                    <?= $coupon->id ?>:<?= $coupon->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label required">该方案下最多发放次数</label>
                    </div>
                    <div class="col-9">
                        <input value="<?= $model->send_times ?>" class="form-control"
                               name="send_times" type="number">
                        <div class="fs-sm text-muted">如不限制发放次数，请填写0</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        <input type="button" class="btn btn-default ml-4" 
                               name="Submit" onclick="javascript:history.back(-1);" value="返回">
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<!--<script src="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>-->
<script>


</script>