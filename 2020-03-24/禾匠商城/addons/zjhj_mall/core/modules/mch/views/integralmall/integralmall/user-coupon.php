
<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/28
 * Time: 16:10
 */


use yii\widgets\LinkPager;

/* @var \app\models\Coupon[] $list */

$urlManager = Yii::$app->urlManager;
$this->title = '用户优惠券';
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="float-right">
                <form method="get" hidden>
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
                <th>用户</th>
                <th>最低消费金额（元）</th>
                <th>优惠方式</th>
                <th>兑换所需积分</th>
                <th>售价</th>
                <th>有效时间</th>
            </tr>
            </thead>
            <?php if ($list != null) : ?>
                <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['coupon']['name'] ?></td>
                    <td>
                        <?= $item['user']['nickname'] ?>
                        <?php if (isset($item['user']['platform']) && intval($item['user']['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($item['user']['platform']) && intval($item['user']['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $item['coupon']['min_price'] ?></td>
                    <td>
                        <div>优惠：<?= $item['coupon']['discount_type'] == 2 ? $item['coupon']['sub_price'] . '元' : '--' ?></div>
                        <!--<div>折扣：<?= $item->discount_type == 1 ? $item->discount : '--' ?></div>-->
                    </td>
                    <td><?= $item['coupon']['integral'] ?></td>
                    <td><?= $item['coupon']['price'] ?></td>
                    <td>
                        <?php if ($item->expire_type == 1) : ?>
                            <span>领取<?= $item->expire_day ?>天过期</span>
                        <?php else : ?>
                            <span><?= date('Y-m-d', $item['coupon']['begin_time']) ?> -- <?= date('Y-m-d', $item['coupon']['end_time']) ?></span>
                        <?php endif; ?>
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
</div>
