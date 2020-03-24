<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/25
 * Time: 15:45
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '订单详情';
?>
<style>
    tr > td:first-child {
        text-align: right;
        width: 100px;
    }

    .orderProcess{
        margin-bottom: 1.5rem;
        width: 100%;
        height: 12rem;
        border: 1px solid #ECEEEF;
        position: relative;
        margin-left: 1.1rem;
        margin-right: 1.1rem;
    }

    table .orderProcess ul{
        padding-left: 1rem;
    }

    .orderWord{
        height: 3rem;
    }
    
    .over{
        color: green;
    }

    .noOver{
        color: #888888;
    }

    .orderProcess ul{
        list-style: none;
        position: absolute;
        top:50%;
        left: 50%;
        margin-top: -4rem;
        margin-left: -28rem;
        padding-left: 0;
    }
    ul li{
        float: left;
        text-align: center;
        width: 8rem;
    }

    .orderIcon .iconfont{
        font-size: 2rem;
    }

    li i{
        height: 3.8rem;
        line-height: 3.8rem;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><a href="<?=$urlManager->createUrl(['mch/integralmall/integralmall/order'])?>">订单列表</a> >> <?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div style="overflow-x: hidden">
                <div class="row">
                    <div class="orderProcess">
                        <ul>
                            <li>
                                <div>
                                    <div class="orderIcon"><i class="iconfont icon-xiadan over"></i></div>
                                    <div class="over">已下单</div>
                                </div>
                                <div class="orderWord over">
                                    <?= date('Y-m-d H:i:s', $order['addtime']) ?>
                                </div>                                          
                            </li>
                            <?php if ($order['is_delete'] == 1 || $order['is_cancel'] == 1) : ?>
                            <li class="orderWord over">
                                <i class="iconfont icon-dian"></i>
                                <i class="iconfont icon-dian"></i>
                                <i class="iconfont icon-dian"></i>
                                <i class="iconfont icon-dian"></i>
                                <i class="iconfont icon-jiantouyou"></i>
                            </li>
                            <li class="over">
                                <div class="orderIcon">
                                    <i class="iconfont icon-iconfontzhizuobiaozhun0262"></i>
                                </div>
                                <div>已取消</div>
                            </li>
                            <?php else : ?>
                                <?php if ($order['is_pay'] == 1) : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-jiantouyou"></i>
                                </li>
                                <?php else : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-jiantouyou noOver"></i>
                                </li>    
                                <?php endif; ?>
                                <li>
                                    <div>
                                    <?php if ($order['is_pay'] == 1) : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-shouye over"></i>
                                        </div>
                                        <div class="over">已付款</div> 
                                        <div class="orderWord over">
                                            <?= date('Y-m-d H:i:s', $order['pay_time']) ?>
                                        </div>    
                                    <?php else : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-shouye noOver"></i>
                                        </div>
                                        <div class="noOver">未付款</div>
                                    <?php endif; ?> 
                                    </div>                                  
                                </li>
                                <?php if ($order['is_send'] == 1) : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-jiantouyou"></i>
                                </li>
                                <?php elseif ($order['is_pay'] == 1) : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-jiantouyou noOver"></i>
                                </li>  
                                <?php else : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-jiantouyou noOver"></i>
                                </li>    
                                <?php endif; ?>
                                <li>
                                    <div>
                                    <?php if ($order['is_send'] == 1) : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-fahuo over"></i>
                                        </div>
                                        <div class="over">已发货</div>
                                        <div class="orderWord over"><?= date('Y-m-d H:i:s', $order['send_time']) ?></div>
                                    <?php else : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-fahuo noOver"></i>                             
                                        </div>    
                                        <div class="noOver">未发货</div>         
                                    <?php endif; ?>
                                    </div>                              
                                </li>
                                <?php if ($order['is_confirm'] == 1) : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-jiantouyou"></i>
                                </li>
                                <?php elseif ($order['is_send'] == 1) : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-jiantouyou noOver"></i>
                                </li>  
                                <?php else : ?>
                                <li class="orderWord over">
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-dian noOver"></i>
                                    <i class="iconfont icon-jiantouyou noOver"></i>
                                </li>    
                                <?php endif; ?>
                                <li>
                                    <div>
                                    <?php if ($order['is_confirm'] == 1) : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-icon-receive over"></i>
                                        </div>
                                        <div class="over">已收货</div>
                                        <div class="orderWord over"><?= date('Y-m-d H:i:s', $order['confirm_time']) ?></div>
                                    <?php else : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-icon-receive noOver"></i>
                                        </div>
                                        <div class="noOver">未收货</div>
                                    <?php endif; ?>
                                    </div>                            
                                </li>
                            <?php endif; ?>    
                        </ul>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td>订单号</td>
                                <td><?= $order['order_no'] ?></td>
                            </tr>
                            <tr>
                                <td>下单时间</td>
                                <td><?= date('Y-m-d H:i', $order['addtime']) ?></td>
                            </tr>
                            <tr>
                                <td>用户</td>
                                <td><?= $order['user']['nickname'] ?></td>
                            </tr>
                            <tr>
                                <td>支付方式</td>
                                <td>
                                    <?php if ($order['pay_type'] == 0) : ?>
                                        <span class="badge badge-default">未支付</span>
                                    <?php else :?>
                                        <span class="badge badge-success">线上支付</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>收货信息</td>
                                <td>
                                    <div>
                                        <span>收货人：<?= $order['name'] ?></span>
                                    </div>
                                    <div>
                                        <span>电话：<?= $order['mobile'] ?></span>
                                    </div>
                                    <?php if ($order['address']) : ?>
                                        <div>
                                            <span>收货地址：<?= $order['address'] ?></span>
                                        </div>
                                    <?php else : ?>
                                        <div>
                                            <span>收货方式：<span class="badge badge-warning mt-1">到店自提</span></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($order['express']) : ?>
                                <tr>
                                    <td>快递信息</td>
                                    <td>
                                        <div>
                                            <span>快递公司：<?= $order['express'] ?></span>
                                        </div>
                                        <div>
                                            <span>快递单号：<?= $order['express_no'] ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="2" style="text-align: center">订单金额</td>
                            </tr>
                            <tr>
                                <td>总金额<br>（含运费）</td>
                                <td><?= $order['total_price'] ?>元</td>
                            </tr>
                            <tr>
                                <td>运费</td>
                                <td>
                                    <?= $order['express_price'] ?>元
                                </td>
                            </tr>
                            <tr>
                                <td>实付金额</td>
                                <td><?= $order['pay_price'] ?>元</td>
                            </tr>
                            <tr>
                                <td>支付积分</td>
                                <td><?= $order['integral'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="3" style="text-align: center">商品信息</td>
                            </tr>
                                <tr>
                                    <td rowspan="4">商品</td>
                                    <td class="text-right">商品名</td>
                                    <td><?= $order['detail']['goods_name'] ?></td>
                                </tr>
                                <tr>
                                    <td>规格</td>
                                    <td>
                                        <div>
                                        <span class="text-danger">
                                            <?php $attr_list = json_decode($order['detail']['attr']); ?>

                                            <?php if (is_array($attr_list)) :
                                                foreach ($attr_list as $attr) : ?>
                                                <span class="mr-3"><?= $attr->attr_group_name ?>
                                                    :<?= $attr->attr_name ?></span>
                                                <?php endforeach;
                                                ;
                                            endif; ?>

                                        </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>数量</td>
                                    <td><?= $order['detail']['num'] ?></td>
                                </tr>
                                <tr>
                                    <td>小计</td>
                                    <td><?= $order['detail']['total_price'] ?>元</td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $('.btn-success').on('click', function () {
        var seller_comments = $("#seller_comments").val();
        var btn = $(this);
        btn.btnLoading("正在提交");
        var url = "<?=$urlManager->createUrl(['mch/order/seller-comments', 'order_id' => $order_id])?>";
        $.ajax({
            url: url,
            type: "get",
            data: {
                seller_comments: seller_comments,
            },
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    btn.text(res.msg);
                    window.location.href = "<?=$urlManager->createUrl(['mch/order/index'])?>";
                }
                if (res.code == 1) {
                    btn.text(res.msg);
                    btn.btnReset();
                }
            }
        });
    });

</script>
