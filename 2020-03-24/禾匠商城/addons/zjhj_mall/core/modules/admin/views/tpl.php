<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 12:04
 */
/* @var \yii\web\View $this */
$this->title = '编辑信息';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
?>
<table class="table table-bordered bg-white">
    <thead>
    <tr>
        <th>ID</th>
        <th>A</th>
        <th>B</th>
        <th>操作</th>
    </tr>
    </thead>
    <tr>
        <td>1</td>
        <td>A</td>
        <td>B</td>
        <td>OP</td>
    </tr>
</table>
<form method="post" return="" class="auto-submit-form card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-6">
                <input type="text" readonly class="form-control-plaintext form-control-sm" value="email@example.com">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-4">
                <input type="password" class="form-control form-control-sm" placeholder="Password">
            </div>
        </div>
    </div>
</form>