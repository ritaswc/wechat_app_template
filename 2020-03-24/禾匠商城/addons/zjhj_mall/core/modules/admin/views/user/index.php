<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 11:44
 */
/* @var \yii\web\View $this */
/* @var \app\models\Admin[] $list */
$this->title = '账户列表';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$return_url = $url_manager->createUrl(['admin/user/index']);
$this->params['active_nav_link'] = 'admin/user/index';
?>
<nav aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">账户列表</li>
    </ol>
</nav>
<table class="table bg-white">
    <thead>
    <tr>
        <th>ID</th>
        <th>账户</th>
        <th>可创建小程序数量</th>
        <th>有效期</th>
        <th>操作</th>
    </tr>
    </thead>
    <col style="width: 5%">
    <col style="width: 15%">
    <col style="width: 30%">
    <col style="width: 20%">
    <col style="width: 30%">
    <?php foreach ($list as $item) : ?>
        <tr>
            <td><?= $item->id ?></td>
            <td>
                <div><?= $item->username ?></div>
                <div class="text-muted fs-sm"><?= $item->remark ?></div>
            </td>
            <td><?= $item->app_max_count == 0 ? '无限制' : $item->app_max_count ?></td>
            <td><?= $item->expire_time == 0 ? '永久' : date('Y-m-d', $item->expire_time) ?></td>
            <td>
                <a class="mr-3" href="<?= $url_manager->createUrl(['admin/user/edit', 'id' => $item->id]) ?>">编辑</a>
                <?php if ($item->id != 1) : ?>
                    <a class="mr-3 modify-password"
                       href="<?= $url_manager->createUrl(['admin/user/modify-password', 'id' => $item->id]) ?>">修改密码</a>
                    <a class="delete"
                       href="<?= $url_manager->createUrl(['admin/user/delete', 'id' => $item->id]) ?>">删除</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]) ?>
<script>
    $(document).on("click", ".modify-password", function () {
        var href = $(this).attr("href");
        $.myPrompt({
            title: "修改密码",
            content: "请输入新密码：",
            confirm: function (val) {
                if (!val) {
                    $.myToast({
                        content: "密码不能为空",
                    });
                    return;
                }
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    type: "post",
                    dataType: "json",
                    data: {
                        _csrf: _csrf,
                        password: val,
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                        });
                    }
                });

            }
        });
        return false;
    });
    $(document).on("click", ".delete", function () {
        var href = $(this).attr("href");
        $.myConfirm({
            content: "确认删除此用户？此操作将不可恢复！",
            confirm: function () {
                $.myLoading({
                    title: "正在提交",
                });
                $.ajax({
                    url: href,
                    type: "post",
                    dataType: "json",
                    data: {
                        _csrf: _csrf,
                    },
                    success: function (res) {
                        $.myLoadingHide();
                        $.myToast({
                            content: res.msg,
                        });
                        location.reload();
                    }
                });

            }
        });
        return false;
    });
</script>