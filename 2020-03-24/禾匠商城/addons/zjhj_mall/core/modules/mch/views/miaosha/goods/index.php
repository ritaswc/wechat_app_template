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
$this->title = '商品列表';
$this->params['active_nav_group'] = 2;
?>
<style>
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
                <a href="<?= $urlManager->createUrl(['mch/miaosha/goods/edit']) ?>" class="btn btn-primary"><i
                            class="iconfont icon-playlistadd"></i>添加商品</a>
                <!--
                <a href="javascript:void(0)" class="btn btn-secondary batch"
                   data-url="<?= $urlManager->createUrl(['mch/miaosha/goods/batch']) ?>" data-content="是否批量上架"
                   data-type="0">批量上架</a>
                <a href="javascript:void(0)" class="btn btn-warning batch"
                   data-url="<?= $urlManager->createUrl(['mch/miaosha/goods/batch']) ?>" data-content="是否批量下架"
                   data-type="1">批量下架</a>
                <a href="javascript:void(0)" class="btn btn-danger batch"
                   data-url="<?= $urlManager->createUrl(['mch/miaosha/goods/batch']) ?>" data-content="是否批量删除"
                   data-type="2">批量删除</a>
                <a href="javascript:" data-toggle="modal" data-target="#attrAddModal" class="btn btn-primary">批量设置积分</a>-->

                <div class="dropdown float-right ml-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        批量设置
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                         style="max-height: 200px;overflow-y: auto">
                        <a href="javascript:void(0)" class="btn btn-secondary batch dropdown-item"
                           data-url="<?= $urlManager->createUrl(['mch/miaosha/goods/batch']) ?>" data-content="是否批量上架"
                           data-type="0">批量上架</a>
                        <a href="javascript:void(0)" class="btn btn-warning batch dropdown-item"
                           data-url="<?= $urlManager->createUrl(['mch/miaosha/goods/batch']) ?>" data-content="是否批量下架"
                           data-type="1">批量下架</a>
                        <a href="javascript:void(0)" class="btn btn-danger batch dropdown-item"
                           data-url="<?= $urlManager->createUrl(['mch/miaosha/goods/batch']) ?>" data-content="是否批量删除"
                           data-type="2">批量删除</a>
                        <a href="javascript:" data-toggle="modal" data-target="#attrAddModal" class="btn btn-primary dropdown-item">批量设置积分</a>
                    </div>
                </div>
                <div class="dropdown float-right ml-2">

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                         style="max-height: 200px;overflow-y: auto">
                        <a class="dropdown-item" href="<?= $urlManager->createUrl(['mch/miaosha/goods/goods']) ?>">全部类型</a>
                        <?php if (is_array($cat_list)) :
                            foreach ($cat_list as $index => $value) : ?>
                            <a class="dropdown-item"
                               href="<?= $urlManager->createUrl(array_merge(['mch/miaosha/goods/goods'], $_GET, ['cat' => $value])) ?>"><?= $value ?></a>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
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
        <table class="table table-bordered bg-white table-hover">
            <thead>
            <tr>
                <th style="text-align: center">
                    <label class="checkbox-label" style="margin-right: 0px;">
                        <input type="checkbox" class="goods-all">
                        <span class="label-icon"></span>
                    </label>
                </th>
                <th><span class="label-text">商品ID</span></th>
                <th>商品名称</th>
                <th>商品图片</th>
                <th>售价</th>
                <th>库存</th>
                <th>状态</th>
                <th>已出售量</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 4%">
            <col style="width: 7%">
            <col style="width: 22%">
            <col style="width: 8%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 5%">
            <col style="width: 13%">
            <tbody>
            <?php foreach ($list as $index => $goods) : ?>
                <tr>
                    <td class="nowrap" style="text-align: center">
                        <label class="checkbox-label" style="margin-right: 0px;">
                            <input data-num="<?= $goods->num ?>" type="checkbox"
                                   class="goods-one"
                                   value="<?= $goods->id ?>">
                            <span class="label-icon"></span>
                            
                        </label>
                    </td>
                    <td data-toggle="tooltip"
                        data-placement="top" title="<?=$goods->id?>">
                        <span class="label-text"><?= $goods->id ?></span></td>
                    <td class="text-left ellipsis" data-toggle="tooltip"
                        data-placement="top" title="<?=$goods->name?>">
                        <a data-index="<?= $index ?>" style="color: #ffffff;cursor: pointer;" class="btn btn-sm btn-primary edit-good-name">修改</a>
                        <?= $goods->name ?></td>
                    <td class="p-0" style="vertical-align: middle" hidden>
                        <div class="goods-pic" style="background-image: url(<?= $goods->cover_pic ?>)"></div>
                    </td>
                    <td class="p-0" style="vertical-align: middle">
                        <div class="goods-pic" style="background-image: url(<?= $goods->cover_pic ?>)"></div>
                    </td>
                    <td class="nowrap text-danger"><?= $goods->original_price ?>元</td>
                    <td class="nowrap">
                        <a href="<?= $urlManager->createUrl(['mch/miaosha/goods/edit', 'id' => $goods->id]) ?>#step3"><?= $goods->num ?></a>
                    </td>
                    <td class="nowrap">
                        <?php if ($goods->status == 1) : ?>
                            <span class="badge badge-success"><?= $status[$goods->status] ?></span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $goods->id ?>,'down');">下架</a>
                        <?php else : ?>
                            <span class="badge badge-default"><?= $status[$goods->status] ?></span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $goods->id ?>,'up');">上架</a>
                        <?php endif ?>
                    </td>
                    <td class="nowrap">
                        <?= $goods->virtual_sales ?>
                    </td>
                    <td class="nowrap">
                        <?= $goods->sort ?>
                    </td>
                    <td class="nowrap">
                        <!--<button type="button" class="btn btn-success" onclick="getGoodsQrcode(<?/*= $goods->id */?>//);">小程序码</button>-->

                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/miaosha/goods/edit', 'id' => $goods->id]) ?>">修改</a>
                        <a class="btn btn-sm btn-primary copy" data-clipboard-text="/pages/goods/goods?id=<?= $goods->id ?>"
                           href="javascript:" hidden>复制链接</a>
                        <a class="btn btn-sm btn-danger del"
                           href="<?= $urlManager->createUrl(['mch/miaosha/goods/del', 'id' => $goods->id]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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

<?= $this->render('/layouts/goods', [
    'goodsType' => 'MIAOSHA',
    'goodsList' => $goodsListArray
]) ?>

<script>
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
        } else {
            text = '下架';
        }

        var url = "<?= $urlManager->createUrl(['mch/miaosha/goods/goods-up-down']) ?>";
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
                        }, function () {
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
        //                 alert(res.msg);
        //                 if (res.return_url) {
        //                     location.href = res.return_url;
        //                 }
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
            url: "<?= Yii::$app->urlManager->createUrl(['mch/miaosha/goods/batch-integral']) ?>",
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