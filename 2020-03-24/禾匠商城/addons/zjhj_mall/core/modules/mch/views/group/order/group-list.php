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
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '拼团详情';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;
$status = Yii::$app->request->get('status');
$is_offline = Yii::$app->request->get('is_offline');
$user_id = Yii::$app->request->get('user_id');
$condition = ['user_id' => $user_id, 'is_offline' => $is_offline, 'clerk_id' => $_GET['clerk_id'], 'shop_id' => $_GET['shop_id']];
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
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

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .table tbody tr td{
        vertical-align: middle;
    }

    .titleColor{
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
<script language="JavaScript" src="<?= $statics ?>/mch/js/LodopFuncs.js"></script>
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <tr>
                <th class="order-tab-1">商品信息</th>
                <th class="order-tab-2">金额</th>
                <th class="order-tab-3">实际付款</th>
                <th class="order-tab-4">订单状态</th>
                <th class="order-tab-5">操作</th>
            </tr>
        </table>
        <?php foreach ($list as $order_item) : ?>
            <div class="order-item">
                <table class="table table-bordered bg-white">
                    <tr>
                        <td colspan="5">
                            <span class="mr-3"><span class="titleColor">下单时间：</span><?= date('Y-m-d H:i:s', $order_item['addtime']) ?></span>
                            <span class="mr-1">
                                <?php if ($order_item['is_pay'] == 1) : ?>
                                    <span class="badge badge-success">已付款</span>
                                <?php else : ?>
                                    <span class="badge badge-default">未付款</span>
                                <?php endif; ?>
                            </span>
                            <?php if ($order_item['is_send'] == 1) : ?>
                                <span  class="mr-1">
                                    <?php if ($order_item['is_confirm'] == 1) : ?>
                                        <span class="badge badge-success">已收货</span>
                                    <?php else : ?>
                                        <span class="badge badge-default">未收货</span>
                                    <?php endif; ?>
                                </span>
                            <?php else : ?>
                                <?php if ($order_item['is_pay'] == 1 && $order_item['is_success'] == 1) : ?>
                                    <span  class="mr-1">
                                        <?php if ($order_item['is_send'] == 1) : ?>
                                            <span class="badge badge-success">已发货</span>
                                        <?php else : ?>
                                            <span class="badge badge-default">未发货</span>
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="mr-5"><span class="titleColor">订单号：</span><?= $order_item['order_no'] ?></span>
                            <span><span class="titleColor">用户：</span><?= $order_item['nickname'] ?></span>
                            <?php if ($order_item['parent_id'] == 0 && $order_item['is_group'] == 1) : ?>
                                <span class="mr-5">
                                <span class="badge badge-danger">团长</span>
                            </span>
                            <?php endif; ?>
                            <?php if ($order_item['order_no'] == 'robot') : ?>
                                <span class="badge badge-danger">机器人</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-tab-1">
                            <div class="goods-item" flex="dir:left box:first">
                                <div class="fs-0">
                                    <div class="goods-pic"
                                         style="background-image: url('<?= $order_item['pic'] ?>')"></div>
                                </div>
                                <div class="goods-info">
                                    <div class="goods-name"><?= $order_item['goods_name'] ?></div>
                                    <span class="fs-sm">
                                        规格：
                                    <span class="text-danger">
                                        <?php $attr_list = json_decode($order_item['attr']); ?>
                                        <?php if (is_array($attr_list)) :
                                            foreach ($attr_list as $attr) : ?>
                                            <span class="mr-3"><?= $attr->attr_group_name ?>
                                                :<?= $attr->attr_name ?></span>
                                            <?php endforeach;
                                            ;
                                        endif; ?>
                                    </span>
                                    </span>
                                    <span class="fs-sm">数量：
                                        <span class="text-danger"><?= $order_item['num'] ?>件</span>
                                    </span>
                                    <div class="fs-sm">小计：
                                        <span class="text-danger"><?= $order_item['total_price'] ?>元</span></div>
                                </div>
                            </div>
                        </td>
                        <td class="order-tab-2">
                            <div class="titleColor">总金额：<span style="color:blue;"><?= $order_item['total_price'] ?></span>
                                元（含运费：<span style="color:green;"><?= $order_item['express_price'] ?></span>
                                元）</div>
                            <?php if ($order_item['colonel'] && $order_item['parent_id'] == 0 && $order_item['is_group'] == 1) : ?>
                                <div class="titleColor">团长优惠：<span style="color:red;"><?= $order_item['colonel'] ?></span>元</div>
                            <?php endif; ?>
                        </td>
                        <td class="order-tab-3">
                            <div class="titleColor"><span style="color:blue;"><?= $order_item['pay_price'] ?></span>元</div>
                        </td>
                        <td class="order-tab-4 titleColor">
                            <div>
                                拼团状态：
                                <?php if ($order_item['status'] == 3) : ?>
                                    <span class="badge badge-success">拼团成功</span>
                                <?php elseif ($order_item['status'] == 4) : ?>
                                    <span class="badge badge-danger">拼团失败</span>
                                    <?php if ($order_item['is_returnd'] == 1) : ?>
                                        <span class="badge badge-pill badge-warning">已退款</span>
                                    <?php endif; ?>
                                <?php elseif ($order_item['status'] == 2) : ?>
                                    <span class="badge badge-default">拼团中</span>
                                <?php elseif ($order_item['status'] == 1) : ?>
                                    <span class="badge badge-default">待付款</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($order_item['is_send'] == 1) : ?>
                                <?php if ($order_item['is_offline'] == 0 || $order_item['express']) : ?>
                                    <div>快递单号：<a href="https://www.baidu.com/s?wd=<?= $order_item['express_no'] ?>"
                                                 target="_blank"><?= $order_item['express_no'] ?></a></div>
                                    <div>快递公司：<span class="badge badge-default"><?= $order_item['express'] ?></span></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="order-tab-5">
                            <?php if ($order_item['is_pay'] == 1 && $order_item['is_confirm'] != 1 && $order_item['is_success'] == 1) : ?>
                                <a class="btn btn-sm btn-primary send-btn" href="javascript:"
                                   data-order-id="<?= $order_item['id'] ?>"><?= ($order_item['is_send'] == 1) ? "修改快递单号" : "发货" ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div>
                                <?php if ($order_item['is_offline'] == 0) : ?>
                                    <span class="mr-3"><span class="titleColor">收货人：</span><?= $order_item['name'] ?></span>
                                    <span class="mr-3"><span class="titleColor">电话：</span><?= $order_item['mobile'] ?></span>
                                    <span><span class="titleColor">地址：</span><?= $order_item['address'] ?></span>
                                <?php else : ?>
                                    <span class="mr-3"><span class="titleColor">联系人：</span><?= $order_item['name'] ?></span>
                                    <span class="mr-3"><span class="titleColor">联系电话：</span><?= $order_item['mobile'] ?></span>
                                <?php endif; ?>
                            </div>
                            <div><span><span class="titleColor">备注：</span><?= $order_item['content'] ?></span></div>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
        <!--隐藏该功能 -->
        <?php if (false && $surplus > 0 && $order_item['status'] != 4 && $order_item['status'] != 3) : ?>
            <div class="order-item">
                <table class="table table-bordered bg-white">
                    <tr>
                        <td colspan="5" style="text-align: right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoboot">
                                添加机器人(<?= $surplus ?>)
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
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
                                <label>收件人邮编</label>
                                <input class="form-control" placeholder="请输入收件人邮编" type="text" autocomplete="off"
                                       name="post_code">
                                <label><a class="print" href="javascript:">打印面单</a></label>
                                <label><a href='http://www.c-lodop.com/download.html' target='_blank'>下载插件</a></label>
                                <div class="text-danger">需要设置面单打印功能</div>
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

<div class="modal fade" id="addRoboot" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">添加机器人</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-3 text-right">
                        <label class=" col-form-label required">机器人</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group short-row">
                            <select class="form-control robot_id" name="robot_id">
                                <?php foreach ($robot as $value) : ?>
                                    <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="pid" name="pid" value="<?= $pid ?>">
                <input type="hidden" class="goods_id" name="goods_id" value="<?= $goods_id ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary add-robot">添加</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".apply-status-btn", function () {
        var url = $(this).attr("href");
        $.myConfirm({
            content: "确认“" + $(this).text() + "”？",
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
        $(".send-modal").modal("show");
    });
    $(document).on("click", ".send-confirm-btn", function () {
        var btn = $(this);
        var error = $(".send-form").find(".form-error");
        btn.btnLoading("正在提交");
        error.hide();
        console.log(error);
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/group/order/send'])?>",
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

    $(document).on("click", '.add-robot', function () {
        console.log(1);
        var btn = $(this);
        btn.btnLoading("正在添加");
        console.log(1);
        var robot_id = $('.robot_id').val();
        var goods_id = $('.goods_id').val();
        var pid = $('.pid').val();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/group/order/add-robot'])?>",
            type: "get",
            data: {robot_id: robot_id, goods_id: goods_id, pid: pid},
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    btn.text(res.msg);
                    location.reload();
                    $(".send-modal").modal("hide");
                    window.location.href = "<?= $urlManager->createUrl(['mch/group/order/group-list', 'pid' => $pid]) ?>"
                }
                if (res.code == 1) {
                    btn.btnReset();
                    error.html(res.msg).show();
                }
            }
        });

    })

</script>
<!--打印函数-->
<script>
    var LODOP; //声明为全局变量
    //检测是否含有插件
    function CheckIsInstall() {
        try {
            var LODOP = getLodop();
            if (LODOP.VERSION) {
                if (LODOP.CVERSION)
                    $.myAlert({
                        content: "当前有C-Lodop云打印可用!\n C-Lodop版本:" + LODOP.CVERSION + "(内含Lodop" + LODOP.VERSION + ")"
                    });
                else
                    $.myAlert({
                        content: "本机已成功安装了Lodop控件！\n 版本号:" + LODOP.VERSION
                    });

            }
        } catch (err) {
        }
    }
    ;
    //打印预览
    function myPreview() {
        LODOP.PRINT_INIT("");
        LODOP.ADD_PRINT_HTM(10, 50, '100%', '100%', $('#print').html());
    }
    $(document).on('click', '.print', function () {
        var id = $(".send-modal input[name=order_id]").val();
        var express = $(".send-modal input[name=express]").val();
        var post_code = $(".send-modal input[name=post_code]").val();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/group/order/print'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                id: id,
                express: express,
                post_code: post_code
            },
            success: function (res) {
                if (res.code == 0) {
                    LODOP.PRINT_INIT("");
                    LODOP.ADD_PRINT_HTM(10, 50, '100%', '100%', res.data.PrintTemplate);
                    LODOP.PREVIEW();
                    $(".send-modal input[name=express_no]").val(res.data.Order.LogisticCode);
                } else {
                    $.myAlert({
                        content: res.msg
                    });
                }
            }
        });
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