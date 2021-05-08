<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8
 * Time: 14:03
 */
?>

<div class="panel mb-3 all-module-list" v-if="selected == 'topic'">
    <div class="panel-header">专题自定义</div>
    <div class="panel-body">
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">首页专题显示数量</label>
            </div>
            <div class="col-sm-6">
                <select class="form-control" name="cat_goods_cols" v-model="update_list.topic.count">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">专题LOGO1</label>
            </div>
            <div class="col-sm-6">
                <div class="upload-group">
                    <div class="input-group">
                        <input class="form-control file-input" v-model="update_list.topic.logo_1">
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
                        <span class="upload-preview-tip">104&times;32</span>
                        <img class="upload-preview-img"
                             :src="update_list.topic.logo_1">
                    </div>
                </div>
                <div>专题显示数量为1是显示</div>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">专题LOGO2</label>
            </div>
            <div class="col-sm-6">
                <div class="upload-group">
                    <div class="input-group">
                        <input class="form-control file-input" v-model="update_list.topic.logo_2">
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
                        <span class="upload-preview-tip">104&times;50</span>
                        <img class="upload-preview-img"
                             :src="update_list.topic.logo_2">
                    </div>
                </div>
                <div>专题显示数量为2是显示</div>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group-label col-sm-2 text-right">
                <label class="col-form-label">专题标签</label>
            </div>
            <div class="col-sm-6">
                <div class="upload-group">
                    <div class="input-group">
                        <input class="form-control file-input" v-model="update_list.topic.heated">
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
                        <span class="upload-preview-tip">54&times;28</span>
                        <img class="upload-preview-img"
                             :src="update_list.topic.heated">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
