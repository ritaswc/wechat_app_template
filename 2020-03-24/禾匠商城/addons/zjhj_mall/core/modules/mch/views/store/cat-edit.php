<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '分类编辑';
$this->params['active_nav_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/store/cat']) ?>">

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">父级分类</label>
                </div>
                <div class="col-sm-6">
                    <select class="form-control parent" name="model[parent_id]">
                        <option value="0">无</option>
                        <?php foreach ($parent_list as $cat) : ?>
                            <option value="<?= $cat->id ?>" <?= $cat->id == $list['parent_id'] ? 'selected' : '' ?>><?= $cat->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">分类名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[name]" value="<?= $list['name'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">排序</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="number" name="model[sort]"
                           value="<?= $list['sort'] ? $list['sort'] : 100 ?>">
                    <div class="text-muted fs-sm">排序值越小排序越靠前</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">分类图标</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="upload-group">
                            <div class="input-group">
                                <input class="form-control file-input" name="model[pic_url]"
                                       value="<?= $list['pic_url'] ?>">
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
                                <span class="upload-preview-tip">200&times;200</span>
                                <img class="upload-preview-img" src="<?= $list['pic_url'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">分类大图（显示在分类页面）</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="model[big_pic_url]"
                                   value="<?= $list['big_pic_url'] ?>">
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
                            <span class="upload-preview-tip">702&times;212</span>
                            <img class="upload-preview-img" src="<?= $list['big_pic_url'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="advert" <?= empty($list['parent_id']) ? '' : 'style="display:none"' ?>>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">分类广告</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="upload-group">
                            <div class="input-group">
                                <input class="form-control file-input" name="model[advert_pic]"
                                       value="<?= $list['advert_pic'] ?>">
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
                                <span class="upload-preview-tip">500&times;184</span>
                                <img class="upload-preview-img" src="<?= $list['advert_pic'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">分类广告链接</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group page-link-input">
                            <input class="form-control link-input advert_url"
                                   name="model[advert_url]"
                                   value="<?= $list['advert_url'] ?>" readonly>
                            <span class="input-group-btn">
                            <a class="btn btn-secondary pick-link-btn" href="javascript:" open-type="navigate">选择链接</a>
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否显示</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio1" <?= $list['is_show'] == 1 ? 'checked' : 'checked' ?>
                               value="1"
                               name="model[is_show]" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">显示</span>
                    </label>
                    <label class="radio-label">
                        <input id="radio2" <?= $list['is_show'] == 2 ? 'checked' : null ?>
                               value="2"
                               name="model[is_show]" type="radio" class="custom-control-input">
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

<script>
    $(document).on('change', '.parent', function () {
        var p = $(this).val();
        if (p == '0') {
            $('.advert').show();
        } else {
            $('input[name="model[advert_url]"]').val('').trigger('change');
            $('input[name="model[advert_pic]"]').val('').trigger('change');
            $('input[name="model[advert_pic]"]').next('.image-picker-view').css('background-image', 'url("")');
            $('.advert').hide();
        }
    })
</script>