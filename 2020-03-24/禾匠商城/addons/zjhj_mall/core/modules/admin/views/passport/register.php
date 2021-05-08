<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/11 11:58
 */

defined('YII_ENV') or exit('Access Denied');

use app\models\Option;

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 14:08
 */
$this->title = '注册账户';
$logo = Option::get('logo', 0, 'admin', null);
$logo = $logo ? $logo : Yii::$app->request->baseUrl . '/statics/admin/images/logo.png';

$copyright = Option::get('copyright', 0, 'admin');
$copyright = $copyright ? $copyright : '©2017 <a href="http://www.zjhejiang.com" target="_blank">禾匠信息科技</a>';

$passport_bg = Option::get('passport_bg', 0, 'admin', Yii::$app->request->baseUrl . '/statics/admin/images/passport-bg.jpg');
?>
<style>
    html {
        position: relative;
        min-height: 100%;
        min-width: 800px;
        overflow-x: auto;
    }

    body {
        background-image: none;
        padding-bottom: 70px;
        min-height: 100%;
        overflow: hidden;
        background-color: #fff;
    }

    .main {
        height: 100%;
    }

    form .custom-control-input:checked ~ .custom-control-indicator {
        border-color: transparent;
    }

    .header {
        background: #f6f6f6;
        padding: 50px 0;
        text-align: center;
        margin-bottom: 40px;
    }

    .header .logo-a {
        display: inline-block;
        height: 50px;
        padding: 0;
        margin-bottom: 20px;
    }

    .logo {
        display: inline-block;
        height: 100%;
    }

    .footer {
        position: absolute;
        height: 70px;
        background: #f6f6f6;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    .copyright {
        padding: 24px 0;
    }

    .step-block {
        margin: 0 auto;
        max-width: 800px;
        margin-bottom: 60px;
    }

    .step-block .step-item {
        text-align: center;
        position: relative;
        color: #888;
    }

    .step-block .step-item:after {
        display: block;
        content: " ";
        height: 1px;
        width: calc(100% - 20px);
        background: #ccc;
        position: absolute;
        top: 20px;
        left: calc(50% + 20px);
    }

    .step-block .step-item:last-child:after {
        display: none;
    }

    .step-block .step-icon {
        width: 40px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 999px;
        display: inline-block;
        background: #d9d9d9;
        color: #fff !important;
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .step-block .step-item.active {
        color: #307ed7;
    }

    .step-block .step-item.active .step-icon {
        background: #307ed7;
    }

    .step {
        max-width: 500px;
        margin: 0 auto;
    }

    .step .row {
        transform: translate(-70px, 0);
    }

    .send-code-timeout {
        position: absolute;
        left: 100%;
        top: 0;
    }
</style>
<div class="main" id="app">

    <div class="header">
        <a class="logo-a" href="<?= Yii::$app->request->baseUrl ?>">
            <img class="logo" src="<?= $logo ?>">
        </a>
        <div>
            <span class="text-muted">已有账号？</span>
            <a class="p-0"
               href="<?= Yii::$app->urlManager->createUrl(['admin/passport/login', 'return_url' => Yii::$app->urlManager->createUrl(['admin'])]) ?>">登录</a>
        </div>
    </div>

    <div>
        <div class="step-block row">
            <div class="col-sm-4 step-item step-item-1 active">
                <div class="step-icon">1</div>
                <div>账号信息</div>
            </div>
            <div class="col-sm-4 step-item step-item-2">
                <div class="step-icon">2</div>
                <div>申请信息</div>
            </div>
            <div class="col-sm-4 step-item step-item-3">
                <div class="step-icon">3</div>
                <div>提交注册申请</div>
            </div>
        </div>

        <div class="step step-1 mb-4">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">帐户名</label>
                <div class="col-sm-8">
                    <input class="form-control" v-model="username" placeholder="请输入帐户名">
                    <div class="fs-sm text-muted">帐户名必须是字母开头，只允许有字母、数字、下划线 _</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">设置密码</label>
                <div class="col-sm-8">
                    <input class="form-control" type="password" v-model="password" placeholder="请输入密码">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">确认密码</label>
                <div class="col-sm-8">
                    <input class="form-control" type="password" v-model="password2" placeholder="请再次输入密码">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right"></label>
                <div class="col-sm-8 text-right">
                    <a class="btn btn-primary go-step-2" href="javascript:">下一步</a>
                </div>
            </div>
        </div>

        <div class="step step-2 mb-4" style="display: none">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">姓名/企业名称</label>
                <div class="col-sm-8">
                    <input class="form-control" v-model="name" placeholder="请输入帐户名">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">联系人手机号</label>
                <div class="col-sm-8">
                    <input class="form-control" v-model="mobile" placeholder="请输入手机号">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">验证码</label>
                <div class="col-sm-8" style="position: relative">
                    <div class="input-group">
                        <input class="form-control" v-model="sms_code" placeholder="请输入验证码">
                        <span class="input-group-btn">
                            <a v-if="send_sms_code_timeout > 0" href="javascript:"
                               class="btn btn-primary show-sms-code-modal disabled" data-toggle="modal"
                               data-target="#smsCodeModal">获取验证码</a>
                            <a v-else href="javascript:" class="btn btn-primary show-sms-code-modal" data-toggle="modal"
                               data-target="#smsCodeModal">获取验证码</a>
                        </span>
                    </div>
                    <a class="btn btn-light disabled send-code-timeout" v-if="send_sms_code_timeout > 0">{{send_sms_code_timeout}}秒再次获取</a>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right">申请原因</label>
                <div class="col-sm-8">
                    <textarea class="form-control" v-model="desc" placeholder="请输入申请原因" rows="4"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label text-right"></label>
                <div class="col-sm-8 text-right">
                    <a class="btn btn-outline-primary mr-3 go-step-1" href="javascript:">上一步</a>
                    <a class="btn btn-primary submit" href="javascript:">提交</a>
                </div>
            </div>
        </div>


        <div class="step step-3 text-center mb-4" style="display: none">
            <img style="margin-bottom: 40px"
                 src="<?= Yii::$app->request->baseUrl ?>/statics/admin/images/register-result-1.png">
            <div>您的注册申请已提交</div>
            <div>我们将以手机短信的方式通知注册结果</div>
            <div>请您留意</div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="smsCodeModal" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">获取验证码</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>验证码将以手机短信方式发送到您填写的手机号上。</p>
                    <div class="row">
                        <div class="col-sm-8">
                            <input v-model="captcha_code" class="form-control mb-3" placeholder="请输入图片验证码">
                        </div>
                        <div class="col-sm-4">
                            <img class="refresh-captcha"
                                 data-refresh="<?= Yii::$app->urlManager->createUrl(['admin/passport/sms-code-captcha', 'refresh' => 1,]) ?>"
                                 src="<?= Yii::$app->urlManager->createUrl(['admin/passport/sms-code-captcha',]) ?>"
                                 style="height: 33px;width: 72px;float: right;cursor: pointer;border: 1px solid #ccc"
                                 title="点击刷新验证码">
                        </div>
                    </div>
                    <p v-if="send_sms_error" class="text-danger">{{send_sms_error}}</p>
                    <div class="text-right">
                        <a class="btn btn-primary send-sms-code" href="javascript:">发送验证码</a>
                    </div>
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
            username: '',
            password: '',
            password2: '',
            name: '',
            mobile: '',
            sms_code: '',
            desc: '',
            captcha_code: '',
            send_sms_code_timeout: 0,
            send_sms_error: '',
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

    $(document).on('click', '.go-step-2', function () {
        var btn = $(this);
        btn.btnLoading();
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['admin/passport/register-validate'])?>',
            type: 'post',
            dataType: 'json',
            data: {
                _csrf: _csrf,
                username: app.username,
                password: app.password,
                password2: app.password2,
            },
            success: function (res) {
                if (res.code == 0) {
                    $('.step-1').hide();
                    $('.step-2').show();
                    $('.step-3').hide();
                } else {
                    $.myToast({
                        content: res.msg,
                    });
                }
            },
            complete: function () {
                btn.btnReset();
            }
        });
    });

    $(document).on('click', '.go-step-1', function () {
        $('.step-1').show();
        $('.step-2').hide();
        $('.step-3').hide();
    });

    $(document).on('click', '.submit', function () {
        var btn = $(this);
        btn.btnLoading();
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                _csrf: _csrf,
                username: app.username,
                password: app.password,
                password2: app.password2,
                name: app.name,
                mobile: app.mobile,
                sms_code: app.sms_code,
                desc: app.desc,
            },
            success: function (res) {
                if (res.code == 0) {
                    $('.step-1').hide();
                    $('.step-2').hide();
                    $('.step-3').show();
                } else {
                    $.myToast({
                        content: res.msg,
                    });
                }
            },
            complete: function () {
                btn.btnReset();
            }
        });
    });

    $(document).on('click', '.send-sms-code', function () {
        var btn = $(this);
        btn.btnLoading();
        app.send_sms_error = false;
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['admin/passport/send-register-sms-code'])?>',
            type: 'post',
            dataType: 'json',
            data: {
                _csrf: _csrf,
                mobile: app.mobile,
                captcha_code: app.captcha_code,
            },
            success: function (res) {
                if (res.code == 0) {
                    $('#smsCodeModal').modal('hide');
                    starSendSmsTimeout(res.data.timeout);
                } else {
                    app.send_sms_error = res.msg;
                }
            },
            complete: function () {
                btn.btnReset();
            },
        });
    });

    function starSendSmsTimeout(timeout) {
        app.send_sms_code_timeout = timeout;
        var timer = setInterval(function () {
            if (app.send_sms_code_timeout == 0) {
                clearInterval(timer);
            } else {
                app.send_sms_code_timeout--;
            }
        }, 1001);
    }

</script>