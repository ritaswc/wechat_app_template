<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:50
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '售后订单';
$this->params['active_nav_group'] = 3;
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
$url = Yii::$app->request->url;
$urlHandle = $urlManager->createUrl(['mch/miaosha/order/refund-handle']);
$urlPlatform = Yii::$app->controller->route;
?>
    <style>
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

        .orderTitle {
            color: #888888;
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
            width: 30%;
        }

        .order-tab-3 {
            width: 20%;
            /* text-align: center; */
        }

        .order-tab-4 {
            width: 10%;
            text-align: center;
        }

        .status-item.active {
            color: inherit;
        }

        .img-view-list {
            margin-left: -.5rem;
            margin-top: -.5rem;
        }

        .img-view {
            width: 4rem;
            height: 4rem;
            display: inline-block;
            background-size: cover;
            background-position: center;
            cursor: pointer;
            opacity: .85;
            margin-top: .5rem;
            margin-left: .5rem;
        }

        .img-view:hover {
            opacity: 1;
        }

        .img-view-box {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
            z-index: 1030;
            visibility: hidden;
            opacity: 0;
        }

        .img-view-box.active {
            visibility: visible;
            opacity: 1;
        }

        .img-view-close {
            position: absolute;
            right: 2rem;
            top: 2rem;
            display: inline-block;
            font-size: 3rem;
            color: #ddd !important;
            cursor: pointer;
            width: 3rem;
            height: 3rem;
            line-height: 3rem;
            text-align: center;
        }

        .img-view-close:hover {
            color: #fff !important;
            text-decoration: none;
        }
    </style>


    <div class="panel mb-3" id="app">
        <div class="panel-header"><?= $this->title ?></div>
        <div class="panel-body">
            <?= $this->render('/layouts/order-search/order-search', [
                'urlPlatform' => $urlPlatform,
                'recycle_is_show' => false
            ]) ?>
        </div>
    </div>
    <div class="mb-4">
        <ul class="nav nav-tabs status">
            <li class="nav-item">
                <a class="status-item nav-link <?= $status == -1 ? 'active' : null ?>"
                   href="<?= $urlManager->createUrl(['mch/miaosha/order/refund']) ?>">全部</a>
            </li>
            <li class="nav-item">
                <a class="status-item nav-link <?= $status == 0 ? 'active' : null ?>"
                   href="<?= $urlManager->createUrl(['mch/miaosha/order/refund', 'status' => 0]) ?>">待处理</a>
            </li>
            <li class="nav-item">
                <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
                   href="<?= $urlManager->createUrl(['mch/miaosha/order/refund', 'status' => 1]) ?>">已处理</a>
            </li>
        </ul>
    </div>
    <table class="table table-bordered bg-white">
        <tr>
            <th class="order-tab-1">商品信息</th>
            <th class="order-tab-2">退款退货|换货</th>
            <th class="order-tab-3">状态</th>
            <th class="order-tab-4">操作</th>
        </tr>
    </table>
<?php foreach ($list as $order_item) : ?>
    <div class="order-item">
        <table class="table table-bordered bg-white">
            <tr>
                <td colspan="5">
                    <span class="mr-5">售后申请时间：<?= date('Y-m-d H:i:s', $order_item['addtime']) ?></span>
                    <span class="mr-5">订单号：<?= $order_item['order_no'] ?></span>
                    <span>
                                用户：<?= $order_item['nickname'] ?>
                        <?php if (isset($order_item['platform']) && intval($order_item['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($order_item['platform']) && intval($order_item['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                            </span>
                </td>
            </tr>
            <tr>
                <td class="order-tab-1">

                    <div class="goods-item" flex="dir:left box:first">
                        <div class="fs-0">
                            <div class="goods-pic"
                                 style="background-image: url('<?= $order_item['goods_pic'] ?>')"></div>
                        </div>
                        <div class="goods-info">
                            <div class="goods-name"><?= $order_item['goods_name'] ?></div>
                            <div class="mt-1">
                                        <span class="fs-sm">
                                        规格：
                                    <span class="text-danger">
                                            <?php $attr_list = json_decode($order_item['attr']); ?>
                                        <?php if (is_array($attr_list)) :
                                            foreach ($attr_list as $attr) : ?>
                                                <span class="mr-3"><?= $attr->attr_group_name ?>
                                                    :<?= $attr->attr_name ?></span>
                                            <?php endforeach;;
                                        endif; ?>
                                        </span>
                                        </span>
                                <span class="fs-sm">数量：
                                        <span class="text-danger"><?= $order_item['num'] ?>件</span>
                                        </span>
                                <div class="fs-sm">金额：
                                    <span class="text-danger"><?= $order_item['total_price'] ?>元</span></div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="order-tab-2">
                    <?php if ($order_item['refund_type'] == 1) : ?>
                        <div class="orderTitle">售后类型：<span class="badge badge-warning">退货退款</span></div>
                        <div class="orderTitle">退款金额：<span
                                    class="text-danger"><?= $order_item['refund_price'] ?>元</span></div>
                        <div><span class="orderTitle">申请理由：</span><?= $order_item['refund_desc'] ?></div>
                        <div class="img-view-list">
                            <?php $pic_list = json_decode($order_item['refund_pic_list']) ?>
                            <?php if (is_array($pic_list)) :
                                foreach ($pic_list as $pic) : ?>
                                    <div class="img-view" data-src="<?= $pic ?>"
                                         style="background-image: url('<?= $pic ?>')"></div>
                                <?php endforeach;;
                            endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($order_item['refund_type'] == 2) : ?>
                        <div class="orderTitle">售后类型：<span class="badge badge-primary">换货</span></div>
                        <div class="orderTitle">退款金额：<span
                                    class="text-danger"><?= $order_item['refund_price'] ?>元</span></div>
                        <div><span class="orderTitle">申请理由：</span><?= $order_item['refund_desc'] ?></div>
                        <div class="img-view-list">
                            <?php $pic_list = json_decode($order_item['refund_pic_list']) ?>
                            <?php if (is_array($pic_list)) :
                                foreach ($pic_list as $pic) : ?>
                                    <div class="img-view" data-src="<?= $pic ?>"
                                         style="background-image: url('<?= $pic ?>')"></div>
                                <?php endforeach;;
                            endif; ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="order-tab-3">
                    <?php if ($order_item['refund_status'] == 0) : ?>
                        <?php if ($order_item['is_agree'] == 1) : ?>
                            <?php if ($order_item['is_user_send'] == 1) : ?>
                                <span class="badge badge-default">用户已发货</span>
                                <div>快递公司：<?= $order_item['user_send_express'] ?></div>
                                <div>快递单号：<a target="_blank"
                                             href="https://www.baidu.com/s?wd=<?= $order_item['user_send_express'] . ' ' . $order_item['user_send_express_no'] ?>"><?= $order_item['user_send_express_no'] ?></a>
                                </div>
                            <?php else : ?>
                                <span class="badge badge-default">待用户发货</span>
                            <?php endif; ?>
                        <?php else : ?>
                            <span class="badge badge-default">待处理</span>
                        <?php endif; ?>
                    <?php elseif ($order_item['refund_status'] == 1) : ?>
                        <span class="badge badge-success">已同意退款退货</span>
                    <?php elseif ($order_item['refund_status'] == 2) : ?>
                        <span class="badge badge-success">已同意换货</span>
                    <?php elseif ($order_item['refund_status'] == 3) : ?>
                        <?php if ($order_item['refund_type'] == 1) : ?>
                            <span class="badge badge-danger">已拒绝退货退款</span>
                        <?php else : ?>
                            <span class="badge badge-danger">已拒换货</span>
                        <?php endif; ?>
                        <div><?= $order_item['refund_refuse_desc'] ?></div>
                    <?php endif; ?>
                    <?php if ($order_item['pay_type'] == 2) : ?>
                        <div class="text-danger">注：货到付款方式的退款需要线下与客户自行协商</div>
                    <?php endif; ?>
                </td>
                <td class="order-tab-4">

                    <?php if ($order_item['refund_status'] == 0) : ?>
                        <?php if ($order_item['refund_type'] == 1) : ?>
                            <?php if ($order_item['is_agree'] == 1) : ?>
                                <?php if ($order_item['is_user_send'] == 1) : ?>
                                    <div class="mb-2">
                                        <a href="javascript:" class="btn btn-sm btn-success agree-btn-3"
                                           data-id="<?= $order_item['order_refund_id'] ?>"
                                           data-price="<?= $order_item['refund_price'] ?>">确认收货</a>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <div class="mb-2">
                                    <a href="javascript:" class="btn btn-sm btn-success agree-btn-1"
                                       data-toggle="modal" data-target="#retreatModal"
                                       onclick="refund_retreat(<?= $order_item['order_refund_id'] ?>,<?= $order_item['refund_price'] ?>)"
                                       data-id="<?= $order_item['order_refund_id'] ?>"
                                       data-price="<?= $order_item['refund_price'] ?>">同意退货</a>
                                </div>
                                <div class="mb-2">
                                    <a href="javascript:" class="btn btn-sm btn-danger disagree-btn-1"
                                       data-id="<?= $order_item['order_refund_id'] ?>">拒绝退货</a>
                                </div>
                            <?php endif; ?>

                        <?php else : ?>
                            <div class="mb-2">
                                <a href="javascript:" class="btn btn-sm btn-success agree-btn-2"
                                   data-toggle="modal" data-target="#changeModal"
                                   onclick="refund_change(<?= $order_item['order_refund_id'] ?>)"
                                   data-id="<?= $order_item['order_refund_id'] ?>">同意换货</a>
                            </div>
                            <div class="mb-2">
                                <a href="javascript:" class="btn btn-sm btn-danger disagree-btn-2"
                                   data-id="<?= $order_item['order_refund_id'] ?>">拒绝换货</a>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($order_item['refund_status'] == 1) : ?>
                    <?php elseif ($order_item['refund_status'] == 2) : ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <span class="mr-3"><span class="orderTitle">收货人：</span><?= $order_item['name'] ?></span>
                    <span class="mr-3"><span class="orderTitle">电话：</span><?= $order_item['mobile'] ?></span>
                    <span><span class="orderTitle">地址：</span><?= $order_item['address'] ?></span>
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


    <div class="img-view-box" flex="cross:center main:center">
        <a class="img-view-close" href="javascript:">×</a>
        <img src="">
    </div>


<?= $this->render('/layouts/ss', [
    'exportList' => $exportList,
    'list' => $list
]) ?>

<?= $this->render('/layouts/order-refund', [
    'orderType' => 'MIAOSHA'
]) ?>