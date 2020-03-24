<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 * @var \yii\web\View $this
 */
$urlManager = Yii::$app->urlManager;
$this->title = '我的商城';
$this->params['active_nav_group'] = 0;
?>
<style>
    .home-row {
        margin-right: -.5rem;
        margin-left: -.5rem;
    }

    .home-row .home-col {
        padding-left: .5rem;
        padding-right: .5rem;
        margin-bottom: 1rem;
    }

    .panel-1 {
        height: 10rem;
    }

    .panel-2 {
        height: 10rem;
    }

    .panel-3 {
        height: 16rem;
    }

    .panel-4 {
        height: 22rem;
    }

    .panel-5 {
        height: 20rem;
    }

    .panel-6 {
        height: 26rem;
    }

    .panel-2 hr {
        border-top-color: #eee;
    }

    .panel-2-item {
        height: 8rem;
        border-right: 1px solid #eee;
    }

    .panel-2-item .item-icon {
        width: 42px;
        height: 42px;
    }

    .panel-2-item > div {
        padding: 0 0;
    }

    .panel-3 .nav-left{
         width: 50%;
         height: 3.5rem;
         line-height: 3.5rem;
         margin-left: 0.2rem;
    }

    .panel-1 .nav-left{
         width: 50%;
         height: 3.5rem;
         line-height: 3.5rem;
         margin-left: 0.2rem;
    }

    .panel-4 .nav-left{
         width: 50%;
         height: 3.5rem;
         line-height: 3.5rem;
         margin-left: 0.2rem;
    }

    .nav select{
        margin: 0.7rem 1rem;
        padding: 1px 0.5rem;
        width: 33%;
    }

    @media (min-width: 1100px) {
        .panel-2-item > div {
            padding: 0 0.4rem;
        }
    }

    @media (min-width: 1300px) {
        .panel-2-item > div {
            padding: 0 2rem;
        }
    }

    @media (min-width: 1500px) {
        .panel-2-item > div {
            padding: 0 3rem;
        }
    }

    @media (min-width: 1700px) {
        .panel-2-item > div {
            padding: 0 5rem;
        }
    }

    .panel-3-item {
        height: calc(13rem - 50px);
    }

    .panel .panel-body .tab-body {
        display: none;
    }

    .panel .panel-body .tab-body.active {
        display: block;
    }

    .panel-5 table {
        table-layout: fixed;
        margin-top: -1rem;
    }

    .panel-5 td:nth-of-type(2) div {
        width: 100%;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .panel-5 table th {
        border-top: none;
    }

    .panel-5 .table td, .panel-5 .table th {
        padding: .5rem;
    }

    .panel-5 .nav-left{
         width: 50%;
         height: 3.5rem;
         line-height: 3.5rem;
         margin-left: 0.2rem;
    }


    .panel-6 .nav-left{
         width: 50%;
         height: 3.5rem;
         line-height: 3.5rem;
         margin-left: 0.2rem;
    }

    .panel-6 .user-top-list {
        margin-left: -1rem;
        white-space: nowrap;
    }

    .panel-6 .user-top-item {
        display: inline-block;
        width: 75px;
        margin-left: 1rem;
    }

    .panel-6 .user-avatar {
        background-size: cover;
        width: 100%;
        height: 75px;
        background-position: center;
        margin-bottom: .2rem;
    }

    .panel-foot{
        margin-top: -4rem;
        margin-left: 6.8%;
    }

    .panel-foot ul{
        padding-left: 0;
    }

    .panel-foot li{
        width: 5%;
        height: 3rem;
        list-style: none;
        white-space:nowrap;
        float: left;
        margin-right: 5.3%;
    }

    .panel-foot li:last-child{
        margin-right: 0;
    }

    .panel-foot img{
        width: 100%;
    }

    .userNum,.goodsNum,.orderNum{
        height: 4.5rem;
        width: 4.5rem; 
        margin: 0 auto;
        cursor:pointer;       
    }

    .card{
        height: 4.5rem;
        width: 4.5rem;
        background-color: black;
        color: white;
        text-align: center;
        margin: 0 auto;
    }

    .panel-6 .user-nickname,
    .panel-6 .user-money {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }

    .loading_4{
        margin: 3rem auto;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        border: 5px solid #BEBEBE;
        border-left: 5px solid #498aca;
        animation: load 1s linear infinite;
        -moz-animation:load 1s linear infinite;
        -webkit-animation: load 1s linear infinite;
        -o-animation:load 1s linear infinite;
    }

    .loading_3{
        margin: 2rem auto;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        border: 5px solid #BEBEBE;
        border-left: 5px solid #498aca;
        animation: load 1s linear infinite;
        -moz-animation:load 1s linear infinite;
        -webkit-animation: load 1s linear infinite;
        -o-animation:load 1s linear infinite;
    }

    .loading_2{
        margin: 4rem auto;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        border: 5px solid #BEBEBE;
        border-left: 5px solid #498aca;
        animation: load 1s linear infinite;
        -moz-animation:load 1s linear infinite;
        -webkit-animation: load 1s linear infinite;
        -o-animation:load 1s linear infinite;
    }
    .loading{
        margin: 6rem auto;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        border: 5px solid #BEBEBE;
        border-left: 5px solid #498aca;
        animation: load 1s linear infinite;
        -moz-animation:load 1s linear infinite;
        -webkit-animation: load 1s linear infinite;
        -o-animation:load 1s linear infinite;
    }
    @-webkit-keyframes load
    {
        from{-webkit-transform:rotate(0deg);}
        to{-webkit-transform:rotate(360deg);}
    }
    @-moz-keyframes load
    {
        from{-moz-transform:rotate(0deg);}
        to{-moz-transform:rotate(360deg);}
    }
    @-o-keyframes load
    {
        from{-o-transform:rotate(0deg);}
        to{-o-transform:rotate(360deg);}
    }
    .url-to {
        cursor: pointer;
    }

    .toggle{
        display: none;
    }
</style>
<div class="row home-row" id="app" style="display: none">
    <div v-if="loading" class="col-sm-12 text-center" style="margin-top: 20rem;">
        <svg width='50px' height='50px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
             preserveAspectRatio="xMidYMid" class="uil-default">
            <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(0 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.8s' repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(45 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.7000000000000001s'
                         repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(90 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.6000000000000001s'
                         repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(135 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.5s' repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(180 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.4s' repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(225 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.30000000000000004s'
                         repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(270 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.2s' repeatCount='indefinite'/>
            </rect>
            <rect x='45' y='38' width='10' height='24' rx='4' ry='4' fill='#000000'
                  transform='rotate(315 50 50) translate(0 -30)'>
                <animate attributeName='opacity' from='1' to='0' dur='0.8s' begin='-0.1s' repeatCount='indefinite'/>
            </rect>
        </svg>
        <div class="text-muted">数据加载中</div>
    </div>

    <div class="home-col col-md-5">
        <div class="panel panel-1" v-if="panel_1">
            <div class="panel-header">
                <div class="nav nav-left">
                    <span>商城信息</span>
                    <select class="form-control panel_1" >
                      <option value="normal">普通订单</option>
                      <option v-for='plug in plugs' :value = 'plug.value'>{{ plug.name }}</option>
                    </select>
                </div>
            </div>
            <div class="loading_3 toggle"></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-4 text-center">
                        <div class="userNum text-center numUrl">
                            <div style="font-size: 1.75rem;width: 100%">{{ msg.user_count }}</div>
                            <div>用户数</div>
                        </div>

                    </div>
                    <div class="col-4 text-center">
                        <div class="goodsNum text-center numUrl">
                            <div style="font-size: 1.75rem;width: 100%">{{ msg.goods_count }}</div>
                            <div>商品数</div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="orderNum text-center numUrl">
                            <div style="font-size: 1.75rem;width: 100%">
                                {{ msg.order_count }}
                            </div>
                            <div>订单数</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-col col-md-7">
        <div class="panel panel-2" v-if="panel_2">
            <div class="panel-body" style="padding-top: 1rem;">
                <div class="loading_4 toggle"></div>
                <div class="row">
                    <div class="col-4 panel-2-item" flex="cross:center main:center">
                        <div flex="dir:left box:last" class="w-100">
                            <div flex="cross:center">
                                <img class="mr-3 item-icon"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/mch-home/1.png">
                            </div>
                            <div style="width: 100px;text-align: center" class="url-to goodsNum">
                                <div style="font-size: 1.75rem">{{refund.goods_zero_count}}</div>
                                <div>已售罄商品</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 panel-2-item" flex="cross:center main:center">
                        <div flex="dir:left box:last" class="w-100">
                            <div flex="cross:center">
                                <img class="mr-3 item-icon"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/mch-home/2.png">
                            </div>
                            <div style="width: 100px;text-align: center" class="url-to sendUrl">
                                <div style="font-size: 1.75rem">{{refund.order_no_send_count}}</div>
                                <div>待发货订单</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 panel-2-item" flex="cross:center main:center">
                        <div flex="dir:left box:last" class="w-100">
                            <div flex="cross:center">
                                <img class="mr-3 item-icon"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/mch-home/3.png">
                            </div>
                            <div style="width: 100px;text-align: center" class="url-to refundUrl">
                                <div style="font-size: 1.75rem">
                                    {{refund.order_refunding_count}}
                                </div>
                                <div>维权中订单</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-col col-md-6">
        <div class="panel panel-5 mb-3" v-if="panel_5">
            <div class="panel-header">
                <div class="nav nav-left">
                    <span>商品销量排行</span>                 
                </div>
                <ul class="nav nav-right goods_statistics">
                    <li class="nav-item">
                        <input hidden value="1">
                        <a class="nav-link active" href="javascript:" data-tab=".tab-1">今日</a>
                    </li>
                    <li class="nav-item">
                        <input hidden value="2">
                        <a class="nav-link" href="javascript:" data-tab=".tab-1">昨日</a>
                    </li>
                    <li class="nav-item">
                        <input hidden value="3">
                        <a class="nav-link" href="javascript:" data-tab=".tab-1">最近7天</a>
                    </li>
                    <li class="nav-item">
                        <input hidden value="4">
                        <a class="nav-link" href="javascript:" data-tab=".tab-1">最近30天</a>
                    </li>
                </ul>
            </div>
            <div class="loading toggle"></div>
            <div class="panel-body">
                <div class="tab-body tab-1 active">
                    <table class="table">
                        <col style="width: 10%">
                        <col style="width: 75%">
                        <col style="width: 15%">
                        <thead>
                        <tr>
                            <th>排名</th>
                            <th>商品名称</th>
                            <th class="text-center">成交数量</th>
                        </tr>
                        </thead>
                        <tr v-if="goods.length==0">
                            <td colspan="3" class="text-center">暂无销售记录</td>
                        </tr>
                        <tr v-else v-for="(item,index) in goods">
                            <td>{{index+1}}</td>
                            <td>
                                <div>{{item.name}}</div>
                            </td>
                            <td class="text-center">{{item.num}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel panel-4" v-if="panel_4">
            <div class="panel-header">
                <div class="nav nav-left">
                    <span>近七日交易走势</span>            
                </div>
            </div>
            <div class="panel-body">
                <div id="echarts_1" style="height:18rem;"></div>
            </div>
        </div>
    </div>
    <div class="home-col col-md-6">
        <div class="panel panel-3 mb-3" v-if="panel_3">
            <div class="panel-header">
                <div class="nav nav-left">
                    <span>订单概述</span>                 
                </div>
                <ul class="nav nav-right order_statistics">
                    <li class="nav-item">
                        <input hidden value="1">
                        <a class="nav-link active" href="javascript:" data-tab=".tab-1">今日</a>
                    </li>
                    <li class="nav-item">
                        <input hidden value="2">
                        <a class="nav-link" href="javascript:" data-tab=".tab-1">昨日</a>
                    </li>
                    <li class="nav-item">
                        <input hidden value="3">
                        <a class="nav-link" href="javascript:" data-tab=".tab-1">最近7天</a>
                    </li>
                    <li class="nav-item">
                        <input hidden value="4">
                        <a class="nav-link" href="javascript:" data-tab=".tab-1">最近30天</a>
                    </li>
                </ul>
            </div>
            <div class="loading_2 toggle"></div>
            <div class="panel-body">
                <div class="tab-body tab-1 active">
                    <div class="row" v-for="item in order">
                        <div class="col-sm-4 panel-3-item" flex="cross:center main:center">
                            <div class="text-center">
                                <div style="font-size: 1.75rem;color: #facf5b;">{{item.order_goods_count}}
                                </div>
                                <div class="">成交量（件）</div>
                                </div>
                        </div>
                        <div class="col-sm-4 panel-3-item" flex="cross:center main:center">
                            <div class="text-center">
                                <div style="font-size: 1.75rem;color: #facf5b;">{{item.order_price_count}}
                                </div>
                                <div class="">成交额（元）</div>
                            </div>
                        </div>
                        <div class="col-sm-4 panel-3-item" flex="cross:center main:center">
                            <div class="text-center">
                                <div style="font-size: 1.75rem;color: #facf5b;">{{item.order_price_average}}
                                </div>
                                <div class="">订单平均消费（元）</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-6" v-if="panel_6">
            <div class="panel-header">
                <div class="nav nav-left">
                    <span>用户购买力排行</span>
                </div>
            </div>
            <div class="panel-body">
                <div id="echarts_2" style="height:21rem;"></div>
                <div class="panel-foot" id="footer">
                    <ul>
                        <li v-for='avatar in avatars'>
                            <img :src="avatar.avatar">
                        </li>                        
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/echarts/echarts.min.js"></script>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            loading: true,
            panel_1: null,
            panel_2: null,
            panel_3: null,
            panel_4: null,
            panel_5: null,
            panel_6: null,
            avatars: null,
            goods: null,
            order : null,
            msg: null,
            refund :null,
            type: null,
            userUrl: `<?= $urlManager->createUrl('/mch/user/index') ?>`,
            goodsUrl: `<?= $urlManager->createUrl('/mch/goods/goods') ?>`,
            orderUrl: `<?= $urlManager->createUrl('/mch/order/index') ?>`,
            sendUrl: `<?= $urlManager->createUrl(['mch/order/index', 'status' => 1]) ?>`,
            refundUrl: `<?= $urlManager->createUrl(['mch/order/refund', 'status' => 0]) ?>`,
            buyPlug: <?=json_encode((array)$plug, JSON_UNESCAPED_UNICODE)?>,
            plugs:[]
        },
        created:function(){
            for(let i = 0;i < this.buyPlug.length;i++){
                switch(this.buyPlug[i]){
                    case 'miaosha':
                        this.plugs.push({value: 'miaosha', name: '秒杀订单'})
                        break;
                    case 'pintuan':
                        this.plugs.push({value: 'group', name: '拼团订单'})
                        break;
                    case 'book':
                        this.plugs.push({value: 'book', name: '预约订单'})
                        break;
                    case 'bargain':
                        this.plugs.push({value: 'bargain', name: '砍价订单'})
                        break;
                    case 'integralmall':
                        this.plugs.push({value: 'integralmall', name: '积分商城'})
                        break;
                    case 'mch':
                        this.plugs.push({value: 'mch', name: '多商户订单'})
                        break;
                }                     
            }
        }
    });
    $('#app').show();
    $(document).on('click', '.panel .panel-header .nav-link', function () {
        $(this).parents('.panel').find('.nav-link').removeClass('active');
        $(this).parents('.panel').find('.tab-body').removeClass('active');
        var target = $(this).attr('data-tab');
        $(this).addClass('active');
        $(this).parents('.panel').find(target).addClass('active');
    });

    $(document).on('click', '.goods_statistics .nav-link', function () {
        $('.loading').removeClass('toggle');
        $('.panel-5').children('.panel-body').addClass('toggle');
        var val = $('.goods_statistics .active').prev().val();
        if(app.type !== null){
                $.ajax({
                    url: '<?= $urlManager->createUrl('mch/store/stats')?>',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        sign: val,
                        type: 'goods',
                        name: app.type
                    },
                    success: function (res) {
                        $('.loading').addClass('toggle');
                        $('.panel-5').children('.panel-body').removeClass('toggle');
                        app.goods = res.data.panel_5.data_1
                        if (res.code != 0) {
                            $.alert({
                                content: res.msg,
                            });
                            return;
                        }
                    }
                })            
        }else{
        $.ajax({
            url: '<?= $urlManager->createUrl('mch/store/index')?>',
            method: 'GET',
            dataType: 'json',
            data: {
                sign: val,
                type: 'goods'
            },
            success: function (res) {
                $('.loading').addClass('toggle');
                $('.panel-5').children('.panel-body').removeClass('toggle');
                if (res.code != 0) {
                    $.alert({
                        content: res.msg,
                    });
                    return;
                }
                app.panel_5 = res.data.panel_5;
                app.goods = app.panel_5.data_1
            }
        })
    }
    });

    $(document).on('mouseenter', '.numUrl', function (){
        $(this).addClass("card");
    });
    $(document).on('mouseleave', '.numUrl', function (){
        $(this).removeClass("card");
    });

    $(document).on('mouseenter', '.url-to', function (){
        $(this).addClass("card");
    });
    $(document).on('mouseleave', '.url-to', function (){
        $(this).removeClass("card");
    });


    $(document).on('click', '.order_statistics .nav-link', function () {
        var val = $('.order_statistics .active').prev().val();
        $('.loading_2').removeClass('toggle');
        $('.panel-3').children('.panel-body').addClass('toggle');
        if(app.type !== null){
                $.ajax({
                    url: '<?= $urlManager->createUrl('mch/store/stats')?>',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        sign: val,
                        type: 'order',
                        name: app.type
                    },
                    success: function (res) {
                $('.loading_2').addClass('toggle');
                $('.panel-3').children('.panel-body').removeClass('toggle');
                        app.order = [res.data.panel_3.data_1]
                        if (res.code != 0) {
                            $.alert({
                                content: res.msg,
                            });
                            return;
                        }
                    }
                })            
        }else{
        $.ajax({
            url: '<?= $urlManager->createUrl('mch/store/index')?>',
            method: 'GET',
            dataType: 'json',
            data: {
                sign: val,
                type: 'order'
            },
            success: function (res) {
                $('.loading_2').addClass('toggle');
                $('.panel-3').children('.panel-body').removeClass('toggle');
                if (res.code != 0) {
                    $.alert({
                        content: res.msg,
                    });
                    return;
                }
            app.panel_3 = res.data.panel_3;
            app.order = [app.panel_3.data_1];

            }
        })
    }
    });

    $.ajax({
        dataType: 'json',
        data: {
            sign: 1
        },
        success: function (res) {
            app.loading = false;
            $.loadingHide();
            if (res.code != 0) {
                $.alert({
                    content: res.msg,
                });
                return;
            }
            app.panel_1 = res.data.panel_1;
            app.panel_2 = res.data.panel_2;
            app.panel_3 = res.data.panel_3;
            app.panel_4 = res.data.panel_4;
            app.panel_5 = res.data.panel_5;
            app.panel_6 = res.data.panel_6;
            app.goods = app.panel_5.data_1;
            app.refund = app.panel_2;
            app.msg = app.panel_1;
            app.avatars = app.panel_6;
            app.order = [app.panel_3.data_1];
            let person = [];
            let money = [];
            res.data.panel_6.forEach(function(e){
                person.push(e.nickname);
                money.push(e.money);
            });
            person.length = 10;
            for(let i = 0; i <person.length;i++){
                if (person[i] === undefined){
                    person[i] = '';
                }
            }
            money.length = 10;

            setTimeout(function () {
                var echarts_1 = echarts.init(document.getElementById('echarts_1'));
                var echarts_2 = echarts.init(document.getElementById('echarts_2'));
                echarts1(this.app.panel_4.date, this.app.panel_4.order_goods_data.data, this.app.panel_4.order_goods_price_data.data);
                echarts2(person,money);
                window.onresize = function(){
                    echarts_1.resize();
                    echarts_2.resize();
                    var label = document.getElementById("footer");
                }
            }, 500);
        }
    });

    $(document).on('click', '.userNum', function(){
        window.location.href = app.userUrl
    })

    $(document).on('click', '.orderNum', function(){
        window.location.href = app.orderUrl
    })

    $(document).on('click', '.goodsNum', function(){
        window.location.href = app.goodsUrl
    })

    $(document).on('click', '.sendUrl', function(){
        window.location.href = app.sendUrl
    })

    $(document).on('click', '.refundUrl', function(){
        window.location.href = app.refundUrl
    })

    var echarts1 = function(date, amount, price){
            var echarts_1 = echarts.init(document.getElementById('echarts_1'));
            setTimeout(function () {
                var echarts_1_option = {
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['成交量', '成交额']
                    },
                    grid: {
                        left: '0%',
                        right: '0%',
                        bottom: '5%',
                        containLabel: true
                    },
                    xAxis: {
                        data: date,
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name: '成交量',
                            type: 'line',
                            data: amount,
                        },
                        {
                            name: '成交额',
                            type: 'line',
                            data: price,
                        },
                    ]
                };
                echarts_1.setOption(echarts_1_option);
            }, 500);    
    };

    var echarts2 = function(person,money){
            var echarts_2 = echarts.init(document.getElementById('echarts_2'));
            setTimeout(function () {
                var echarts_2_option = {
                    color: ['#3398DB'],
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {
                            type : 'shadow'
                        }
                    },
                    grid: {
                        top: '10%',
                        left: '0%',
                        right: '0%',
                        bottom: '18%',
                        containLabel: true
                    },
                    xAxis : {
                        data: person,
                        axisLabel: {
                          formatter: function(value) {
                             var res = value;
                             if(res.length > 4) {
                                 res = res.substring(0, 3) + ".."
                             }
                             return res
                         }
                     }
                    },
                    yAxis : {
                        type : 'value'
                    },
                    series : [
                        {
                            name:'购物力',
                            type:'bar',
                            barWidth: '60%',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                            data: money
                        }
                    ]
                };
                echarts_2.setOption(echarts_2_option);
            }, 500);    
    };
</script>

<script>
    $(document).on('change', '.panel_1', function (){
        app.msg = [];
        app.refund = [];
        app.order = [];
        app.goods = [];
        // 商品销量排行
        $('.loading').removeClass('toggle');
        $('.panel-5').children('.panel-body').addClass('toggle');
        // 七日交易走势
        let echarts_1 = echarts.init(document.getElementById('echarts_1'));
        echarts_1.showLoading({
            text:'正在拼命加载中...'
        });
        let date = app.panel_4.date;
        let amount = [];
        let price = [];
        // 购买力排行榜
        let avatar = [];
        let person = [];
        let money = [];
        let echarts_2 = echarts.init(document.getElementById('echarts_2'));
        echarts_2.showLoading({
            text:'正在拼命加载中...'
        });
        // 订单概述
        $('.loading_2').removeClass('toggle');
        $('.panel-3').children('.panel-body').addClass('toggle');
        // 待发货订单数
        $('.loading_4').removeClass('toggle');
        $('.panel-2').children('.row').addClass('toggle');  
        // 商品信息
        $('.loading_3').removeClass('toggle');
        $('.panel-1').children('.panel-body').addClass('toggle');      
        let val = 1;
        let url = '<?= $urlManager->createUrl('mch/store/stats')?>';
        switch($(this).val()){
            case 'normal':
                app.type = null;

                app.goodsUrl= `<?= $urlManager->createUrl('/mch/goods/goods') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/order/index') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/order/index', 'status' => 1]) ?>`;
                app.refundUrl = `<?= $urlManager->createUrl(['mch/order/refund', 'status' => 0]) ?>`;
                //订单概述
                app.order = [app.panel_3.data_1]; 
                setTimeout(function(){
                    $('.loading_2').addClass('toggle');
                    $('.panel-3').children('.panel-body').removeClass('toggle');
                },1000);
                // 1秒后隐藏加载动画
                setTimeout(function(){
                    $('.loading_3').addClass('toggle');
                    $('.panel-1').children('.panel-body').removeClass('toggle');
                    app.msg = app.panel_1;                    
                },1000);
                setTimeout(function(){
                    $('.loading_4').addClass('toggle');
                    $('.panel-2').children('.panel-body').removeClass('toggle');
                    app.refund = app.panel_2;
                },1000);
                // 购买力排行榜
                setTimeout(function(){
                    echarts_2.hideLoading();
                    app.panel_6.forEach(function(e){
                        person.push(e.nickname);
                        money.push(e.money);
                    });
                    person.length = 10;
                    for(let i = 0; i <person.length;i++){
                        if (person[i] === undefined){
                            person[i] = '';
                        }
                    }
                    money.length = 10;
                    app.avatars = app.panel_6
                    echarts2(person, money);
                },1000);
                // 七日交易走势
                setTimeout(function(){
                    amount = app.panel_4.order_goods_data.data,
                    price = app.panel_4.order_goods_price_data.data;   
                    echarts1(date, amount, price);
                    echarts_1.hideLoading();                 
                },1000);
                // 商品销量排行
                app.goods = app.panel_5.data_1;
                setTimeout(function(){
                    $('.loading').addClass('toggle');
                    $('.panel-5').children('.panel-body').removeClass('toggle');
                },1000);
                break;
            case 'miaosha':
                app.type = 'ms';
                app.goodsUrl= `<?= $urlManager->createUrl('/mch/miaosha/goods/index') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/miaosha/order/index') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/miaosha/order/index', 'status' => 1]) ?>`;
                app.refundUrl = `<?= $urlManager->createUrl(['mch/miaosha/order/refund', 'status' => 0]) ?>`;
                break;
            case 'group':
                app.type = 'pt';
                app.goodsUrl= `<?= $urlManager->createUrl('/mch/group/goods/index') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/group/order/index') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/group/order/index', 'status' => 1]) ?>`;
                app.refundUrl = `<?= $urlManager->createUrl(['mch/group/order/refund', 'status' => 0]) ?>`;
                break;
            case 'book':
                app.type = 'yy';
                app.goodsUrl= `<?= $urlManager->createUrl('/mch/book/goods/index') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/book/order/index') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/book/order/index']) ?>`;
                app.refundUrl = `<?= $urlManager->createUrl(['mch/book/order/index', 'status' => 3]) ?>`;
                break;
            case 'bargain':
                app.type = 'kj';
                app.goodsUrl= `<?= $urlManager->createUrl('/mch/bargain/goods/goods') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/bargain/order/index') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/bargain/order/index', 'status' => 1]) ?>`;
                app.refundUrl = `#`;
                break;
            case 'integralmall':
                app.type = 'jf';
                app.goodsUrl= `<?= $urlManager->createUrl('/mch/integralmall/integralmall/goods') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/integralmall/integralmall/order') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/integralmall/integralmall/order', 'status' => 1]) ?>`;
                app.refundUrl = `#`;
                break;
            case 'mch':
                app.type = 'mch';
                app.goodsUrl= `<?= $urlManager->createUrl('/mch/mch/goods/goods') ?>`;
                app.orderUrl= `<?= $urlManager->createUrl('/mch/mch/order/index') ?>`;
                app.sendUrl = `<?= $urlManager->createUrl(['mch/mch/order/index', 'status' => 1]) ?>`;
                app.refundUrl = `#`;
                break;
        }
        if(app.type !== null){
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            data: {
                sign: val,
                name: app.type
            },
            success: function (res) {
                // 商品销量排行
                app.goods = res.data.panel_5.data_1;
                setTimeout(function(){
                    $('.loading').addClass('toggle');
                    $('.panel-5').children('.panel-body').removeClass('toggle');
                },1000);
                // 七日交易走势
                amount = res.data.panel_4.order_goods_data.data;
                price = res.data.panel_4.order_goods_price_data.data;
                echarts1(date, amount, price);
                echarts_1.hideLoading();
                // 排行榜
                app.avatars = res.data.panel_6
                res.data.panel_6.forEach(function(e){
                    person.push(e.nickname);
                    money.push(e.money);
                });
                person.length = 10;
                for(let i = 0; i <person.length;i++){
                    if (person[i] === undefined){
                        person[i] = '';
                    }
                }
                money.length = 10;
                echarts2(person, money);
                echarts_2.hideLoading();
                // 订单概述
                app.order = [res.data.panel_3.data_1];
                setTimeout(function(){
                    $('.loading_2').addClass('toggle');
                    $('.panel-3').children('.panel-body').removeClass('toggle');
                },1000);
                // 商城信息
                $('.loading_3').addClass('toggle');
                $('.panel-1').children('.panel-body').removeClass('toggle');
                app.msg = res.data.panel_1;
                // 待发货订单信息
                $('.loading_4').addClass('toggle');
                $('.panel-2').children('.row').removeClass('toggle');
                app.refund = res.data.panel_2;
                if (res.code != 0) {
                    $.alert({
                        content: res.msg,
                    });
                    return;
                }
            }
        })
        }
        $(".order_statistics").find(".active").removeClass('active');
        $(".order_statistics li:first").children('a').addClass('active');
        $(".goods_statistics").find(".active").removeClass('active');
        $(".goods_statistics li:first").children('a').addClass('active');
    });
</script>