<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '入驻商审核';
$url_manager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link <?= !isset($get['review_status']) || $get['review_status'] == 0 ? 'active' : null ?>"
                   href="<?= $url_manager->createUrl(['mch/mch/index/apply', 'review_status' => 0]) ?>">待审核</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($get['review_status']) && $get['review_status'] == 1 ? 'active' : null ?>"
                   href="<?= $url_manager->createUrl(['mch/mch/index/apply', 'review_status' => 1]) ?>">已通过</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isset($get['review_status']) && $get['review_status'] == 2 ? 'active' : null ?>"
                   href="<?= $url_manager->createUrl(['mch/mch/index/apply', 'review_status' => 2]) ?>">未通过</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <?php if (!$list || count($list) == 0) : ?>
            <div class="p-5 text-center text-muted">暂无商户</div>
        <?php else : ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>店铺</th>
                    <th>用户</th>
                    <th>联系人</th>
                    <th>操作</th>
                </tr>
                </thead>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                            <img src="<?= $item['logo'] ?>"
                                 style="width: 25px;height: 25px;margin: -.5rem .5rem -.5rem 0">
                            <?= $item['name'] ?>
                        </td>
                        <td>
                            <img src="<?= $item['avatar_url'] ?>" style="width: 25px;height: 25px;margin: -.5rem .5rem -.5rem 0"><?= $item['nickname'] ?>
                            <?php if (isset($item['platform']) && intval($item['platform']) === 0): ?>
                                <span class="badge badge-success">微信</span>
                            <?php elseif (isset($item['platform']) && intval($item['platform']) === 1): ?>
                                <span class="badge badge-primary">支付宝</span>
                            <?php else: ?>
                                <span class="badge badge-default">未知</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['realname'] ?>（<?= $item['tel'] ?>）</td>
                        <td>
                            <?php if ($item['review_status'] == 0) : ?>
                                <a href="<?= $url_manager->createUrl(['mch/mch/index/edit', 'id' => $item['id'],]) ?>">审核</a>
                            <?php else : ?>
                                <a href="<?= $url_manager->createUrl(['mch/mch/index/edit', 'id' => $item['id'],]) ?>">详情</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
        <?php endif; ?>
    </div>
</div>
