<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Cje
 */
defined('YII_ENV') or exit('Access Denied');
$this->title = '收支明细';
$urlManager = Yii::$app->urlManager;
$type = Yii::$app->request->get('type');
if ($type === '' || $type === null || $type == -1) {
    $type = -1;
}
?>
<style>
    .status-item.active {
        color: inherit;
    }
</style>
<div class="panel">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div class="mr-4">
                            <div class="form-group row">
                                <div class="">
                                    <label class="col-form-label">时间：</label>
                                </div>
                                <div class="">
                                    <div class="input-group">
                                        <input class="form-control" id="date_start" name="date_start"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_start']) ? trim($_GET['date_start']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_start" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                        <span class="middle-center input-group-addon" style="padding:0 4px">至</span>
                                        <input class="form-control" id="date_end" name="date_end"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_end']) ? trim($_GET['date_end']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_end" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group-label">
                                    <a href="javascript:" class="new-day btn btn-primary" data-index="7">近7天</a>
                                    <a href="javascript:" class="new-day btn btn-primary" data-index="30">近30天</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div flex="dir:left">
                        <div class="mr-4">
                            <div class="form-group">
                                <button class="btn btn-primary mr-4">筛选</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mb-4">
            <ul class="nav nav-tabs status">
                <li class="nav-item">
                    <a class="status-item nav-link <?= $type == -1 ? 'active' : '' ?>"
                       href="<?= $urlManager->createUrl(array_merge(['user/mch/account/log'])) ?>">全部</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $type == 1 ? 'active' : '' ?>"
                       href="<?= $urlManager->createUrl(array_merge(['user/mch/account/log'], ['type' => 1])) ?>">收入</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $type == 2 ? 'active' : '' ?>"
                       href="<?= $urlManager->createUrl(array_merge(['user/mch/account/log'], ['type' => 2])) ?>">支出</a>
                </li>
            </ul>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>收入/支出</th>
                <th>金额（元）</th>
                <th>说明</th>
                <th>时间</th>
            </tr>
            </thead>
            <?php if (!is_array($list) || !count($list)) : ?>
                <tr>
                    <td colspan="4" class="text-center text-muted p-5">暂无记录</td>
                </tr>
            <?php else : ?>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <td>
                            <?php if ($item['type'] == 1) : ?>
                                <span class="text-primary">收入</span>
                            <?php else : ?>
                                <span class="text-danger">支出</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['desc'] ?></td>
                        <td><?= date('Y-m-d H:i', $item['addtime']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

<?= $this->render('/layouts/ss') ?>