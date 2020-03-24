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
$imgurl = Yii::$app->request->baseUrl;
$this->title = '积分商品列表';
?>
<style>
    .modal-dialog{
        position:fixed;
        top:20%;
        left:45%;
        width:240px;
    }
    .modal-content{
        width:240px;
    }
    .modal-body{
        /*height:200px;*/
    }
    table {
        table-layout: fixed;
    }

    th {
        text-align: center;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td {
        text-align: center;
        line-height: 30px;
    }

    .ellipsis {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td.nowrap {
        white-space: nowrap;
        overflow: hidden;
    }

    .goods-pic {
        margin: 0 auto;
        width: 3rem;
        height: 3rem;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $status = ['已下架', '已上架'];
        ?>
        <div class="mb-3 clearfix">
            <div class="float-left">
                <!--
                <a href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-list']) ?>" class="btn btn-primary">
                    <i class="iconfont icon-playlistadd"></i>选择商城商品</a>
                -->
                <a href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-edit']) ?>" class="btn btn-primary"><i
                        class="iconfont icon-playlistadd"></i>添加商品</a>
            </div>
            <div class="float-right">
                <form method="get" hidden>
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
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th style="text-align: center;text-overflow:clip;">
                    <label class="checkbox-label" style="margin-right: 0px;">
                        <input type="checkbox" class="goods-all">
                        <span class="label-icon"></span>
                    </label>
                </th>
                <th><span class="label-text">商品ID</span></th>
                <th>商品类型</th>
                <th>商品名称</th>
                <th>商品图片</th>
                <th>售价</th>
                <th>所需积分</th>
                <th>每人可兑换数</th>
                <th>库存</th>
                <th>状态</th>
                <th>放置首页</th>
                <th>已出售量</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 3%">
            <col style="width: 6%">
            <col style="width: 6%">
            <col style="width: 14%">
            <col style="width: 5%">
            <col style="width: 8%">
            <col style="width: 7%">
            <col style="width: 7%">
            <col style="width: 7%">
            <col style="width: 8%">
            <col style="width: 8%">
            <col style="width: 6%">
            <col style="width: 6%">
            <col style="width: 9%">
            <tbody>
            <?php if ($list != null) : ?>
                <?php foreach ($list as $index => $goods) : ?>
                    <tr>
                        <td class="nowrap" style="text-align: center;">
                            <label class="checkbox-label" style="margin-right: 0px;">
                                <input data-num="" type="checkbox"
                                       class="goods-one"
                                       value="<?= $goods['id'] ?>">
                                <span class="label-icon"></span>
                            </label>
                        </td>
                        <td data-toggle="tooltip"
                            data-placement="top" title="<?=$goods['id']?>">
                            <span class="label-text"><?= $goods['id'] ?></span>
                        </td>
                        <td class="ellipsis" data-toggle="tooltip"
                            data-placement="top" title="<?=$goods['cat_name']?>">
                            <span class="badge badge-info" style="width: 100%"><?=$goods['cat_name']?></span>
                        </td>
                        <td class="text-left ellipsis" data-toggle="tooltip"
                            data-placement="top" title="<?=$goods['name']?>">
                            <?=$goods['name']?>
                        </td>
                        <td class="p-0" style="vertical-align: middle">
                            <div class="goods-pic" style="background-image: url(<?= $goods['cover_pic'] ?>)"></div>
                        </td>
                        <td class="nowrap text-danger"><?= $goods['price'] ?>元</td>
                        <td class="nowrap text-danger"><?= $goods['integral'] ?></td>
                        <td class="nowrap text-danger"><?= $goods['user_num'] ?></td>
                        <td class="nowrap">
                            <a href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-edit', 'id' => $goods['id']]) ?>#step3"><?= $goods['goods_num'] ?></a>
                        </td>
                        <td class="nowrap">
                            <?php if ($goods['status'] == 1) : ?>
                                <span class="badge badge-success"><?= $status[$goods['status']] ?></span>
                                |
                                <a href="javascript:" onclick="upDown(<?= $goods['id'] ?>,'down');">下架</a>
                            <?php else : ?>
                                <span class="badge badge-default"><?= $status[$goods['status']] ?></span>
                                |
                                <a href="javascript:" onclick="upDown(<?= $goods['id'] ?>,'up');">上架</a>
                            <?php endif ?>
                        </td>
                        <td class="nowrap">
                            <?php if ($goods['is_index'] == 0) : ?>
                                <span class="badge badge-success">不放</span>
                                |
                                <a href="javascript:" onclick="upDown(<?= $goods['id'] ?>,'index');">放置</a>
                            <?php else : ?>
                                <span class="badge badge-default">放置</span>
                                |
                                <a href="javascript:" onclick="upDown(<?= $goods['id'] ?>,'no-index');">不放</a>
                            <?php endif ?>
                        </td>

                        <td class="nowrap">
                            <?= $goods['virtual_sales'] ?>
                        </td>
                        <td class="nowrap">
                            <?= $goods['sort'] ?>
                        </td>
                        <td class="nowrap">
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-edit', 'id' => $goods['id']]) ?>">修改</a>
                            <a class="btn btn-sm btn-danger del"
                               href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-del', 'id' => $goods['id']]) ?>">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif ?>
            </tbody>

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
        </div>
    </div>
</div>


<!--添加规格-->
<div class="modal fade" id="attrAddModal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">积分设置</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <div class="input-group short-row">
                        <input type="text" step="1" class="form-control short-row" name="integral[give]"
                               value="" placeholder="积分赠送">
                        <span class="input-group-addon">分</span>
                    </div>
                    <div class="fs-sm text-muted">
                        会员购物赠送的积分, 如果不填写或填写0，则默认为不赠送积分，如果带%则为按成交价格的比例计算积分
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="input-group short-row">
                        <span class="input-group-addon">最多抵扣</span>
                        <input type="text" step="1" class="form-control short-row" name="integral[forehead]"
                               value="" placeholder="积分抵扣">
                        <span class="input-group-addon">元</span>
                    </div>
                    <div class="input-group short-row">
                        <label class="custom-control custom-checkbox">
                            <input value="1"
                                   name="integral[more]"
                                   type="checkbox"
                                   class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">允许多件累计折扣</span>
                        </label>
                    </div>
                    <div class="fs-sm text-muted">
                        如果设置0，则不支持积分抵扣 如果带%则为按成交价格的比例计算抵扣多少元
                    </div>
                </div>

                <div class="form-error text-danger mt-3 modelError" style="display: none">ddd</div>
                <div class="form-success text-success mt-3" style="display: none">sss</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary save-attr-btn">提交</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#myModal").modal({backdrop: "static", keyboard: false});

    $("#closeModel").click(function(){
        $("#goods_qrcode").attr("src",'');
    });


    $(document).on('click', '.del', function () {
        if (confirm("是否删除？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });

    function upDown(id, type) {
        var text = '';
        if (type == 'up') {
            text = "上架";
        }else if(type == 'down'){
            text = '下架';
        }else if(type == 'index'){
            text = "放置首页";
        }else{
            text = "下架首页";
        }
        var url = "<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-up-down']) ?>";
        layer.confirm("是否" + text + "？", {
            btn: [text, '取消'] //按钮
        }, function () {
            layer.msg('加载中', {
                icon: 16
                , shade: 0.01
            });
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                data: {id: id, type: type},
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    }
                    if (res.code == 1) {
                        layer.alert(res.msg, {
                            closeBtn: 0
                        },function () {
                            if (res.return_url) {
                                location.href = res.return_url;
                            }
                        });
                    }
                }
            });
        });
        return false;
        // if (confirm("是否" + text + "？")) {
        //     $.ajax({
        //         url: url,
        //         type: 'get',
        //         dataType: 'json',
        //         data: {id: id, type: type},
        //         success: function (res) {
        //             if (res.code == 0) {
        //                 window.location.reload();
        //             }
        //             if (res.code == 1) {
        //                 $.myAlert({
        //                     content: res.msg,confirm:function(e){
        //                         window.location.reload();
        //                     }
        //                 });
        //             }
        //         }
        //     });
        // }
        // return false;
    }

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
    // 批量设置积分
    $(document).on('click', '.save-attr-btn', function () {
        var give = $('input[name^="integral[give]"]').val();
        var forehead = $('input[name^="integral[forehead]"]').val();
//        var more = $('input[name^="integral[more]"]').val();
        if ($('input[name^="integral[more]"]').is(':checked')) {
            var more = 1;
        } else {
            var more = '';
        }
        console.log(more);
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
            return;
        }
        var a = $(this);
        var goods_group = [];
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                var goods = {};
                goods_group.push($(all[i]).val());
            }
        });

        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['mch/goods/batch-integral']) ?>",
            type: 'get',
            dataType: 'json',
            data: {
                goods_group: goods_group,
                give: give,
                forehead: forehead,
                more: more,
            },
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    $('.modelError').text(res.msg);
                    $('.modelError').css('display', 'block');
                }
            },
//            complete: function () {
//                $.myLoadingHide();
//            }
        });


    });
    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var goods_group = [];
        var all = $('.goods-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                var goods = {};
                goods.id = $(all[i]).val();
                goods.num = $(all[i]).data('num');
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
                            $.myAlert({
                                content:res.msg,
                                confirm:function(){
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.myAlert({
                                content:res.msg
                            });
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
    $(document).ready(function () {
        var clipboard = new Clipboard('.copy');
        clipboard.on('success', function (e) {
            $.myAlert({
                title: '提示',
                content: '复制成功'
            });
        });
        clipboard.on('error', function (e) {
            $.myAlert({
                title: '提示',
                content: '复制失败，请手动复制。链接为：' + e.text
            });
        });
    })
</script>