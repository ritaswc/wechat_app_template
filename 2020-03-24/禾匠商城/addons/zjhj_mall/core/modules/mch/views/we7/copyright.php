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
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">底部版权文字</label>
                </div>
                <div class="col-sm-6" style="max-width: 360px">
                    <textarea class="form-control" name="text" style="resize: none;min-height: 100px;"><?=$data['copyright']['text']?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">底部版权图标</label>
                </div>
                <div class="col-sm-6" style="max-width: 360px">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="icon" value="<?=$data['copyright']['icon']?>">
                                        <span class="input-group-btn">
                                        <a class="btn btn-secondary upload-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="上传文件">
                                            <span class="iconfont icon-cloudupload"></span>
                                        </a>
                                    </span>
                                        <span class="input-group-btn">
                                        <a class="btn btn-secondary select-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="从文件库选择">
                                            <span class="iconfont icon-viewmodule"></span>
                                        </a>
                                    </span>
                                        <span class="input-group-btn">
                                        <a class="btn btn-secondary delete-file" href="javascript:"
                                           data-toggle="tooltip"
                                           data-placement="bottom" title="删除文件">
                                            <span class="iconfont icon-close"></span>
                                        </a>
                                    </span>
                        </div>
                        <div class="upload-preview text-center upload-preview">
                            <span class="upload-preview-tip">240&times;60</span>
                            <img class="upload-preview-img" src="<?=$data['copyright']['icon']?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
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
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否开启一键拨号</label>
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
                <div class="form-group-label col-sm-2 text-right">
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
