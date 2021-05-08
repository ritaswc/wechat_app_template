<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 15:40
 */
/* @var $list \app\models\Setting */
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '佣金设置';
$this->params['active_nav_group'] = 5;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" style="max-width: 50rem;">
            <div class="form-body">
                <?php if ($list->level != 0) : ?>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">分销佣金类型</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="radio-label price_type">
                                    <input id="radio1" <?= $list->price_type == 0 ? 'checked' : null ?>
                                           value="0"
                                           name="model[price_type]" type="radio" class="custom-control-input">
                                    <span class="label-icon"></span>
                                    <span class="label-text">百分比</span>
                                </label>
                                <label class="radio-label price_type">
                                    <input id="radio2" <?= $list->price_type == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[price_type]" type="radio" class="custom-control-input">
                                    <span class="label-icon"></span>
                                    <span class="label-text">固定金额</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!--2018-05-22 注释--风哀伤
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">自购返利</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group">
                                <input class="form-control" type="number" name="model[rebate]" min="0"
                                       value="<?= $list->rebate ? $list->rebate : 0 ?>">
                                <span class="input-group-addon percent"><?= $list->price_type == 0 ? '%' : '元' ?></span>
                            </div>
                            <div class="fs-sm text-danger">注：开启分销内购，分销商自己购买商品，享受分销自购返现，上级享受一级佣金，上上级享受二级佣金，上上上级享受三级佣金</div>
                            <div class="fs-sm text-danger">需要在“<a href="<?=$urlManager->createUrl(['mch/share/basic'])?>" target="_blank">分销设置=>基础设置</a>”中开启自购返利</div>
                        </div>
                    </div>-->
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">一级名称</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group">
                                <input class="form-control" name="model[first_name]"
                                       value="<?= $list->first_name ? $list->first_name : "一级" ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">一级佣金</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group">
                                <input class="form-control" type="number" name="model[first]" min="0"
                                       value="<?= $list->first ? $list->first : 0 ?>">
                                <span class="input-group-addon percent"><?= $list->price_type == 0 ? '%' : '元' ?></span>
                            </div>
                        </div>
                    </div>
                    <?php if ($list->level > 1) : ?>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label">二级名称</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input class="form-control" name="model[second_name]"
                                           value="<?= $list->second_name ? $list->second_name : "二级" ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class=" col-form-label required">二级佣金</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input class="form-control" type="number" name="model[second]" min="0"
                                           value="<?= $list->second ? $list->second : 0 ?>">
                                    <span
                                        class="input-group-addon percent"><?= $list->price_type == 0 ? '%' : '元' ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if ($list->level > 2) : ?>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">三级名称</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group">
                                        <input class="form-control" name="model[third_name]"
                                               value="<?= $list->third_name ? $list->third_name : "三级" ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label required">三级佣金</label>
                                </div>
                                <div class="col-9">
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="model[third]" min="0"
                                               value="<?= $list->third ? $list->third : 0 ?>">
                                        <span
                                            class="input-group-addon percent"><?= $list->price_type == 0 ? '%' : '元' ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                        </div>
                        <div class="col-9">
                            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>
<script>
    $(document).on('click', '.price_type', function () {
        var price_type = $(this).children('input');
        if ($(price_type).val() == 1) {
            $('.percent').html('元');
        } else {
            $('.percent').html('%');
        }
    });
</script>