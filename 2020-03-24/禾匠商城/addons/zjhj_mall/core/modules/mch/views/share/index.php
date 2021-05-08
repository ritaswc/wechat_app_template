<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:57
 */
/* @var $pagination yii\data\Pagination */

/* @var $setting \app\models\Setting */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '分销商列表';
$this->params['active_nav_group'] = 5;
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
$urlPlatform = Yii::$app->controller->route;
?>

<style>
    .table tbody tr td {
        vertical-align: middle;
    }

    .msg {
        height: 59.25px;
        line-height: 59.25px;
    }

    .badge {
        font-size: 100%;
    }

    .cell {
        padding-left: 10.5px;
    }

    hr {
        margin: 5.25px 0;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div class="dropdown float-right mr-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($_GET['platform'] === '1') :
                                    ?>支付宝
                                <?php elseif ($_GET['platform'] === '0') :
                                    ?>微信
                                <?php elseif ($_GET['platform'] == '') :
                                    ?>全部分销商
                                <?php else : ?>
                                <?php endif; ?>
                            </button>
                            <div class="dropdown-menu" style="min-width:8rem">
                                <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlPlatform]) ?>">全部分销商</a>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 1]) ?>">支付宝</a>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 0]) ?>">微信</a>
                            </div>
                        </div>
                        <div>
                            <div class="input-group">
                                <input class="form-control"
                                       placeholder="姓名/昵称"
                                       name="keyword"
                                       autocomplete="off"
                                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                                <span class="input-group-btn">
                                        <button class="btn btn-primary">搜索</button>
                                    </span>
                                <span style="padding-left:20px" class="input-group-btn">
                                        <a class="btn btn-primary batch" href="javascript:void(0)"
                                           data-url="<?= $urlManager->createUrl(['mch/share/batch']) ?>"
                                           data-content="是否批量通过" data-type="0">批量通过</a>
                                    </span>
                                <span style="padding-left: 20px;">
                                        <a class="btn btn-secondary export-btn"
                                           href="javascript:">批量导出</a>
                                    </span>
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
                       href="<?= $urlManager->createUrl(['mch/share/index']) ?>">全部</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 0 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(['mch/share/index', 'status' => 0]) ?>">未审核<?= $count['count_1'] ? '(' . $count['count_1'] . ')' : "(0)" ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(['mch/share/index', 'status' => 1]) ?>">已审核<?= $count['count_2'] ? '(' . $count['count_2'] . ')' : "(0)" ?></a>
                </li>
            </ul>
        </div>
        <table class="table table-bordered bg-white">
            <tr>
                <td style="text-align: center;">
                    <label class="checkbox-label" style="margin-right: 0;">
                        <input type="checkbox" class="goods-all">
                        <span class="label-icon"></span>
                    </label>
                <td><span class="label-text">ID</span></td>
                </td>
                <td width="200px">基本信息</td>
                <td>
                    <div>姓名</div>
                    <div>手机号</div>
                </td>
                <td style="padding:5.25px 0;">
                    <div class="cell">累计佣金</div>
                    <hr>
                    <div class="cell">可提现佣金</div>
                </td>
                <td>推荐人</td>
                <td>下级用户</td>
                <td>状态</td>
                <td>时间</td>
                <td>会员订单</td>
                <td>备注信息</td>
                <td>操作</td>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td class="nowrap" style="text-align: center;">
                        <label class="checkbox-label" style="margin-right: 0;">
                            <input data-user_id="<?= $value['user_id'] ?>" type="checkbox"
                                   class="goods-one"
                                   value="<?= $value['id'] ?>">
                            <span class="label-icon"></span>
                        </label>
                    </td>
                    <td data-toggle="tooltip"
                        data-placement="top" title="<?= $value['user_id'] ?>"><span
                                class="label-text"><?= $value['user_id'] ?></span></td>
                    <td data-toggle="tooltip" data-placement="top" class="text-center"
                        title="<?= $value['nickname'] ?>">
                        <div>
                            <img src="<?= $value['avatar_url'] ?>"
                                 style="width: 30px;height: 30px;margin-right: 10px;margin-bottom: 10px;">
                        </div>
                        <div style="width: 180px;display:block;white-space:nowrap; overflow:hidden; text-overflow:ellipsis;margin-bottom: 10px;">
                            <?= $value['nickname'] ?>
                        </div>
                        <div>
                            <?php if (isset($value['platform']) && intval($value['platform']) === 0): ?>
                                <span class="badge badge-success">微信</span>
                            <?php elseif (isset($value['platform']) && intval($value['platform']) === 1): ?>
                                <span class="badge badge-primary">支付宝</span>
                            <?php else: ?>
                                <span class="badge badge-default">未知</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="padding:5.25px 0;height: 130px;">
                        <div class="cell msg"><?= $value['name'] ?></div>
                        <hr>
                        <div class="cell msg"><?= $value['mobile'] ?></div>
                    </td>
                    <td style="padding:5.25px 0;height: 130px;">
                        <div class="cell msg"><?= $value['total_price'] ?></div>
                        <hr>
                        <div class="cell msg"><?= $value['price'] ?></div>
                    </td>
                    <td><?= $value['parent_nickname'] ? $value['parent_nickname'] : '总店' ?></td>
                    <td>
                        <?php if ($value['status'] == 1) : ?>
                            <?php if ($setting->level == 0) : ?>
                                <span>0</span>
                            <?php else : ?>
                                <?php if ($setting->level >= 1) : ?>
                                    <div><a class="team" data-index="<?= $value['user_id'] ?>" data-level="1"
                                            data-name="<?= $value['nickname'] ?>"
                                            href="javascript:" data-toggle="modal"
                                            data-target="#exampleModal"><?= $setting->first_name ? $setting->first_name : "一级" ?>
                                            ：<?= $value['first'] ?></a></div>
                                <?php endif; ?>
                                <?php if ($setting->level >= 2) : ?>
                                    <div><a class="team" data-index="<?= $value['user_id'] ?>" data-level="2"
                                            data-name="<?= $value['nickname'] ?>"
                                            href="javascript:" data-toggle="modal"
                                            data-target="#exampleModal"><?= $setting->second_name ? $setting->second_name : "二级" ?>
                                            ：<?= $value['second'] ?></a></div>
                                <?php endif; ?>
                                <?php if ($setting->level == 3) : ?>
                                    <div><a class="team" data-index="<?= $value['user_id'] ?>" data-level="3"
                                            data-name="<?= $value['nickname'] ?>"
                                            href="javascript:" data-toggle="modal"
                                            data-target="#exampleModal"><?= $setting->third_name ? $setting->third_name : "三级" ?>
                                            ：<?= $value['third'] ?></a></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($value['status'] == 0) : ?>
                            <span class="badge badge-warning">审核中</span>
                        <?php else: ?>
                            <?php if ($value['status'] == 1) : ?>
                                <span class="badge badge-success">通过</span>
                            <?php else: ?>
                                <span class="badge badge-danger">未通过</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    <td>
                        <div class="fs-sm">申请时间：<?= date('Y-m-d H:i', $value['addtime']); ?></div>
                        <div class="fs-sm">
                            审核时间：<?= ($value['time'] != 0) ? date('Y-m-d H:i', $value['time']) : ""; ?></div>
                    </td>
                    <td style="padding:5.25px 0;">
                        <div class="cell">
                            商城订单：
                            <?php if ($value['order_count'] && $value['order_count'] > 0) : ?>
                                <a target="_blank"
                                   href="<?= $urlManager->createUrl(['mch/order/index', 'user_id' => $value['user_id']]) ?>"><?= $value['order_count'] ?></a>
                            <?php else : ?>
                                <span>0</span>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="cell">
                            秒杀订单：
                            <?php if ($value['ms_order_count'] && $value['ms_order_count'] > 0) : ?>
                                <a target="_blank"
                                   href="<?= $urlManager->createUrl(['mch/miaosha/order/index', 'user_id' => $value['user_id']]) ?>"><?= $value['ms_order_count'] ?></a>
                            <?php else : ?>
                                <span>0</span>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="cell">
                            拼团订单：
                            <?php if ($value['pt_order_count'] && $value['pt_order_count'] > 0) : ?>
                                <a target="_blank"
                                   href="<?= $urlManager->createUrl(['mch/group/order/index', 'user_id' => $value['user_id']]) ?>"><?= $value['pt_order_count'] ?></a>
                            <?php else : ?>
                                <span>0</span>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="cell">
                            预约订单：
                            <?php if ($value['yy_order_count'] && $value['yy_order_count'] > 0) : ?>
                                <a target="_blank"
                                   href="<?= $urlManager->createUrl(['mch/book/order/index', 'user_id' => $value['user_id']]) ?>"><?= $value['yy_order_count'] ?></a>
                            <?php else : ?>
                                <span>0</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div style='width:120px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;'
                             title='<?= $value['seller_comments'] ?>'>
                            <?= $value['seller_comments'] ?>
                        </div>
                    </td>

                    <td>
                        <div class="btn btn-group" role="group">
                            <a class="btn btn-secondary dropdown-toggle" href="javascript:" type="button"
                               id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                操作
                            </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">
                                <?php if ($value['status'] == 0) : ?>
                                    <a class="dropdown-item del" href="javascript:"
                                       data-url="<?= $urlManager->createUrl(['mch/share/status', 'status' => 1, 'id' => $value['id']]) ?>"
                                       data-content="是否审核通过？">审核通过</a>
                                    <a class="dropdown-item del" href="javascript:"
                                       data-url="<?= $urlManager->createUrl(['mch/share/status', 'status' => 2, 'id' => $value['id']]) ?>"
                                       data-content="是否审核不通过？">不通过</a>
                                <?php elseif ($value['status'] == 1) : ?>
                                    <a class="dropdown-item"
                                       href="<?= $urlManager->createUrl(['mch/share/order', 'parent_id' => $value['user_id']]) ?>">分销订单</a>
                                    <a class="dropdown-item"
                                       href="<?= $urlManager->createUrl(['mch/share/cash', 'id' => $value['id']]) ?>">提现明细</a>
                                    <a class="dropdown-item del" href="javascript:"
                                       data-url="<?= $urlManager->createUrl(['mch/share/del', 'id' => $value['id']]) ?>"
                                       data-content="是否删除分销商？">删除分销商</a>
                                <?php endif; ?>
                                <a href="javascript:" class="dropdown-item" data-toggle="modal" data-target="#myModal"
                                   onclick="add_comments(<?= $value['id'] ?>,'<?= $value['seller_comments'] ?>' )">添加备注
                                </a>
                                <a href="javascript:" class="dropdown-item qrcode-btn"
                                   data-id="<?= $value['user_id'] ?>"
                                   data-name="<?= $value['nickname'] ?>">分销二维码</a>
                            </div>
                        </div>
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
            <div class="text-muted">共<?= $pagination->totalCount ? $pagination->totalCount : 0 ?>条数据</div>
        </div>

        <!-- 下线 -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="app" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">下线情况</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 600px; overflow-y: auto">
                        <table class="table table-bordered ">
                            <tr>
                                <td>序号</td>
                                <td>分销商</td>
                                <td>下线等级</td>
                                <td>昵称</td>
                            </tr>
                            <tr v-for="(item,index) in list">
                                <td>{{index+1}}</td>
                                <td>{{name}}</td>
                                <td>{{level}}</td>
                                <td>{{item.nickname}}</td>
                            </tr>
                            <tr v-if="loading">
                                <td colspan="4" style="text-align: center">加载中</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="height:40px;">
                <h5 class="modal-title" id="myModalLabel">
                    添加备注
                </h5>
            </div>
            <div class="modal-body">
                备注：<textarea name="seller_comments" id="seller_comments" rows="5"
                             style="resize: none;width:100%"></textarea>
                <input type="hidden" value="" name="user_id" id="user_id">
            </div>
            <div class="modal-footer" style="height:40px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                <button type="button" class="btn btn-primary" id="member" onclick="comments()">添加</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="qrcode">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="height:40px;">
                <h5 class="modal-title" id="myModalLabel">分销二维码</h5>
            </div>
            <div class="modal-body">
                <div class="share-name mb-1" style="border-bottom: 1px solid #eee"></div>
                <div style="text-align: center">
                    <a href="javascript:" class="preview-qrcode" target="_blank">
                        <img width="430px" height="430px" src="">
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">关闭</button>
            </div>
        </div>
    </div>
</div>

<?= $this->render('/layouts/ss', [
    'exportList' => $exportList,
]); ?>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            team:<?=$team?>,
            list: [],
            name: "",
            level: "",
            loading: false
        }
    });
    $('#app').show();
    $(document).on('click', '.team', function () {
        var index = $(this).data('index');
        var level = $(this).data('level');
        app.name = $(this).data('name');
        app.list = [];
        app.level = '';
        if (level == 1) {
            app.level = "<?=str_replace("\"", "\\\"", $setting->first_name);?>" || "一级";
        }
        if (level == 2) {
            app.level = "<?=str_replace("\"", "\\\"", $setting->second_name)?>" || "二级";
        }
        if (level == 3) {
            app.level = "<?=str_replace("\"", "\\\"", $setting->third_name)?>" || "三级";
        }
        app.loading = true;
        $.ajax({
            url: "<?= $urlManager->createUrl(['mch/share/get-team'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                user_id: index,
                level: level
            },
            success: function (res) {
                app.list = res.data;
                app.loading = false;
            }
        });
    });

    function add_comments(id, seller_comments) {
        $("#user_id").val(id);
        $("#seller_comments").val(seller_comments);
    }

    var AddCommentsUrl = "<?= $urlManager->createUrl(['mch/share/seller-comments']) ?>";

    function comments() {
        var user_id = $("#user_id").val();
        var seller_comments = $("#seller_comments").val();
        $.ajax({
            url: AddCommentsUrl,
            type: 'get',
            dataType: 'json',
            data: {
                user_id: user_id,
                seller_comments: seller_comments
            },
            success: function (res) {
                if (res.code == 0) {
                    $('#myModal').css('display', 'none');
                    $.myAlert({
                        content: "添加成功", confirm: function (e) {
                            window.location.reload();
                        }
                    });
                } else {
                    $.myAlert({
                        content: "添加失败"
                    });
                }
            }
        });
    }
</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
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

    $(document).on('click', '.goods-all', function () {
        var checked = $(this).prop('checked');
        $('.goods-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.goods-one', function () {
        var checked = $(this).prop('checked');
        var all = $('.goods-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_use = true;
            } else {
                is_all = false;
            }
        });
        if (is_all) {
            $('.goods-all').prop('checked', true);
        } else {
            $('.goods-all').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.batch', function () {
        var all = $('.goods-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选商品"
            });
        }
    });
    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var goods_group = [];
        var all = $('.goods-one');

        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                var goods = {};
                goods.id = $(all[i]).val();
                goods.user_id = $(all[i]).data('user_id');
                goods_group.push(goods);
            }
        });
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        goods_group: goods_group,
                        type: a.data('type'),
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {

                        }
                    },
                    complete: function () {
                        $.myLoadingHide();
                    }
                });
            }
        })
    });
</script>
<script>
    $(document).on('click', '.qrcode-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('.share-name').text('分销商：' + name + '的二维码');
        $.loading();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/share/get-qrcode'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                user_id: id,
            },
            success: function (res) {
                $.loadingHide();
                if (res.code == 0) {
                    $('#qrcode').modal('show');
                    $('.preview-qrcode').attr('href', res.data.url);
                    $('.preview-qrcode').children('img').attr('src', res.data.url);
                } else {
                    $.alert({
                        content: res.msg
                    });
                }
            }
        });
    });
</script>
