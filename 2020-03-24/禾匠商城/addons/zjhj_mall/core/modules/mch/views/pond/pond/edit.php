<?php
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '九宫格抽奖设置';
$returnUrl = Yii::$app->request->referrer;
?>
<style>
    * {
        padding: 0;
        margin: 0;
    }
    .tabs li {
        float: left;
        margin-right: 8px;
        list-style: none;
    }
    
    .tabs .tab-link {
        display: block;
        width: 100px;
        height: 49px;
        text-align: center;
        line-height: 49px;
        background-color: #5597B4;
        color: #fff;
        text-decoration: none;
    }
    
    .tabs .tab-link.active {
        height: 47px;
        border-bottom: 2px solid #E35885;
        transition: .3s;
    }
    
    .cards {
        float: left;
    }
    
    .cards .tab-card {
        display: none;
    }
    
    .clearfix:after {
        content: "";
        display: block;
        height: 0;
        clear: both;
    }
        .status .nav-item, .bg-shaixuan {
        background-color: #f8f8f8;
    }

    .status .nav-item .nav-link {
        color: #464a4c;
        border: 1px solid #ddd;
        border-radius: 0;
    }

    .status .nav-item .nav-link.active {
        border-color: #ddd #ddd #fff;
        background-color: #fff;
    }
    .clearfix {
        zoom: 1;
    }
</style>
<div id="app" class="panel mb-3" id="page">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="page">
        <div class="panel-body">
        <div class="mb-4">
            <ul class="nav nav-tabs status">
                <li class="nav-item" v-for="(tab,index) in tabsName">
                    <a href="#" class="tab-link status-item nav-link" @click="tabsSwitch(index)" v-bind:class="{active:tab.isActive}">{{tab.name}}</a>
                </li>
            </ul>
            </div>

            <div class="cards">

                <div class="tab-card" style="display: block;">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type1" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type1" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type1" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type1" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type1" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>

                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type2" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type2" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type2" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type2" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type2" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>

                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type3" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type3" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type3" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type3" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type3" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>

                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type4" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type4" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type4" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type4" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type4" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>

                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type5" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type5" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type5" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type5" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type5" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>

                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type6" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type6" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type6" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type6" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type6" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>


                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type7" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type7" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type7" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type7" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type7" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>


                <div class="tab-card">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label required">奖品选择</label>
                        </div>
                        <div class="col-sm-10">
                            <label class="radio-label">
                                <input name="type8" v-model="form[tabIndex].type" value="5" v-on:click="types(5)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">谢谢参与</span>
                            </label>

                            <label class="radio-label">
                                <input name="type8" v-model="form[tabIndex].type" value="1" v-on:click="types(1)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">余额</span>
                            </label>
                            <label class="radio-label">
                                <input name="type8" v-model="form[tabIndex].type" value="2" v-on:click="types(2)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">优惠卷</span>
                            </label>
                            <label class="radio-label">
                                <input name="type8" v-model="form[tabIndex].type" value="3" v-on:click="types(3)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">积分</span>
                            </label>
                            <label class="radio-label">
                                <input name="type8" v-model="form[tabIndex].type" value="4" v-on:click="types(4)" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">赠品</span>
                            </label>
                        </div>
                    </div>
                    <template>
                        <?= $this->render('/pond/pond/tpl', ['coupon' => $coupon,'urlManager' => $urlManager]) ?>
                    </template>
                </div>
                <a style="margin-left:110px" class="btn btn-info" href="<?= $urlManager->createUrl('mch/pond/pond/index') ?>">返回</a>
                <a id="send" style="margin-left:10px" class="btn btn-primary submit-btn-1 auto-form-btn" href="javascript:">保存</a>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" id="searchGoodsModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="app" class="modal-dialog" role="document">
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
                form: <?=json_encode((array)$list, JSON_UNESCAPED_UNICODE)?>,
                attrs: <?=json_encode((array)$attrs, JSON_UNESCAPED_UNICODE)?>,
                tabsName: [{
                    name: "奖品一",
                    isActive: true
                }, {
                    name: "奖品二",
                    isActive: false
                }, {
                    name: "奖品三",
                    isActive: false
                },{
                    name: "奖品四",
                    isActive: false
                },{
                    name: "奖品五",
                    isActive: false
                },{
                    name: "奖品六",
                    isActive: false
                },{
                    name: "奖品七",
                    isActive: false
                },{
                    name: "奖品八",
                    isActive: false
                }],
                active: false,
                tabIndex:0,
                goodsList: null,
                attr:'',
                items:'',
            },
            methods: {
                tabsSwitch: function(tabIndex) {
                    app.tabIndex = tabIndex;

                    var tabCardCollection = document.querySelectorAll(".tab-card"),
                        len = tabCardCollection.length;
                
                    for(var i = 0; i < len; i++) {
                        tabCardCollection[i].style.display = "none";
                        this.tabsName[i].isActive = false;
                    }
                    this.tabsName[tabIndex].isActive = true;
                    tabCardCollection[tabIndex].style.display = "block";
                },
                types:function(e){
                    var index = app.tabIndex;
                    app.index = e;
                    app.form[index].type = e;
                },

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
        $(document).on("click", ".goods-select", function () {
            var index = $(this).attr("index");
            var goods = app.goodsList[index];
            for (var i in goods.attr) {
                goods.attr[i].miaosha_price = parseFloat(goods.attr[i].price == 0 ? goods.price : goods.attr[i].price);
                goods.attr[i].miaosha_num = goods.attr[i].num;
                goods.attr[i].sell_num = 0;
            }
            app.form[app.tabIndex].gift_id=goods.id;
            app.form[app.tabIndex].gift=goods.name;

            //console.log(goods.attr,12);
            //var attr = JSON.parse(goods.attr); 
            // attr.forEach(function(item,index,array){
            //     console.log(item);
            // })
                               
            $.ajax({
                url: "<?=$urlManager->createUrl(['mch/pond/pond/attr'])?>",
                type: "get",
                dataType: "json",
                data: {
                        id:goods.id,
                    },
                success: function (res) {
                    if (res.code == 0) {
                        console.log(res.data.attr);
                        Vue.set(app.attrs, app.tabIndex, res.data.attr);
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
    $(document).on("click", ".submit-btn-1", "click", function () {
        var btn = $(this);
        btn.btnLoading(btn.text());

        var content = '';
        app.form.forEach(function(item,index,array){
            if(!item.type || item.type<=0){
                var num = index+1;
                content = '奖品'+num+'未选择';
            }
            if(item.type==1){
                if(!item.price || item.price<=0){
                    var num = index+1;
                    content = '奖品'+num+'金额填写错误';
                }
            }
            if(item.type==2){
                if(!item.coupon_id || item.coupon_id<=0){
                    var num = index+1;
                    content = '奖品'+num+'优惠卷填写错误';
                }
            }
            if(item.type==3){
                if(!item.num || item.num<=0){
                    var num = index+1;
                    content = '奖品'+num+'积分填写错误';
                }
                if(item.num%1 !== 0){
                    var num = index+1;
                    content = '奖品'+num+'积分格式错误';
                }
             
            }
            if(item.type==4){
                if(!item.gift_id || item.gift_id<=0){
                    var num = index+1;
                    content = '奖品'+num+'商品填写错误';
                }
            }
        })
       
        if(content){
            $.myAlert({content: content});
            btn.btnReset();
            return;
        }
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/pond/pond/submit'])?>",
            dataType: "json",
            type:'post',
            data: {
                _csrf:_csrf,
                data:JSON.stringify(app.form),
            },
            success: function (res) {
                if(res.code==0){
                    $.alert({
                        content: res.msg,
                    });
                    window.location.reload();               
                }
                if(res.code==1){
                    alert(res.msg);
                }

            },
            complete:function(res){
                console.log(res,'complete');
            },
        });
        return false;
    });
</script>


<script>
/*---- 快速上传组件 改写 ----*/
$(document).on('click', '.upload-group .pond-select-file', function () {
    var btn = $(this);
    var group = btn.parents('.upload-group');
    var input = group.find('.file-input');
    var preview = group.find('.upload-preview');
    var preview_img = group.find('.upload-preview-img');
    $.upload_file({
        accept: group.attr('accept') || 'image/*',
        start: function () {
            btn.btnLoading(btn.text());
        },
        success: function (res) {
            btn.btnReset();
            if (res.code === 1) {
                $.alert({
                    content: res.msg
                });
                return;
            }
            Vue.set(app.form[app.tabIndex],'image_url', res.data.url);
            // input.val(res.data.url).trigger('change');
            // preview_img.attr('src', res.data.url);
        },
    });
});
$(document).on('click', '.upload-group .pond-upload-file', function () {
    var btn = $(this);
    var group = btn.parents('.upload-group');
    var input = group.find('.file-input');
    var preview = group.find('.upload-preview');
    var preview_img = group.find('.upload-preview-img');
    $.select_file({
        success: function (res) {
            Vue.set(app.form[app.tabIndex],'image_url', res.url);
            // input.val(res.url).trigger('change');
            // preview_img.attr('src', res.url);
        },
    });
});
$(document).on('click', '.upload-group .pond-delete-file', function () {
    var btn = $(this);
    var group = btn.parents('.upload-group');
    var input = group.find('.file-input');
    var preview_img = group.find('.upload-preview-img');

    Vue.set(app.form[app.tabIndex],'image_url', '');
    // input.val('').trigger('change');
    // preview_img.attr('src', '');
});
</script>
