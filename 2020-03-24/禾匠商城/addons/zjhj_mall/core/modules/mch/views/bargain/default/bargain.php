<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/15
 * Time: 21:07
 */
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

/* @var \app\models\User $user */
/* @var \app\models\BargainOrder[] $list */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '砍价信息';
$condition = [];
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
?>
<style>
    .order-item {
        border: 1px solid transparent;
        margin-bottom: 1rem;
    }

    .order-item table {
        margin: 0;
    }

    .order-item:hover {
        border: 1px solid #3c8ee5;
    }

    .goods-item {
        margin-bottom: .75rem;
    }

    .goods-item:last-child {
        margin-bottom: 0;
    }

    .goods-pic {
        width: 5.5rem;
        height: 5.5rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 1rem;
    }

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .order-tab-1 {
        width: 40%;
    }

    .order-tab-2 {
        width: 20%;
        text-align: center;
    }

    .order-tab-3 {
        width: 10%;
        text-align: center;
    }

    .order-tab-4 {
        width: 20%;
        text-align: center;
    }

    .order-tab-5 {
        width: 10%;
        text-align: center;
    }

    .status-item.active {
        color: inherit;
    }

    .text-more {
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-break: break-all;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div>
                            <div class="input-group">
                                <input class="form-control"
                                       placeholder='商品名..'
                                       name="keyword"
                                       autocomplete="off"
                                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary">搜索</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mb-4">
            <ul class="nav nav-tabs status">
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == -1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge(['mch/bargain/default/bargain'])) ?>">全部</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 0 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge(['mch/bargain/default/bargain'], $condition, ['status' => 0])) ?>">
                        砍价中</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge(['mch/bargain/default/bargain'], $condition, ['status' => 1])) ?>">
                        砍价成功</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 2 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge(['mch/bargain/default/bargain'], $condition, ['status' => 2])) ?>">
                        砍价失败</a>
                </li>
            </ul>
        </div>
        <?php foreach ($list as $item) : ?>
            <div class="order-item">
                <table class="table table-bordered bg-white">
                    <tr>
                        <td colspan="3">
                            <span class="mr-4">发起时间：<?=$item->addtimeText?></span>
                            <span class="mr-4">
                                发起人：<?=$item->user->nickname?>
                                <?php if (isset($item->user->platform) && intval($item->user->platform) === 0): ?>
                                    <span class="badge badge-success">微信</span>
                                <?php elseif (isset($item->user->platform) && intval($item->user->platform) === 1): ?>
                                    <span class="badge badge-primary">支付宝</span>
                                <?php else: ?>
                                    <span class="badge badge-default">未知</span>
                                <?php endif; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-tab-1">
                            <div class="goods-item" flex="dir:left box:first">
                                <div class="fs-0">
                                    <div class="goods-pic"
                                         style="background-image: url('<?= $item->goods->cover_pic ?>')"></div>
                                </div>
                                <div class="goods-info">
                                    <div class="goods-name"><?= $item->goods->name ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="order-tab-2">
                            <div>售价：<?=$item->original_price?></div>
                            <div>最低价：<?=$item->min_price?></div>
                            <div>当前价：<?= $item->price?></div>
                        </td>
                        <td class="order-tab-3">
                            <div>砍价状态：
                                <?php if ($item->status == 1) : ?>
                                    <span class="badge badge-success"><?= $item->statusText?></span>
                                <?php elseif ($item->status == 2) : ?>
                                    <span class="badge badge-danger"><?= $item->statusText?></span>
                                <?php elseif ($item->status == 0) : ?>
                                    <span class="badge badge-default"><?= $item->statusText?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div flex="dir:left main:left" style="flex-wrap:wrap">
                                <?php foreach($item->orderUser as $k => $v):?>
                                    <div style="display: block;width: 6.5rem;">
                                        <div class="goods-pic"
                                             style="background-image: url('<?= $v->user->avatar_url ?>')"></div>
                                        <div class="text-more" style="width: 5.5rem;"><?= $v->user->nickname ?></div>
                                        <div class="text-more"><?= $v->price ?>元</div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
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
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>
    </div>
</div>
<?= $this->render('/layouts/ss'); ?>
<script></script>

