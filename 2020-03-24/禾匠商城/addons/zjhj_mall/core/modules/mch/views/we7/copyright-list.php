<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 11:17
 */
$urlManager = Yii::$app->urlManager;
$this->title = '版权设置';
$this->params['active_nav_group'] = 1;
?>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form method="get" class="form-inline mb-3">
            <?php foreach ($_GET as $name => $value) :
                if (!in_array($name, ['keyword'])) : ?>
                <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                <?php endif;
            endforeach; ?>
            <input placeholder="商城名称" class="form-control mr-3" name="keyword"
                   value="<?= \Yii::$app->request->get('keyword') ?>">
            <button class="btn btn-primary">查找</button>
            <div class="dropdown float-right ml-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    其它设置
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                     style="max-height: 200px;overflow-y: auto">
                    <a href="<?= $urlManager->createUrl(['mch/store/all-disabled', 'status' => 1]) ?>"
                       class="btn btn-secondary dropdown-item store-status"
                       data-type="0">所有商城禁用</a>
                    <a href="<?= $urlManager->createUrl(['mch/store/all-disabled', 'status' => 0]) ?>"
                       class="btn btn-warning dropdown-item store-status"
                       data-type="1">所有商城解除禁用</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>商城名称</th>
                <th>设置版权</th>
                <th>商城状态</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td>
                        <label class="checkbox-label">
                            <input type="checkbox" value="<?= $item['id'] ?>" class="check-item">
                            <input hidden type="text" value="<?= $item['status'] ?>" class="store-status">
                            <span class="label-icon"></span>
                            <span class="label-text"><?= $item['id'] ?></span>
                        </label>
                    </td>
                    <td><?= $item['name'] ?></td>
                    <td>
                        <a href="<?= $urlManager->createUrl(['mch/we7/copyright', 'id' => $item['id'], 'url' => $urlManager->createUrl(['mch/we7/copyright-list'])]) ?>">修改</a>
                    </td>
                    <td>
                        <a class="disabled-btn"
                           href="<?= $urlManager->createUrl(['mch/store/disabled', 'id' => $item['id'], 'status' => $item['status']]) ?>"><?= $item['status'] ? '解除禁用' : '禁用' ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4">
                    <label class="checkbox-label" style="line-height: 1.9rem">
                        <input type="checkbox" name="checkbox1[]" class="check-all">
                        <span class="label-icon"></span>
                        <span class="label-text">全选本页</span>
                    </label>
                    <a href="<?= $urlManager->createUrl(['mch/store/multi-select-disabled', 'status' => 1]) ?>"
                       class="set-status">禁用</a>
                    <a href="<?= $urlManager->createUrl(['mch/store/multi-select-disabled', 'status' => 0]) ?>"
                       class="set-status">解除禁用</a>
                </td>
            </tr>
            </tfoot>
        </table>

        <nav aria-label="Page navigation example">
            <?php echo \yii\widgets\LinkPager::widget([
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

<script>
    $(document).on("click", ".disabled-btn", function () {
        var href = $(this).attr("href");
        var aText = $(this).text();

        $.myConfirm({
            content: "确认将小程序" + aText + "?",
            confirm: function () {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                            callback: function () {
                                location.reload();
                            }
                        });
                    }
                });

            }
        });
        return false;
    });

    var app = new Vue({
        el: '#app',
        data: {
            storeIds: [],
        },
    });
    $(document).on('change', '.check-all', function () {
        $('.check-item').prop('checked', $(this).prop('checked'));
    });

    $(document).on('click', '.set-status', function () {
        var href = $(this).attr("href");
        var storeIds = [];
        $('.check-item').each(function (i) {
            if ($(this).prop('checked')) {
                storeIds.push($(this).val());
            }
        });
        app.storeIds = storeIds;

        $.myConfirm({
            content: "确定执行此操作?",
            confirm: function (res) {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    methods: "get",
                    dataType: "json",
                    data: {
                        storeIds: app.storeIds
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                            callback: function () {
                                location.reload();
                            }
                        });
                    }
                });

            }
        });
        return false;
    });

    $(document).on('click', '.store-status', function () {
        var href = $(this).attr("href");

        $.myConfirm({
            content: "确定执行此操作?",
            confirm: function (res) {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    methods: "get",
                    dataType: "json",
                    data: {
                        storeIds: app.storeIds
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                            callback: function () {
                                location.reload();
                            }
                        });
                    }
                });

            }
        });
        return false;
    });
</script>

