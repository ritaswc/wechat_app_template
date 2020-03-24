<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/8
 * Time: 17:44
 */
$this->title = '商户设置';
$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/mch/index/index']) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label">是否开启分销</label>
                    </div>
                    <div class="col-9 col-form-label">
                        <label class="radio-label">
                            <input <?= $setting->is_share == 0 ? 'checked' : null ?>
                                value="0" name="model[is_share]" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input <?= $setting->is_share == 1 ? 'checked' : null ?>
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
