<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/1/21
 * Time: 16:38
 */
$this->title = '';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-2">
            <a class="btn btn-primary batch" data-content="是否处理数据"
               data-url="<?= Yii::$app->urlManager->createUrl('/mch/default/share-error')?>"
               href="javascript:">数据处理</a>
        </div>
        <table class="table table-bordered bg-white">
            <tr>
                <td>
                    <label class="checkbox-label" style="margin-right: 0px;">
                        <input type="checkbox" class="checkbox-all">
                        <span class="label-icon"></span>
                    </label>id
                </td>
                <td>订单信息</td>
                <td>用户信息</td>
                <td>异常信息</td>
            </tr>
            <?php foreach($list as $index => $value):?>
                <tr>
                    <td>
                        <label class="checkbox-label" style="margin-right: 0px;">
                            <input type="checkbox"
                                   class="checkbox-one"
                                   value="<?= $value['id']?>">
                            <span class="label-icon"></span>
                        </label>
                        <?= $value['id'] ?>
                    </td>
                    <td>
                        <div>订单号：<?= $value['order_no'] ?></div>
                        <div>下单时间：<?= date('Y-m-d H:i:s', $value['addtime']) ?></div>
                        <div>订单佣金：<?= $value['first_price'] ?></div>
                        <div>订单结算状态：<?= $value['is_price'] == 1 ? "<span class='text-danger'>订单已结算</span>" : "<span class='text-success'>订单未结算</span>" ?></div>
                    </td>
                    <td>
                        <div>下单用户：<?= $value['user']['nickname'] ?>(<?= $value['user']['id']?>)</div>
                        <div>下单时用户上级：<?= $value['user_log_parent'] ? $value['user_log_parent']['nickname'] . '(' . $value['user_log_parent']['id'] . ')' : '总店'?></div>
                        <div>订单中存储的用户上级：<?= $value['order_parent']['nickname'] ?>(<?= $value['order_parent']['id'] ?>)</div>
                        <div>订单中存储的用户上级可提现金额：<?= $value['order_parent']['price'] ?></div>
                    </td>
                    <td>
                        <?php if ($value['is_price'] == 1):?>
                            <div class="text-danger">订单佣金已结算</div>
                        <?php endif;?>
                        <div class="text-danger">订单存储的上级id与下单时用户的上级id不一致</div>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo \yii\widgets\LinkPager::widget([
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
            <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.checkbox-all', function () {
        var checked = $(this).prop('checked');
        $('.checkbox-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.checkbox-one', function () {
        var all = $('.checkbox-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_use = true;
            } else {
                is_all = false;
            }
        });
        if (is_all) {
            $('.checkbox-all').prop('checked', true);
        } else {
            $('.checkbox-all').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.batch', function () {
        var all = $('.checkbox-one');
        var is_all = true;
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选需要处理的选项"
            });
        }
    });
    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var group = [];
        var all = $('.checkbox-one');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                group.push($(all[i]).val());
            }
        });
        $.myPrompt({
            content: a.data('content'),
            confirm: function (e) {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        group: group,
                        type: a.data('type'),
                        token: e
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.myAlert({
                                content: res.msg
                            });
                        }
                    },
                    complete: function () {
                        $.myLoadingHide();
                    }
                });
            }
        })
    });
</script>

