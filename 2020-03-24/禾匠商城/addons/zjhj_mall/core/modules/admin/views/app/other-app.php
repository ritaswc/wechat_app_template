<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 12:04
 */
/* @var \yii\web\View $this */
$this->title = '子账户的商城';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$this->params['active_nav_link'] = 'admin/app/other-app';
?>
<div class="mb-3">
    <form method="get" class="form-inline">
        <input type="hidden" name="r" value="admin/app/other-app">
        <input value="<?= $keyword ?>" placeholder="名称、账户" type="text" name="keyword"
               class="form-control form-control-sm mr-2">
        <button class="btn btn-primary btn-sm">查找</button>
    </form>
</div>
<table class="table bg-white">
    <thead>
    <tr>
        <th>ID</th>
        <th>账户</th>
        <th>名称</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <?php if (count($list) == 0) : ?>
        <tr>
            <td colspan="5" class="text-center p-5">
                <!--<a href="javascript:" class="add-app">添加小程序商城</a>-->
                <span class="text-muted">暂无相关小程序商城</>
            </td>
        </tr>
    <?php endif; ?>
    <?php foreach ($list as $item) : ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['username'] ?></td>
            <td>
                <?php if ($item['is_recycle'] == 0) : ?>
                    <a href="<?= $url_manager->createUrl(['admin/app/entry', 'id' => $item['id']]) ?>"><?= $item['name'] ?></a>
                <?php else : ?>
                    <?= $item['name'] ?>
                <?php endif; ?>
            </td>
            <td><?= $item['is_recycle'] == 0 ? '正常' : '回收站' ?></td>
            <td>
                <?php if ($item['is_recycle'] == 0) : ?>
                    <a class="recycle-btn"
                       data-desc="确认将小程序放进回收站？"
                       href="<?= $url_manager->createUrl(['admin/app/set-recycle', 'id' => $item['id'], 'action' => 1,]) ?>">回收</a>
                <?php else : ?>
                    <a class="recycle-btn"
                       data-desc="确认恢复小程序？"
                       href="<?= $url_manager->createUrl(['admin/app/set-recycle', 'id' => $item['id'], 'action' => 0,]) ?>">恢复</a>
                <?php endif; ?>
                <span>|</span>
                <a class="delete-btn" href="<?= $url_manager->createUrl(['admin/app/delete', 'id' => $item['id']]) ?>">删除</a>
                <?php if (Yii::$app->admin->id == 1) : ?>
                    <span>|</span>
                    <a href="javascript:" class="removal-btn" data-id="<?= $item['id'] ?>" data-name="<?= $item['name'] ?>">迁移</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php if ($pagination) : ?>
    <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
<?php endif; ?>
<?= $this->render('removal-modal'); ?>
<script>
    $(document).on("click", ".delete-btn", function () {
        var href = $(this).attr("href");
        var desc = $(this).attr('data-desc');
        $.myConfirm({
            content: desc,
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

    $(document).on("click", ".recycle-btn", function () {
        var href = $(this).attr("href");
        var desc = $(this).attr("data-desc");
        $.myConfirm({
            content: desc,
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
</script>