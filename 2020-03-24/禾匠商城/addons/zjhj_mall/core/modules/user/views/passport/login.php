<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/9
 * Time: 10:28
 */
?>
<style>
    body {
        background: #f7f6f1;
    }

    .main-box {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
    }

    .main-content {
        max-width: 260px;
    }

    .title {
        text-align: center;
        padding: 16px;
        font-size: 1.35rem;
    }

    .qrcode {
        max-width: 260px;
        border-radius: 0;
        border: 1px solid #eee;
        margin-bottom: 20px;
        padding: 1rem;
        background-color: #fff;
    }

    .desc {
        background: #eee;
        max-width: 100%;
        text-align: center;
        padding: 12px;
        border-radius: 999px;
        box-shadow: inset 1px 1px 3px 0px rgba(0, 0, 0, .2), 1px 1px 1px #fff;
    }

    .login-success {
        color: #1f9832;
        display: none;
    }

    .platform-item {
        text-decoration: none;
        text-align: center;
        padding: .5rem;
        margin: 0 1rem;
    }
</style>
<div class="main-box" flex="dir:left main:center cross:center">
    <?php if ($_platform == 'wx'): ?>
        <div class="main-content">
            <div class="title">微信登陆</div>
            <img class="qrcode" src="<?= $img_url ?>">
            <div class="desc">
                <div class="login-tip">请使用微信扫描小程序码登录</div>
            </div>
        </div>
    <?php elseif ($_platform == 'my'): ?>
        <div class="main-content">
            <div class="title">支付宝登录</div>
            <img class="qrcode" src="<?= $img_url ?>">
            <div class="desc">
                <div class="login-tip">请使用支付宝扫描小程序码登录</div>
            </div>
        </div>
    <?php else: ?>
        <div class="main-content">
            <div class="title">请选择您的用户类型</div>
            <div flex="dir:left main:center">
                <a class="platform-item"
                   href="<?= Yii::$app->request->baseUrl ?>/mch.php?store_id=<?= Yii::$app->request->get('store_id') ?>&_platform=wx">
                    <div>
                        <img style="width: 100px;height: 100px"
                             src="https://open.weixin.qq.com/zh_CN/htmledition/res/assets/res-design-download/icon64_appwx_logo.png">
                    </div>
                    <div>微信用户</div>
                </a>
                <?php if ($isAlipay == 1): ?>
                    <a class="platform-item"
                       href="<?= Yii::$app->request->baseUrl ?>/mch.php?store_id=<?= Yii::$app->request->get('store_id') ?>&_platform=my">
                        <div>
                            <img style="width: 100px;height: 100px"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/alipay.png">
                        </div>
                        <div>支付宝用户</div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    var stop = false;
    var token = '<?=$token?>';

    function checkLogin() {
        if (stop)
            return;
        if (!token || token == '')
            return;
        $.ajax({
            url: '<?=Yii::$app->urlManager->createUrl(['user/passport/check-login',])?>',
            data: {
                token: token,
            },
            dataType: 'json',
            success: function (res) {
                $('.login-tip').text(res.msg);
                if (res.code == 1) {
                    stop = true;
                }
                if (res.code == 0) {
                    location.href = '<?=Yii::$app->urlManager->createUrl(['user'])?>';
                }
                if (res.code == -1) {
                    checkLogin();
                }
            },
            error: function () {
                stop = true;
            }
        });
    }

    checkLogin();
</script>