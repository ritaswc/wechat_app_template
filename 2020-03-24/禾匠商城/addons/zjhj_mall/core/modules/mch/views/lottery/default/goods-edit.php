<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '奖品编辑';
?>
<div id="app">
    <div class="panel mb-3">
        <div class="panel-header"><?= $this->title ?></div>
        <div class="panel-body"> 
            <form class="scratch-form" autocomplete="off">
                <div class="form-body">

                    <div class="form-group row gift-status">
                        <div class="form-group-label col-3 text-right">
                            <label class="col-form-label required">商品</label>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">             
                                <input class="form-control" v-model="list['goods_name']" readonly>
                                <span class="input-group-btn">
                                    <a href="javascript:" class="btn btn-secondary search-goods" data-toggle="modal" data-target="#searchGoodsModal">选择商品</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row gift-status">
                        <div class="form-group-label col-3  text-right">
                            <label class="col-form-label required">规格</label>
                        </div>
                        <div class="col-sm-5">
                            <select class="form-control parent" v-model="list['attr']">
                                <option v-for="(item,index) in attrs" :value="item['attr_list']">
                                    <template v-for="items in item['attr_list']">
                                        {{items['attr_group_name']}}:{{items['attr_name']}}  
                                    </template>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class="col-form-label required">开始日期</label>
                        </div>
                        <div class="col-sm-5"> 
                            <div class="input-group">
                                <input class="form-control"
                                       id="start_time"
                                       
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-3 text-right">
                            <label class="col-form-label required">结束日期</label>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input class="form-control"
                                       id="end_time"
                                      
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-group row stock-status">
                        <div class="form-group-label col-3 text-right">
                            <label class="col-form-label required">中奖数量</label>
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" type="number" step="1" max="100000000" min="1" v-model="list['stock']">
                        </div>
                    </div>

                    <div class="form-group row stock-status">
                        <div class="form-group-label col-3  text-right">
                            <label class="col-form-label">排序</label>
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" type="number" step="1" max="100000000" min="1" v-model="list['sort']">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">状态</label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-label">
                                <input v-model="list['status']" 
                                name="status"
                                    :checked="list['status']==0"
                                    value="0" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">关闭</span>
                            </label>
                            <label class="radio-label">
                                <input v-model="list['status']"
                                    name="status"
                                    :checked="list['status']==1"
                                    value="1" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                        </div>
                        <div class="col-sm-6">
                            <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        </div>
                    </div>
                </div> 
            </form>
        </div>
    </div>

    <!-- Modal 公用大转盘抽奖 -->
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
            app.list['goods_id'] = goods.id;
           
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
                        app.list['goods_name'] = goods.name;
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
    
        list['start_time'] = $('#start_time').val();
        list['end_time'] = $('#end_time').val();
        var content = '';


        if(content){
            $.myAlert({content: content});
            btn.btnReset();
            return;
        }
        var data = {
            d:list,
        };
        $.ajax({ 
            type: 'post',
            dataType: 'json',
            data: {
                data:JSON.stringify(data),
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

<script>
    (function () {
        $.datetimepicker.setLocale('zh');
        $('#start_time').datetimepicker({
            format: 'Y-m-d H:i',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#end_time').val() ? $('#end_time').val() : false
                })
            },
            timepicker: true,
        });
        $('#end_time').datetimepicker({
            format: 'Y-m-d H:i',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#begin_time').val() ? $('#begin_time').val() : false
                })
            },
            timepicker: true,
        });
    })();
</script>


