<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/28
 * Time: 15:53
 */
$this->title = '微信配置';
?>
<div class="panel mb-3">
    <div class="panel-header">微信配置</div>
    <div class="panel-body">

        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">小程序AppId</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $model->app_id ?>" name="app_id">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">小程序AppSecret</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-hide">
                        <input class="form-control" value="<?= $model->app_secret ?>" name="app_secret">
                        <div class="tip-block">已隐藏内容，点击查看或编辑</div>
                    </div>
                    <div class="text-danger">注：若微信支付尚未开通，以下选项请设置0</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">微信支付商户号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" value="<?= $model->mch_id ?>" name="mch_id">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">微信支付Api密钥</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-hide">
                        <input class="form-control" value="<?= $model->key ?>" name="key">
                        <div class="tip-block">已隐藏内容，点击查看或编辑</div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">微信支付apiclient_cert.pem</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-hide">
                        <textarea rows="5" class="form-control secret-content" name="cert_pem"><?= $model->cert_pem ?></textarea>
                        <div class="tip-block">已隐藏内容，点击查看或编辑</div>
                    </div>
                    <div class="fs-sm text-muted">使用文本编辑器打开apiclient_cert.pem文件，将文件的全部内容复制进来</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">微信支付apiclient_key.pem</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-hide">
                        <textarea rows="5" class="form-control secret-content" name="key_pem"><?= $model->key_pem ?></textarea>
                        <div class="tip-block">已隐藏内容，点击查看或编辑</div>
                    </div>
                    <div class="fs-sm text-muted">使用文本编辑器打开apiclient_key.pem文件，将文件的全部内容复制进来</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>

    </div>
</div>
