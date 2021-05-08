<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '小程序发布';
$this->params['active_nav_group'] = 1;
?>
<style>
.wxdev-tool-login-qrcode,
.wxdev-tool-preview-qrcode {
    border: 1px solid #e3e3e3;
}

.wxapp-upload-header {
    border-bottom: 1px solid #e3e3e3;
    padding: 2rem;
}

.wxapp-upload-header .active-icon {
    display: none;
}

.wxapp-upload-header .active .gray-icon {
    display: none;
}

.wxapp-upload-header .active .active-icon {
    display: inline;
}

.wxapp-upload-body {
    background: #fafbfc;
}

.step-body {
    padding: 4rem 2rem;
    text-align: center;
    display: none;
}

.step-body.active {
    display: block;
}
</style>

<?php if (!strstr(Yii::$app->request->hostInfo, 'https://')) : ?>
    <div class="alert alert-danger rounded-0">请确认您的服务器是否支持https访问，如不支持，小程序将无法正常运行。</div>
<?php endif; ?>
<?php if ($this->context->is_ind || $this->context->is_we7_offline) : ?>
    <div style="margin: 1rem 0;display: none;">
        <a class="btn btn-primary download-wxapp" href="javascript:">打包并下载小程序</a>
    </div>
<?php endif; ?>
<div style="margin-bottom: 1rem">如果您的小程序中有跳转其它小程序的链接，请先点击·小程序跳转配置·完成配置。</div>
<div style="margin-bottom: 1rem">
    <a class="btn btn-primary wxapp-qrcode" href="javascript:">获取小程序二维码</a>
    <a class="btn btn-primary" data-toggle="modal" data-target="#jumpAppidModal" href="javascript:">小程序跳转配置</a>
</div>
<div style="margin-bottom: 1rem">
    <img src="" class="wxapp-qrcode-img" style="max-width: 240px;border: 1px solid #e3e3e3;border-radius: 2px">
</div>

<?php if ($branch && $branch == 'nomch') : ?>
    <div class="alert alert-danger rounded-0" style="margin-bottom: 1rem">注意：当前分支上传的小程序端无多商户功能！</div>
<?php endif; ?>
<div class="card">
    <div class="wxapp-upload-header">
        <div class="row">
            <div class="col-3 text-center step-title step-title-1 active">
                <img class="mr-2 gray-icon"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/1-A.png">
                <img class="mr-2 active-icon"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/1-B.png">
                <b>扫描二维码登录</b>
            </div>
            <div class="col-1 text-center">
                <img src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/pointer.png">
            </div>
            <div class="col-3 text-center step-title step-title-2">
                <img class="mr-2 gray-icon"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/2-A.png">
                <img class="mr-2 active-icon"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/2-B.png">
                <b>预览小程序</b>
            </div>
            <div class="col-1 text-center">
                <img src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/pointer.png">
            </div>
            <div class="col-3 text-center step-title step-title-3">
                <img class="mr-2 gray-icon"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/3-A.png">
                <img class="mr-2 active-icon"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/3-B.png">
                <b>上传成功</b>
            </div>
        </div>
    </div>
    <div class="wxapp-upload-body">
        <div class="step-body step-body-1 active">
            <div class="wxdev-login-block" style="display: none">
                <img src="" class="wxdev-tool-login-qrcode mb-3" style="height: 120px">
                <div class="mb-3">请使用小程序管理员或开发者微信扫码登录</div>
                <div class="text-muted">扫码后会有大约10秒钟左右延时，请耐心等待</div>
            </div>
            <a href="javascript:" class="btn btn-primary wxdev-tool-login">获取登录二维码</a>
        </div>
        <div class="step-body step-body-2">
            <div class="wxdev-preview-block mb-3">
                <img src="" class="wxdev-tool-preview-qrcode mb-3" style="height: 120px">
                <div>扫描二维码预览小程序</div>
            </div>
            <a href="javascript:" class="btn btn-primary wxdev-tool-upload">上传小程序</a>
        </div>
        <div class="step-body step-body-3">
            <div class="mb-3">上传成功！请登录微信小程序平台（<a href="https://mp.weixin.qq.com/"
                                                 target="_blank">https://mp.weixin.qq.com/</a>）发布小程序
            </div>
            <div class="mb-3">
                <div>版本号：<span class="upload-version"></span></div>
                <div>描述：<span class="upload-desc"></span></div>
            </div>
            <div>
                <img style="max-width: 100%;border: 1px dashed #35b635"
                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/wxapp-upload/upload-tip.png">
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<div class="modal fade" id="jumpAppidModal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">跳转小程序设置</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form auto-form" method="post"
                      action="<?= $urlManager->createUrl(['mch/store/jump-appid']) ?>">
                    <div class="mb-3 text-warning">最多可配置10个，超出无效</div>
                    <div class="jump-appid-list">
                        <?php if (count($jump_appid_list)) : ?>
                            <?php foreach ($jump_appid_list as $item) : ?>
                                <input class="form-control mb-3" type="text" name="jump_appid_list[]"
                                       value="<?= $item ?>"
                                       placeholder="请填写跳转的小程序appid">
                            <?php endforeach; ?>
                        <?php else : ?>
                            <input class="form-control mb-3" type="text" name="jump_appid_list[]" value=""
                                   placeholder="请填写跳转的小程序appid">
                        <?php endif; ?>
                    </div>
                    <div>
                        <a href="javascript:" class="btn btn-secondary add-jump-appid">添加</a>
                        <a style="float: right" class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var wxdev_token = '';
$(document).on("click", ".download-wxapp", function () {
    var btn = $(this);
    btn.btnLoading(btn.text());
    $.ajax({
        type: "post",
        dataType: "json",
        data: {
            _csrf: _csrf,
            action: 'download',
        },
        success: function (res) {
            btn.btnReset();
            if (res.code == 0) {
                window.open(res.data);
            }
            if (res.code == 1) {
            }
        }
    });
});

$(document).on("click", ".wxdev-tool-login", function () {
    var btn = $(this);
    btn.btnLoading(btn.text());
    $.ajax({
        type: "post",
        dataType: "json",
        data: {
            _csrf: _csrf,
            action: 'wxdev_tool_login',
        },
        success: function (res) {
            btn.btnReset();
            if (res.code == 0) {
                btn.hide();
                $('.wxdev-login-block').show();
                $('.wxdev-tool-login-qrcode').attr('src', res.data.qrcode);
                wxdev_token = res.token;
                checkQrcodeScan();
            } else {
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        location.reload();
                    }
                });
            }
        }
    });
});

function checkQrcodeScan() {
    $.ajax({
        type: 'post',
        dataType: "json",
        data: {
            _csrf: _csrf,
            action: 'wxdev_tool_preview',
            token: wxdev_token,
        },
        success: function (res) {
            if (res.code == 0) {
                $('.step-title').removeClass('active');
                $('.step-title-2').addClass('active');
                $('.step-body').removeClass('active');
                $('.step-body-2').addClass('active');
                $('.wxdev-tool-preview-qrcode').attr('src', res.data.qrcode);
            }
            if (res.code == -1) {
                checkQrcodeScan();
            }
            if (res.code == 1) {
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        location.reload();
                    }
                });
            }
        },
    });
}

$(document).on('click', '.wxdev-tool-upload', function () {
    var btn = $(this);
    btn.btnLoading(btn.text());
    $.ajax({
        type: 'post',
        dataType: "json",
        data: {
            _csrf: _csrf,
            action: 'wxdev_tool_upload',
            token: wxdev_token,
        },
        success: function (res) {
            if (res.code == 0) {
                $('.step-title').removeClass('active');
                $('.step-title-3').addClass('active');
                $('.step-body').removeClass('active');
                $('.step-body-3').addClass('active');
                $('.upload-version').html(res.data.version);
                $('.upload-desc').html(res.data.desc);
            }
            if (res.code == 1) {
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

$(document).on("click", ".wxapp-qrcode", function () {
    var btn = $(this);
    btn.btnLoading("正在处理");
    $.ajax({
        url: "<?=$urlManager->createUrl(['mch/store/wxapp-qrcode'])?>",
        type: "post",
        dataType: "json",
        data: {
            _csrf: _csrf,
        },
        success: function (res) {
            btn.btnReset();
            if (res.code == 0) {
                $(".wxapp-qrcode-img").attr("src", res.data);
            }
            if (res.code == 1) {
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        location.reload();
                    }
                });
            }
        }
    });
});

$(document).on("click", ".add-jump-appid", function () {
    var html = '<input class="form-control mb-3" type="text" name="jump_appid_list[]" value="" placeholder="请填写跳转的小程序appid">';
    $('.jump-appid-list').append(html);
});
</script>