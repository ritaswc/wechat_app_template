<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/31
 * Time: 10:15
 */

use yii\widgets\LinkPager;

$this->title = '红包记录';
?>
<style>
    .hongbao-item:hover {
        background: #f6f6f6;
    }

    .hongbao-item:hover th,
    .hongbao-item:hover td {
        border-color: #e0e0e0;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <form class="form-inline d-inline-block float-right" style="margin: -.25rem 0" method="get">
            <input type="hidden" name="r" value="mch/fxhb/index/list">
            <div class="input-group">
                <input class="form-control" name="keyword" value="<?= $get['keyword'] ?>" placeholder="发起人">
                <span class="input-group-btn">
                    <button class="btn btn-secondary">查找</button>
                </span>
            </div>
        </form>
    </div>
    <div class="panel-body">
        <?php if (!$list || count($list) == 0) : ?>
            <div class="p-5 text-muted text-center">暂无记录</div>
        <?php else : ?>
            <table class="table table-bordered">
                <colgroup>
                    <col style="width: 33.33333%">
                    <col style="width: 33.33333%">
                    <col style="width: 33.33333%">
                </colgroup>
                <thead>
                <tr>
                    <th>参与者</th>
                    <th>获得奖金</th>
                    <th>参与时间</th>
                </tr>
                </thead>
            </table>
            <?php foreach ($list as $item) : ?>
                <table class="table table-bordered hongbao-item">
                    <colgroup>
                        <col style="width: 33.33333%">
                        <col style="width: 33.33333%">
                        <col style="width: 33.33333%">
                    </colgroup>
                    <tr>
                        <td colspan="3">
                        <span class="mr-4">发起人：
                        <span style="min-width: 100px;max-width: 250px;display: inline-block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;vertical-align: bottom">
                            <?= $item['nickname'] ?>
                            <?php if (isset($item['platform']) && intval($item['platform']) === 0): ?>
                                <span class="badge badge-success">微信</span>
                            <?php elseif (isset($item['platform']) && intval($item['platform']) === 1): ?>
                                <span class="badge badge-primary">支付宝</span>
                            <?php else: ?>
                                <span class="badge badge-default">未知</span>
                            <?php endif; ?>
                        </span>
                        </span>
                            <span class="mr-4">总金额：<?= number_format($item['coupon_total_money'], 2, '.', '') ?>元</span>
                            <span class="mr-4">发起时间：<?= date('Y-m-d H:i:s', $item['addtime']) ?></span>
                            <span class="mr-4">所需人数：<?= $item['user_num'] ?>人</span>
                            <span>状态：
                                <?php if ($item['is_expire']) : ?>
                                    <span class="badge badge-danger">已过期</span>
                                <?php elseif ($item['is_finish'] == 1) : ?>
                                    <span class="badge badge-success">已完成</span>
                                <?php else : ?>
                                    <span class="badge badge-default">进行中</span>
                                <?php endif; ?>
                        </span>
                        </td>
                    </tr>
                    <?php foreach ($item['sub_list'] as $sub_item) : ?>
                        <tr>
                            <td>
                                <img src="<?= $sub_item['avatar_url'] ?>"
                                     style="width: 25px;height: 25px;border-radius: 2px;margin:-.5rem .5rem -.5rem 0; ">
                                <span><?= $sub_item['nickname'] ?></span>
                            </td>
                            <td><?= $item['is_finish'] ? number_format($sub_item['coupon_money'], 2, '.', '') : '-' ?></td>
                            <td><?= date('Y-m-d H:i:s', $sub_item['addtime']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
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
                <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
            </div>
        <?php endif; ?>
    </div>
</div>