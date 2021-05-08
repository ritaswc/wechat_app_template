<?php
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '兑换记录';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>类型</th>
                    <th>活动/商品</th>
                    <th>收支情况(活力币)</th>
                    <th>当前步数</th>
                    <th>创建时间</th>
                </tr>
            </thead>
            <?php foreach ($log as $u) : ?>
                <tr>   
                    <td>
                        <?= $u['type'] == 0 ? '步数兑换' : '' ?>
                        <?= $u['type'] == 1 && $u['status'] == 1 ? '商品兑换(订单取消)' : '' ?>
                        <?= $u['type'] == 1 && $u['status'] == 2 ? '商品兑换' : '' ?>
                        <?= $u['type'] == 2 && $u['activity']['type']!=2 ? '步数挑战' : '' ?>
                        <?= $u['type'] == 2 && $u['activity']['type']==2 ? '步数挑战(活动解散)' : '' ?>
                    </td>
                    <td>
                        <?= $u['type'] == 1 ?  $u['order']['goods'][0]['name']: '' ?>
                        <?= $u['type'] == 2 ?  $u['activity']['name'] : '' ?>
                    </td>
                    <td>
                        <?php if($u['status'] == 1): ?>
                            <span class="btn btn-success"><?= +$u[step_currency] ?></span>
                        <?php elseif($u['status'] == 2): ?>
                            <span class="btn btn-danger"><?= -$u[step_currency] ?></span>
                        <?php endif;?>
                    </td>
                    <td><?= $u['num'];?>步</td>
                    <td><?= date('Y-m-d H:i:s', $u['create_time']); ?></td>
                </tr>
            <?php endforeach; ?>
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