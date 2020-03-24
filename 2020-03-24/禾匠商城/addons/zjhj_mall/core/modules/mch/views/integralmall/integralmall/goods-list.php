<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/15
 * Time: 9:12
 */
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$imgurl = Yii::$app->request->baseUrl;
$this->title = '商城商品列表';
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
        width: 3rem;
        height: 3rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header"><a href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods']) ?>">积分商品列表</a> >> <?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $status = ['已下架', '已上架'];
        ?>
        <div class="mb-3 clearfix">
            <div class="float-left">
                <div class="dropdown float-right ml-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= isset($_GET['cat']) ? $_GET['cat'] : '全部类型' ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                         style="max-height: 200px;overflow-y: auto">
                        <a class="dropdown-item" href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-list']) ?>">全部类型</a>
                        <?php if ($cat_list != null) : ?>
                            <?php foreach ($cat_list as $index => $value) : ?>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl(array_merge(['mch/integralmall/integralmall/goods-list'], $_GET, ['cat' => $value])) ?>"><?= $value ?></a>
                            <?php endforeach; ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="float-right">
                <form method="get">
                    <?php $_s = ['keyword','page','per-page'] ?>
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
                <th style="text-align: left;width:5%;">
                    <label class="checkbox-label">
                        <input type="checkbox" class="goods-all">
                        <span class="label-icon"></span>
                        <span class="label-text">ID</span>
                    </label>
                </th>
                <th class="text-left">商品名称</th>
                <th>商品图片</th>
                <th>售价</th>
                <th>库存</th>
                <th>状态</th>
                <th>已出售量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($list != null) : ?>
                <?php foreach ($list as $index => $goods) : ?>
                <tr>
                    <td class="nowrap" style="text-align: left;" data-toggle="tooltip"
                        data-placement="top" title="<?=$goods->id?>">
                        <label class="checkbox-label">
                            <input data-num="<?= $goods->num ?>" type="checkbox"
                                   class="goods-one"
                                   value="<?= $goods->id ?>">
                            <span class="label-icon"></span>
                            <span class="label-text"><?= $goods->id ?></span>
                        </label>
                    </td>
                    <td class="text-left ellipsis" data-toggle="tooltip"
                        data-placement="top" title="<?=$goods->name?>"><?= $goods->name ?></td>
                    <td class="p-0" style="vertical-align: middle" hidden>
                        <div class="goods-pic" style="background-image: url(<?= $goods->getGoodsPic(0)->pic_url ?>)"></div>
                    </td>
                    <td class="p-0" style="vertical-align: middle">
                        <div class="goods-pic" style="background-image: url(<?= $goods->getGoodsCover() ?>)"></div>
                    </td>
                    <td class="nowrap text-danger"><?= $goods->price ?></td>
                    <td class="nowrap">
                        <?= $goods->num ?>
                    </td>
                    <td class="nowrap">
                        <?php if ($goods->status == 1) : ?>
                            上架
                        <?php else : ?>
                            下架
                        <?php endif ?>
                    </td>
                    <td class="nowrap">
                        <?= $goods->virtual_sales ?>
                    </td>
                    <td class="nowrap">
                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal" onclick="add_integral(<?= $goods->id ?>);">加入积分商城</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif ?>
            </tbody>

        </table>
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

    <div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal"  data-backdrop="static">
        <div class="modal-dialog" style="display: none">
            <div class="modal-content">
                <div class="modal-header" style="height:40px;">
                    <h5 class="modal-title" id="myModalLabel">
                        设置
                    </h5>
                </div>
                <form class="form auto-form" method="post" autocomplete="off" data-return="<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods']) ?>">
                    <div class="modal-body" id="page">
                            商品类别：<div class="input-group short-row">
                                        <select class="form-control parent" name="model[cat_id]">
                                            <option value="">请选择分类</option>
                                            <?php if ($cat != null) : ?>
                                                <?php foreach ($cat as $value) : ?>
                                                    <option
                                                        value="<?= $value['id'] ?>" <?= $value['id'] == $goods['cat_id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif ?>
                                        </select>
                                    </div>
                            商品售价：<input type="number" class="form-control"
                                        name="model[price]" id="price" placeholder="不填表示0元" >
                            所需积分：<input type="number" step="1" class="form-control"
                                        name="model[integral]" min="1" id="integral" >
                            用户每日可兑换数：<input type="number" step="1" class="form-control"
                                    name="model[user_num]" min="1" id="user_num" >
                            商品排序：<input type="number" step="1" class="form-control"
                                    name="model[sort]" min="1" id="sort" value="100">
                            <div v-if="attr_group_list && attr_group_list.length>0">
                                商品规格：
                                <div class="attr-edit-block">
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <table class="table table-bordered attr-table">
                                                        <thead>
                                                        <tr>
                                                            <th v-for="(attr_group,i) in attr_group_list"
                                                                v-if="attr_group.attr_list && attr_group.attr_list.length>0">
                                                                {{attr_group.attr_group_name}}
                                                            </th>
                                                            <th>
                                                                <div class="input-group">
                                                                    <span>库存</span>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="input-group">
                                                                    <span>售价</span>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="input-group">
                                                                    <span>所需积分</span>
                                                                </div>
                                                            </th>
                                                            <th hidden>
                                                                <div class="input-group">
                                                                    <span>货号</span>
                                                                </div>
                                                            </th>
                                                            <th hidden>规格图片</th>
                                                        </tr>
                                                        </thead>
                                                        <tr v-for="(item,index) in checked_attr_list">
                                                            <td v-for="(attr,attr_index) in item.attr_list">
                                                                <input type="hidden"
                                                                       v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_id]'"
                                                                       v-bind:value="attr.attr_id">

                                                                <input type="hidden" style="width: 40px"
                                                                       v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_name]'"
                                                                       v-bind:value="attr.attr_name">

                                                                <input type="hidden" style="width: 40px"
                                                                       v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_group_name]'"
                                                                       v-bind:value="attr.attr_group_name">
                                                                <span>{{attr.attr_name}}</span>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="hidden" min="0"
                                                                       step="1" style="width: 60px" v-bind:name="'attr['+index+'][num]'"
                                                                       v-bind:value="item.num">
                                                                <span  class="text-left">{{item.num}}</span>
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="number" min="0"
                                                                       step="0.01" style="width: 70px"
                                                                       v-bind:name="'attr['+index+'][price]'"
                                                                       v-bind:value="0">
                                                            </td>
                                                            <td>
                                                                <input class="form-control form-control-sm" type="number" min="0"
                                                                       step="0.01" style="width: 70px"
                                                                       v-bind:name="'attr['+index+'][integral]'"
                                                                       v-bind:value="0">
                                                            </td>
                                                            <td hidden>
                                                                <input class="form-control form-control-sm" style="width: 100px"
                                                                       v-bind:name="'attr['+index+'][no]'"
                                                                       v-bind:value="item.no">
                                                            </td>
                                                            <td hidden>
                                                                <div class="input-group input-group-sm" v-bind:data-index="index">
                                                                    <input class="form-control form-control-sm" style="width: 40px"
                                                                           v-bind:name="'attr['+index+'][pic]'"
                                                                           v-model="item.pic">
                                                                <span class="input-group-btn">
                                                                <a class="btn btn-secondary upload-attr-pic" href="javascript:"
                                                                   data-toggle="tooltip"
                                                                   data-placement="bottom" title="上传文件">
                                                                    <span class="iconfont icon-cloudupload"></span>
                                                                </a>
                                                                </span>
                                                                <span class="input-group-btn">
                                                                    <a class="btn btn-secondary select-attr-pic" href="javascript:"
                                                                       data-toggle="tooltip"
                                                                       data-placement="bottom" title="从文件库选择">
                                                                        <span class="iconfont icon-viewmodule"></span>
                                                                    </a>
                                                                </span>
                                                                <span class="input-group-btn">
                                                                    <a class="btn btn-secondary delete-attr-pic" href="javascript:"
                                                                       data-toggle="tooltip"
                                                                       data-placement="bottom" title="删除文件">
                                                                        <span class="iconfont icon-close"></span>
                                                                    </a>
                                                                </span>
                                                                </div>
                                                                <img v-if="item.pic" v-bind:src="item.pic"
                                                                     style="width: 50px;height: 50px;margin: 2px 0;border-radius: 2px">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div class="text-muted fs-sm" style="color:red;">规格售价和积分为0表示保持原售价和所需积分</div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            <input type="hidden" v-bind:value="goods_info" name="model[goods]">
                            <input type="hidden" v-bind:value="goods_pic" name="model[goods_pic]">
                    </div>
                    <div class="modal-footer" style="height:40px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                        <a class="btn btn-primary auto-form-btn" href="javascript:">添加</a>
<!--                        <button type="button" class="btn btn-primary" id="add_goods">添加</button>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#page",
        data: {
            attr_group_list: false,
            checked_attr_list: false,
            goods_info:false,
            goods_pic:false,
        }
    });
    var AddMemberUrl = "<?= $urlManager->createUrl(['mch/integralmall/integralmall/goods-info']) ?>";
    function add_integral($id){
        $.ajax({
            url: AddMemberUrl,
            type: 'get',
            dataType: 'json',
            data: {
                id: $id,
            },
            success: function (res) {
                if (res.code == 0) {
                    app.attr_group_list = res.list.attr;
                    app.checked_attr_list = res.list.checked_attr_list;
                    app.goods_info = res.list.goods_info;
                    app.goods_pic = res.list.goods_pic;
                    $('.modal-dialog').css('display','');
                }else{
                    $.myAlert({
                        content: res.msg,confirm:function(e){
                            window.location.reload();
                        }
                    });
                }
            }
        });
    }
    $("#close").click(function(){
        $('.modal-dialog').css('display','none');
        app.attr_group_list = false;
        app.checked_attr_list = false;
        app.goods_info = false;
        app.goods_pic = false;
    });

</script>