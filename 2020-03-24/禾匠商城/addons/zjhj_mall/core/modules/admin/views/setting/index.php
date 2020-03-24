<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 12:04
 */
/* @var \yii\web\View $this */
$this->title = '系统设置';
$url_manager = Yii::$app->urlManager;
$current_url = Yii::$app->request->absoluteUrl;
$return_url = $url_manager->createUrl(['admin/setting/index']);
$this->params['active_nav_link'] = 'admin/setting/index';
?>
<style>
    form {
        position: relative;
    }

    .form-disable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, .75);
        z-index: 10;
        text-align: center;
        padding: 50px 0;
    }

    .form-disable .alert {
        display: table;
        margin: 0 auto;
    }
</style>

<form method="post" return="<?= $return_url ?>" class="auto-submit-form card">
    <div class="card-header"><?= $this->title ?></div>
    <div class="card-body">
        <div class="mb-3"><b>基础配置</b></div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">网站名称</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= $option['name'] ?>" name="name">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Logo图片URL</label>
            <div class="col-sm-6">
                <div class="input-group mb-2">
                    <input class="form-control" value="<?= $option['logo'] ?>" name="logo">
                    <span class="input-group-btn">
                        <a class="btn btn-secondary upload-btn" href="javascript:">上传图片</a>
                    </span>
                </div>
                <span class="upload-preview-tip">98&times;36</span>
                <div style="display: inline-block;background: #fff;border: 1px solid #e3e3e3">
                    <img src="<?= $option['logo'] ?>" class="logo-preview"
                         style="height: 50px;width: auto;display: inline-block">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">底部版权信息</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= \yii\helpers\Html::encode($option['copyright']) ?>"
                       name="copyright">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">登录页背景图</label>
            <div class="col-sm-6">
                <div class="input-group mb-2">
                    <input class="form-control" value="<?= $option['passport_bg'] ?>" name="passport_bg">
                    <span class="input-group-btn">
                        <a class="btn btn-secondary upload-passport-btn" href="javascript:">上传图片</a>
                    </span>
                </div>
                <div style="display: inline-block;background: #fff;border: 1px solid #e3e3e3">
                    <img src="<?= $option['passport_bg'] ?>" class="passport-preview"
                         style="height: 50px;width: auto;display: inline-block" alt="请上传图片">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">是否开放注册</label>
            <div class="col-sm-6">
                <label class="col-form-label px-0 py-2 mr-3">
                    <input type="radio" <?= !$option['open_register'] ? 'checked' : null ?> value="0"
                           name="open_register">
                    <span>否</span>
                </label>
                <label class="col-form-label px-0 py-2">
                    <input type="radio" <?= $option['open_register'] ? 'checked' : null ?> value="1"
                           name="open_register">
                    <span>是</span>
                </label>
            </div>
        </div>

        <hr>
        <div class="mb-3">
            <b>短信配置（阿里云）</b>
            <div class="text-muted fs-sm">用于已设置手机号的户账户修改密码</div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">AccessKeyId</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= $option['ind_sms']['aliyun']['access_key_id'] ?>"
                       name="ind_sms[aliyun][access_key_id]">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">AccessKeySecret</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= $option['ind_sms']['aliyun']['access_key_secret'] ?>"
                       name="ind_sms[aliyun][access_key_secret]">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">短信签名</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= $option['ind_sms']['aliyun']['sign'] ?>"
                       name="ind_sms[aliyun][sign]">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">验证码模板ID</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= $option['ind_sms']['aliyun']['tpl_id'] ?>"
                       name="ind_sms[aliyun][tpl_id]">
                <div class="text-muted fs-sm">模板示例：您的验证码是${code}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">注册审核结果ID</label>
            <div class="col-sm-6">
                <input class="form-control" value="<?= $option['ind_sms']['aliyun']['register_result_tpl_id'] ?>"
                       name="ind_sms[aliyun][register_result_tpl_id]">
                <div class="text-muted fs-sm">用于用户注册审核结果的通知，模板示例：您注册的账户${name}审核结果：${result}。</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-6 offset-sm-3">
                <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
            </div>
        </div>

    </div>
</form>
<script>
    $('.upload-btn').plupload({
        url: '<?=$url_manager->createUrl(["upload/image"])?>',
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=logo]').val(res.data.url);
                $('.logo-preview').attr('src', res.data.url);
            }
        },
    });
    $('.upload-passport-btn').plupload({
        url: '<?=$url_manager->createUrl(["upload/image"])?>',
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=passport_bg]').val(res.data.url);
                $('.passport-preview').attr('src', res.data.url);
            }
        },
    });
</script>