<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

/**
 * Created by IntelliJ IDEA.
 * User: wxf
 * Date: 2017/6/19
 * Time: 16:52
 */

$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link"
                   href="<?= $urlManager->createUrl('mch/permission/role/create') ?>">添加角色</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>角色</th>
                <th>描述</th>
                <th>创建者</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item->name ?></td>
                    <td><?= $item->description ?></td>
                    <td>管理员：<?=!Yii::$app->admin->isGuest ? $item->user->username : $item->user->nickname ?></td>
                    <td><?= date('Y-m-d H:i:s', $item->created_at) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/permission/role/edit', 'id' => $item->id]) ?>">编辑</a>

                        <a class="btn btn-sm btn-danger destroy"
                           href="<?= $urlManager->createUrl(['mch/permission/role/destroy', 'id' => $item->id]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <?php if (!empty($pagination)) : ?>
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".destroy", function () {
        var href = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    location.reload();
                                }
                            }
                        })
                    }
                });
            }
        });
        return false;
    });
</script>
