<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '首页导航图标编辑';
$this->params['active_nav_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/store/home-nav']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[name]" value="<?= $model['name'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">排序</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="number" name="model[sort]"
                           value="<?= $model['sort'] ? $model['sort'] : 100 ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">图标</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="model[pic_url]"
                                   value="<?= $model['pic_url'] ?>">
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
                            <span class="upload-preview-tip">88&times;88</span>
                            <img class="upload-preview-img" src="<?= $model['pic_url'] ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">链接</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group page-link-input">
                        <input class="form-control link-input"
                               readonly
                               name="model[url]"
                               value="<?= $model['url'] ?>">
                        <input class="link-open-type" name="model[open_type]" value="<?= $model['open_type'] ?>"
                               type="hidden">
                               
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pick-link-btn" href="javascript:">选择链接</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否显示</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio1" <?= $model['is_hide'] == 0 ? 'checked' : null ?>
                               value="0"
                               name="model[is_hide]" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">显示</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio2" <?= $model['is_hide'] == 1 ? 'checked' : null ?>
                               value="1"
                               name="model[is_hide]" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">隐藏</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>