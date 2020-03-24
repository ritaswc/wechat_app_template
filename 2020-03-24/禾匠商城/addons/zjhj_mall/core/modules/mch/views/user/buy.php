<?php

defined('YII_ENV') or exit('Access Denied');
use \app\models\User;
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '会员购买记录';
$this->params['active_nav_group'] = 4;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <form method="get">
                <?php $_s = ['keyword', 'date_start', 'date_end', 'page', 'per-page'] ?>
                <?php foreach ($_GET as $_gi => $_gv) :
                    if (in_array($_gi, $_s)) {
                        continue;
                    } ?>
                    <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                <?php endforeach; ?>
                <div flex="dir:left">
                    <div class="mr-3 ml-3">
                        <div class="form-group row">
                            <div>
                                <label class="col-form-label">下单时间：</label>
                            </div>
                            <div>
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
                            <div class="middle-center mr-2">
                                <a href="javascript:" class="new-day btn btn-primary ml-2" data-index="7">近7天</a>
                                <a href="javascript:" class="new-day btn btn-primary ml-2" data-index="30">近30天</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ml-1">
                    <div>
                        <div class="input-group">
                            <input class="form-control"
                                   placeholder="昵称"
                                   name="keyword"
                                   autocomplete="off"
                                   value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary ml-3 mr-4">筛选</button>
                        <a class="btn btn-secondary export-btn" href="javascript:">批量导出</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="text-danger"></div>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>订单号</th>
                <th>昵称</th>
                <th>支付金额</th>
                <th>购买前</th>
                <th>购买后</th>
                <th>支付日期</th>
            </tr>
            </thead>
            <?php foreach ($list as $v) : ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= $v['order_no'] ?></td>
                    <td>
                        <?= $v['nickname'] ?>
                        <?php if (isset($v['platform']) && intval($v['platform']) === 0): ?>
                            <span class="badge badge-success">微信</span>
                        <?php elseif (isset($v['platform']) && intval($v['platform']) === 1): ?>
                            <span class="badge badge-primary">支付宝</span>
                        <?php else: ?>
                            <span class="badge badge-default">未知</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $v['pay_price'] ?></td>
                    <td><span class="badge badge-primary" style="font-size: 100%"><?= $v['current_name'] ?></span></td>
                    <td><span class="badge badge-primary" style="font-size: 100%"><?= $v['after_name'] ?></span></td>
                    <td><?= date('Y-m-d H:i:s', $v['pay_time']); ?></td>
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
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>
    </div>
</div>
<?= $this->render('/layouts/ss', [
    'exportList'=>$exportList
]) ?>
