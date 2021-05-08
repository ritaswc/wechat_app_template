<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '退货地址编辑';
$this->params['active_nav_group'] = 4;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="">
            <form method="post" class="form auto-form" autocomplete="off"
                  return="<?= $urlManager->createUrl(['mch/refund-address/index']) ?>">
                <div class="form-body">
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label required">姓名</label>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="name" placeholder="请输入收货人姓名"
                                    value="<?= $model->name ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label required">联系电话</label>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="mobile" placeholder="请输入联系电话"
                                    value="<?= $model->mobile ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                            <label class="col-form-label required">收货地址</label>
                        </div>
                        <div class="col-5">
                            <textarea class="form-control form-control-sm add-attr-input" name="address" rows="5"><?= $model->address?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-2 text-right">
                        </div>
                        <div class="col-5">
                            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                            <input type="button" class="btn btn-default ml-4" 
                                   name="Submit" onclick="javascript:history.back(-1);" value="返回">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
