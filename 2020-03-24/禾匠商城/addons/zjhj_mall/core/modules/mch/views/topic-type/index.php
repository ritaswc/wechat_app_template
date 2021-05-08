<?php
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '专题分类';
$this->params['active_nav_group'] = 8;
?>
<style>
    .cover-pic {
        display: block;
        width: 8rem;
        height: 5rem;
        background-size: cover;
        background-position: center;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/topic-type/edit']) ?>">添加专题分类</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 20%">
            <col style="width: 40%">
            <col style="width: 20%">
            <col style="width: 20%">
            <tbody>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= $item->name ?></td>
                    <td><?= $item->sort ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                        href="<?= $urlManager->createUrl(['mch/topic-type/edit', 'id' => $item->id]) ?>">编辑</a>
                        
                        <a class="btn btn-sm btn-danger delete-btn"
                        href="<?= $urlManager->createUrl(['mch/topic-type/delete', 'id' => $item->id]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <div class="text-center">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination,]) ?>
            <div class="text-muted"><?= $pagination->totalCount ?>条数据</div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".delete-btn", function () {
        var url = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        location.reload();
                    }
                });
            }
        });
        return false;
    });
</script>