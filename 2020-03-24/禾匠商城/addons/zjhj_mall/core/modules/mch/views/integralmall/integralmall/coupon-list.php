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
$this->title = '选择优惠券';
?>
<div class="panel mb-3">
    <div class="panel-header"><a href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/coupon']) ?>">优惠券管理</a> >> <?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
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
                    <td><?= ($item->is_join == 1) ? "未加入" : "加入" ?></td>
                    <td><?= $item->sort ?></td>
                    <td>
                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal" onclick="add_integral(<?= $item['id'] ?>);">加入积分商城</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif ?>
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
    <div class="modal fades" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" id="myModal">
        <div class="modal-dialog" style="display: none;margin-top: 200px;">
            <div class="modal-content">
                <div class="modal-header" style="height:40px;">
                    <h5 class="modal-title" id="myModalLabel">
                        设置
                    </h5>
                </div>
                <div class="modal-body">
                    <div id="inputs">
                        售价：<input type="number" step="1" class="form-control"
                                  name="price" min="0" id="price" value="0">
                        所需积分：<input type="number" step="1" class="form-control"
                                    name="integral" min="1" id="integral" >
                        发放总数：<input type="number" step="1" class="form-control"
                                    name="total_num" min="1" id="total_num" >
                        每人限制兑换数量：<input type="number" step="1" class="form-control"
                                    name="user_num" min="1" id="user_num" >
                    </div>
                    <input type="hidden" value="" name="coupon_id" id="coupon_id">
                </div>
                <div class="modal-footer" style="height:40px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" id="member" onclick="member()">添加</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function add_integral(id){
        $("#coupon_id").val(id);
        $('.modal-dialog').css('display','');
    }
    $("#close").click(function(){
        $('.modal-dialog').css('display','none');
        $("#coupon_id").val('');
    });
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
        var btn = $("#member");

        btn.btnLoading("正在提交");
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
                    btn.text(res.msg);
                    window.location.reload();
                }else{
                    btn.text(res.msg);
                    window.location.reload();
                }
            }
        });
    }
</script>