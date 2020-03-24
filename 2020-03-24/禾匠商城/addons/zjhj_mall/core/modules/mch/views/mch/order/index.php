<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 11:01
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

/* @var \app\models\User $user */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '订单管理';
$status = Yii::$app->request->get('status');
$is_recycle = Yii::$app->request->get('is_recycle');
$condition = ['platform' => $_GET['platform']];
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
$urlStr = get_plugin_url();
$urlPlatform = Yii::$app->controller->route;
?>
<style>
    .titleColor {
        color: #888888;
    }

    .order-item {
        border: 1px solid transparent;
        margin-bottom: 1rem;
    }

    .order-item table {
        margin: 0;
    }

    .order-item:hover {
        border: 1px solid #3c8ee5;
    }

    .goods-item {
        /* margin-bottom: .75rem; */
        border: 1px solid #ECEEEF;
        padding: 10px;
        margin-top: -1px;
    }

    .table tbody tr td {
        vertical-align: middle;
    }

    .goods-item:last-child {
        margin-bottom: 0;
    }

    .goods-pic {
        width: 5.5rem;
        height: 5.5rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 1rem;
    }

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .order-tab-1 {
        width: 40%;
    }

    .order-tab-2 {
        width: 20%;
        text-align: center;
    }

    .order-tab-3 {
        width: 10%;
        text-align: center;
    }

    .order-tab-4 {
        width: 20%;
        text-align: center;
    }

    .order-tab-5 {
        width: 10%;
        text-align: center;
    }

    .status-item.active {
        color: inherit;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <?= $this->render('/layouts/order-search/order-search', [
            'urlPlatform' => $urlPlatform,
            'urlStr' => $urlStr
        ]) ?>

    </div>
</div>
<div class="mb-4">
    <ul class="nav nav-tabs status">
        <li class="nav-item">
            <a class="status-item nav-link <?= $status == -1 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']])) ?>">全部</a>
        </li>
        <li class="nav-item">
            <a class="status-item nav-link <?= $status == 0 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 0])) ?>">未付款</a>

        </li>
        <li class="nav-item">
            <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 1])) ?>">待发货</a>
        </li>
        <li class="nav-item">
            <a class="status-item  nav-link <?= $status == 2 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 2])) ?>">待收货</a>
        </li>
        <li class="nav-item">
            <a class="status-item  nav-link <?= $status == 3 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 3])) ?>">已完成</a>
        </li>
        <li class="nav-item">
            <a class="status-item  nav-link <?= $status == 6 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 6])) ?>">待处理</a>
        </li>
        <li class="nav-item">
            <a class="status-item  nav-link <?= $status == 5 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 5])) ?>">已取消</a>
        </li>
        <li class="nav-item">
            <a class="status-item  nav-link <?= $status == 7 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 7])) ?>">异常订单</a>
        </li>
        <li class="nav-item">
            <a class="status-item  nav-link <?= $status == 8 ? 'active' : null ?>"
               href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 8])) ?>">回收站</a>
        </li>
    </ul>
</div>
<table class="table table-bordered bg-white">
    <tr>
        <th class="order-tab-1">商品信息</th>
        <th class="order-tab-2">金额</th>
        <th class="order-tab-3">实际付款</th>
        <th class="order-tab-4">订单状态</th>
        <th class="order-tab-5">操作</th>
    </tr>
</table>
<?php foreach ($list as $k => $order_item) : ?>
    <div class="order-item" style="<?= $order_item['flag'] == 1 ? 'color:#ff4544' : '' ?>">
        <?php if ($order_item['flag'] == 1) : ?>
            <div class="text-danger">注：此订单数据异常，请谨慎发货，及时联系管理员处理</div>
        <?php endif; ?>
        <table class="table table-bordered bg-white">
            <tr>
                <td colspan="5" class="text-center">
                    <span class="mr-4"><span class="titleColor">商户店铺名称：</span><?= $order_item['mch_name'] ?></span>
                    <span class="mr-4"><span
                                class="titleColor">商户联系人：</span><?= $order_item['realname'] ?></span>
                    <span class="mr-4"><span class="titleColor">商户联系电话：</span><?= $order_item['tel'] ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                            <span class="mr-3"><span
                                        class="titleColor">下单时间：</span><?= date('Y-m-d H:i:s', $order_item['addtime']) ?></span>
                    <span class="mr-1">
                                <?php if ($order_item['is_pay'] == 1) : ?>
                                    <span class="badge badge-success">已付款</span>
                                <?php else : ?>
                                    <span class="badge badge-default">未付款</span>
                                <?php endif; ?>
                            </span>
                    <?php if ($order_item['is_send'] == 1) : ?>
                        <span class="mr-1">
                                    <?php if ($order_item['is_confirm'] == 1) : ?>
                                        <span class="badge badge-success">已收货</span>
                                    <?php else : ?>
                                        <span class="badge badge-default">未收货</span>
                                    <?php endif; ?>
                                </span>
                    <?php else : ?>
                        <?php if ($order_item['is_pay'] == 1) : ?>
                            <span class="mr-1">
                                    <?php if ($order_item['is_send'] == 1) : ?>
                                        <span class="badge badge-success">已发货</span>
                                    <?php else : ?>
                                        <span class="badge badge-default">未发货</span>
                                    <?php endif; ?>
                                </span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="mr-5"><span class="titleColor">订单号：</span><?= $order_item['order_no'] ?></span>
                    <span class="mr-5"><span class='titleColor'>
                                用户名(ID)：</span><?= $order_item['nickname'] ?> <span class='titleColor'>(<?= $order_item['user_id'] ?>)</span>
                        <?php if (isset($order_item['platform']) && intval($order_item['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($order_item['platform']) && intval($order_item['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                            </span>
                    <?php if ($order_item['refund']) : ?>
                        <span>
                                    <?php if ($order_item['refund'] == 0) : ?>
                                        <span>待商家处理</span>
                                    <?php elseif ($order_item['refund'] == 1) : ?>
                                        <span>同意并已退款</span>
                                    <?php elseif ($order_item['refund'] == 2) : ?>
                                        <span>已同意换货</span>
                                    <?php elseif ($order_item['refund'] == 3) : ?>
                                        <span>已拒绝退换货</span>
                                    <?php endif; ?>
                                </span>
                    <?php endif; ?>
                    <?php if ($order_item['apply_delete'] == 1) : ?>
                        <span class="titleColor">
                                    申请取消该订单：
                            <?php if ($order_item['is_delete'] == 0) : ?>
                                <span class="badge badge-warning">申请中</span>
                            <?php else : ?>
                                <span class="badge badge-warning">申请成功</span>
                            <?php endif; ?>
                                </span>
                    <?php endif; ?>

                </td>
            </tr>
            <tr>
                <td class="order-tab-1">
                    <?php foreach ($order_item['goods_list'] as $goods_item) : ?>
                        <div class="goods-item" flex="dir:left box:first">
                            <div class="fs-0">
                                <div class="goods-pic"
                                     style="background-image: url('<?= $goods_item['goods_pic'] ?>')"></div>
                            </div>
                            <div class="goods-info">
                                <div class="goods-name"><?= $goods_item['name'] ?></div>
                                <span class="fs-sm">
                                            规格：
                                        <span class="text-danger">
                                            <?php $attr_list = json_decode($goods_item['attr']); ?>
                                            <?php if (is_array($attr_list)) :
                                                foreach ($attr_list as $attr) : ?>
                                                    <span class="mr-3"><?= $attr->attr_group_name ?>
                                                        :<?= $attr->attr_name ?></span>
                                                <?php endforeach;;
                                            endif; ?>
                                        </span>
                                        </span>
                                <span class="fs-sm">数量：
                                            <span
                                                    class="text-danger"><?= $goods_item['num'] . $goods_item['unit'] ?></span>
                                        </span>
                                <div class="fs-sm">小计：
                                    <span class="text-danger"><?= $goods_item['total_price'] ?>元</span></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </td>
                <td class="order-tab-2">
                    <div>
                                <span class="titleColor">总金额：<span
                                            style="color:blue;"><?= $order_item['total_price'] ?></span>元</span>
                        <?php if ($order_item['express_price_1']) : ?>
                            <span class="titleColor">（含运费：<span
                                        style="color:green;"><?= $order_item['express_price_1'] ?></span>元）</span>
                            <div class="text-danger">包邮，运费减免</div>
                        <?php else : ?>
                            <span class="titleColor">（含运费：<span
                                        style="color:green;"><?= $order_item['express_price'] ?></span>元）</span>
                        <?php endif; ?>
                        <?php if ($order_item['user_coupon_id']) : ?>
                            <span class="titleColor">优惠券：<span
                                        style="color:red;"><?= $order_item['coupon_sub_price'] ?></span>元</span>
                        <?php endif; ?>
                        <?php if ($order_item['before_update_price']) : ?>
                            <?php if ($order_item['pay_price'] > $order_item['before_update_price']) : ?>
                                <div class="titleColor">后台加价：<span
                                            style="color:red;"><?= $order_item['pay_price'] - $order_item['before_update_price'] ?></span>元
                                </div>
                            <?php else : ?>
                                <div class="titleColor">后台优惠：<span
                                            style="color:red;"><?= $order_item['before_update_price'] - $order_item['pay_price'] ?></span>元
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($order_item['integral'] && $order_item['integral']['forehead_integral']) : ?>
                            <div class="titleColor">使用<?= $order_item['integral']['forehead_integral'] ?>
                                积分抵扣：<span style="color:red;"><?= $order_item['integral']['forehead'] ?></span>元
                            </div>
                        <?php endif; ?>
                        <?php if ($order_item['discount'] && $order_item['discount'] != 10) : ?>
                            <div class="titleColor">会员折扣：<span
                                        style="color:red;"><?= $order_item['discount'] ?></span>折
                            </div>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="order-tab-3">
                    <div><span style="color:blue;"><?= $order_item['pay_price'] ?></span>元</div>
                </td>
                <td class="order-tab-4">
                    <?php if ($order_item['pay_type'] == 2) : ?>
                        <div>
                            支付方式：
                            <span class="badge badge-success">货到付款</span>
                        </div>
                    <?php elseif ($order_item['pay_type'] == 3) : ?>
                        <div>
                            支付方式：
                            <span class="badge badge-success">余额支付</span>
                        </div>
                    <?php else : ?>
                        <div>
                            支付方式：
                            <span class="badge badge-success">线上支付</span>
                        </div>
                    <?php endif; ?>

                    <div>
                        发货方式：
                        <?php if ($order_item['is_offline'] == 1) : ?>
                            <span class="badge badge-warning">到店自提</span>
                        <?php else : ?>
                            <span class="badge badge-warning">快递发送</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="order-tab-5">
                    <div>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/mch/order/detail', 'order_id' => $order_item['id']]) ?>">详情</a>
                    </div>
                    <?php if ($order_item['is_recycle'] == 1) : ?>
                        <div>
                            <a class="btn btn-sm btn-primary del mt-2" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/mch/order/recycle', 'order_id' => $order_item['id'], 'is_recycle' => 0]) ?>"
                               data-content="是否移出回收站">移出回收站</a>
                        </div>
                        <div>
                            <a class="btn btn-sm btn-danger del mt-2" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/mch/order/delete', 'order_id' => $order_item['id']]) ?>"
                               data-content="是否删除">删除订单</a>
                        </div>
                    <?php else : ?>
                        <div>
                            <a class="btn btn-sm btn-danger del mt-2" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/mch/order/recycle', 'order_id' => $order_item['id'], 'is_recycle' => 1]) ?>"
                               data-content="是否移入回收站">移入回收站</a>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                            <span>
                                <?php if ($order_item['is_offline'] == 0) : ?>
                                    <span class="mr-2"><span
                                                class="titleColor">收货人：</span><?= $order_item['name'] ?></span>
                                    <span class="mr-2"><span
                                                class="titleColor">电话：</span><?= $order_item['mobile'] ?></span>
                                    <span class="mr-3"><span class="titleColor">地址：</span><?= $order_item['address'] ?></span>
                                    <?php if ($order_item['is_send'] == 0) : ?>
                                        <a class="btn btn-sm btn-primary edit-address"
                                           data-index="<?= $k ?>"
                                           data-order-type="store" href="javascript:">修改地址</a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span class="mr-3"><span
                                                class="titleColor">联系人：</span><?= $order_item['name'] ?></span>
                                    <span class="mr-3"><span class="titleColor">联系电话：</span><?= $order_item['mobile'] ?></span>
                                <?php endif; ?>
                            </span>
                    <?php if ($order_item['is_send'] == 1) : ?>
                        <?php if ($order_item['is_offline'] == 0 || $order_item['express']) : ?>
                            <?php if ($order_item['express_no'] != '') : ?>
                                <span class=" badge badge-default"><?= $order_item['express'] ?></span>
                                <span><span class="titleColor">快递单号：</span><a
                                            href="https://www.baidu.com/s?wd=<?= $order_item['express_no'] ?>"
                                            target="_blank"><?= $order_item['express_no'] ?></a></span>
                            <?php endif; ?>
                        <?php elseif ($order_item['is_offline'] == 1) : ?>
                            <div><span class="titleColor">核销员：</span><?= $order_item['clerk_name'] ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div <?= $order_item['remark'] ? '' : 'hidden' ?>>
                        <span class="titleColor">用户备注：</span><?= $order_item['remark'] ?>
                    </div>
                    <?php if ($order_item['shop_id']) : ?>
                        <div>
                            <span class="mr-3">门店名称：<?= $order_item['shop']['name'] ?></span>
                            <span class="mr-3">门店地址：<?= $order_item['shop']['address'] ?></span>
                            <span class="mr-3">电话：<?= $order_item['shop']['mobile'] ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order_item['content']) : ?>
                        <div><span><span class="titleColor">备注：</span><?= $order_item['content'] ?></span></div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>
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
    <div class="text-muted">共<?= $row_count ?>条数据</div>
</div>
</div>
</div>

<?= $this->render('/layouts/ss', [
    'exportList' => $exportList,
    'list' => $list
]) ?>

<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            $.myAlert({
                                content: res.msg,
                                confirm: function (res) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.myAlert({
                                content: res.msg
                            });
                        }
                    },
                    complete: function (res) {
                        $.myLoadingHide();
                    }
                });
            }
        });
        return false;
    });
</script>
<script>
    $(document).on('click', '.is-express', function () {
        if ($(this).val() == 0) {
            $('.is-true-express').prop('hidden', true);
        } else {
            $('.is-true-express').prop('hidden', false);
        }
    });
</script>
