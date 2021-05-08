<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/5
 * Time: 9:58
 */
defined('YII_ENV') or exit('Access Denied');
use yii\widgets\LinkPager;

/* @var \app\models\User $user */
/* @var \yii\data\Pagination $pagination */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '用户消费排行';
$this->params['active_nav_group'] = 14;
$get = Yii::$app->request->get();
?>
<style>
    table {
        table-layout: fixed;
    }

    .goods-pic {
        width: 2rem;
        height: 2rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 1rem;
        border-radius: 2rem;
    }

    th {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .ellipsis {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td.nowrap {
        white-space: nowrap;
        overflow: hidden;
    }

    .table tbody tr td{
        vertical-align: middle;
    }

    td.data {
        text-align: center;
        vertical-align: middle;
    }

    td.data.active {
        color: #ff4544
    }

</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="float-left">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_GET['status'] == 2 ? '按订单数' : '按消费金额' ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                         style="max-height: 200px;overflow-y: auto">
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl(array_merge(['mch/data/user'], $_GET, ['status' => 1])) ?>">按消费金额</a>
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl(array_merge(['mch/data/user'], $_GET, ['status' => 2])) ?>">按订单数</a>
                    </div>
                </div>
            </div>
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
                        <input class="form-control" placeholder="用户昵称" name="keyword"
                               value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                    <span class="input-group-btn">
                    <button class="btn btn-primary">搜索</button>
                </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="fs-sm text-danger">注：消费金额和订单数（包括售后的订单）根据已付款的订单来计算；</div>
        <table class="table table-hover table-bordered bg-white">
            <thead>
            <tr>
                <th class="text-center">排行</th>
                <th>用户信息</th>
                <th>会员等级</th>
                <th>消费金额</th>
                <th>订单数</th>
            </tr>
            </thead>
            <col style="width: 10%;">
            <col style="width: 50%;">
            <col style="width: 20%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <tbody>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td class="data <?= ($index + 1 + ($pagination->page * $pagination->limit)) <= 3 ? 'active' : '' ?>"><?= $index + 1 + ($pagination->page * $pagination->limit) ?></td>
                    <td>
                        <div flex="dir:left box:first">
                            <div style="height: 2rem;">
                                <div class="goods-pic"
                                     style="background-image: url('<?= $value['avatar_url'] ?>');"></div>
                            </div>
                            <div flex="cross:center">
                                <div class="ellipsis"><?= $value['nickname'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="nowrap"><span class="badge badge-primary" style="font-size: 100%;"><?= $value['level_name'] ?></span></td>
                    <td class="nowrap"><?= $value['sales_price'] ?></td>
                    <td class="nowrap"><?= $value['sales_count'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
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
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>
    </div>
</div>