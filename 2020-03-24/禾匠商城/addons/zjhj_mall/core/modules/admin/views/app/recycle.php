<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 12:04
 */
/* @var \yii\web\View $this */
$this->title = '回收站';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$this->params['active_nav_link'] = 'admin/app/recycle';
?>
<table class="table bg-white">
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <?php if (count($list) == 0) : ?>
        <tr>
            <td colspan="3" class="text-center p-5 text-muted">回收站暂无内容</td>
        </tr>
    <?php endif; ?>
    <?php foreach ($list as $item) : ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->name ?></td>
            <td>
                <a class="recycle-btn"
                   href="<?= $url_manager->createUrl(['admin/app/set-recycle', 'id' => $item->id, 'action' => 0,]) ?>">恢复</a>
                <span>|</span>
                <a class="delete-btn"
                   href="<?= $url_manager->createUrl(['admin/app/delete', 'id' => $item->id]) ?>">永久删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(document).on("click", ".delete-btn", function () {
        var href = $(this).attr("href");
        $.myConfirm({
            content: "确认删除小程序？此操作将不可恢复！",
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
        $.myConfirm({
            content: "确认恢复小程序？",
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