<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 10:33
 */

/* @var $pagination yii\data\Pagination */

use yii\widgets\LinkPager;
use \app\models\Cash;

$urlManager = Yii::$app->urlManager;
$this->title = '提现列表';
$this->params['active_nav_group'] = 5;
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
$urlPlatform = Yii::$app->controller->route;
?>

<style>
    .table tbody tr td{
        vertical-align: middle;
    }
    .titleColor {
        color: #888888;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">

                    <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end', 'page', 'per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div>
                            <div flex="dir:left">
                                <div>
                                    <label class="col-form-label">下单时间：</label>
                                </div>
                                <div class="mr-2">
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
                                    <a href="javascript:" class="new-day btn btn-primary" data-index="7">近7天</a>
                                    <a href="javascript:" class="new-day btn btn-primary" data-index="30">近30天</a>
                                </div>
                            </div>
                            <div flex="dir:left" class="mt-3">
                                <div>
                                    <label class="col-form-label">来源平台：</label>
                                </div>
                                <div class="dropdown float-right mr-5">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php if ($_GET['platform'] === '1') :
                                            ?>支付宝
                                        <?php elseif ($_GET['platform'] === '0') :
                                            ?>微信
                                        <?php elseif ($_GET['platform'] == '') :
                                            ?>所有用户
                                        <?php else : ?>
                                        <?php endif; ?>
                                    </button>
                                    <div class="dropdown-menu" style="min-width:8rem">
                                        <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlPlatform]) ?>">所有用户</a>
                                        <a class="dropdown-item"
                                           href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 1]) ?>">支付宝</a>
                                        <a class="dropdown-item"
                                           href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 0]) ?>">微信</a>
                                    </div>
                                </div>
                                <div flex="dir:left">
                                    <input class="form-control mr-2"
                                           style="width: 80%;"
                                           placeholder="姓名/昵称"
                                           name="keyword"
                                           autocomplete="off"
                                           value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                                    <span class="input-group-btn mr-2"><button class="btn btn-primary">搜索</button></span>
                                </div>
                                <a class="btn btn-secondary export-btn ml-2"
                                   href="javascript:">批量导出</a>
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
                       href="<?= $urlManager->createUrl(['mch/share/cash']) ?>">全部</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 0 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(['mch/share/cash', 'status' => 0]) ?>">未审核<?= $count['count_1'] ? "(" . $count['count_1'] . ")" : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(['mch/share/cash', 'status' => 1]) ?>">待打款<?= $count['count_2'] ? "(" . $count['count_2'] . ")" : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 2 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(['mch/share/cash', 'status' => 2]) ?>">已打款<?= $count['count_3'] ? "(" . $count['count_3'] . ")" : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 3 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(['mch/share/cash', 'status' => 3]) ?>">无效<?= $count['count_4'] ? "(" . $count['count_4'] . ")" : null ?></a>
                </li>
            </ul>
        </div>
        <table class="table table-bordered bg-white">
            <tr>
                <td width="50px">ID</td>
                <td width="200px">基本信息</td>
                <td>账号信息</td>
                <td>提现信息</td>
                <td>状态</td>
                <td>申请时间</td>
                <td>操作</td>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['user_id'] ?></td>
                    <td data-toggle="tooltip" data-placement="top" title="<?= $value['nickname'] ?>">
                        <span style="width: 150px;display:block;white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <img src="<?= $value['avatar_url'] ?>" style="width: 30px;height: 30px;margin-right: 10px;">
                            <?= $value['nickname'] ?>
                            <?php if (isset($value['platform']) && intval($value['platform']) === 0): ?>
                                <span class="badge badge-success">微信</span>
                            <?php elseif (isset($value['platform']) && intval($value['platform']) === 1): ?>
                                <span class="badge badge-primary">支付宝</span>
                            <?php else: ?>
                                <span class="badge badge-default">未知</span>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td>
                        <div>
                            <?php if ($value['type'] == 0) : ?>
                                <div><span class="titleColor">姓名：</span><?= $value['name'] ?></div>
                                <span><span class="titleColor">微信号：</span><?= $value['mobile'] ?></span>
                                <span>提现到微信</span>
                            <?php elseif ($value['type'] == 1) : ?>
                                <div><span class="titleColor">姓名：</span><?= $value['name'] ?></div>
                                <span><span class="titleColor">支付账号：</span><?= $value['mobile'] ?></span>
                                <span>提现到支付宝</span>
                            <?php elseif ($value['type'] == 2) : ?>
                                <div><span class="titleColor">姓名：</span><?= $value['name'] ?></div>
                                <div><span class="titleColor">开户行：</span><?= $value['bank_name'] ?></div>
                                <span><span class="titleColor">银行卡号：</span><?= $value['mobile'] ?></span>
                                <span>提现到银行</span>
                            <?php elseif ($value['type'] == 3) : ?>
                                <span class="titleColor">余额提现</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div><span class="titleColor">用户申请提现金额：</span><?= $value['price'] ?></div>
                        <div><span class="titleColor">手续费：</span><?= $value['price'] - $value['money'] ?></div>
                        <div><span class="titleColor">实际打款金额：</span><?= $value['money'] ?></div>
                    </td>
                    <td>
                        <?php if ($value['pay_type'] != 1) : ?>
                            <?= Cash::$status[$value['status']] ?><?= ($value['status'] == 2) ? "(" . Cash::$type[$value['type']] . ")" : "" ?>
                            <?php if ($value['status'] == 5) : ?>
                                <span>已打款</span>
                            <?php endif; ?>
                        <?php else : ?>
                            <?= Cash::$status[$value['status']] ?><?= ($value['status'] == 2) ? "(微信自动打款)" : "" ?>
                        <?php endif; ?>
                    </td>
                    <td><?= date('Y-m-d H:i', $value['addtime']); ?></td>
                    <td>
                        <?php if ($value['status'] == 0) : ?>
                            <a class="btn btn-sm btn-primary del" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/share/apply', 'status' => 1, 'id' => $value['id']]) ?>"
                               data-content="是否通过申请？">通过</a>
                            <a class="btn btn-sm btn-danger del" href="javascript:"
                               data-url="<?= $urlManager->createUrl(['mch/share/apply', 'status' => 3, 'id' => $value['id']]) ?>"
                               data-content="是否驳回申请？">驳回</a>
                        <?php elseif ($value['status'] == 1) : ?>
                            <div>
                                <a class="btn btn-sm btn-danger del" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/share/apply', 'status' => 3, 'id' => $value['id']]) ?>"
                                   data-content="是否驳回申请？">驳回</a>
                            </div>
                            <div class="mt-2">
                                <a class="btn btn-sm btn-primary pay" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/share/confirm', 'status' => 2, 'id' => $value['id']]) ?>"
                                   data-content="是否确认打款？">确认打款</a>
                                <span class="titleColor">（微信支付自动打款）</span>
                            </div>
                            <div class="mt-2">
                                <a class="btn btn-sm btn-primary pay" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/share/confirm', 'status' => 4, 'id' => $value['id']]) ?>"
                                   data-content="是否确认打款？">手动打款</a>
                                <span class="titleColor">（线下打款）</span>
                            </div>
                        <?php endif; ?>
                    </td>
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
            <div class="text-muted">共<?= $pagination->totalCount ?>条数据</div>
        </div>
    </div>
</div>
<?= $this->render('/layouts/ss', [
    'exportList' => $exportList
]) ?>
<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        if (confirm(a.data('content'))) {
            a.btnLoading();
            $.ajax({
                url: a.data('url'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    } else {
                        $.myAlert({
                            content: res.msg
                        });
                        a.btnReset();
                    }
                }
            });
        }
        return false;
    });
</script>
<script>
    $(document).on('click', '.pay', function () {
        var a = $(this);
        var btn = $('.pay');
        if (confirm(a.data('content'))) {
            btn.btnLoading();
            $.ajax({
                url: a.data('url'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    } else {
                        $.myAlert({
                            content: res.msg
                        });
                        btn.btnReset();
                    }
                }
            });
        }
        return false;
    });
</script>