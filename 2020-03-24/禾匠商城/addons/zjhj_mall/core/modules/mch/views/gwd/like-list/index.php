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
$this->title = '想买好物圈列表';
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

    .order-tab-0 {
        width: 5%;
        text-align: center;
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
        width: 25%;
        text-align: center;
    }

    .user-info-box {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .user-info-box .item-box {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 10px;
    }

    .user-info-box .item-box img {
        width: 55px;
        height: 55px;
    }

    .user-info-box .item-box span {
        font-size: 14px;
    }
</style>
<div id="app">
    <div class="panel mb-3">
        <div class="panel-header"><?= $this->title ?></div>
        <div class="panel-body">
            <div style="background-color: #fce9e6;width: 100%;border-color: #edd7d4;color: #e55640;border-radius: 2px;padding: 15px;margin-bottom: 20px;">
                <p>服务商&商家需要保证在导入数据时</p>
                <p>1.订单&商品数据是用户真实的操作产生的； 2.在适当的场景调用好物圈提供的相关接口（这里指商品和订单的新增&删除）；
                    如果发现伪造数据/不当调用的情况，微信搜索平台将对服务商和商家采取惩罚措施。</p>
            </div>
            <div class="mb-3 clearfix">
                <div class="float-left">
                    <div class="dropdown float-right ml-2">
                        <a href="javascript:void(0)" class="btn btn-secondary batch-destroy-good"
                           data-url="<?= $urlManager->createUrl([$urlStr . '/destroy-good']) ?>"
                           data-content="确认执行此操作?">批量删除商品</a>
                    </div>
                    <a href="javascript:" class="btn btn-secondary add-good-btn">添加商品</a>
                </div>
                <div class="float-right">
                    <form method="get">
                        <?php $_s = ['keyword'] ?>
                        <?php foreach ($_GET as $_gi => $_gv) :
                            if (in_array($_gi, $_s)) {
                                continue;
                            } ?>
                            <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                        <?php endforeach; ?>

                        <div class="input-group">
                            <input class="form-control" placeholder="商品名" name="keyword"
                                   value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                            <span class="input-group-btn">
                    <button class="btn btn-primary">搜索</button>
                </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered bg-white">
        <tr>
            <th class="order-tab-0">
                <label class="checkbox-label" style="margin-right: 0px;">
                    <input type="checkbox" class="all-goods">
                    <span class="label-icon"></span>
                </label>
            </th>
            <th class="order-tab-1">商品信息</th>
            <th class="order-tab-2">上架状态</th>
            <th class="order-tab-3">想买人数</th>
            <th class="order-tab-4">操作</th>
        </tr>
    </table>
    <?php foreach ($list as $k => $item) : ?>
        <table class="table table-bordered bg-white">
            <tr>
                <td class="order-tab-0">
                    <?php if ($item['user_count'] == 0): ?>
                        <label class="checkbox-label" style="margin-right: 0px;">
                            <input type="checkbox"
                                   class="item-good-one"
                                   data-id='<?= $item['id'] ?>'
                                   value="<?= $item['id'] ?>">
                            <span class="label-icon"></span>
                        </label>
                    <?php endif; ?>
                </td>
                <td class="order-tab-1">
                    <div class="goods-item" flex="dir:left box:first">
                        <div class="fs-0">
                            <div class="goods-pic"
                                 style="background-image: url('<?= $item['cover_pic'] ?>')"></div>
                        </div>
                        <div class="goods-info">
                            <div class="goods-name"><?= $item['name'] ?></div>
                            <div class="mt-1">
                            <span class="fs-sm">
                                库存：<?= $item['goods_num'] ?>
                            </span>
                            </div>
                            <div>
                            <span class="fs-sm">
                                价格：<span class="text-danger mr-4"><?= $item['price'] ?>元</span>
                            </span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="order-tab-2">
                    <?php if ($item['status'] == 1) : ?>
                        <span class="badge badge-success">已上架</span>
                    <?php else : ?>
                        <span class="badge badge-default">已下架</span>
                    <?php endif ?>
                </td>
                <td class="order-tab-3">
                    <a class="show-users-info" href="javascript:"
                       data-url="<?= $urlManager->createUrl([$urlStr . '/like-users', 'id' => $item['id']]) ?>"
                       data-id="<?= $item['id'] ?>">
                        <?= $item['user_count'] ?>
                    </a>
                    <a style="margin-left: 10px" class="btn btn-sm btn-primary show-user-modal" href="javascript:"
                       data-id="<?= $item['id'] ?>">添加</a>
                </td>
                <td class="order-tab-4">
                    <a class="btn btn-sm btn-primary"
                       href="<?= $urlManager->createUrl([$urlStr . '/edit', 'id' => $item['id']]) ?>">编辑</a>
                    <?php if ($item['user_count'] == 0): ?>
                        <a class="btn btn-sm btn-danger destroy-good"
                           href="<?= $urlManager->createUrl([$urlStr . '/destroy-good', 'id' => $item['id']]) ?>">删除</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    <?php endforeach; ?>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="searchGoodsModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">查找商品</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-secondary batch-add-good"
                            data-url="<?= $urlManager->createUrl([$urlStr . '/add-good']) ?>" data-content="确认执行此操作?"
                            style="margin-bottom: 10px;cursor: pointer;">批量添加
                    </button>
                    <form action="<?= $urlManager->createUrl(['mch/gwd/like-list/goods-search']) ?>"
                          class="input-group  goods-search-form" method="get">
                        <input name="keyword" class="form-control" placeholder="商品名称">
                        <span class="input-group-btn">
                        <button class="btn btn-secondary submit-btn">查找</button>
                    </span>
                    </form>
                    <div v-if="goodsList==null" class="text-muted text-center p-5">请输入商品名称查找商品</div>
                    <template v-else>
                        <div v-if="goodsList.length==0" class="text-muted text-center p-5">未查找到相关商品
                        </div>
                        <template v-else>
                            <div class="col-12" style="padding-left: 11px;">
                                <label class="checkbox-label" style="margin-right: 0px;">
                                    <input type="checkbox" class="all-data">
                                    <span class="label-icon"></span>
                                    全选
                                </label>
                            </div>
                            <div class="goods-item row mt-3 mb-3" v-for="(item,index) in goodsList">
                                <div class="col-2">
                                    <label class="checkbox-label" style="margin-right: 0px;">
                                        <input type="checkbox"
                                               class="item-one"
                                               :data-id='item.id'
                                               :value="item.id">
                                        <span class="label-icon"></span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                        {{item.name}}
                                    </div>
                                </div>
                                <div class="col-2 text-right">￥{{item.price}}</div>
                                <div class="col-2 text-right">
                                    <a href="javascript:" class="btn btn-primary goods-select"
                                       v-bind:index="index">添加</a>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static"
         data-show="false">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="margin-bottom: 10px;" class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                             aria-valuemax="100" :style="{'width': progress_c_increment + '%'}">
                            {{progress_c_increment <= 100 ? progress_c_increment : 100}}%
                        </div>
                    </div>
                    <div>总共 {{count}} 条,成功{{send_result.count -
                        send_result.error_count}}条,失败{{send_result.error_count}}条
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close"
                            data-dismiss="modal" id="closeModel">关闭
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="userModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">添加想买用户</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-secondary batch-add-user"
                            data-url="<?= $urlManager->createUrl([$urlStr . '/add-user']) ?>" data-content="确认执行此操作?"
                            style="margin-bottom: 10px;cursor: pointer;">批量添加
                    </button>
                    <!--
                    <button class="btn btn-secondary add-all-user"
                            data-url="<?= $urlManager->createUrl([$urlStr . '/add-user']) ?>" data-content="确认执行此操作?"
                            style="margin-bottom: 10px;cursor: pointer">随机添加50个用户
                    </button>
                    -->
                    <form action="<?= $urlManager->createUrl(['mch/gwd/like-list/user-list']) ?>"
                          class="input-group  users-search-form" method="get">
                        <input name="keyword" class="form-control" placeholder="用户昵称">
                        <input name="id" :value="likeId" hidden>
                        <span class="input-group-btn">
                            <button class="btn btn-secondary submit-btn-user">查找</button>
                        </span>
                    </form>
                    <div v-if="userList==null" class="text-muted text-center p-5">请输入商品名称查找商品</div>
                    <template v-else>
                        <div v-if="userList.length==0" class="text-muted text-center p-5">未查找到符合用户
                        </div>
                        <template v-else>
                            <div class="col-12" style="padding-left: 11px;">
                                <label class="checkbox-label" style="margin-right: 0px;">
                                    <input type="checkbox" class="all-data">
                                    <span class="label-icon"></span>
                                    全选
                                </label>
                            </div>
                            <div class="goods-item row mt-3 mb-3" v-for="(item,index) in userList">
                                <div class="col-2">
                                    <label class="checkbox-label" style="margin-right: 0px;">
                                        <input type="checkbox"
                                               class="item-one"
                                               :data-id='item.id'
                                               :value="item.id">
                                        <span class="label-icon"></span>
                                    </label>
                                </div>
                                <div class="col-8">
                                    <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                        {{item.nickname}}
                                    </div>
                                </div>
                                <div class="col-2 text-right">
                                    <a href="javascript:" class="btn btn-primary user-select"
                                       v-bind:index="index">添加</a>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="usersInfoModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">用户信息列表</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="user-info-box">
                        <template v-if="likeUserList.length > 0">
                            <div v-for="item in likeUserList" class="item-box">
                                <img :src="item.user.avatar_url">
                                <span>{{item.user.nickname}}</span>
                            </div>
                        </template>
                    </div>
                    <div class="text-center" style="margin-top: 25px">
                        <div class="mt-2 d-flex align-items-center">
                            <div>共{{modal_list.count}}条，每页30条</div>
                            <div class="text-center ml-4" v-if="modal_list.count > 30">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination vue-pagination" style="margin: 0;">
                                        <template v-if="modal_list.page > 1">
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:"
                                                   :data-url="modal_list.page_url + '&page=1'">首页</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:"
                                                   :data-url="modal_list.page_url + '&page=' + (modal_list.page-1)">上一页</a>
                                            </li>
                                        </template>
                                        <template v-else>
                                            <li class="page-item disabled">
                                                <span class="page-link">首页</span>
                                            </li>
                                            <li class="page-item disabled">
                                                <span class="page-link">上一页</span>
                                            </li>
                                        </template>
                                        <template v-for="i in modal_list.page_count"
                                                  v-if="i >= (modal_list.page > 5 ? (modal_list.page_count >= modal_list.page+5 ? modal_list.page-4 : modal_list.page_count-9) : 1) && i <= (modal_list.page > 5 ? (modal_list.page_count >= modal_list.page+5 ? modal_list.page+5 : modal_list.page_count) : 10)">
                                            <li :class="'page-item ' + (modal_list.page == i ? 'active' : '')">
                                                <a class="page-link" href="javascript:"
                                                   :data-url="modal_list.page_url + '&page=' + i">{{i}}</a>
                                            </li>
                                        </template>
                                        <template v-if="modal_list.page < modal_list.page_count">
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:"
                                                   :data-url="modal_list.page_url + '&page=' + (parseInt(modal_list.page)+1)">下一页</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:"
                                                   :data-url="modal_list.page_url + '&page=' + modal_list.page_count">尾页</a>
                                            </li>
                                        </template>
                                        <template v-else>
                                            <li class="page-item disabled">
                                                <span class="page-link">下一页</span>
                                            </li>
                                            <li class="page-item disabled">
                                                <span class="page-link">尾页</span>
                                            </li>
                                        </template>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


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
    var app = new Vue({
        el: "#app",
        data: {
            goodsList: [],
            userList: [],
            likeId: '',
            progress_c_increment: 0,//进度条当前进度
            count: 0,
            send_result: {
                count: 0,
                error_count: 0,
                error_list: [],
            },
            modal_list: {
                count: 0,
            },
            likeUserList: [],
        }
    });

    $(document).on("submit", ".goods-search-form", function () {
        var form = $(this);
        var btn = form.find(".submit-btn");
        btn.btnLoading("正在查找");
        $.ajax({
            url: form.attr("action"),
            type: "get",
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.goodsList = res.data.list;
                }
            }
        });
        return false;
    });

    $(document).on("submit", ".users-search-form", function () {
        var form = $(this);
        var btn = form.find(".submit-btn-user");
        btn.btnLoading("正在查找");
        $.ajax({
            url: form.attr("action"),
            type: "get",
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    console.log(res.data.list);
                    app.userList = res.data.list;
                    console.log(app.userList)
                }
            }
        });
        return false;
    });
    $(document).on("click", ".add-good-btn", function () {
        var btn = $(this);
        app.goodsList = [];
        $('#searchGoodsModal').modal('show');
        return false;
    });

    $(document).on("click", ".goods-select", function () {
        var index = $(this).attr("index");
        var good = app.goodsList[index];

        var btn = $(this);
        btn.btnLoading(btn.text());

        $.ajax({
            url: '<?= $urlManager->createUrl([$urlStr . '/add-good']) ?>',
            type: 'post',
            dataType: 'json',
            data: {
                good_id: good.id,
                _csrf: _csrf,
            },
            success: function (res) {
                alert(res.msg);
                btn.btnReset();
                if (res.code == 0) {
                    btn.btnReset();
                    window.location.reload();
                }
            }
        });
    });

    $(document).on('click', '.batch-add-good', function () {
        var btn = $(this);
        var arrList = [];
        var all = $('.item-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                arrList.push($(all[i]).data('id'));
            }
        });
        if (!arrList.length > 0) {
            $.myAlert({
                content: "请先勾选商品"
            });
            return;
        }
        app.count = arrList.length;
        $.myConfirm({
            content: btn.data('content'),
            confirm: function () {
                $('#searchGoodsModal').modal('hide');
                $('#myModal').modal('show');
                for (var i in arrList) {
                    $.ajax({
                        url: btn.data('url'),
                        type: 'post',
                        dataType: 'json',
                        data: {
                            good_id: arrList[i],
                            _csrf: _csrf,
                        },
                        success: function (res) {
                            app.progress_c_increment = Math.ceil(app.progress_c_increment + (100 / arrList.length));
                            app.send_result.count += 1;
                            if (res.code == 1) {
                                app.send_result.error_count += 1;
                                app.send_result.error_list.push({
                                    msg: res.msg
                                })
                            }
                        },
                        complete: function () {

                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '.batch-destroy-good', function () {
        var btn = $(this);
        var arrList = [];
        var all = $('.item-good-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                arrList.push($(all[i]).data('id'));
            }
        });
        if (!arrList.length > 0) {
            $.myAlert({
                content: "请先勾选商品"
            });
            return;
        }
        app.count = arrList.length;
        $.myConfirm({
            content: btn.data('content'),
            confirm: function () {
                $('#myModal').modal('show');
                for (var i in arrList) {
                    $.ajax({
                        url: btn.data('url'),
                        type: 'get',
                        dataType: 'json',
                        data: {
                            id: arrList[i],
                        },
                        success: function (res) {
                            app.progress_c_increment = Math.ceil(app.progress_c_increment + (100 / arrList.length));
                            app.send_result.count += 1;
                            if (res.code == 1) {
                                app.send_result.error_count += 1;
                                app.send_result.error_list.push({
                                    msg: res.msg
                                })
                            }
                        },
                        complete: function () {

                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '.destroy-good', function () {
        if (confirm("是否删除？")) {
            var btn = $(this);
            btn.btnLoading('删除中');
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
                },

            });
        }
        return false;
    });

    $(document).on("click", ".user-select", function () {
        var index = $(this).attr("index");
        var user = app.userList[index];

        var btn = $(this);
        btn.btnLoading(btn.text());

        $.ajax({
            url: '<?= $urlManager->createUrl([$urlStr . '/add-user']) ?>',
            type: 'post',
            dataType: 'json',
            data: {
                user_id: user.id,
                like_id: app.likeId,
                _csrf: _csrf,
            },
            success: function (res) {
                alert(res.msg);
                btn.btnReset();
                if (res.code == 0) {
                    btn.btnReset();
                    window.location.reload();
                }
            }
        });
    });

    $(document).on('click', '.batch-add', function () {
        var a = $(this);
        var idList = [];
        $.ajax({
            url: '<?= $urlManager->createUrl([$urlStr . '/good-ids']) ?>',
            type: 'get',
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    var idList = res.data.list;
                    app.count = idList.length;
                }

                if (!app.count) {
                    alert('没有可添加的商品啦！');
                    return;
                }


                $.myConfirm({
                    content: a.data('content'),
                    confirm: function () {
                        $('#myModal').modal('show');
                        for (var i in idList) {
                            $.ajax({
                                url: a.data('url'),
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    good_id: idList[i].id,
                                    _csrf: _csrf
                                },
                                success: function (res) {
                                    app.progress_c_increment = Math.ceil(app.progress_c_increment + (100 / idList.length));
                                    app.send_result.count += 1;
                                    if (res.code == 1) {
                                        app.send_result.error_count += 1;
                                        app.send_result.error_list.push({
                                            msg: res.msg
                                        })
                                    }
                                },
                                complete: function () {

                                }
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.add-all-user', function () {
        var btn = $(this);
        var idList = [];
        $.ajax({
            url: '<?= $urlManager->createUrl([$urlStr . '/all-user']) ?>',
            type: 'get',
            dataType: 'json',
            data: {
                like_id: app.likeId
            },
            success: function (res) {
                if (res.code == 0) {
                    var idList = res.data.list;
                    app.count = idList.length;
                }

                if (!app.count) {
                    alert('没有可添加的用户！');
                    return;
                }

                $.myConfirm({
                    content: btn.data('content'),
                    confirm: function () {
                        $('#userModal').modal('hide');
                        $('#myModal').modal('show');
                        for (var i in idList) {
                            $.ajax({
                                url: btn.data('url'),
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    user_id: idList[i].id,
                                    like_id: app.likeId,
                                    _csrf: _csrf
                                },
                                success: function (res) {
                                    app.progress_c_increment = Math.ceil(app.progress_c_increment + (100 / idList.length));
                                    app.send_result.count += 1;
                                    if (res.code == 1) {
                                        app.send_result.error_count += 1;
                                        app.send_result.error_list.push({
                                            msg: res.msg
                                        })
                                    }
                                },
                                complete: function () {

                                }
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.modal-close', function () {
        window.location.reload();
    });

    $(document).on('click', '.show-user-modal', function () {
        var a = $(this);
        app.likeId = a.data('id');
        app.userList = [];
        $('#userModal').modal('show');
    });

    $(document).on('click', '.show-users-info', function () {
        var a = $(this);
        app.likeId = a.data('id');
        $.ajax({
            url: a.data('url'),
            type: 'get',
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    app.likeUserList = res.data.list;
                    app.modal_list = res.data.modal_list;
                }
            },
            complete: function () {

            }
        });

        $('#usersInfoModal').modal('show');
    });

    $(document).on('click', '.all-data', function () {
        var checked = $(this).prop('checked');
        $('.item-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });

    $(document).on('click', '.item-one', function () {
        var checked = $(this).prop('checked');
        var all = $('.item-one');
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        var checkNum = 0;
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                checkNum += 1;
                is_use = true;
            }
        });
        if (checkNum == all.length) {
            $('.all-data').prop('checked', true);
        } else {
            $('.all-data').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });

    $(document).on('click', '.batch-add-user', function () {
        var btn = $(this);
        var arrList = [];
        var all = $('.item-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                arrList.push($(all[i]).data('id'));
            }
        });
        if (!arrList.length > 0) {
            $.myAlert({
                content: "请先勾选用户"
            });
            return;
        }
        app.count = arrList.length;
        $.myConfirm({
            content: btn.data('content'),
            confirm: function () {
                $('#userModal').modal('hide');
                $('#myModal').modal('show');
                for (var i in arrList) {
                    $.ajax({
                        url: btn.data('url'),
                        type: 'post',
                        dataType: 'json',
                        data: {
                            user_id: arrList[i],
                            like_id: app.likeId,
                            _csrf: _csrf,
                        },
                        success: function (res) {
                            app.progress_c_increment = Math.ceil(app.progress_c_increment + (100 / arrList.length));
                            app.send_result.count += 1;
                            if (res.code == 1) {
                                app.send_result.error_count += 1;
                                app.send_result.error_list.push({
                                    msg: res.msg
                                })
                            }
                        },
                        complete: function () {

                        }
                    });
                }
            }
        });
    });

    // 分页点击
    $(document).on('click', '.vue-pagination .page-link', function () {
        $.ajax({
            url: $(this).data('url'),
            type: 'get',
            dataType: 'json',
            data: {
                id: app.likeId
            },
            success: function (res) {
                if (res.code == 0) {
                    app.likeUserList = res.data.list;
                    app.modal_list = res.data.modal_list;
                }
            },
            complete: function () {

            }
        });
    });


    $(document).on('click', '.all-goods', function () {
        var checked = $(this).prop('checked');
        $('.item-good-one').prop('checked', checked);

        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });

    $(document).on('click', '.item-good-one', function () {
        var checked = $(this).prop('checked');
        var all = $('.item-good-one');
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        var checkNum = 0;
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                checkNum += 1;
                is_use = true;
            }
        });
        if (checkNum == all.length) {
            $('.all-goods').prop('checked', true);
        } else {
            $('.all-goods').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
</script>