<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 15:03
 */
use yii\widgets\LinkPager;

defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '核销员管理';
$this->params['active_nav_group'] = 4;
$urlPlatform = Yii::$app->controller->route;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="float-left pt-2">
            <a class="btn btn-primary" href="javascript:" data-toggle="modal" data-target="#edit"
               data-backdrop="static">添加核销员</a>
            <div class="dropdown float-right ml-2">
                <button class="btn btn-secondary dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php if ($_GET['platform'] === '1') :
                        ?>支付宝
                    <?php elseif ($_GET['platform'] === '0') :
                        ?>微信
                    <?php elseif ($_GET['platform'] == '') :
                        ?>全部核销员
                    <?php else : ?>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu" style="min-width:8rem">
                    <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlPlatform]) ?>">全部核销员</a>
                    <a class="dropdown-item"
                       href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 1]) ?>">支付宝</a>
                    <a class="dropdown-item"
                       href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 0]) ?>">微信</a>
                </div>
            </div>
        </div>
        <div class="float-right mb-4">
            <form method="get">

                <?php $_s = ['keyword'] ?>
                <?php foreach ($_GET as $_gi => $_gv) :
                    if (in_array($_gi, $_s)) {
                        continue;
                    } ?>
                    <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                <?php endforeach; ?>

                <div class="input-group">
                    <input class="form-control"
                           placeholder="昵称"
                           name="keyword"
                           autocomplete="off"
                           value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                    <span class="input-group-btn">
                    <button class="btn btn-primary">搜索</button>
                </span>
                </div>
            </form>
        </div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>头像</th>
                <th>昵称</th>
                <th>所属门店</th>
                <th>加入时间</th>
                <th>身份</th>
                <th>核销订单数</th>
                <th>核销总额</th>
                <th>核销卡券数</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $u) : ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td>
                        <img src="<?= $u['avatar_url'] ?>" style="width: 34px;height: 34px;margin: -.6rem 0;">
                    </td>
                    <td>
                        <?= $u['nickname']; ?>
                        <?php if (isset($u['platform']) && intval($u['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($u['platform']) && intval($u['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $u['shop_name']; ?></td>
                    <td><?= date('Y-m-d H:i:s', $u['addtime']) ?></td>
                    <td><span class="badge badge-success"  style="font-size: 100%;">核销员</span></td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['mch/order/index', 'clerk_id' => $u['id']]) ?>"><?= $u['order_count'] ?></a>
                    </td>
                    <td><?= $u['total_price']?></td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['mch/user/card', 'clerk_id' => $u['id']]) ?>"><?= $u['card_count'] ?></a>
                    </td>
                    <td>
                        <?php if ($u['is_clerk'] == 0) : ?>
                            <a class="btn btn-sm btn-primary del" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/user/clerk-edit', 'id' => $u['id'], 'status' => 1]) ?>"
                               data-content="是否设为核销员？">设为核销员</a>
                        <?php else : ?>
                            <a class="btn btn-sm btn-danger del" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/user/clerk-edit', 'id' => $u['id'], 'status' => 0]) ?>"
                               data-content="是否解除核销员">解除核销员</a>
                        <?php endif; ?>
                        <a class="btn btn-primary btn-sm user-shop-edit" data-id="<?= $u['id'] ?>"
                           href="javascript:">修改门店</a>
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
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>
        <div id="app">
            <!-- 设置核销员 -->
            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">设置核销员</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class="col-form-label required">门店选择</label>
                                </div>
                                <div class="col-9">
                                    <template v-if="shop_list.length>0">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{select_shop.name}}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                 style="max-height: 200px;overflow-y: auto">
                                                <a class="dropdown-item shop-select" href="javascript:"
                                                   v-for="(item,index) in shop_list"
                                                   :data-index="index">{{item.name}}</a>
                                            </div>
                                        </div>
                                        <div class="shop-error text-danger" hidden></div>
                                    </template>
                                    <template v-else>
                                        <label class="col-form-label">暂未设置门店，<a
                                                href="<?= $urlManager->createUrl(['mch/store/shop']) ?>">请前往设置</a></label>
                                    </template>
                                </div>
                            </div>
                            <template v-if="shop_list.length>0">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control keyword" placeholder="输入昵称查找">
                                        <input class="form-control order-id" type="hidden">
                            <span class="input-group-btn">
                                    <button v-on:click="showKeyword()" class="btn btn-info">
                                        查找
                                    </button>
                                </span>
                                    </div>
                                </div>
                                <div style="max-height:400px;overflow: auto">
                                    <table class="table table-bordered">
                                        <tr v-for="(item,index) in show_user_list">
                                            <td>{{item.id}}</td>
                                            <td>{{item.nickname}}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm send" href="javascript:"
                                                   data-url="<?= $urlManager->createUrl(['mch/user/clerk-edit', 'status' => 1]) ?>"
                                                   :data-index="item.id">设为核销员</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--修改门店-->
            <div class="modal fade" id="shop-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">设置核销员</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class="col-form-label required">门店选择</label>
                                </div>
                                <div class="col-9">
                                    <template v-if="shop_list.length>0">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{select_shop.name}}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                 style="max-height: 200px;overflow-y: auto">
                                                <a class="dropdown-item shop-select" href="javascript:"
                                                   v-for="(item,index) in shop_list"
                                                   :data-index="index">{{item.name}}</a>
                                            </div>
                                        </div>
                                        <div class="shop-error text-danger" hidden></div>
                                    </template>
                                    <template v-else>
                                        <label class="col-form-label">暂未设置门店，<a
                                                href="<?= $urlManager->createUrl(['mch/store/shop']) ?>">请前往设置</a></label>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-primary send" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/user/clerk-edit', 'status' => 1, 'edit' => 1]) ?>"
                               :data-index="edit_user_id">提交</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            user_list:<?=$user_list?>,
            show_user_list:<?=$user_list?>,
            shop_list:<?=$shop_list?>,
            select_shop: "",
            edit_user_id: "-1",
        },
        methods: {
            //关键字查询
            showKeyword: function () {
                var _self = this;
                var keyword = $.trim($('.keyword').val());
                if (keyword == "") {
                    _self.show_user_list = _self.user_list;
                    return;
                }
                _self.show_user_list = [];
                $.ajax({
                    url: '<?=$urlManager->createUrl(['mch/user/get-user'])?>',
                    dataType: 'json',
                    type: 'get',
                    data: {
                        keyword: keyword
                    },
                    success: function (res) {
                        _self.show_user_list = res;
                    }
                });
//                for (var i = 0; i < _self.user_list.length; i++) {
//                    if (_self.user_list[i].nickname.indexOf(keyword) != -1) {
//                        _self.show_user_list.push(_self.user_list[i]);
//                    }
//                }
            }
        }
    });
    app.select_shop = app.shop_list.length > 0 ? app.shop_list[0] : ""
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
    $(document).on('click', '.send', function () {
        var a = $(this);
        var index = $(this).data('index');
        $('.shop-error').prop('hidden', true);
        if (app.select_shop == "") {
            $('.shop-error').prop('hidden', false).html('请先选择门店');
            return;
        }
        $.ajax({
            url: a.data('url'),
            type: 'get',
            dataType: 'json',
            data: {
                id: index,
                shop_id: app.select_shop.id
            },
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
        return false;
    });
    $(document).on('click', '.shop-select', function () {
        var index = $(this).data('index');
        app.select_shop = app.shop_list[index];
    });
    $(document).on('click', '.user-shop-edit', function () {
        if (app.user_list.length > 0) {
            app.edit_user_id = $(this).data('id');
            $('#shop-edit').modal('show');
        }
    });
</script>

