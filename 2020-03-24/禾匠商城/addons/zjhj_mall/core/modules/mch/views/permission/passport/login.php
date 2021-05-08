<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
$this->title = '员工登录';
$urlManager = Yii::$app->urlManager;
$passport_bg = Yii::$app->request->baseUrl . '/statics/mch/images/passport_bg.png';
?>
<style>
    html {
        position: relative;
        min-height: 100%;
        height: 100%;
    }

    body {
        height: 100%;
        overflow: hidden;
    }

    .main {
        background-image: url("<?=$passport_bg?>");
        background-size: cover;
        background-position: center;
        height: 100%;
    }

    form {
        max-width: 360px;
        margin: 0 auto;
    }

    form.card {
        border: none;
        background: rgba(255, 255, 255, .85);
        padding: 16px 10px;
    }

    form h1 {
        font-size: 20px;
        font-weight: normal;
        text-align: center;
        margin: 0 0 32px 0;
    }

    form .custom-checkbox .custom-control-indicator {
        border: 1px solid #ccc;
        background-color: #eee;
    }

    form .custom-control-input:checked ~ .custom-control-indicator {
        border-color: transparent;
    }

    .header {
        height: 50px;
        margin-bottom: 120px;
    }

    .header a {
        display: inline-block;
        height: 50px;
        padding: 8px 30px;
    }
</style>
<div class="main">

    <div class="header"></div>
    <form method="post" class="auto-submit-form card"
          action="<?= $urlManager->createUrl(['mch/permission/passport/login']) ?>"
          return="<?= $urlManager->createUrl('mch/store/index') ?>">
        <div class="card-body">
            <h1>员工登录</h1>
            <input class="form-control mb-3" name="username" placeholder="请输入用户名">
            <input class="form-control mb-3" name="password" placeholder="请输入密码" type="password">
            <div class="form-inline mb-3">
                <div class="w-100">
                    <input hidden name="store_id" value="<?= $_GET['store_id'] ?>">
                    <input class="form-control" name="captcha_code" placeholder="图片验证码" style="width: 150px">
                    <img class="refresh-captcha"
                         data-refresh="<?= Yii::$app->urlManager->createUrl(['mch/permission/passport/captcha', 'refresh' => 1,]) ?>"
                         src="<?= Yii::$app->urlManager->createUrl(['mch/permission/passport/captcha',]) ?>"
                         style="height: 33px;width: 80px;float: right;cursor: pointer;" title="点击刷新验证码">
                </div>
            </div>
            <button class="btn btn-block btn-primary submit-btn mb-3">登录</button>
        </div>
    </form>

</div>
<script>
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

</script>
