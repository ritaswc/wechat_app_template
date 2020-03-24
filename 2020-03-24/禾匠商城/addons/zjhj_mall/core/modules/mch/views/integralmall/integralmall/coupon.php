<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 10:18
 */

use yii\widgets\LinkPager;

/* @var \app\models\Coupon[] $list */

$urlManager = Yii::$app->urlManager;
$this->title = '优惠券管理';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <a class="btn btn-primary mb-3" href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/coupon-list']) ?>">选择优惠券</a>
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
                        <input class="form-control" placeholder="优惠券名称" name="keyword"
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
                <th>ID</th>
                <th>优惠券名称</th>
                <th>最低消费金额（元）</th>
                <th>优惠方式</th>
                <th>兑换所需积分</th>
                <th>售价</th>
                <th>发放剩余总数</th>
                <th>单个用户可领数</th>
                <th>有效时间</th>
                <th>数量</th>
                <th>加入领券中心</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php if ($list != null) : ?>
                <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><?= $item->name ?></td>
                    <td><?= $item->min_price ?></td>
                    <td>
                        <div>优惠：<?= $item->discount_type == 2 ? $item->sub_price . '元' : '--' ?></div>
                        <!--<div>折扣：<?= $item->discount_type == 1 ? $item->discount : '--' ?></div>-->
                    </td>
                    <td><?= $item->integral ?></td>
                    <td><?= $item->price ?></td>
                    <td><?= $item->total_num ?></td>
                    <td><?= $item->user_num ?></td>
                    <td>
                        <?php if ($item->expire_type == 1) : ?>
                            <span>领取<?= $item->expire_day ?>天过期</span>
                        <?php else : ?>
                            <span><?= date('Y-m-d', $item->begin_time) ?>-<?= date('Y-m-d', $item->end_time) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>总数量：<?= ($item->total_count == -1) ? "无限制" : $item->total_count ?></div>
                        <div>剩余数量：<?= ($item->total_count == -1) ? "无限制" : ($item->total_count - $item->count) ?></div>
                    </td>
                    <td><?= ($item->is_join == 1) ? "不加入" : "加入" ?></td>
                    <td><?= $item->sort ?></td>
                    <td>
                        <button type="button" class="btn btn-success btn-xs"
                                data-toggle="modal" data-target="#myModal" onclick="add_integral(<?= $item->id ?>,<?= $item->price ?>,<?= $item->integral ?>,<?= $item->total_num ?>,<?= $item->user_num ?>);">编辑</button>
                        <button type="button" class="btn btn-danger delete-confirm" id="<?= $item['id'] ?>">删除</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif ?>
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
    <div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal" style="margin-top:200px;display: ;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="height:40px;">
                    <h5 class="modal-title" id="myModalLabel">
                        兑换所需积分
                    </h5>
                </div>
                <div class="modal-body">
                    <div id="inputs">
                        售价：<input type="number" step="1" class="form-control"
                                    name="price" min="0" id="price" value="">
                        所需积分：<input type="number" step="1" class="form-control"
                                    name="integral" min="1" id="integral" value="">
                        发放总数：<input type="number" step="1" class="form-control"
                                    name="total_num" min="1" id="total_num" value="" >
                        每人限制兑换数量：<input type="number" step="1" class="form-control"
                                        name="user_num" min="1" id="user_num" value="" >
                    </div>
                    <input type="hidden" value="" name="coupon_id" id="coupon_id">
                </div>
                <div class="modal-footer" style="height:40px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" id="member" onclick="member()">修改</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".delete-confirm", function () {
        var id = $(this).attr("id");
        var url = "<?= $urlManager->createUrl(['mch/integralmall/integralmall/in-coupon-del']) ?>";
        $.myConfirm({
            content: "确认删除？",
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    data: {
                        id:id
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        }else{
                            $.myLoadingHide();
                            $.myAlert({
                                content: res.msg,confirm:function(e){
                                    window.location.reload();
                                }
                            });
                        }
                    }
                });
            },
        });
        return false;
    });

    function add_integral($id,$price,$integral,$total_num,$user_num){
        $("#coupon_id").val($id);
        $("#price").val($price);
        $("#integral").val($integral);
        $("#total_num").val($total_num);
        $("#user_num").val($user_num);
    }
    var AddMemberUrl = "<?= $urlManager->createUrl(['mch/integralmall/integralmall/in-coupon-add']) ?>";
    function member(){
        var coupon_id = $("#coupon_id").val();
        var integral = $("#integral").val();
        var total_num = $("#total_num").val();
        var user_num = $("#user_num").val();
        var price = $("#price").val();
//        var arr = [
//            {a:integral,msg:'所需积分'},
//            {a:total_num,msg:'发放总数'},
//            {a:user_num,msg:'每人限制兑换数量'}
//        ];
//        arr.map(function(el){
//            if(el.a < 1){
//            $.myAlert({
//                content: el.msg
//            });
//            return
//            }
//        });
        if(price < 0){
            $.myAlert({
                content: "售价最低为0"
            });
            return
        }
        if(integral < 1){
            $.myAlert({
                content: "所需积分最低为1"
            });
            return
        }
        if(total_num < 1){
            $.myAlert({
                content: "发放总数最低为1"
            });
            return
        }
        if(user_num < 1){
            $.myAlert({
                content: "每人限制兑换数量最低为1"
            });
            return
        }
        var arr = {
            'coupon_id':coupon_id,
            'integral':integral,
            'total_num':total_num,
            'user_num':user_num,
            'price':price,
        };
        $.ajax({
            url: AddMemberUrl,
            type: 'post',
            dataType: 'json',
            data: {
                arr:arr,
                _csrf:_csrf
            },
            success: function (res) {
                if (res.code == 0) {
                    $('#myModal').css('display','none');
                    $.myAlert({
                        content: "修改成功",confirm:function(e){
                            window.location.reload();
                        }
                    });
                }else{
                    $('#myModal').css('display','none');
                    $.myAlert({
                        content: res.msg,confirm:function(e){
                            window.location.reload();
                        }
                    });
                }
            }
        });
    }
</script>