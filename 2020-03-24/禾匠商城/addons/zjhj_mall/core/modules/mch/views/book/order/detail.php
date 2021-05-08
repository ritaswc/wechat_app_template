<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 14:15
 */
defined('YII_ENV') or exit('Access Denied');


use yii\widgets\LinkPager;

/* @var \app\models\User $user */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '订单详情';
$this->params['active_nav_group'] = 3;
$order_id = $_GET['order_id'];
$urlStr = get_plugin_url();
?>
<style>
    tr > td:first-child {
        text-align: right;
        width: 100px;
    }

    tr td {
        word-wrap: break-word;
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
    <div class="panel-header"><?= $this->title ?></div>
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
                                            <i class="iconfont icon-shouye"></i>
                                        </div>
                                        <div class="noOver">未付款</div>
                                    <?php endif; ?> 
                                    </div>                                  
                                </li>

                                <?php if ($order['is_pay'] == 1) : ?>
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
                                    <?php if ($order['is_use'] == 1) : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-icon-receive over"></i>
                                        </div>
                                        <div class="over">已使用</div>
                                        <div class="orderWord over"><?= date('Y-m-d H:i:s', $order['use_time']) ?></div>
                                    <?php else : ?>
                                        <div class="orderIcon">
                                            <i class="iconfont icon-icon-receive noOver"></i>
                                        </div>
                                        <div class="noOver">未使用</div>
                                    <?php endif; ?>
                                    </div>                            
                                </li>
                            <?php endif; ?>    
                        </ul>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                       <table class="table table-bordered">
                            <tr>
                                <td colspan="2" style="text-align: center">售后详情</td>
                            </tr>
                            <?php if ($order['apply_delete'] == 1) : ?>
                                <tr>
                                    <td>售后状态</td>
                                    <td>
                                        <span class="mr-1">
                                            <?php if ($order['is_refund'] == 1) : ?>
                                                <span class="badge badge-success">已退款</span>
                                            <?php elseif($order['is_refund'] == 2) : ?>
                                                <span class="badge badge-danger">拒绝退款</span>
                                            <?php else : ?>
                                                <span class="badge badge-warning">申请退款中</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <td>订单号</td>
                                <td><?= $order['order_no'] ?></td>
                            </tr>
                            <tr>
                                <td>用户</td>
                                <td><?= $user['nickname'] ?></td>
                            </tr>
                            <tr>
                                <td>支付方式</td>
                                <td>
                                    <?php if ($order['pay_type'] == 2) : ?>
                                        <span class="badge badge-success">余额支付</span>
                                    <?php else : ?>
                                        <span>线上支付</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>总金额</td>
                                <td><?= $order['total_price'] ?>元</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">表单信息</td>
                            </tr>
                            
                            <?php foreach ($goods_list['form'] as $k => $v) : ?>
                                <?php if ($v['type'] == 'uploadImg') : ?>
                                    <tr>
                                        <td><?= $v['key'] ?></td>
                                        <td colspan="2">                
                                            <?php if ($v['value']) : ?>
                                                <a href="<?= $v['value'] ?>" target="_blank" style="width: 80px; height: 80px;">
                                                    <img src="<?= $v['value'] ?>" style="width: auto; height: auto; max-width: 100%;max-height: 100%;">
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td><?= $v['key'] ?></td>
                                        <td colspan="2"><?= $v['value'] ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </table> 
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered"> 
                            <tr>
                                <td colspan="3" style="text-align: center">商品信息</td>
                            </tr>
                            <tr>
                                <td class="text-right">商品名</td>
                                <td><?= $goods_list['name'] ?></td>
                            </tr>
                            <tr>
                                <td>规格</td>
                                <td>
                                    <div class="text-danger">
                                        <?php $attr_list = json_decode($order['attr']); ?>
                                        <?php if (is_array($attr_list)) :
                                            foreach ($attr_list as $attr) : ?>
                                            <span class="mr-3"><?= $attr->attr_group_name ?>
                                                :<?= $attr->attr_name ?></span>
                                        <?php endforeach;endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>商家备注</td>
                                <td colspan="2">
                                    <textarea id="seller_comments" name="seller_comments" cols="90"
                                     rows="5" 
                                     style="resize: none;width: 100%;"><?= $order['seller_comments'] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center">
                                    <button type="button" class="btn btn-success">确定</button>
                                </td>
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
        var url = "<?=$urlManager->createUrl([$urlStr.'/seller-comments', 'order_id' => $order_id])?>";
        $.ajax({
            url: url,
            type: "get",
            data: {
                seller_comments: seller_comments,
            },
            dataType: "json",
            success: function (res) {
                $.myAlert({
                    content:res.msg,
                    confirm:function(e){
                        if(res.code == 0){
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

</script>