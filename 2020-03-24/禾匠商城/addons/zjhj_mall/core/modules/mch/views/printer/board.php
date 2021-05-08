<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 13:40
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

/* @var \app\models\Coupon[] $list */

$urlManager = Yii::$app->urlManager;
$this->title = '打印机模板库';
$this->params['active_nav_group'] = 13;
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
    <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/printer/board-edit']) ?>">添加新模板</a>
    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <td><span>打印机名称</span></td>
            <td>打印机品牌</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.myAlert({
                                title: res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });
</script>

