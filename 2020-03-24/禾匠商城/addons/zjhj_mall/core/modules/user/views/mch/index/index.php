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
        height: 32rem;
    }

    .panel-4 {
        height: 28rem;
    }

    .panel-5 {
        height: 32rem;
    }

    .panel-6 {
        height: 12rem;
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

    @media (min-width: 1100px) {
        .panel-2-item > div {
            padding: 0 1rem;
        }
    }

    @media (min-width: 1300px) {
        .panel-2-item > div {
            padding: 0 2rem;
        }
    }

    @media (min-width: 1500px) {
        .panel-2-item > div {
            padding: 0 3.5rem;
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

    .panel .panel-header .tab-body {
        display: none;
    }

    .panel .panel-header .tab-body.active {
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

    .panel-6 .user-nickname,
    .panel-6 .user-money {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }

    .goodsNum,.orderNum{
        height: 4.5rem;
        width: 4.5rem; 
        margin: 0 auto;      
    }

    .card{
        height: 4.5rem;
        width: 4.5rem;
        background-color: black;
        color: white;
        text-align: center;
        margin: 0 auto;
    }

    .echart_hide{
        display: none;
    }

    .url-to {
        cursor: pointer;
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
    <div class="home-col col-md-4">
        <div class="panel panel-1" v-if="panel_1">
            <div class="panel-header">商城信息</div>
            <div class="panel-body">
                <div class="row">
                    <div class="text-center url-to goodsNum"
                         data-url="<?= $urlManager->createUrl(['user/mch/goods/index']) ?>">
                        <div style="font-size: 1.75rem">{{panel_1.goods_count}}</div>
                        <div>商品数</div>
                    </div>
                    <div class="text-center url-to orderNum"
                         data-url="<?= $urlManager->createUrl(['user/mch/order/index']) ?>">
                        <div style="font-size: 1.75rem">{{panel_1.order_count}}</div>
                        <div>订单数</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-col col-12 col-md-8">
        <div class="panel panel-2" v-if="panel_2">
            <div class="panel-body">
                <div class="row">
                    <div class="col-4 panel-2-item" flex="cross:center main:center">
                        <div flex="dir:left box:mean" class="w-100">
                            <div flex="cross:center">
                                <img class="mr-3 item-icon"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/mch-home/1.png">
                            </div>
                            <div style="text-align: center" class="url-to"
                                 data-url="<?= $urlManager->createUrl(['user/mch/goods/index']) ?>">
                                <div style="font-size: 1.75rem">{{panel_2.goods_zero_count}}</div>
                                <div>已售罄商品</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 panel-2-item" flex="cross:center main:center">
                        <div flex="dir:left box:mean" class="w-100">
                            <div flex="cross:center">
                                <img class="mr-3 item-icon"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/mch-home/2.png">
                            </div>
                            <div style="text-align: center" class="url-to"
                                 data-url="<?= $urlManager->createUrl(['user/mch/order/index', 'status' => 1]) ?>">
                                <div style="font-size: 1.75rem">{{panel_2.order_no_send_count}}</div>
                                <div>待发货订单</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 panel-2-item" flex="cross:center main:center">
                        <div flex="dir:left box:mean" class="w-100">
                            <div flex="cross:center">
                                <img class="mr-3 item-icon"
                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/mch-home/3.png">
                            </div>
                            <div style="text-align: center" class="url-to"
                                 data-url="<?= $urlManager->createUrl(['user/mch/order/refund', 'status' => 0]) ?>">
                                <div style="font-size: 1.75rem">{{panel_2.order_refunding_count}}</div>
                                <div>维权中订单</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-col col-md-6">
        <div class="panel panel-3 mb-3" v-if="panel_3">
            <div class="panel-header">
                <span>订单概述</span>
                <ul class="nav nav-right">
                    <li class="nav-item">
                        <a class="nav-link active day" href="javascript:">按日统计</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link big month" href="javascript:">按月统计</a>
                    </li>
                </ul>
            </div>
            <div class="panel panel-4 echarts_1" v-if="panel_3">
                <div class="panel-body">
                    <div id="echarts_1" style="height: 25rem;"></div>
                </div>
            </div>
            <div class="panel panel-4 echarts_2 echart_hide" v-if="panel_3">
                <div class="panel-body">
                    <div id="echarts_2" style="height: 25rem;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-col col-md-6">
        <div class="panel panel-5 mb-3" v-if="panel_5">
            <div class="panel-header">
                <span>商品销量排行</span>
                <ul class="nav nav-right">
                    <li class="nav-item">
                        <a class="nav-link active small" href="javascript:" data-tab="0">今日</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small" href="javascript:" data-tab="1">昨日</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small" href="javascript:" data-tab="2">最近7天</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small" href="javascript:" data-tab="3">最近30天</a>
                    </li>
                </ul>
            </div>
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
                        <tr v-if="show_panel_5.length==0">
                            <td colspan="3" class="text-center">暂无销售记录</td>
                        </tr>
                        <tr v-else v-for="(item,index) in show_panel_5">
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
            panel_5: null,
            show_panel_1: null,
            show_panel_2: null,
            show_panel_3: null,
            show_panel_5: null,
            panel_3_index: null
        }
    });
    $('#app').show();
    $.ajax({
        dataType: 'json',
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
            app.panel_5 = res.data.panel_5;
            app.panel_3_index = 'date';
            app.show_panel_3 = app.panel_3['date'][0];
            app.show_panel_5 = app.panel_5[0];

            echarts_1();

            window.onresize = function(e){
                echarts_1.resize();
                echarts_2.resize();
            }

        }
    });


    echarts_1 = function(){
            setTimeout(function () {
                echarts.init(document.getElementById('echarts_2')).dispose();
                var echarts_1 = echarts.init(document.getElementById('echarts_1'));
                let day = app.panel_3.date;
                let order_count = [];
                let order_goods_count = [];
                let order_price_average = [];
                let order_price_count = [];

                day.forEach(function(e){
                    order_count.push(e.order_count);
                    order_goods_count.push(e.order_goods_count);
                    order_price_average.push(e.order_price_average);
                    order_price_count.push(e.order_price_count);
                });
                var echarts_1_option = {
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    legend: {
                        data:['订单数量','成交额（元）','平均消费（元）','成交量（件）']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data : ['今日','昨日','最近一周','最近一月']
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'订单数量',
                            type:'bar',
                            data: order_count,
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                        },
                        {
                            name:'平均消费（元）',
                            type:'bar',
                            data: order_price_average,
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                        },
                        {
                            name:'成交额（元）',
                            type:'bar',
                            data: order_price_count,
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                        },
                        {
                            name:'成交量（件）',
                            type:'bar',
                            data: order_goods_count,
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                        }
                    ]
                };
                echarts_1.setOption(echarts_1_option);
                window.onresize = function(e){
                    echarts_1.resize();
                }

            }, 500);        
    }

    echarts_2 = function(){
        let month = app.panel_3.month;
        let monthOrder_count = [];
        let monthOrder_price_count = [];
        let monthOrder_price_average = [];
        let monthOrder_goods_count = [];
        let day_order_price_count = [];

        month.forEach(function(e){
            monthOrder_count.push(e.order_count);
            monthOrder_price_count.push(e.order_price_count);
            monthOrder_price_average.push(e.order_price_average);
            monthOrder_goods_count.push(e.order_goods_count);
            day_order_price_count.push(e.day_order_price_count);
        });
        setTimeout(function () {
            echarts.init(document.getElementById('echarts_1')).dispose();
            var echarts_2 = echarts.init(document.getElementById('echarts_2'));
            var echarts_2_option = {
                tooltip : {
                    trigger: 'axis',
                    axisPointer : { 
                        type : 'shadow' 
                    }
                },
                legend: {
                    data:['月成交订单数（件）','月成交额（元）','平均消费（元）','月成交量（件）','日均成交额（元）']
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'月成交订单数（件）',
                        type:'line',
                        data: monthOrder_count,
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                    },
                    {
                        name:'月成交额（元）',
                        type:'line',
                        data: monthOrder_price_count,
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                    },
                    {
                        name:'平均消费（元）',
                        type:'bar',
                        data: monthOrder_price_average,
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                    },
                    {
                        name:'月成交量（件）',
                        type:'bar',
                        data: monthOrder_goods_count,
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                    },
                    {
                        name:'日均成交额（元）',
                        type:'bar',
                        data: day_order_price_count,
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                    }

                ]
            };
            echarts_2.setOption(echarts_2_option);

            window.onresize = function(e){
                echarts_2.resize();
            }

        },500);
    }


    $(document).on('mouseenter', '.goodsNum', function (){
        $(".goodsNum").addClass("card");
    });
    $(document).on('mouseleave', '.goodsNum', function (){
        $(".goodsNum").removeClass("card");
    });
    $(document).on('mouseenter', '.orderNum', function (){
        $(".orderNum").addClass("card");
    });
    $(document).on('mouseleave', '.orderNum', function (){
        $(".orderNum").removeClass("card");
    });

    $(document).on('click', '.url-to', function () {
        window.location.href = $(this).data('url');
    });

    $(document).on('click', '.panel-5 .panel-header .nav-link.small', function () {
        $(this).parents('.panel').find('.nav-link.small').removeClass('active');
        var target = $(this).attr('data-tab');
        $(this).addClass('active');
        app.show_panel_5 = app.panel_5[target]
    });

    $(document).on('click', '.day', function () {
        echarts_1();
        $('.month').removeClass('active');
        $(this).addClass('active');

        $('.echarts_1').removeClass('echart_hide')
        $('.echarts_2').addClass('echart_hide')

    });
    $(document).on('click', '.month', function () {
        echarts_2();
        $('.day').removeClass('active');
        $(this).addClass('active');

        $('.echarts_2').removeClass('echart_hide')
        $('.echarts_1').addClass('echart_hide');
    });

</script>