<?php
defined('YII_ENV') or exit('Access Denied');

use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '中奖名单';
$this->params['active_nav_group'] = 4;
$urlPlatform = Yii::$app->controller->route;
?>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>商品ID</th>
                <th>头像</th>
                <th>昵称</th>
                <th>所属平台</th>
                <th>绑定手机号</th>
                <th>状态</th>
            </tr>
            </thead>
            <?php foreach ($list as $u) : ?>
                <tr>   
                    <td><?= $u['lottery_id']; ?></td>
                    <td>
                        <img src="<?= $u['user']['avatar_url'] ?>" style="width: 34px;height: 34px;margin: -.6rem 0;">
                    </td>
                    <td><?= $u['user']['nickname']; ?></td>
                    <td>
                        <?php if (isset($u['user']['platform']) && intval($u['user']['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($u['user']['platform']) && intval($u['user']['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $u['user']['binding']; ?></td>
                    <td>
                        <span class="badge badge-success"><?= $u['status'] == 2 ? '未兑换' : '' ?></span>
                        <span class="badge badge-primary"><?= $u['status'] == 3 ? '已兑换' : '' ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>