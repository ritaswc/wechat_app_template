<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '标题';
$this->params['active_nav_group'] = -1;
?>

<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['mch/store/index']) ?>">我的商城</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3">
    <form class="card auto-submit-form" method="post" autocomplete="off">
        <div class="card-header">表单名称</div>
        <div class="card-block">

            <div class="form-group row">
                <div class="col-sm-2 text-right">
                    <label class="col-form-label required">表单项（必填）</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2 text-right">
                    <label class="col-form-label">表单项</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2 text-right">
                    <label class="col-form-label">单选框</label>
                </div>
                <div class="col-sm-6" style="padding-top: calc(.5rem - 1px * 2)">
                    <label class="custom-control custom-radio">
                        <input id="radio1" name="radio" type="radio" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Toggle this custom radio</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input id="radio2" name="radio" type="radio" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Or toggle this other custom radio</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 text-right">
                    <label class="col-form-label">多选框</label>
                </div>
                <div class="col-sm-6" style="padding-top: calc(.5rem - 1px * 2)">
                    <label class="custom-control custom-checkbox">
                        <input id="radio1" name="checkbox" type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Toggle this custom radio</span>
                    </label>
                    <label class="custom-control custom-checkbox">
                        <input id="radio2" name="checkbox" type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Or toggle this other custom radio</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2 text-right">
                    <label class="col-form-label">选择图片（单张）</label>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo \app\widgets\ImageUpload::widget([
                        'name' => 'icon_upload',
                        'value' => 'http://wx1.sinaimg.cn/large/9612d709gy1fi33swtdrqj20m80goglh.jpg'
                    ]);
                    ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 text-right">
                    <label class="col-form-label">选择图片（多张）</label>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo \app\widgets\ImageUpload::widget([
                        'name' => 'cover_pic',
                        'value' => 'http://wx1.sinaimg.cn/large/9612d709gy1fi33swtdrqj20m80goglh.jpg',
                        'multiple' => true,
                    ]);
                    ?>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-6 offset-md-2">
                    <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                    <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                    <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                </div>
            </div>
        </div>

    </form>

</div>
