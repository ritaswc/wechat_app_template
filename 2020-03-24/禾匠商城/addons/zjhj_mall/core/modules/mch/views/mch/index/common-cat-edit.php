<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '类目编辑';
$url_manager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body">
        <form class="auto-form" method="post"
              return="<?= Yii::$app->request->referrer ? Yii::$app->request->referrer : $url_manager->createUrl(['mch/mch/index/common-cat']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">类目名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right required">
                    <label class="col-form-label required">排序</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="sort" value="<?= $model->sort ? $model->sort : 1000 ?>">
                    <div>升序，数字越小排序越靠前</div>
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