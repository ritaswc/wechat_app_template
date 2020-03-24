<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 16:24
 */
defined('YII_ENV') or exit('Access Denied');
/** @var \app\models\Recharge $model */
$urlManager = Yii::$app->urlManager;
$this->title = '设置';
$this->params['active_nav_group'] = 12;
$returnUrl = Yii::$app->request->referrer;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off">
            <div class="form-body">

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">开启余额功能</label>
                    </div>
                    <div class="col-9">
                        <label class="radio-label">
                            <input id="radio2" <?= $list['status'] == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="status" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input id="radio1" <?= $list['status'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="status" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">是否开放自定义金额</label>
                    </div>
                    <div class="col-9">
                        <label class="radio-label">
                            <input id="radio2" <?= $list['type'] == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="type" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">关闭</span>
                        </label>
                        <label class="radio-label">
                            <input id="radio1" <?= $list['type'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="type" type="radio" class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">开启</span>
                        </label>
                        <div class="fs-sm">用户可以自定义充值金额，但是不会有赠送</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">背景图片</label>
                    </div>
                    <div class="col-9">

                        <div class="upload-group">
                            <div class="input-group">
                                <input class="form-control file-input" name="pic_url" value="<?=$list['pic_url']?>">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary select-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="从文件库选择">
                                    <span class="iconfont icon-viewmodule"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary delete-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="删除文件">
                                    <span class="iconfont icon-close"></span>
                                </a>
                            </span>
                            </div>
                            <div class="upload-preview text-center upload-preview">
                                <span class="upload-preview-tip">750&times;324</span>
                                <img class="upload-preview-img" src="<?=$list['pic_url']?>">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">广告图片</label>
                    </div>
                    <div class="col-9">

                        <div class="upload-group">
                            <div class="input-group">
                                <input class="form-control file-input" name="ad_pic_url" value="<?=$list['ad_pic_url']?>">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary select-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="从文件库选择">
                                    <span class="iconfont icon-viewmodule"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary delete-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="删除文件">
                                    <span class="iconfont icon-close"></span>
                                </a>
                            </span>
                            </div>
                            <div class="upload-preview text-center upload-preview">
                                <span class="upload-preview-tip">750&times;180</span>
                                <img class="upload-preview-img" src="<?=$list['ad_pic_url']?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">广告图片跳转链接</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group page-link-input">
                            <input class="form-control link-input"
                                   name="page_url"
                                   readonly
                                   value="<?= $list['page_url'] ?>">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pick-link-btn" href="javascript:" open-type="navigate">选择链接</a>
                        </span>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">充值说明图标</label>
                    </div>
                    <div class="col-9">

                        <div class="upload-group">
                            <div class="input-group">
                                <input class="form-control file-input" name="p_pic_url" value="<?=$list['p_pic_url']?>">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary upload-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="上传文件">
                                    <span class="iconfont icon-cloudupload"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary select-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="从文件库选择">
                                    <span class="iconfont icon-viewmodule"></span>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <a class="btn btn-secondary delete-file" href="javascript:" data-toggle="tooltip"
                                   data-placement="bottom" title="删除文件">
                                    <span class="iconfont icon-close"></span>
                                </a>
                            </span>
                            </div>
                            <div class="upload-preview text-center upload-preview">
                                <span class="upload-preview-tip">36&times;36</span>
                                <img class="upload-preview-img" src="<?=$list['p_pic_url']?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="col-form-label">充值说明</label>
                    </div>
                    <div class="col-9">
                        <textarea class="form-control" style="min-height: 125px" name="help"><?=$list['help']?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        <input type="button" class="btn btn-default ml-4" 
                               name="Submit" onclick="javascript:history.back(-1);" value="返回">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
