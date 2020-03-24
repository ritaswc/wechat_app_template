<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

use yii\widgets\LinkPager;

$this->title = '操作日志';
$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span>日志列表</span>
        <ul class="nav nav-right">
            <li class="nav-item"></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="float-left">
            </div>
        </div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>操作</th>
                <th>操作类型</th>
                <th>数据ID</th>
                <th>操作人</th>
                <th>日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item->title ?></td>
                    <td><?= $item->action_type ?></td>
                    <td><?= $item->obj_id ?></td>
                    <td><?= $item->admin_name ?></td>
                    <td><?= date('Y-m-d H:i:s', $item->addtime) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/log/edit', 'id' => $item->id]) ?>">详情</a>
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
    $(document).on("click", ".del", function () {
        var href = $(this).attr("href");
        $.confirm({
            content: "确认清空日志？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function (res) {
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
