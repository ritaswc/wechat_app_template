<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '专题编辑';
$this->params['active_nav_group'] = 8;
?>
<style>
    .goods-item,
    .video-item {
        margin: 1rem 0;
    }

    .goods-item .goods-name,
    .video-item .video-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/topic/index']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">标题</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="title" value="<?= str_replace("\"", "&quot", $model->title) ?>">
                </div>
            </div>
            <div class="form-group row" hidden>
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">副标题</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="sub_title" value="<?= str_replace("\"", "&quot", $model->sub_title) ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">专题列表布局方式</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input value="0" <?= $model->layout == 0 ? 'checked' : null ?> name="layout" type="radio"
                               class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">小图模式</span>
                    </label>
                    <label class="radio-label">
                        <input value="1" <?= $model->layout == 1 ? 'checked' : null ?> name="layout" type="radio"
                               class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">大图模式</span>
                    </label>
                    <div class="text-muted fs-sm">小图模式建议封面图片大小：268×202，大图模式建议封面图片大小：702×350</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">封面图</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="cover_pic" value="<?= $model->cover_pic ?>">
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
                            <span class="upload-preview-tip">268&times;202</span>
                            <img class="upload-preview-img" src="<?= $model->cover_pic ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">海报分享图</label>
                </div>
                <div class="col-sm-6">
                    <div class="upload-group">
                        <div class="input-group">
                            <input class="form-control file-input" name="qrcode_pic" value="<?= $model->qrcode_pic ?>">
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
                            <span class="upload-preview-tip">670&times;394</span>
                            <img class="upload-preview-img" src="<?= $model->qrcode_pic ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否精选</label>
                </div>
                 <div class="col-sm-6">
                    <label class="radio-label">
                        <input value="0" <?= $model->is_chosen == 0 ? 'checked' : null ?> name="is_chosen" type="radio"
                               class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">不精选</span>
                    </label>
                    <label class="radio-label">
                        <input value="1" <?= $model->is_chosen == 1 ? 'checked' : null ?> name="is_chosen" type="radio"
                               class="custom-control-input">
                        <span class="label-icon"></span> 
                        <span class="label-text">精选</span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-2 text-right">
                    <label class="col-form-label required">分类</label>
                </div>
                <div class="col-2">
                    <select class="form-control" name="type">
                        <option value="-1" <?= $user->type == -1 ? "selected" : "" ?>>全部</option>
                            <?php foreach ($select as $item) : ?>
                            <option
                                value="<?= $item->value ?>" <?= ($item->value == $model->type) ? "selected" : "" ?>><?= $item->name ?></option>
                            <?php endforeach; ?> 
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">虚拟阅读量</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="virtual_read_count" value="<?= $model->virtual_read_count ?>">
                    <div class="text-muted fs-sm">手机端显示的阅读量=实际阅读量+虚拟阅读量</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">虚拟收藏量</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="virtual_favorite_count"
                           value="<?= $model->virtual_favorite_count ?>">
                    <div class="text-muted fs-sm">手机端显示的收藏量=实际收藏量+虚拟收藏量</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">排序</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" name="sort" value="<?= $model->sort ?>">
                    <div class="text-muted fs-sm">升序，数字越小排序越靠前，默认1000</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">专题详情</label>
                </div>
                <div class="col-sm-6">
                    <div flex="dir:left box:first">
                        <div>
                                <textarea class="short-row" id="editor"
                                          style="width: 30rem"
                                          name="content"><?= $model->content ?></textarea>
                        </div>
                        <div class="text-right">
                            <div>
                                <a href="javascript:" class="btn btn-secondary mb-3" data-toggle="modal"
                                   data-target="#searchGoodsModal">
                                    <i class="iconfont icon-goods" style="font-size: 2rem;color: #555"></i>
                                    <div class="fs-sm">添加商品</div>
                                </a>
                            </div>
                            <div>
                                <a href="javascript:" class="btn btn-secondary" data-toggle="modal"
                                   data-target="#searchVideoModal">
                                    <i class="iconfont icon-video1" style="font-size: 2rem;color: #555"></i>
                                    <div class="fs-sm">添加视频</div>
                                </a>
                            </div>
                        </div>
                    </div>
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

        <!-- Modal -->
        <div class="modal fade" data-backdrop="static" id="searchGoodsModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b class="modal-title" id="exampleModalLongTitle">添加商品</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="search-goods-form"
                              action="<?= $urlManager->createUrl(['mch/topic/search-goods']) ?>">
                            <div class="input-group">
                                <input class="form-control" placeholder="商品名" name="keyword">
                                <span class="input-group-btn">
                            <button class="btn btn-secondary">搜索</button>
                        </span>
                            </div>
                        </form>
                        <template v-if="goods_list">
                            <template v-if="goods_list.length==0">
                                <div class="p-5 text-center text-muted">搜索结果为空</div>
                            </template>
                            <template v-else>
                                <div v-for="(item,index) in goods_list" class="goods-item" flex="dir:left">
                                    <div style="width: 60%">
                                        <div class="goods-name">{{item.name}}</div>
                                    </div>
                                    <div style="width: 20%" class="goods-price text-right">￥{{item.price}}</div>
                                    <div style="width: 20%" class="goods-price text-right">
                                        <a v-bind:index="index" href="javascript:" class="insert-goods">添加</a>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <div class="p-5 text-center text-muted">请输入关键字搜索商品</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" data-backdrop="static" id="searchVideoModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b class="modal-title" id="exampleModalLongTitle">添加视频</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="search-video-form"
                              action="<?= $urlManager->createUrl(['mch/topic/search-video']) ?>">
                            <div class="input-group">
                                <input class="form-control" placeholder="视频专区的视频名" name="keyword">
                                <span class="input-group-btn">
                            <button class="btn btn-secondary">搜索</button>
                        </span>
                            </div>
                        </form>
                        <template v-if="video_list">
                            <template v-if="video_list.length==0">
                                <div class="p-5 text-center text-muted">搜索结果为空</div>
                            </template>
                            <template v-else>
                                <div v-for="(item,index) in video_list" class="video-item" flex="dir:left">
                                    <div style="width: 80%" class="pr-3">
                                        <div class="video-name">{{item.name}}</div>
                                    </div>
                                    <div style="width: 20%" class="text-right">
                                        <a v-bind:index="index" href="javascript:" class="insert-video">添加</a>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <div class="p-5 text-center text-muted">请输入关键字搜索视频</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js?v=1.6.2"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            goods_list: null,
            video_list: null,
        },
    });
    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
        enableAutoSave: false,
        saveInterval: 1000 * 3600,
        enableContextMenu: false,
        autoHeightEnabled: false,
    });

    $(document).on("submit", ".search-goods-form", function () {
        var form = $(this);
        var btn = form.find(".btn");
        btn.btnLoading("正在搜索");
        $.ajax({
            url: form.attr("action"),
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.goods_list = res.data.list;
                }
            }
        });
        return false;
    });

    $(document).on("click", ".insert-goods", function () {
        var index = $(this).attr("index");
        var goods = app.goods_list[index];
        var _html = '';
        _html += '<br><div>';
        _html += '<a class="goods-link" goods="true" href="/pages/goods/goods?id=' + goods.id + '" style="display: block;background: #f3f3f3;border: 1px solid #eee;position: relative;height: 6rem;color: #333;text-decoration: none">';
        _html += '<img mode="aspectFill" class="goods-img" src="' + goods.cover_pic + '">';
        _html += '<div class="goods-info flex-col" style="padding:.5rem .5rem .5rem 6rem">';
        _html += '<div class="goods-name flex-grow-1">' + goods.name + '</div>';
        _html += '<div class="flex-grow-0">';
        _html += '<b class="goods-price" style="color:#ff4544">￥' + goods.price + '</b>';
        _html += '<span class="buy-btn" style="display: inline-block;float: right;font-size: 12px;border: 1px solid #ff4544;color: #ff4544;border-radius: .15rem;padding: .25rem .5rem;">去购买</span>';
        _html += '</div>';
        _html += '</div>';
        _html += '</a>';
        _html += '</div>';
        ue.execCommand("inserthtml", _html);
    });


    $(document).on("submit", ".search-video-form", function () {
        var form = $(this);
        var btn = form.find(".btn");
        btn.btnLoading("正在搜索");
        $.ajax({
            url: form.attr("action"),
            dataType: "json",
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.video_list = res.data.list;
                }
            }
        });
        return false;
    });

    $(document).on("click", ".insert-video", function () {
        var index = $(this).attr("index");
        var video = app.video_list[index];
        var _html = '';
        _html += '<video src="' + video.src + '"></video>';
        _html += '';
        _html += '';
        _html += '';
        _html += '';
        _html += '';
        _html += '';
        ue.execCommand("inserthtml", _html);
    });


</script>
