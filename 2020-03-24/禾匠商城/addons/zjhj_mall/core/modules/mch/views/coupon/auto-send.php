<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 10:18
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '优惠券自动发放';
$this->params['active_nav_group'] = 7;
$events = [
    1 => '页面转发',
    2 => '购买并付款',
];
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/coupon/auto-send-edit']) ?>">添加自动发放方案</a>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>触发事件</th>
                <th>优惠券</th>
                <th>发放次数限制</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= $events[$item->event] ?></td>
                    <td><?= $item->coupon->name ?></td>
                    <td><?= $item->send_times === 0 ? '无限制' : $item->send_times ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/coupon/auto-send-edit', 'id' => $item->id]) ?>">编辑</a>
                        <a class="btn btn-sm btn-danger delete-confirm"
                           href="<?= $urlManager->createUrl(['mch/coupon/auto-send-delete', 'id' => $item->id]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script>
    $(document).on("click", ".delete-confirm", function () {
        var url = $(this).attr("href");
        $.myConfirm({
            content: "确认删除？",
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        if (res.code == 0) {
                            location.reload();
                        } else {
                            $.myLoadingHide();
                            $.myAlert({
                                content: res.msg,
                            });
                        }
                    }
                });
            },
        });
        return false;
    });
</script>