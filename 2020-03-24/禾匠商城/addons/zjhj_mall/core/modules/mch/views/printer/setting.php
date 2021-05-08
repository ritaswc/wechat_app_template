<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 16:16
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;
use \app\models\Option;

/* @var \app\models\Printer[] $list */
/* @var \app\models\PrinterSetting $model */

$urlManager = Yii::$app->urlManager;
$this->title = '打印设置';
$this->params['active_nav_group'] = 13;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">选择打印机</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" name="printer_id">
                        <?php foreach ($list as $index => $value) : ?>
                            <option value="<?= $value->id ?>" <?= $model->printer_id == $value['id'] ? "selected" : "" ?>><?= $value->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">订单打印方式</label>
                </div>
                <div class="col-sm-6">
                    <label class="checkbox-label">
                        <input id="radio1"
                               value="1" <?= $model->type['order'] == 1 ? "checked" : "" ?>
                               name="type[order]" type="checkbox" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">下单打印</span>
                    </label>
                    <label class="checkbox-label">
                        <input id="radio2"
                               value="1" <?= $model->type['pay'] == 1 ? "checked" : "" ?>
                               name="type[pay]" type="checkbox" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">付款打印</span>
                    </label>
                    <label class="checkbox-label">
                        <input id="radio2"
                               value="1" <?= $model->type['confirm'] == 1 ? "checked" : "" ?>
                               name="type[confirm]" type="checkbox" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">确认收货打印</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否打印规格</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio1" <?= $model->is_attr == 1 ? 'checked' : null ?>
                               value="1"
                               name="is_attr" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio2" <?= $model->is_attr == 0 ? 'checked' : null ?>
                               value="0"
                               name="is_attr" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">显示大小</label>
                    <div class="fs-sm">收货信息或门店</div>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="big1" <?= $model->big == 1 ? 'checked' : null ?>
                               value="1"
                               name="big" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">一倍</span>
                    </label>
                    <label class="radio-label">
                        <input id="big2" <?= $model->big == 2 ? 'checked' : null ?>
                               value="2"
                               name="big" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">两倍</span>
                    </label>
                    <label class="radio-label">
                        <input id="big3" <?= $model->big == 3 ? 'checked' : null ?>
                               value="3"
                               name="big" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">三倍</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>
