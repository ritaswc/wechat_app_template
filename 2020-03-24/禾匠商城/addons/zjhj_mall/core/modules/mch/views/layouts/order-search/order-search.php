<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

$urlManager = Yii::$app->urlManager;
$page_type = isset($page_type) ? $page_type : '';

$recycle_is_show = isset($recycle_is_show) ? $recycle_is_show : true;//清空回收站，默认显示
$offline_is_show = isset($offline_is_show) ? $offline_is_show : false;// 自提状态，默认不显示

$condition = ['status' => $_GET['status'], 'offline' => $_GET['offline'], 'platform' => $_GET['platform']];

?>

<div class="mb-3 clearfix">
    <div class="p-4 bg-shaixuan">
        <form method="get">
            <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end', 'page', 'per-page', 'offline'] ?>
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
                        <div class="middle-center">
                            <a href="javascript:" class="new-day btn btn-primary" data-index="7">近7天</a>
                            <a href="javascript:" class="new-day btn btn-primary" data-index="30">近30天</a>
                        </div>
                    </div>
                </div>
            </div>
            <div flex="dir:left">
                <div class="mr-4">
                    <div class="form-group row w-30">
                        <div class="col-4">
                            <select class="form-control" name="keyword_1">
                                <option value="1" <?= $_GET['keyword_1'] == 1 ? "selected" : "" ?>>订单号</option>
                                <option value="2" <?= $_GET['keyword_1'] == 2 ? "selected" : "" ?>>用户名</option>
                                <option value="4" <?= $_GET['keyword_1'] == 4 ? "selected" : "" ?>>用户ID</option>
                                <option value="5" <?= $_GET['keyword_1'] == 5 ? "selected" : "" ?>>商品名称</option>

                                <?php if ($page_type !== 'BOOK'): ?>
                                    <option value="3" <?= $_GET['keyword_1'] == 3 ? "selected" : "" ?>>收件人</option>
                                    <option value="6" <?= $_GET['keyword_1'] == 6 ? "selected" : "" ?>>收件人电话</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-8">
                            <input class="form-control"
                                   name="keyword"
                                   autocomplete="off"
                                   value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        </div>
                    </div>
                </div>
                <div class="mr-4">
                    <div class="form-group">
                        <button class="btn btn-primary mr-4">筛选</button>
                        <a class="btn btn-secondary export-btn"
                           href="javascript:">批量导出</a>

                        <?php if ($recycle_is_show): ?>
                            <a class="btn btn-secondary del"
                               href="javascript:"
                               data-url="<?= $urlManager->createUrl([$urlStr . '/delete-all']) ?>"
                               data-content="是否清空回收站？">清空回收站</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div flex="dir:left">
                <div class="mr-4">
                    <?php if ($user) : ?>
                        <span class="status-item mr-3">会员：<?= $user->nickname ?>的订单</span>
                    <?php endif; ?>
                    <?php if ($clerk) : ?>
                        <span class="status-item mr-3">核销员：<?= $clerk->nickname ?>的订单</span>
                    <?php endif; ?>
                    <?php if ($shop) : ?>
                        <span class="status-item mr-3">门店：<?= $shop->name ?>的订单</span>
                    <?php endif; ?>
                </div>
            </div>
            <div flex="dir:left">
                <label class="col-form-label">来源平台：</label>
                <div class="dropdown float-right">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if ($_GET['platform'] === '1') :
                            ?>支付宝
                        <?php elseif ($_GET['platform'] === '0') :
                            ?>微信
                        <?php else : ?>
                            全部订单
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu" style="min-width:8rem">
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl(array_merge([$urlPlatform], $condition, ['platform' => ''])) ?>">全部订单</a>
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl(array_merge([$urlPlatform], $condition, ['platform' => 1])) ?>">支付宝</a>
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl(array_merge([$urlPlatform], $condition, ['platform' => 0])) ?>">微信</a>
                    </div>
                </div>

                <?php if ($offline_is_show): ?>
                    <label class="col-form-label ml-5">自提状态：</label>
                    <div class="dropdown float-left">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if ($_GET['offline'] === '1'): ?>非自提
                            <?php elseif ($_GET['offline'] === '2'): ?>自提
                            <?php else: ?>所有订单<?php endif; ?>
                        </button>
                        <div class="dropdown-menu" style="min-width:8rem">
                            <a class="dropdown-item"
                               href="<?= $urlManager->createUrl(array_merge(['mch/group/order/index'], $condition, ['offline' => ''])) ?>">所有订单</a>
                            <a class="dropdown-item"
                               href="<?= $urlManager->createUrl(array_merge(['mch/group/order/index'], $condition, ['offline' => 1])) ?>">非自提</a>
                            <a class="dropdown-item"
                               href="<?= $urlManager->createUrl(array_merge(['mch/group/order/index'], $condition, ['offline' => 2])) ?>">自提</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>
