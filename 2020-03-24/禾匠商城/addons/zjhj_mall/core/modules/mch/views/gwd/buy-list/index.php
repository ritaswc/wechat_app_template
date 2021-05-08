<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:50
 */

use yii\widgets\LinkPager;

/* @var \app\models\User $user */

$urlManager = Yii::$app->urlManager;
$urlStr = get_plugin_url();
$this->title = '好物圈列表';
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
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="mb-3 clearfix" style="padding: 15px 0;">
        <div class="float-left">
            <div class="dropdown float-right ml-2">
                <button class="btn btn-secondary dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php if ($_GET['orderType'] === '2') :
                        ?>拼团订单
                    <?php elseif ($_GET['orderType'] === '1') :
                        ?>秒杀订单
                    <?php elseif ($_GET['orderType'] === '0') :
                        ?>商城订单
                    <?php elseif ($_GET['orderType'] == '') :
                        ?>全部订单
                    <?php else : ?>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu" style="min-width:8rem">
                    <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlStr . '/index']) ?>">全部订单</a>
                    <a class="dropdown-item"
                       href="<?= $urlManager->createUrl([$urlStr . '/index', 'orderType' => 0]) ?>">商城订单</a>
                    <a class="dropdown-item"
                       href="<?= $urlManager->createUrl([$urlStr . '/index', 'orderType' => 1]) ?>">秒杀订单</a>
                    <a class="dropdown-item"
                       href="<?= $urlManager->createUrl([$urlStr . '/index', 'orderType' => 2]) ?>">拼团订单</a>
                </div>
            </div>
        </div>
        <div class="float-right">
            <a href="<?= $urlManager->createUrl([$urlStr . '/edit']) ?>" class="btn btn-primary"><i
                        class="iconfont icon-playlistadd"></i>导入好物圈</a>
        </div>
    </div>
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
<?php foreach ($list as $k => $item) : ?>
    <div class="order-item">
        <table class="table table-bordered bg-white">
            <tr>
                <td colspan="5">
                            <span class="mr-3"><span
                                        class='titleColor'>下单时间：</span><?= date('Y-m-d H:i:s', $item['order']['addtime']) ?></span>
                    <?php if ($item['order']['seller_comments']) : ?>
                        <span class="badge badge-danger ellipsis mr-1" data-toggle="tooltip"
                              data-placement="top"
                              title="<?= $item['order']['seller_comments'] ?>">有备注</span>
                    <?php endif; ?>
                    <sapn class="mr-1">
                        <?php if ($item['order']['is_pay'] == 1) : ?>
                            <span class="badge badge-success">
                                    已付款</span>
                        <?php else : ?>
                            <span class="badge badge-default">
                                    未付款</span>
                        <?php endif; ?>
                    </sapn>
                    <?php if ($item['order']['is_send'] == 1) : ?>
                        <span class="mr-1">
                                    <?php if ($item['order']['is_confirm'] == 1) : ?>
                                        <span class="badge badge-success">已收货</span>
                                    <?php else : ?>
                                        <span class="badge badge-default">未收货</span>
                                    <?php endif; ?>
                                </span>
                    <?php else : ?>
                        <?php if ($item['order']['is_pay'] == 1) : ?>
                            <span class="mr-1">
                                    <?php if ($item['order']['is_send'] == 1) : ?>
                                        <span class="badge badge-success">已发货</span>
                                    <?php else : ?>
                                        <span class="badge badge-default">未发货</span>
                                    <?php endif; ?>
                                </span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="mr-5"><span class='titleColor'>订单号：</span><?= $item['order']['order_no'] ?></span>
                    <span class="mr-5"><span class='titleColor'>
                                用户名(ID)：</span><?= $item['user']['nickname'] ?> <span
                                class='titleColor'>(<?= $item['user']['id'] ?>)</span>
                        </span>
                </td>
            </tr>
            <tr>
                <td class="order-tab-1">
                    <?php foreach ($item['order']['orderDetail'] as $goods_item) : ?>
                        <div class="goods-item" flex="dir:left box:first">
                            <div class="fs-0">
                                <div class="goods-pic"
                                     style="background-image: url('<?= $goods_item['pic'] ?>')"></div>
                            </div>
                            <div class="goods-info">
                                <div class="goods-name"><?= $goods_item['name'] ?></div>
                                <div class="mt-1">
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
                                            <span class="text-danger"><?= $goods_item['num'] . $goods_item['unit'] ?></span>
                                        </span>
                                </div>
                                <div>
                                        <span class="fs-sm">小计：
                                            <span class="text-danger mr-4"><?= $goods_item['total_price'] ?>元</span>
                                        </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </td>
                <td class="order-tab-2">
                    <div class='titleColor'>总金额：<span
                                style="color:blue;"><?= $item['order']['total_price'] ?></span>元
                        <?php if ($item['order']['express_price_1']) : ?>
                            (含运费：<span style="color:green;"><?= $item['order']['express_price_1'] ?></span>元)</span>
                            <span class="text-danger">(包邮，运费减免)</span>
                        <?php else : ?>
                            (运费：<span style="color:green;"><?= $item['order']['express_price'] ?></span>元)</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="order-tab-3">
                    <div><span style="color:blue;"><?= $item['order']['pay_price'] ?></span>元</div>
                </td>
                <td class="order-tab-4">
                    <div>
                        订单来源：
                        <?php if ($item['order']['type'] == 0) : ?>
                            <span class="badge badge-primary mt-1">商城订单</span>
                        <?php elseif ($item['order']['type'] == 1) : ?>
                            <span class="badge badge-primary mt-1">秒杀订单</span>
                        <?php elseif ($item['order']['type'] == 2) : ?>
                            <span class="badge badge-primary mt-1">拼团订单</span>
                        <?php else: ?>
                            <span class="badge badge-primary mt-1">未知订单</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($item['order']['pay_type'] == 2) : ?>
                        <div>
                            支付方式：
                            <span class="badge badge-success">货到付款</span>
                        </div>
                    <?php elseif ($item['order']['pay_type'] == 3) : ?>
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
                        <?php if ($item['order']['is_offline'] == 1) : ?>
                            <span class="badge badge-warning mt-1">到店自提</span>
                        <?php else : ?>
                            <span class="badge badge-warning mt-1">快递发送</span>
                        <?php endif; ?>
                    </div>
                </td>

                <td class="order-tab-5">
                    <a class="btn btn-sm btn-danger destroy"
                       href="<?= $urlManager->createUrl([$urlStr . '/destroy', 'id' => $item['order_id'], 'orderType' => $item['type']]) ?>">从好物圈删除</a>
                    <a class="btn btn-sm btn-primary update-status"
                       href="<?= $urlManager->createUrl([$urlStr . '/update-order-status', 'id' => $item['order_id'], 'orderType' => $item['type']]) ?>">更新订单状态</a>
                </td>

            </tr>
            <tr>
                <td colspan="5">
                    <div>
                        <?php if ($item['is_offline'] == 0) : ?>
                            <span class="mr-2"><span
                                        class="titleColor">收货人：</span><?= $item['order']['name'] ?></span>
                            <span class="mr-2"><span
                                        class="titleColor">电话：</span><?= $item['order']['mobile'] ?></span>
                            <span class="mr-3"><span
                                        class="titleColor">地址：</span><?= $item['order']['address'] ?></span>
                        <?php endif; ?>
                        <?php if ($item['order']['is_send'] == 1) : ?>
                            <?php if ($item['order']['is_offline'] == 0 || $item['order']['express']) : ?>
                                <?php if ($item['order']['express_no'] != '') : ?>
                                    <span class=" badge badge-default"><?= $item['order']['express'] ?></span>
                                    <span class="mr-3"><span class="titleColor">快递单号：</span><a
                                                href="https://www.baidu.com/s?wd=<?= $item['order']['express_no'] ?>"
                                                target="_blank"><?= $item['order']['express_no'] ?></a></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
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
    <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
</div>

<script>
    $(document).on('click', '.destroy', function () {
        if (confirm("是否删除？")) {
            var btn = $(this);
            btn.btnLoading(btn.text())
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    btn.btnReset();
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });
    $(document).on('click', '.update-status', function () {
        if (confirm("是否更新订单状态？")) {
            var btn = $(this);
            btn.btnLoading(btn.text())
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    btn.btnReset();
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });
</script>