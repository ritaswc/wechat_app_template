<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 * @var \yii\web\View $this
 */
$urlManager = Yii::$app->urlManager;
$this->title = '首页设置';
$this->params['active_nav_group'] = 1;
?>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/uploadVideo.js"></script>
<style>
    body.dragging, body.dragging * {
        cursor: move !important;
    }

    .dragged {
        position: absolute;
        z-index: 2000;
        box-shadow: 2px 2px 1px rgba(0, 0, 0, .05);
    }

    .home-block {
        position: relative;
    }

    .home-block .block-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .home-block .block-name {
        color: #fff;
        padding: 6px;
        text-align: center;
        background: rgba(0, 0, 0, .2);
        opacity: .9;
    }

    .home-block:hover .block-name {
        background: rgba(0, 0, 0, .7);
        opacity: 1;
    }

    .home-block .block-img {
        width: 100%;
        height: auto;
    }

    .sortable-ghost {
        opacity: .3;
    }

    .mobile-box {
        width: 219px;
        height: 450px;
        background-image: url("<?=Yii::$app->request->baseUrl?>/statics/images/mobile-iphone.png");
        background-size: cover;
        position: relative;
        font-size: .85rem;
        float: left;
        margin-right: 1rem;
    }

    .mobile-box .mobile-screen {
        position: absolute;
        top: 52px;
        left: 12px;
        right: 13px;
        bottom: 54px;
        border: 1px solid #999;
        background: #ccc;
        overflow-y: hidden;
    }

    .mobile-box .mobile-navbar {
        position: absolute;
        top: 0px;
        left: 0px;
        right: 0px;
        height: 38px;
        line-height: 38px;
        text-align: center;
        background: #fff;
    }

    .mobile-box .mobile-content {
        position: absolute;
        top: 38px;
        left: 0;
        right: 0;
        bottom: 0;
        overflow-y: auto;
    }

    .mobile-box .mobile-content::-webkit-scrollbar {
        width: 2px;
    }

    .edit-box {
        list-style: none;
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .edit-box .module-item {
        background: #fff;
        cursor: move;
        border: none;
        margin-bottom: 1px;
    }

    .edit-box .module-item {
    }

    .right-box {
        float: left;
        width: 480px;
    }

    .all-module-list {
        border: 1px solid #eee;
        border-radius: 5px;
    }

    .all-module-list .panel-body {
        height: 280px;
        overflow: auto;
    }

    .all-module-list .module-item {
        display: inline-block;
        border: 1px solid #eee;
        background: #fff;
        width: 80px;
        height: 80px;
        overflow: hidden;
        float: left;
        margin-right: 1rem;
        margin-bottom: 1rem;
    }

    .all-module-list .module-item:hover {
        border-color: #b8dcff;
    }

    .all-module-list .module-name {
        height: 50px;
        text-align: center;
        padding: 5px 0;
    }

    .all-module-list .module-option {
        text-align: center;
        border-top: 1px dashed #eee;
        height: 30px;
        line-height: 30px;
        display: block;
    }

    .all-module-list .edit {
        float: left;
        width: 50%;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="" method="post">

            <div class="clearfix">
                <div class="mobile-box">
                    <div class="mobile-screen">
                        <div class="mobile-navbar">商城首页</div>
                        <div class="mobile-content">
                            <ol class="edit-box" id="sortList">
                                <li v-for="(item,i) in edit_list" class="module-item"
                                    v-bind:data-module-name="item.name">
                                    <div style="position: relative;height: 0;z-index:2;">
                                        <div class="operations">
                                            <a href="javascript:" class="operate-icon item-delete"
                                               v-bind:data-index="i">
                                                <img style="width: 16px;height: 16px"
                                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/icon-delete.png">
                                            </a>
                                            <a href="javascript:" class="operate-icon item-update"
                                               v-bind:data-name="item.name" v-if="item.name.indexOf('video') >= 0">
                                                <img style="width: 16px;height: 16px"
                                                     src="<?= Yii::$app->request->baseUrl ?>/statics/images/icon-edit.png">
                                            </a>
                                        </div>
                                    </div>
                                    <div v-html="item.content"></div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="right-box">
                    <div class="panel mb-3 all-module-list">
                        <div class="panel-header">可选模块</div>
                        <div class="panel-body">
                            <div v-for="(item,i) in module_list" class="module-item"
                                 v-bind:data-module-name="item.name">
                                <div>
                                    <span hidden>{{item.name}}</span>
                                    <div class="module-name">{{item.display_name}}</div>
                                    <div v-if="allow_list.indexOf(item.name) >= 0">
                                        <a href="javascript:" class="module-option item-add edit" v-bind:data-index="i">添加</a>
                                        <a href="javascript:" class="module-option item-update edit"
                                           v-bind:data-index="i">编辑</a>
                                    </div>
                                    <a v-else href="javascript:" class="module-option item-add"
                                       v-bind:data-index="i">添加</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-box ml-4" style="width: 580px;">
                    <div class="panel mb-3 all-module-list" v-if="selected == 'topic'">
                        <div class="panel-header">专题自定义</div>
                        <div class="panel-body" style="padding-right: 15px;">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-2 text-right">
                                    <label class="col-form-label">首页专题显示数量</label>
                                </div>
                                <div class="col-sm-6">
                                    <select class="form-control" name="topic[count]" v-model="update_list.topic.count">
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
                                            <input class="form-control file-input img" name="topic[logo_1]"
                                                   data-index="logo_1"
                                                   v-model="update_list.topic.logo_1">
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
                                            <input class="form-control file-input img" name="topic[logo_2]"
                                                   data-index="logo_2"
                                                   v-model="update_list.topic.logo_2">
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
                                            <input class="form-control file-input img" name="topic[heated]"
                                                   data-index="heated"
                                                   v-model="update_list.topic.heated">
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
                    <div class="panel mb-3 all-module-list" v-if="selected == 'notice'">
                        <div class="panel-header">公告自定义</div>
                        <div class="panel-body" style="padding-right: 15px;">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">公告名称</label>
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="notice[name]"
                                           v-model="update_list.notice.name">

                                    <div class="text-danger fs-sm">建议：公告名称最好不要超过4个字</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">公告内容</label>
                                </div>
                                <div class="col-sm-6">
                                    <textarea class="form-control" type="text" rows="3" placeholder="请填写商城公告"
                                              name="notice" v-model="notice"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">公告背景色</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="color" name="notice[bg_color]" v-model="update_list.notice.bg_color">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">公告文字颜色</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="color" name="notice[color]" v-model="update_list.notice.color">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">公告图标</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="upload-group">
                                        <div class="input-group">
                                            <input class="form-control file-input img" name="notice[icon]"
                                                   data-index="icon"
                                                   v-model="update_list.notice.icon">
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
                                            <span class="upload-preview-tip">36&times;36</span>
                                            <img class="upload-preview-img"
                                                 :src="update_list.notice.icon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--视频自定义-->
                    <div class="panel mb-3 all-module-list" :style="{display:selected=='video'?'block':'none'}">
                        <div class="panel-header">视频自定义</div>
                        <div class="panel-body" style="padding-right: 15px;">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">视频链接</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="video-picker"
                                         data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                                        <div class="input-group">
                                            <input class="video-picker-input video form-control img" name="video[][url]"
                                                   v-model="video.url" data-index="video_url"
                                                   placeholder="请输入视频源地址或者选择上传视频">
                                            <a href="javascript:" class="btn btn-secondary video-picker-btn">选择视频</a>
                                        </div>
                                        <div class="video-preview"></div>
                                        <div>
                                            <span class="text-danger">支持格式mp4;支持编码H.264;</span>
                                        </div>
                                        <div>
                                            <span class="text-success">支持腾讯视频;例如：https://v.qq.com/x/page/h056607xye8.html</span>
                                        </div>
                                        <div>
                                            <span
                                                class="text-danger">视频大小不能超过<?= \app\models\UploadForm::getMaxUploadSize() ?>
                                                M</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">封面图</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="upload-group">
                                        <div class="input-group">
                                            <input class="form-control file-input img" name="video[][pic_url]"
                                                   v-model="video.pic_url" data-index="pic_url">
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
                                            <span class="upload-preview-tip">750&times;400</span>
                                            <img class="upload-preview-img" :src="video.pic_url">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--优惠券自定义-->
                    <div class="panel mb-3 all-module-list" v-if="selected == 'coupon'">
                        <div class="panel-header">优惠券自定义</div>
                        <div class="panel-body" style="padding-right: 15px;">
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">未领取图</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="upload-group">
                                        <div class="input-group">
                                            <input class="form-control file-input img" name="coupon[bg]"
                                                   v-model="update_list.coupon.bg" data-index="coupon_bg">
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
                                            <span class="upload-preview-tip">256&times;130</span>
                                            <img class="upload-preview-img" :src="update_list.coupon.bg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group-label col-sm-3 text-right">
                                    <label class="col-form-label">已领取图</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="upload-group">
                                        <div class="input-group">
                                            <input class="form-control file-input img" name="coupon[bg_1]"
                                                   v-model="update_list.coupon.bg_1" data-index="coupon_bg_1">
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
                                            <span class="upload-preview-tip">256&times;130</span>
                                            <img class="upload-preview-img" :src="update_list.coupon.bg_1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary mb-3 submit-btn" href="javascript:">保存</a>
                    <div class="text-muted">
                        提示：
                        <br>图片魔方可以添加到小程序端，如果没有可以<a
                            href="<?= $urlManager->createUrl(['mch/store/home-block-edit']) ?>">点击这里添加图片魔方</a>；
                        <br>首页更新小程序端下拉刷新就可以看到。
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Sortable.min.js"></script>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            module_list: <?=json_encode($module_list, true)?>,
            edit_list: <?=json_encode($edit_list, true)?>,
            allow_list: ['topic', 'notice', 'coupon'],
            selected: '',
            update_list:<?=json_encode($update_list, true)?>,
            notice:<?=json_encode($notice, true);?>,
            selected_id: -1,
            video: ""
        }
    });
    $(document).on("click", ".item-add", function () {
        var index = $(this).attr("data-index");
        var list = app.module_list[index];
        var obj = JSON.parse(JSON.stringify(list));
        if (obj.name == 'video') {
            var count = 0;
            $(app.edit_list).each(function (i) {
                if (app.edit_list[i].name.indexOf('video') != -1) {
                    var arr = app.edit_list[i].name.split('-');
                    if (parseInt(arr[1]) > count) {
                        count = parseInt(arr[1]);
                    }
                }
            });
            count += 1;
            obj.name = obj.name + '-' + count;
            var video_default = JSON.parse(JSON.stringify(app.update_list.video[0]));
            video_default['name'] = count;
            app.update_list.video.push(video_default);
        }
        app.edit_list.push(obj);
    });
    $(document).on("click", ".item-delete", function () {
        var index = $(this).attr("data-index");
        var item = $(this).parents(".module-item");
        var timeout = 200;
        item.slideUp(timeout, function () {
            item.addClass("delete");
            app.edit_list[index].delete = true;
        });
        var name = $(item).attr('data-module-name');
        var arr = name.split('-');
        var list = app.update_list.video;
        var new_list = [];
        $(list).each(function (i) {
            if (list[i].name == arr[1]) {
                return;
            } else {
                new_list.push(list[i]);
            }
        });
        app.update_list.video = new_list;
    });

    function removeArr(list, index) {
        var new_list = [];
        $(list).each(function (i) {
            if (i === index) {
                return;
            } else {
                new_list[i] = list[i];
            }
        });
        return new_list;
    }


    Sortable.create(document.getElementById("sortList"), {
        animation: 150,
    }); // That's all.


    $(document).on("click", ".submit-btn", function () {
        var module_list = [];
        $(".edit-box .module-item").each(function (i) {
            if ($(this).hasClass("delete"))
                return;
            var name = $(this).attr("data-module-name");
            module_list.push({
                name: name,
            });
        });
        app.update_list.video.splice(0, 1);
        var btn = $(this);
        var success = $(".form-success");
        var error = $(".form-error");
        success.hide();
        error.hide();
        btn.btnLoading(btn.text());
        $.ajax({
            type: "post",
            dataType: "json",
            data: {
                _csrf: _csrf,
                module_list: JSON.stringify(module_list),
                update_list: JSON.stringify(app.update_list),
                notice: app.notice
            },
            success: function (res) {
                $.alert({
                    content: res.msg,
                    confirm: function () {
                        if (res.code == 0) {
                            location.reload();
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.item-update', function () {
        var index = $(this).attr("data-index");
        if (index) {
            app.selected = app.module_list[index].name
        }
        var name = $(this).attr("data-name");
        if (name) {
            var arr = name.split('-');
            app.selected = arr[0];
            app.selected_id = arr[1];
            $(app.update_list.video).each(function (i) {
                if (app.update_list.video[i].name == arr[1]) {
                    app.video = app.update_list.video[i];
                }
            });
        }
    });

    $(document).on('change', ".img", function () {
        var index = $(this).data('index');
        var val = $(this).val();
        if (index == 'logo_1') {
            app.update_list.topic.logo_1 = val;
        }
        if (index == 'logo_2') {
            app.update_list.topic.logo_2 = val;
        }
        if (index == 'heated') {
            app.update_list.topic.heated = val;
        }
        if (index == 'icon') {
            app.update_list.notice.icon = val;
        }
        if (index == 'pic_url') {
            $(app.update_list.video).each(function (i) {
                if (app.update_list.video[i].name == app.selected_id) {
                    app.update_list.video[i].pic_url = val;
                }
            });
        }
        if (index == 'video_url') {
            $(app.update_list.video).each(function (i) {
                if (app.update_list.video[i].name == app.selected_id) {
                    app.update_list.video[i].url = val;
                }
            });
        }
        if (index == 'coupon_bg') {
            app.update_list.coupon.bg = val;
        }
        if (index == 'coupon_bg_1') {
            app.update_list.coupon.bg_1 = val;
        }
    });
</script>