<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '未命名标题';
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
    <div class="card">
        <div class="card-header"><?= $this->title ? $this->title : '' ?></div>
        <div class="card-block">
            <form class="auto-submit-form" method="post" autocomplete="off">

                <div class="form-group row">
                    <div class="col-2 text-right">
                        <label class="col-form-label">表单项</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control" placeholder="提示">
                        <div class="fs-sm text-muted">表单说明</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-2 text-right">
                        <label class="col-form-label required">表单项</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control" placeholder="提示">
                        <div class="fs-sm text-muted">表单说明</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-2 text-right">
                        <label class="col-form-label">多选框</label>
                    </div>
                    <div class="col-6">
                        <div class="col-form-label">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">选项1</span>
                            </label>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">选项2</span>
                            </label>
                        </div>
                        <div class="fs-sm text-muted">表单说明</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-2 text-right">
                        <label class="col-form-label">单选框</label>
                    </div>
                    <div class="col-6">
                        <div class="col-form-label">
                            <label class="custom-control custom-radio">
                                <input type="radio" name="textradio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">选项1</span>
                            </label>
                            <label class="custom-control custom-radio">
                                <input type="radio" name="textradio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">选项2</span>
                            </label>
                        </div>
                        <div class="fs-sm text-muted">表单说明</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-2 text-right">
                        <label class="col-form-label">单图上传</label>
                    </div>
                    <div class="col-6">

                        <?php
                        echo \app\widgets\ImageUpload::widget([
                            'name' => 'cover_pic',
                            'value' => 'http://wx1.sinaimg.cn/large/9612d709gy1fi33swtdrqj20m80goglh.jpg'
                        ]);
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-2 text-right">
                        <label class="col-form-label">多图上传</label>
                    </div>
                    <div class="col-6">
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
                    <div class="col-6 offset-sm-2">
                        <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                        <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                        <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
