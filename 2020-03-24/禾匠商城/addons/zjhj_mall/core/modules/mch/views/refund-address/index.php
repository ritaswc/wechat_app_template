<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '退货地址列表';
?>

<div class="panel mb-3">
    <div class="panel-header">     
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/refund-address/edit']) ?>">添加退货地址</a>
            </li>
        </ul>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>联系电话</th>
                <th>地址</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 5%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 40%">
            <col style="width: 10%">
            <?php foreach ($list as $u) : ?>
                <tr>
                    <td><?= $u->id ?></td>
                    <td><?= $u->name; ?></td>
                    <td><?= $u->mobile; ?></td>
                    <td><?= $u->address; ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?= $urlManager->createUrl(['mch/refund-address/edit', 'id' => $u->id]) ?>">编辑</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:"
                           data-url="<?= $urlManager->createUrl(['mch/refund-address/del', 'id' => $u->id]) ?>"
                           data-content="是否删除？">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <nav aria-label="Page navigation example">
            <?php echo LinkPager::widget([
                'pagination' => $pagination,
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '尾页',
                'maxButtonCount' => 5,
                'options' => [
                    'class' => 'pagination',
                ],
                'prevPageCssClass' => 'page-item',
                'pageCssClass' => "page-item",
                'nextPageCssClass' => 'page-item',
                'firstPageCssClass' => 'page-item',
                'lastPageCssClass' => 'page-item',
                'linkOptions' => [
                    'class' => 'page-link',
                ],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ])
            ?>
        </nav>
    </div>
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
