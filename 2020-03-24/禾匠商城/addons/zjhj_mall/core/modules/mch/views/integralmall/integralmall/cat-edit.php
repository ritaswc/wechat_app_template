<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/10
 * Time: 11:09
 */

$urlManager = Yii::$app->urlManager;
$this->title = '商品分类编辑';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/integralmall/integralmall/cat']) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">分类名称</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="model[name]" value="<?= $list['name'] ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">排序</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="model[sort]"
                               value="<?= $list['sort'] ? $list['sort'] : 100 ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">分类图标</label>
                    </div>
                    <div class="col-sm-6">
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
                <div class="form-group row">
                    <div class="form-group-label col--sm-2 text-right">
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        <input type="button" class="btn btn-default ml-4" 
                               name="Submit" onclick="javascript:history.back(-1);" value="返回">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
