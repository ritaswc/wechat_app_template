<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/30
 * Time: 10:26
 */
?>
<style>
    .card {
        max-width: 600px;
        margin: 0 auto;
    }
</style>
<div class="p-5">
    <div class="card">
        <div class="card-header text-center">安装程序</div>
        <div class="card-body">
            <form method="post" autocomplete="off" id="install_form">
                <input name="step" value="1" type="hidden">
                <b>配置数据库信息</b>
                <hr>
                <div class="form-group">
                    <label>数据库IP</label>
                    <div class="input-group input-group-sm">
                        <input value="<?= $model['host'] ? $model['host'] : 'localhost' ?>"
                               name="model[host]"
                               type="text"
                               class="form-control form-control-sm"
                               placeholder="localhost">
                        <span class="input-group-addon">端口</span>
                        <input value="<?= $model['port'] ? $model['port'] : 3306 ?>"
                               name="model[port]"
                               type="number"
                               style="width: 2rem;flex: auto"
                               class="form-control form-control-sm"
                               placeholder="默认3306">
                    </div>
                    <small class="form-text text-muted">如果您的数据库跟代码是同一服务器请填写localhost</small>
                </div>
                <div class="form-group">
                    <label>数据库名</label>
                    <input value="<?= $model['dbname'] ?>"
                           name="model[dbname]"
                           type="text"
                           class="form-control form-control-sm"
                           placeholder="">
                    <small class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label>数据表前缀</label>
                    <input value="<?= $model['tablePrefix'] ? $model['tablePrefix'] : 'hjmallind_' ?>"
                           name="model[tablePrefix]"
                           type="text"
                           readonly
                           class="form-control form-control-sm"
                           placeholder="默认hjmallind_">
                    <small class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label>数据库用户名</label>
                    <input value="<?= $model['username'] ?>"
                           name="model[username]"
                           type="text"
                           class="form-control form-control-sm"
                           placeholder="">
                    <small class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label>数据库密码</label>
                    <input value="<?= $model['password'] ?>"
                           name="model[password]"
                           type="text"
                           class="form-control form-control-sm"
                           placeholder="">
                    <small class="form-text text-muted"></small>
                </div>
                <b>设置管理员账号</b>
                <hr>
                <div class="form-group">
                    <label>用户名</label>
                    <input value="<?= $model['admin_username'] ?>"
                           name="model[admin_username]"
                           type="text"
                           class="form-control form-control-sm"
                           placeholder="">
                    <small class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                    <label>密码</label>
                    <div class="input-group input-group-sm">
                        <input value="<?= $model['admin_password'] ?>"
                               name="model[admin_password]"
                               type="password"
                               class="form-control form-control-sm"
                               placeholder="">
                    </div>
                    <small class="form-text text-muted"></small>
                </div>

                <div class="alert alert-danger form-error" style="display: none"></div>

                <div class="text-center">
                    <a href="javascript:" class="btn btn-sm btn-primary install-btn">立即安装</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <b>提示</b>
            </div>
            <div class="modal-body">
                <div>恭喜您，商城安装成功！<a href="<?= Yii::$app->request->baseUrl ?>">立即使用</a></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".install-btn", function () {
        var btn = $(this);
        var form = $("#install_form");
        var error = $(".form-error");
        btn.text("正在安装...").addClass("disabled");
        error.hide();
        $.ajax({
            type: "post",
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                if (res.code == 0) {
                    $("#exampleModal").modal("show");
                } else {
                    error.html(res.msg).show();
                    btn.text("立即安装").removeClass("disabled");
                }
            }
        });
    });
</script>
