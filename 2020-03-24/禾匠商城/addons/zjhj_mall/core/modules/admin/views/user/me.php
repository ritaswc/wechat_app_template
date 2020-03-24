<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 11:44
 */
/* @var \yii\web\View $this */
/* @var \app\models\Admin[] $list */
$this->title = '我的账户';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$return_url = $url_manager->createUrl(['admin/user/me']);
$this->params['active_nav_link'] = 'admin/user/me';

/** @var \app\models\Admin $model */
$model = Yii::$app->admin->identity;
?>
<nav aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">我的账户</li>
    </ol>
</nav>

<div class="form-group row">
    <label class="col-sm-4 col-form-label text-right">用户名：</label>
    <div class="col-sm-5" style="padding-top: calc(.5rem - 1px * 2);">
        <?= $model->username ?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 col-form-label text-right">手机号：</label>
    <div class="col-sm-5" style="padding-top: calc(.5rem - 1px * 2);">
        <?= $model->mobile ? $model->mobile : '-' ?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 col-form-label text-right">可创建小程序数量：</label>
    <div class="col-sm-5" style="padding-top: calc(.5rem - 1px * 2);">
        <?= $model->app_max_count == 0 ? '无限制' : $model->app_max_count ?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 col-form-label text-right">账户有效期至：</label>
    <div class="col-sm-5" style="padding-top: calc(.5rem - 1px * 2);">
        <?= $model->expire_time == 0 ? '永久' : date('Y-m-d', $model->expire_time) ?>
    </div>
</div>