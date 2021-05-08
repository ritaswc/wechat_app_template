<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '专题分类编辑';
$this->params['active_nav_group'] = 8;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" return="<?= $urlManager->createUrl(['mch/topic-type/index']) ?>">
            <div class="form-group row" style="<?php if (!$model->id) {
                echo 'display:none';
                                               } else {
    echo 'display:display';
} ?>"" >
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">ID</label>
                </div>
                <div class="col-sm-6">
                   <div class="col-form-label required"><?= $model->id ?></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">排序</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="sort" value="<?= $model->sort ?>">
                    <div class="text-muted fs-sm">升序，数字越小排序越靠前，默认1000</div>
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
<script>
</script>