<?php
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '参与详情';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>头像</th>
                    <th>所属平台</th>
                    <th>收支情况</th>
                    <th>提交步数</th>
                    <th>创建时间</th>
                </tr>
            </thead>
            <?php foreach ($list as $k=>$u) : ?>
                <tr>   
                    <td><?= $u['id']; ?></td>
                    <td>
                        <img src="<?= $u['step']['user']['avatar_url'] ?>" style="width: 34px;height: 34px;margin: -.6rem 0;">&nbsp&nbsp&nbsp<?= $u['step']['user']['nickname']; ?>
                    </td>
                    <td>
                        <?php if (isset($u['step']['user']['platform']) && intval($u['step']['user']['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($u['step']['user']['platform']) && intval($u['step']['user']['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-warning"><?= $u['status'] == 2 ? "-$u[step_currency]" : '' ?></span>
                        <span class="badge badge-info"><?= $u['status'] == 1 ? "+$u[step_currency]" : '' ?></span>
                    </td>
                    <td>
                        <?= $u['num'] ?>步
                    </td>
                    <td><?= $u['addtime']; ?></td>
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