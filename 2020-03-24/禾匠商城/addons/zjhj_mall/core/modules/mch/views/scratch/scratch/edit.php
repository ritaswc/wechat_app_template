<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '新增奖品';
?>
<div id="app">
    <div class="panel mb-3">
        <div class="panel-header"><?= $this->title ?></div>
        <div class="panel-body">
            <form class="scratch-form" autocomplete="off">
                <div class="form-body">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-label">
                                <input name="type" v-model="list['type']" value="1" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type" v-model="list['type']" value="2" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type" v-model="list['type']" value="3" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type" v-model="list['type']" value="4" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">状态</label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-label">
                                <input v-model="list['status']" 
                                    :checked="list['status']==0"
                                    value="0" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">关闭</span>
                            </label>
                            <label class="radio-label">
                                <input v-model="list['status']"
                                    :checked="list['status']==1"
                                    value="1" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row price-status" v-if="list['type']==1">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">金额</label>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input class="form-control" type="number" step="0.01" min="0" max="100000000" v-model="list['price']">
                                <span class="input-group-addon">元</span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row num-status" v-if="list['type']==3">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">积分数量</label>
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" step="1" min="1" max="100000000" v-model="list['num']">
                        </div>
                    </div>

                    <div class="form-group row coupon-status" v-if="list['type']==2">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class=" col-form-label required">优惠卷</label>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group short-row">
                                <select class="form-control parent" v-model="list['coupon_id']">
                                    <?php foreach ($coupons as $value) : ?>
                                        <option
                                        value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <a class="nav-link" target="_blank" href="<?= $urlManager->createUrl(['mch/coupon/edit']) ?>">新建</a>
                    </div>




                    <div class="form-group row gift-status" v-if="list['type']==4">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">赠品</label>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">             
                                <input class="form-control search-goods-name" v-model="list['gift']" readonly>
                                <span class="input-group-btn">
                                    <a href="javascript:" class="btn btn-secondary search-goods" data-toggle="modal" data-target="#searchGoodsModal">选择商品</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row  gift-status" v-if="list['type']==4">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">规格</label>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control parent" v-model="list['attr']">
                                <option v-for="(item,index) in attrs" :value="item['attr_list']">
                                    <template v-for="items in item['attr_list']">
                                        {{items['attr_group_name']}}:{{items['attr_name']}}  
                                    </template>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row stock-status">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">库存</label>
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" step="1" max="100000000" min="1" v-model="list['stock']">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                        </div>
                        <div class="col-9">
                            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                            <input type="button" class="btn btn-default ml-4" 
                                   name="Submit" onclick="javascript:history.back(-1);" value="返回">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                    <form action="<?= $urlManager->createUrl(['mch/pond/pond/search-goods']) ?>"
                          class="input-group  goods-search-form" method="get">
                        <input name="keyword" class="form-control" placeholder="商品名称">
                        <span class="input-group-btn">
                    <button class="btn btn-secondary submit-btn">查找</button>
                </span>
                    </form>
                    <div v-if="goodsList==null" class="text-muted text-center p-5">请输入商品名称查找商品</div>
                    <template v-else>
                        <div v-if="goodsList.length==0" class="text-muted text-center p-5">未查找到相关商品</div>
                        <template v-else>
                            <div class="goods-item row mt-3 mb-3" v-for="(item,index) in goodsList">
                                <div class="col-8">
                                    <div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis">
                                        {{item.name}}
                                    </div>
                                </div>
                                <div class="col-2 text-right">￥{{item.price}}</div>
                                <div class="col-2 text-right">
                                    <a href="javascript:" class="goods-select" v-bind:index="index">选择</a>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var app = new Vue({
        el: "#app",
        data: {
            goodsList: [],
            attrs:<?=json_encode((array)$attrs, JSON_UNESCAPED_UNICODE)?>,
            list:<?=json_encode((array)$list, JSON_UNESCAPED_UNICODE)?>,
        },
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
        $(document).on("click", ".goods-select", function () {
            var index = $(this).attr("index");
            var goods = app.goodsList[index];

            app.list['gift'] = goods.name;
            app.list['gift_id'] = goods.id;

            $.ajax({
                url: "<?=$urlManager->createUrl(['mch/pond/pond/attr'])?>",
                type: "get",
                dataType: "json",
                data: {
                        id:goods.id,
                    },
                success: function (res) {
                    if (res.code == 0) {
                        app.attrs = res.data.attr;
                        $("#searchGoodsModal").modal("hide");
                        $(".search-goods-name").val(goods.name);
                        $(".search-goods-id").val(goods.id);
                    }
                }
            });
            return false;
        });
</script>



<script>
    $(document).on('click', '.auto-form-btn', function () {
        var btn = $(this);
        btn.btnLoading(btn.text());

        var list = app.list;
        var content = '';
        switch(parseInt(list.type))
        {
            case 1:
                if(!list.price || list.price<=0){
                    content = '余额不能为空或必须为正数';
                }
            break;
            case 2:
                if(!list.coupon_id || list.coupon_id<=0){
                    content = '优惠卷不能为空';
                }
            break;
            case 3:
                if(!list.num || list.num<=0){
                    content = '积分数量不能为空或必须为正数';
                }
                if(list.num%1 !==0){
                    content = '积分数量必须为整数';
                }
            break;
            case 4:
                console.log(list,213);
                if(!list.gift_id || list.gift_id<=0){
                    content = '赠品不能为空';
                }
                if(!list.attr){
                    content = '规格不能为空'
                }
            break;
            default:
                content = '奖品选择不能为空';
        }
        if(list.stock===undefined){
            content = '库存不能为空'
        }
        if(content){
            $.myAlert({content: content});
            btn.btnReset();
            return;
        }
        $.ajax({ 
            type: 'post',
            dataType: 'json',
            data: {
                data:JSON.stringify(list),
                _csrf: _csrf
            },
            success: function (res) {
                if (res.code == 0) {
                    $.alert({
                        content: res.msg,
                        confirm: function () {
                            window.history.back()
                            setTimeout(function () {
                                btn.btnReset();
                            }, 30000);
                        }
                    });
                }
                if (res.code == 1) {
                    btn.btnReset();
                    $.alert({
                        content: res.msg,
                    });
                }
            },
            error: function (e) {
                btn.btnReset();
                $.alert({
                    title: '<span class="text-danger">系统错误</span>',
                    content: e.responseText,
                });
            }
        });
    });


</script>
