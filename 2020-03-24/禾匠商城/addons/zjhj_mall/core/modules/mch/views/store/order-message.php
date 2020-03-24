<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/24
 * Time: 16:37
 */;
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '消息提醒';
$order_type = [
    '商城订单',
    '秒杀订单',
    '拼团订单',
    '预约订单',
    '商品上架申请'
];
?>


<div class="panel">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <a class="btn btn-sm btn-primary mb-4 del" href="javascript:" data-content="是否标记当前消息为已提醒？"
           data-url="<?= $urlManager->createUrl(['mch/get-data/sound-all']) ?>">标记当前消息为已提醒</a>
        <table class="table table-bordered">
            <tr>
                <th>消息名称</th>
                <th>消息所属</th>
                <th>消息类型</th>
                <th>来自用户</th>
                <th>是否已提醒</th>
                <th>消息时间</th>
                <th>操作</th>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['order_no'] ?></td>
                    <td>
                        <?php if ($value['order_type'] == 4) : ?>
                            <span>商品上架申请</span>
                        <?php else : ?>
                            <?php if ($value['type'] == 0) : ?>
                                <span>下单</span>
                            <?php else : ?>
                                <span>售后订单</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $order_type[$value['order_type']] ?></td>
                    <td>
                        <?= $value['name'] ?>
                        <?php if (isset($value['platform']) && intval($value['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($value['platform']) && intval($value['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $value['is_read'] == 0 ? '未提醒' : '已提醒' ?></td>
                    <td><?= date('Y-m-d H:i:s', $value['addtime']) ?></td>
                    <td>
                        <?php if ($value['order_type'] == 4) : ?>
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/mch/goods/goods', 'keyword' => $value['order_no']]) ?>"
                               target="_blank">查看</a>
                        <?php else : ?>
                            <?php if ($value['type'] == 0) : ?>
                                <?php if ($value['order_type'] == 0) : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/order/index', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php elseif ($value['order_type'] == 1) : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/miaosha/order/index', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php elseif ($value['order_type'] == 2) : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/group/order/index', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php else : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/book/order/index', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php if ($value['order_type'] == 0) : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/order/refund', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php elseif ($value['order_type'] == 1) : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/miaosha/order/refund', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php elseif ($value['order_type'] == 2) : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/group/order/refund', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php else : ?>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= $urlManager->createUrl(['mch/book/order/index', 'keyword_1' => 1, 'keyword' => $value['order_no']]) ?>"
                                       target="_blank">查看</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
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
            <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
        </div>
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
                        localStorage.removeItem('sound_list');
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