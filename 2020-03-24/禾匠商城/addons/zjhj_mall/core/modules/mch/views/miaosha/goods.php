<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '秒杀';
$this->params['active_nav_group'] = 10;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="<?= $urlManager->createUrl(['mch/miaosha/goods']) ?>">按商品查看</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/miaosha/calendar']) ?>">按日历查看</a>
            </li>
        </ul>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/miaosha/goods-edit']) ?>">添加秒杀商品</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>商品ID</th>
                <th>商品</th>
                <th>开放次数</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if (!$list || count($list) == 0) : ?>
                <tr>
                    <td colspan="4" class="text-center p-5">
                        <a href="<?= $urlManager->createUrl(['mch/miaosha/goods-edit']) ?>">添加秒杀商品</a>
                    </td>
                </tr>
            <?php else :
    foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item['goods_id'] ?></td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['mch/miaosha/goods-detail', 'goods_id' => $item['goods_id']]) ?>"><?= $item['name'] ?></a>
                    </td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['mch/miaosha/goods-detail', 'goods_id' => $item['goods_id']]) ?>"><?= $item['miaosha_count'] ?>
                            次</a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-danger delete-btn"
                           href="<?= $urlManager->createUrl(['mch/miaosha/goods-delete', 'goods_id' => $item['goods_id']]) ?>">删除</a>
                    </td>
                </tr>
    <?php endforeach;
            endif; ?>
        </table>
    </div>
</div>

<script>
    $(document).on("click", ".delete-btn", function () {
        var url = $(this).attr("href");
        $.confirm({
            content: "确认删除？删除后该商品的所有秒杀设置将全部删除！",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: url,
                    type: "get",
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
