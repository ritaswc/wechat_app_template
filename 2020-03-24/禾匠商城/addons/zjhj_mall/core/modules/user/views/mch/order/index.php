<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Cje
 */
defined('YII_ENV') or exit('Access Denied');
$this->title = '订单管理';

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$condition = [];
$is_recycle = Yii::$app->request->get('is_recycle');
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
if ($is_recycle == 1) {
    $status = 12;
}
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

    .table tbody tr td {
        vertical-align: middle;
    }

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .titleColor {
        color: #888888;
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
    <div class="panel-header">
        <span><?= $this->title ?></span>
    </div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div class="mr-3 ml-3">
                            <div class="form-group row">
                                <div>
                                    <label class="col-form-label">下单时间：</label>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input class="form-control" id="date_start" name="date_start"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_start']) ? trim($_GET['date_start']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_start" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                        <span class="middle-center input-group-addon" style="padding:0 4px">至</span>
                                        <input class="form-control" id="date_end" name="date_end"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_end']) ? trim($_GET['date_end']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_end" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="middle-center">
                                    <label class="new-day col-form-label btn btn-primary" data-index="7">近7天</label>
                                    <label class="new-day col-form-label btn btn-primary" data-index="30">近30天</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div flex="dir:left">
                        <div class="mr-4">
                            <div class="form-group row w-30">
                                <div class="col-4">
                                    <select class="form-control" name="keyword_1">
                                        <option value="1" <?= $_GET['keyword_1'] == 1 ? "selected" : "" ?>>订单号</option>
                                        <option value="2" <?= $_GET['keyword_1'] == 2 ? "selected" : "" ?>>用户</option>
                                        <option value="3" <?= $_GET['keyword_1'] == 3 ? "selected" : "" ?>>收货人</option>
                                    </select>
                                </div>
                                <div class="col-8">
                                    <input class="form-control"
                                           name="keyword"
                                           autocomplete="off"
                                           value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                                </div>
                            </div>
                        </div>
                        <div class="mr-4">
                            <div class="form-group">
                                <button class="btn btn-primary mr-4">筛选</button>
                                <a class="btn btn-secondary"
                                   href="<?= Yii::$app->request->url . "&flag=EXPORT" ?>">批量导出</a>
                            </div>
                        </div>
                    </div>
                </form>
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
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 0])) ?>">未付款<?= $store_data['status_count']['status_0'] ? '(' . $store_data['status_count']['status_0'] . ')' : null ?></a>

                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 1])) ?>">待发货<?= $store_data['status_count']['status_1'] ? '(' . $store_data['status_count']['status_1'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 2 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 2])) ?>">待收货<?= $store_data['status_count']['status_2'] ? '(' . $store_data['status_count']['status_2'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 3 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 3])) ?>">已完成<?= $store_data['status_count']['status_3'] ? '(' . $store_data['status_count']['status_3'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 6 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 6])) ?>">待处理<?= $store_data['status_count']['status_6'] ? '(' . $store_data['status_count']['status_6'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 5 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 5])) ?>">已取消<?= $store_data['status_count']['status_5'] ? '(' . $store_data['status_count']['status_5'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 7 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 7])) ?>">异常订单</a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $is_recycle == 1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['is_recycle' => 1])) ?>">回收站</a>
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

        <?php foreach ($list as $index => $order_item) : ?>
            <div class="order-item" style="<?= $order_item['flag'] == 1 ? 'color:#ff4544' : '' ?>">
                <?php if ($order_item['flag'] == 1) : ?>
                    <div class="text-danger">注：此订单数据异常，请谨慎发货，及时联系管理员处理</div>
                <?php endif; ?>

                <table class="table table-bordered bg-white">
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
                                <span class="mr-1">
                                    <?php if ($order_item['is_send'] == 1) : ?>
                                        <span class="badge badge-success">已发货</span>
                                    <?php else : ?>
                                        <span class="badge badge-default">未发货</span>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                            <span class="mr-5"><span class="titleColor">订单号：</span><?= $order_item['order_no'] ?></span>
                            <span>
                                <span class="titleColor">用户：</span>
                                <?= $order_item['nickname'] ?>
                                <?php if (isset($order_item['platform']) && intval($order_item['platform']) === 0): ?>
                                    <span class="badge badge-success">微信</span>
                                <?php elseif (isset($order_item['platform']) && intval($order_item['platform']) === 1): ?>
                                    <span class="badge badge-primary">支付宝</span>
                                <?php else: ?>
                                    <span class="badge badge-default">未知</span>
                                <?php endif; ?>
                            </span>
                            <?php if ($order_item['apply_delete'] == 1) : ?>
                                <span class="mr-1">
                                <span class="titleColor">申请取消该订单：</span>
                                    <?php if ($order_item['is_delete'] == 0) : ?>
                                        <span class="badge badge-warning">申请中</span>
                                    <?php else : ?>
                                        <span class="badge badge-warning">申请成功</span>
                                    <?php endif; ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($order_item['apply_delete'] == 1) : ?>
                                <?php if ($order_item['is_delete'] == 0) : ?>
                                    <span class="ml-2">
                                        <a class="btn btn-sm btn-info apply-status-btn"
                                           href="<?= $urlManager->createUrl(['user/mch/order/apply-delete-status', 'id' => $order_item['id'], 'status' => 1]) ?>">同意请求</a>
                                    </span>
                                    <span class="ml-2">
                                        <a class="btn btn-sm refuse btn-danger apply-status-btn"
                                           href="<?= $urlManager->createUrl(['user/mch/order/apply-delete-status', 'id' => $order_item['id'], 'status' => 0]) ?>">拒绝请求</a>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($order_item['refund']) : ?>
                                <div class="titleColor mr-1">售后状态：
                                    <?php if ($order_item['refund'] == 0) : ?>
                                        <span>待商家处理</span>
                                    <?php elseif ($order_item['refund'] == 1) : ?>
                                        <span>同意并已退款</span>
                                    <?php elseif ($order_item['refund'] == 2) : ?>
                                        <span>已同意换货</span>
                                    <?php elseif ($order_item['refund'] == 3) : ?>
                                        <span>已拒绝退换货</span>
                                    <?php endif; ?>
                                </div>
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
                                            <span class="text-danger"><?= $goods_item['total_price'] ?>元</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </td>
                        <td class="order-tab-2">
                            <div class='titleColor'>总金额：<span
                                        style="color:blue;"><?= $order_item['total_price'] ?></span>元
                                <?php if ($order_item['express_price_1']) : ?>
                                    (含运费：<span
                                            style="color:green;"><?= $order_item['express_price_1'] ?></span>元)</span>
                                    <span class="text-danger">(包邮，运费减免)</span>
                                <?php else : ?>
                                    (运费：<span style="color:green;"><?= $order_item['express_price'] ?></span>元)</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($order_item['user_coupon_id']) : ?>
                                <div class='titleColor'>优惠券：
                                    <span style="color:red;"><?= $order_item['coupon_sub_price'] ?></span>元
                                </div>
                            <?php endif; ?>
                            <?php if ($order_item['before_update_price']) : ?>
                                <?php if ($order_item['pay_price'] > $order_item['before_update_price']) : ?>
                                    <div class='titleColor'>后台加价：
                                        <span style="color:red;"><?= $order_item['pay_price'] - $order_item['before_update_price'] ?></span>元
                                    </div>
                                <?php else : ?>
                                    <div class='titleColor'>后台优惠：
                                        <span style="color:red;"><?= $order_item['before_update_price'] - $order_item['pay_price'] ?></span>元
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($order_item['integral'] && $order_item['integral']['forehead_integral']) : ?>
                                <span class='titleColor'>使用<?= $order_item['integral']['forehead_integral'] ?>积分抵扣：
                                    <span style="color:red;"><?= $order_item['integral']['forehead'] ?></span>元
                                </span>
                            <?php endif; ?>
                            <?php if ($order_item['discount'] && $order_item['discount'] != 10) : ?>
                                <div class='titleColor'>会员折扣：<span
                                            style="color:red;"><?= $order_item['discount'] ?></span>折
                                </div>
                            <?php endif; ?>
                            <?php if ($order_item['is_pay'] == 0 && $order_item['is_cancel'] == 0 && $order_item['is_send'] == 0) : ?>
                                <div>
                                    <a class="btn btn-sm btn-primary update mb-2" href="javascript:" data-toggle="modal"
                                       data-target="#price" data-id="<?= $order_item['id'] ?>">价格修改</a>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="order-tab-3">
                            <div class='titleColor'><span style="color:blue;"><?= $order_item['pay_price'] ?></span>元
                            </div>
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
                            <?php if (($order_item['is_pay'] == 1 || $order_item['pay_type'] == 2) && $order_item['is_confirm'] != 1) : ?>
                            <div>
                                <a class="btn btn-sm btn-primary send-btn mb-2" href="javascript:"
                                   data-order-id="<?= $order_item['id'] ?>" data-express='<?= $order_item['express'] ?>'
                                   data-express-no='<?= $order_item['express_no'] ?>'><?= ($order_item['is_send'] == 1) ? "修改快递单号" : "发货" ?></a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-primary mb-2" target="_blank"
                                   href="<?= $urlManager->createUrl(['user/mch/order/detail', 'order_id' => $order_item['id']]) ?>">详情</a>
                            </div>
                            <?php if ($order_item['pay_type'] == 2 && $order_item['is_send'] == 1 && $order_item['is_confirm'] != 1) : ?>
                                <div>
                                    <a href="javascript:" class="btn btn-sm btn-primary del mb-2"
                                       data-url="<?= $urlManager->createUrl(['user/mch/order/confirm', 'order_id' => $order_item['id']]) ?>"
                                       data-content="是否确认收货？">确认收货</a>
                                </div>
                            <?php endif; ?>
                            <div>
                                <?php if ($_GET['is_recycle'] == 1) : ?>
                                    <a class="btn btn-sm btn-primary del mb-2" href="javascript:"
                                       data-url="<?= $urlManager->createUrl(['user/mch/order/edit', 'order_id' => $order_item['id'], 'is_recycle' => 0]) ?>"
                                       data-content="是否移出回收站">移出回收站</a>
                                <?php else : ?>
                                    <a class="btn btn-sm btn-danger del mb-2" href="javascript:"
                                       data-url="<?= $urlManager->createUrl(['user/mch/order/edit', 'order_id' => $order_item['id'], 'is_recycle' => 1]) ?>"
                                       data-content="是否移入回收站">移入回收站</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div>
                                <span class="mr-3"><span class="titleColor">收货人：</span><?= $order_item['name'] ?></span>
                                <span class="mr-3"><span
                                            class="titleColor">电话：</span><?= $order_item['mobile'] ?></span>
                                <?php if ($order_item['is_offline'] == 0) : ?>
                                    <span><span class="titleColor">地址：</span><?= $order_item['address'] ?></span>
                                <?php endif; ?>
                                <?php if ($order_item['is_send'] == 1) : ?>
                                    <?php if ($order_item['is_offline'] == 0 || $order_item['express']) : ?>
                                        <span>
                                    <span class=" badge badge-default"><?= $order_item['express'] ?></span>
                                    <span class="titleColor">快递单号：</span><a
                                                    href="https://www.baidu.com/s?wd=<?= $order_item['express_no'] ?>"
                                                    target="_blank"><?= $order_item['express_no'] ?></a></span>
                                    <?php elseif ($order_item['is_offline'] == 1) : ?>
                                        <span><span class="titleColor">核销员：</span><?= $order_item['clerk_name'] ?></span>
                                    <?php endif; ?>
                                    <?php if ($order_item['is_send'] == 0) : ?>
                                        <a class="btn btn-sm btn-primary edit-address"
                                           data-index="<?= $index ?>"
                                           data-order-type="store" href="javascript:">修改地址</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div <?= $order_item['remark'] ? '' : 'hidden' ?>>
                                <span class="titleColor">用户备注：</span><?= $order_item['remark'] ?>
                            </div>
                            <?php if ($order_item['shop_id']) : ?>
                                <div>
                                    <span class="mr-3"><span
                                                class="titleColor">门店名称：</span><?= $order_item['shop']['name'] ?></span>
                                    <span class="mr-3"><span
                                                class="titleColor">门店地址：</span><?= $order_item['shop']['address'] ?></span>
                                    <span class="mr-3"><span
                                                class="titleColor">电话：</span><?= $order_item['shop']['mobile'] ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($order_item['content']) : ?>
                                <span><span class="titleColor">备注：</span><?= $order_item['content'] ?></span>
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
        <!-- 发货 -->
        <div class="modal fade send-modal" data-backdrop="static">
            <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
                <div class="modal-content">
                    <div class="modal-header">
                        <b class="modal-title">物流信息</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="send-form" method="post">
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">物流选择</label>
                                </div>
                                <div class="col-9">
                                    <div class="pt-1">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1" value="1" checked
                                                   name="is_express" type="radio"
                                                   class="custom-control-input is-express">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">快递</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" value="0" name="is_express" type="radio"
                                                   class="custom-control-input is-express">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">无需物流</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="is-true-express">
                                <input class="form-control" type="hidden" autocomplete="off" name="order_id">
                                <label>快递公司</label>
                                <div class="input-group mb-3">
                                    <input class="form-control" placeholder="请输入快递公司" type="text" autocomplete="off"
                                           name="express">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             style="max-height: 250px;overflow: auto">
                                            <?php if (count($express_list['private'])) : ?>
                                                <?php foreach ($express_list['private'] as $item) : ?>
                                                    <a class="dropdown-item" href="javascript:"><?= $item ?></a>
                                                <?php endforeach; ?>
                                                <div class="dropdown-divider"></div>
                                            <?php endif; ?>
                                            <?php foreach ($express_list['public'] as $item) : ?>
                                                <a class="dropdown-item" href="javascript:"><?= $item ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <label>快递单号</label>
                                <input class="form-control" placeholder="请输入快递单号" type="text" autocomplete="off"
                                       name="express_no">
                                <div class="text-danger mt-3 form-error" style="display: none"></div>
                            </div>
                            <div class="mt-2">
                                <label>商家留言（选填）</label>
                                <textarea class="form-control" name="words"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary send-confirm-btn">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 修改价格 -->
<div class="modal fade" data-backdrop="static" id="price">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <b class="modal-title">价格修改</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="order-id" type="hidden">
                <div class="form-group row">
                    <label class="col-4 text-right col-form-label">商品价格修改：</label>
                    <div class="col-8">
                        <input class=" form-control money" type="number" placeholder="请填写增加或减少的价格">
                        <div class="fs-sm">注：商品价格修改，改的是订单中所有商品的总价格</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 text-right col-form-label">运费修改：</label>
                    <div class="col-8">
                        <input class=" form-control update-express" type="number" placeholder="请填写增加或减少的价格">
                        <div class="fs-sm">注：运费修改，改的是订单中运费的价格</div>
                    </div>
                </div>
                <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
            </div>
            <div class="modal-footer">
                <a href="javascript:" class="btn btn-primary add-price" data-type="1">加价</a>
                <a href="javascript:" class="btn btn-primary add-price" data-type="2">优惠</a>
            </div>
        </div>
    </div>
</div>
<?= $this->render('/layouts/ss', [
    'list' => $list
]) ?>

<script>
    console.log(window.location.href)
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

    $(document).on("click", ".refuse", ".apply-status-btn", function () {
        var url = $(this).attr("href");
        $.myConfirm({
            content: "确认拒绝取消该订单？",
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        $.myLoadingHide();
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0)
                                    location.reload();
                            }
                        });
                    }
                });
            }
        });
        return false;
    });

    $(document).on("click", ".btn-info", ".apply-status-btn", function () {
        var url = $(this).attr("href");
        $.myConfirm({
            content: "确认同意取消该订单并退款",
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        $.myLoadingHide();
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0)
                                    location.reload();
                            }
                        });
                    }
                });
            }
        });
        return false;
    });

    $(document).on("click", ".send-btn", function () {
        var order_id = $(this).attr("data-order-id");
        $(".send-modal input[name=order_id]").val(order_id);
        var express_no = $(this).attr("data-express-no");
        $(".send-modal input[name=express_no]").val(express_no);
        var express = $(this).attr("data-express");
        $(".send-modal input[name=express]").val(express);
        $(".send-modal").modal("show");
    });
    $(document).on("click", ".send-confirm-btn", function () {
        var btn = $(this);
        var error = $(".send-form").find(".form-error");
        btn.btnLoading("正在提交");
        error.hide();
        console.log(error);
        $.ajax({
            url: "<?=$urlManager->createUrl(['user/mch/order/send'])?>",
            type: "post",
            data: $(".send-form").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    btn.text(res.msg);
                    location.reload();
                    $(".send-modal").modal("hide");
                }
                if (res.code == 1) {
                    btn.btnReset();
                    error.html(res.msg).show();
                }
            }
        });
    });


</script>

<script>
    $(document).on('click', '.update', function () {
        var order_id = $(this).data('id');
        $('.order-id').val(order_id);
    });
    $(document).on('click', '.add-price', function () {
        var btn = $(this);
        var order_id = $('.order-id').val();
        var price = $('.money').val();
        var update_express = $('.update-express').val();
        var type = btn.data('type');
        var error = $('.form-error');
        btn.btnLoading(btn.text());
        error.hide();
        $.ajax({
            url: "<?=$urlManager->createUrl(['user/mch/order/add-price'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                order_id: order_id,
                price: price,
                type: type,
                update_express: update_express
            },
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    error.html(res.msg).show()
                }
            },
            complete: function (res) {
                btn.btnReset();
            }
        });
    });
    $(document).on('click', '.is-express', function () {
        if ($(this).val() == 0) {
            $('.is-true-express').prop('hidden', true);
        } else {
            $('.is-true-express').prop('hidden', false);
        }
    });
</script>
