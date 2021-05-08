<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 10:11
 */
$urlManager = Yii::$app->urlManager;
$this->title = '版权设置';
$this->params['active_nav_group'] = 1;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?=$url?>">

            <div class="form-group row">
                <div class="form-group-label col-sm-6 text-right">
                    <label class="col-form-label">底部版权文字</label>
                </div>
                <div class="col-sm-6" style="max-width: 360px">
                    <textarea class="form-control" name="text" style="resize: none;min-height: 100px;"><?=$data['copyright']['text']?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-6 text-right">
                    <label class="col-form-label">Logo</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group mb-2">
                        <input class="form-control" value="<?= $data['copyright']['icon'] ?>" name="icon">
                        <span class="input-group-btn">
                        <a class="btn btn-secondary upload-btn" href="javascript:">上传图片</a>
                    </span>
                    </div>
                    <span class="upload-preview-tip">240&times;60</span>
                    <div style="display: inline-block;background: #fff;border: 1px solid #e3e3e3">
                        <img src="<?= $data['copyright']['icon'] ?>" class="logo-preview"
                             style="height: 50px;width: auto;display: inline-block">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-6 text-right">
                    <label class="col-form-label">底部版权链接</label>
                </div>
                <div class="col-sm-6" style="max-width: 360px">
                    <div class="input-group page-link-input">
                        <input class="form-control link-input"
                               readonly
                               name="url" value="<?=$data['copyright']['url']?>">
                        <input class="link-open-type"
                               name="open_type"
                               type="hidden" value="<?=$data['copyright']['open_type']?>">
                                    <span class="input-group-btn">
                                    <a class="btn btn-secondary pick-link-btn" href="javascript:">选择链接</a>
                                </span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-6 text-right">
                    <label class="col-form-label">一键拨号</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio1" <?= $data['copyright']['is_phone'] == 1 ? 'checked' : null ?>
                               value="1"
                               name="is_phone" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio2" <?= $data['copyright']['is_phone'] == 0 ? 'checked' : null ?>
                               value="0"
                               name="is_phone" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                    <div class="fs-sm">若开启一键拨号，则链接失效</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-6 text-right">
                    <label class="col-form-label">联系电话</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="phone"
                           value="<?= $data['copyright']['phone'] ?>">
                    <div class="fs-sm">若为空，则会拨打“商城设置”中的“联系电话”</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6" style="max-width: 360px">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('.upload-btn').plupload({
        url: '<?=$urlManager->createUrl(["upload/image"])?>',
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                console.log(res.data.url)
                $('input[name=icon]').val(res.data.url);
                $('.logo-preview').attr('src', res.data.url);
            }
        },
    });
    $('.upload-passport-btn').plupload({
        url: '<?=$urlManager->createUrl(["upload/image"])?>',
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=passport_bg]').val(res.data.url);
                $('.passport-preview').attr('src', res.data.url);
            }
        },
    });
</script>
