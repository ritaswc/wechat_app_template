<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:57
 */
/* @var $pagination yii\data\Pagination */
/* @var $setting \app\models\Setting */
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '后台充值记录';
$this->params['active_nav_group'] = 4;
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
?>
<style>
    .status-item.active {
        color: inherit;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="app">
        <table class="table table-bordered bg-white">
            <tr>
                <td width="50px">ID</td>
                <td width="200px">操作者</td>
                <td>说明</td>
                <td>昵称</td>
                <td>充值时间</td>
                <?php if ($type == 'money') : ?>
                    <td>备注说明</td>
                <?php endif; ?>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['operator'] ?></td>
                    <td><?= $value['content'] ?></td>
                    <td>
                        <?= $value['username'] ?>
                    </td>
                    <td><?= date('Y-m-d H:i', $value['addtime']); ?></td>
                    <?php if ($type == 'money') : ?>
                        <td>
                            <div>充值图片：</div>
                            <div>
                                <a href="<?= $value['pic_url'] ?>" target="_blank">
                                    <img src="<?= $value['pic_url'] ?>" style="max-width:100px;max-height:100px;">
                                </a>
                            </div>
                            <div>
                                <label>说明：<?= $value['explain'] ?></label>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                ]) ?>
            </nav>
        </div>
    </div>
</div>
