<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 11:44
 */
/* @var \yii\web\View $this */
/* @var \app\models\AdminRegister[] $list */
$this->title = '注册审核列表';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$return_url = $url_manager->createUrl(['admin/user/index']);
$this->params['active_nav_link'] = 'admin/user/index';
?>
<nav aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">注册审核列表</li>
    </ol>
</nav>
<table class="table bg-white">
    <thead>
    <tr>
        <th>申请人</th>
        <th>申请理由</th>
        <th>操作</th>
    </tr>
    </thead>
    <col style="width: 40%">
    <col style="width: 40%">
    <col style="width: 20%">
    <?php foreach ($list as $item) : ?>
        <tr>
            <td>
                <div>帐户名：<?= $item->username ?></div>
                <div>手机号：<?= $item->mobile ?></div>
                <div>姓名/企业名：<?= $item->name ?></div>
                <div class="text-muted">时间：<?= date('Y-m-d H:i:s', $item->addtime) ?></div>
            </td>
            <td><?= $item->desc ?></td>
            <td>
                <a href="javascript:" class="register-option" data-status="1" data-id="<?= $item->id ?>">通过</a>
                <span>|</span>
                <a href="javascript:" class="register-option" data-status="2" data-id="<?= $item->id ?>">拒绝</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]) ?>
<script>
    $(document).on('click', '.register-option', function () {
        var status = $(this).attr('data-status');
        var id = $(this).attr('data-id');
        $.myConfirm({
            content: status == 1 ? '确认通过注册申请？' : '确认拒绝用户注册申请？',
            confirm: function () {
                $.myLoading();
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _csrf: _csrf,
                        id: id,
                        status: status,
                    },
                    success: function (res) {
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                location.reload();
                            }
                        });
                    },
                    complete: function () {
                        $.myLoadingHide();
                    }
                });
            }
        });
    });
</script>