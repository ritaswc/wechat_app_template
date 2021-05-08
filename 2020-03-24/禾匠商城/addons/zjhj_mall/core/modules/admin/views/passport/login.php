<?php


defined('YII_ENV') or exit('Access Denied');

use app\models\Option;

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 14:08
 */
$this->title = '管理员登录';
$logo = Option::get('logo', 0, 'admin', null);
$logo = $logo ? $logo : Yii::$app->request->baseUrl . '/statics/admin/images/logo.png';
$copyright = Option::get('copyright', 0, 'admin');
$copyright = $copyright ? $copyright : '©2017 <a href="http://www.zjhejiang.com" target="_blank">禾匠信息科技</a>';
$passport_bg = Option::get('passport_bg', 0, 'admin', Yii::$app->request->baseUrl . '/statics/admin/images/passport-bg.jpg');
$open_register = Option::get('open_register', 0, 'admin', false);
?>
<style>
    html {
        position: relative;
        min-height: 100%;
        height: 100%;
    }

    body {
        padding-bottom: 70px;
        height: 100%;
        overflow: hidden;
    }

    .main {
        background-image: url("<?=$passport_bg?>");
        background-size: cover;
        background-position: center;
        height: 100%;
    }

    .card {
        max-width: 360px;
        margin: 0 auto;
    }

    .card {
        border: none;
        background: rgba(255, 255, 255, .85);
        padding: 16px 10px;
    }

    .card h1 {
        font-size: 20px;
        font-weight: normal;
        text-align: center;
        margin: 0 0 32px 0;
    }

    .card .custom-checkbox .custom-control-indicator {
        border: 1px solid #ccc;
        background-color: #eee;
    }

    .card .custom-control-input:checked ~ .custom-control-indicator {
        border-color: transparent;
    }

    .header {
        height: 50px;
        background: rgba(255, 255, 255, .5);
        margin-bottom: 120px;
    }

    .header a {
        display: inline-block;
        height: 50px;
        padding: 8px 30px;
    }

    .logo {
        display: inline-block;
        height: 100%;
    }

    .footer {
        position: absolute;
        height: 70px;
        background: #fff;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    .copyright {
        padding: 24px 0;
    }
</style>
<div class="main" id="app">

    <div class="header">
        <a href="<?= Yii::$app->request->baseUrl ?>">
            <img class="logo" src="<?= $logo ?>">
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <h1>管理员登录</h1>
            <input class="form-control mb-3 username" name="username" placeholder="请输入用户名">
            <input class="form-control mb-3 password" name="password" placeholder="请输入密码" type="password">
            <div class="form-inline mb-3">
                <div class="w-100">
                    <input class="form-control captcha_code" name="captcha_code" placeholder="图片验证码"
                           style="width: 150px">
                    <img class="refresh-captcha login-captcha"
                         data-refresh="<?= Yii::$app->urlManager->createUrl(['admin/passport/captcha', 'refresh' => 1,]) ?>"
                         style="height: 33px;width: 80px;float: right;cursor: pointer;">
                </div>
            </div>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">记住我，以后自动登录</span>
            </label>
            <button class="btn btn-block btn-primary mb-3 login">登录</button>
            <a href="javascript:" data-toggle="modal" data-target="#resetPassword">忘记密码</a>
            <?php if ($open_register) : ?>
                <span>|</span>
                <a href="<?= Yii::$app->urlManager->createUrl(['admin/passport/register']) ?>">注册账号</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="resetPassword" data-backdrop="static">
        <div class="modal-dialog modal-sm" style="max-width: 360px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">忘记密码</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="send_sms_code_form"
                          action="<?= \Yii::$app->urlManager->createUrl(['admin/passport/send-sms-code']) ?>">
                        <div class="mb-3">已绑定手机号的账户可通过手机重置密码：</div>
                        <input class="form-control mb-3" name="mobile" placeholder="手机号">
                        <div class="form-inline mb-3">
                            <div class="w-100">
                                <input class="form-control" name="captcha_code" placeholder="图片验证码"
                                       style="width: 150px">
                                <img class="refresh-captcha"
                                     data-refresh="<?= Yii::$app->urlManager->createUrl(['admin/passport/sms-code-captcha', 'refresh' => 1,]) ?>"
                                     src="<?= Yii::$app->urlManager->createUrl(['admin/passport/sms-code-captcha',]) ?>"
                                     style="height: 33px;width: 80px;float: right;cursor: pointer;" title="点击刷新验证码">
                            </div>
                        </div>
                        <div class="mb-3 text-danger send-sms-code-error" style="display: none">错误</div>
                        <a href="javascript:" class="btn btn-primary send-sms-code">发送短信验证码</a>
                    </form>
                    <form id="reset_password_form"
                          style="display: none"
                          action="<?= \Yii::$app->urlManager->createUrl(['admin/passport/reset-password']) ?>">
                        <div class="mb-3">短信验证码已发送到您的手机。</div>
                        <div>请选择重置密码的账户：</div>
                        <select class="form-control mb-3" name="admin_id">
                            <option v-for="item in admin_list" :value="item.id">{{item.username}}</option>
                        </select>
                        <input class="form-control mb-3" name="sms_code" placeholder="手机收到的短信验证码">
                        <input class="form-control mb-3" type="password" name="password" placeholder="新密码">
                        <input class="form-control mb-3" type="password" name="password2" placeholder="确认新密码">
                        <div class="mb-3 text-danger reset-password-error" style="display: none">错误</div>
                        <a href="javascript:" class="btn btn-primary reset-password">重置密码</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="footer">
    <div class="text-center copyright"><?= $copyright ?></div>
</div>
<script>
var app = new Vue({
    el: '#app',
    data: {
        admin_list: [],
    },
});
$(document).on('click', '.refresh-captcha', function () {
    var img = $(this);
    var refresh_url = img.attr('data-refresh');
    $.ajax({
        url: refresh_url,
        dataType: 'json',
        success: function (res) {
            img.attr('src', res.url);
        }
    });
});

$(document).ready(function () {
    var img = $('.login-captcha');
    var refresh_url = img.attr('data-refresh');
    img.css('opacity', 0);
    $.ajax({
        url: refresh_url,
        dataType: 'json',
        success: function (res) {
            img.css('opacity', 1);
            img.attr('src', res.url);
        }
    });
});

$(document).on('click', '.login', function () {
    var username = $('.username').val();
    var password = $('.password').val();
    var captcha_code = $('.captcha_code').val();
    $.ajax({
        url: '<?=Yii::$app->urlManager->createUrl('admin/passport/login')?>',
        type: 'post',
        dataType: 'json',
        data: {
            'username': username,
            'password': password,
            'captcha_code': captcha_code,
            _csrf: _csrf,
        },
        success: function (res) {
            if (res.code === 1) {
                $.myAlert({
                    content: res.msg
                });
            } else {
                location.href = "<?= \Yii::$app->urlManager->createUrl('admin/user/me') ?>";
            }
        }
    })
});

$(document).on('click', '.send-sms-code', function () {
    var form = document.getElementById('send_sms_code_form');
    var mobile = form.mobile.value;
    var captcha_code = form.captcha_code.value;
    var btn = $(this);
    btn.btnLoading();
    $('.send-sms-code-error').html('').hide();
    $.ajax({
        url: form.action,
        type: 'post',
        dataType: 'json',
        data: {
            mobile: mobile,
            captcha_code: captcha_code,
            _csrf: _csrf,
        },
        complete: function () {
            btn.btnReset();
        },
        success: function (res) {
            if (res.code == 1) {
                $('.send-sms-code-error').html(res.msg).show();
            }
            if (res.code == 0) {
                $('#send_sms_code_form').hide();
                $('#reset_password_form').show();
                app.admin_list = res.data.admin_list;
            }
        },
    });
});

$(document).on('click', '.reset-password', function () {
    var form = document.getElementById('reset_password_form');
    var admin_id = form.admin_id.value;
    var sms_code = form.sms_code.value;
    var password = form.password.value;
    var password2 = form.password2.value;
    if (password.length < 6) {
        $('.reset-password-error').html('密码长度不能低于6位。').show();
        return false;
    }
    if (password != password2) {
        $('.reset-password-error').html('两次输入的密码不一致。').show();
        return false;
    }
    var btn = $(this);
    btn.btnLoading();
    $('.reset-password-error').html('').hide();
    $.ajax({
        url: form.action,
        type: 'post',
        dataType: 'json',
        data: {
            admin_id: admin_id,
            sms_code: sms_code,
            password: password,
            _csrf: _csrf,
        },
        complete: function () {
            btn.btnReset();
        },
        success: function (res) {
            if (res.code == 1) {
                $('.reset-password-error').html(res.msg).show();
            }
            if (res.code == 0) {
                $('#resetPassword').hide();
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        location.reload();
                    }
                });
            }
        },
    });
});

</script>