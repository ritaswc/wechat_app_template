<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: wxf
 * Date: 2017/6/19
 * Time: 16:52
 */

$this->title = '员工列表';
$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span style="color: red;">员工登录入口：</span>
        <a href="<?= $adminUrl ?>" target="_blank"><?= $adminUrl ?></a>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link"
                   href="<?= $urlManager->createUrl('mch/permission/user/create') ?>">添加员工</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>账号</th>
                <th>昵称</th>
                <th>创建者</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item->username ?></td>
                    <td><?= $item->nickname ?></td>
                    <td>管理员：<?=!Yii::$app->admin->isGuest ? $item->creator->username : $item->creator->nickname ?></td>
                    <td><?= date('Y-m-d H:i:s', $item->addtime) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/permission/user/edit', 'id' => $item->id]) ?>">编辑</a>
                        <a class="btn btn-sm btn-danger article-delete"
                           href="<?= $urlManager->createUrl(['mch/permission/user/destroy', 'id' => $item->id]) ?>">删除</a>
                        <a class="btn btn-sm btn-success edit-password" data-id="<?= $item->id ?>" data-toggle="modal"
                           data-target="#editPasspord"
                           href="javascript:">修改密码</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="modal fade" id="editPasspord">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">修改密码</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label required">新密码</label>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control password" value="">
                            </div>
                        </div>

                        <input style="display: none;" class="user-id" name="id" value="">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                            </div>
                            <div class="col-sm-6">
                                <a class="btn btn-primary update-password" href="javascript:">保存</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".article-delete", function () {
        var href = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function (res) {
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    location.reload();
                                }
                            }
                        })

                    }
                });
            }
        });
        return false;
    });

    $(document).on("click", ".edit-password", function () {
        var userId = $(this).data('id');
        $('.user-id').val(userId)
    });

    $(document).on("click", ".update-password", function () {
        var href = '<?= $urlManager->createUrl(['mch/permission/user/update-password']) ?>';
        $.ajax({
            url: href,
            type: "post",
            data: {
                id: $('.user-id').val(),
                password: $('.password').val(),
                _csrf:_csrf
            },
            dataType: "json",
            success: function (res) {
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        if (res.code == 0) {
                            location.reload();
                        }
                    }
                })
            }
        });
        return false;
    });
</script>
