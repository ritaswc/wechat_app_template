<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/6
 * Time: 15:40
 */
?>
<style>
    .pick-image-box {
        border: 1px dashed rgba(0, 0, 0, 0.25);
        color: rgba(0, 0, 0, .45);
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        position: relative;
        margin-bottom: 1rem;
        border-radius: .15rem;
    }

    .pick-image-box:hover {
        background: rgba(0, 0, 0, .015);
    }

    .pick-image-box:active {
        background: rgba(0, 0, 0, .05);
    }

    .pick-image-box .uploading {
        background: rgb(247, 247, 247);
        color: #fff;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        padding: 0;
        display: none;
    }

    .pick-image-modal .upload-image-list {
        margin-bottom: 1rem;
        max-height: 20rem;
        overflow-y: auto;
    }

    .pick-image-modal .upload-image-item {
        height: 5rem;
        background-size: cover;
        background-position: center;
        margin-top: 1rem;
        border: 1px solid #e3e3e3;
    }
</style>
<div class="modal fade pick-image-modal" data-backdrop="static" id="pic_image_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <b class="modal-title">选择图片</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="pick-image-box" id="pick_image_box">
                    <div>点击上传文件</div>
                    <div class="uploading" style="padding: 0;">
                        <img style="height: 100%;width: auto"
                             src="<?= Yii::$app->request->baseUrl ?>/statics/images/loading2.svg">
                    </div>
                    <div hidden>支持拖放上传</div>
                </div>
                <div v-if="pic_list.length>0" class="upload-image-list row">
                    <div v-for="pic in pic_list" class="col-3">
                        <div class="upload-image-item"
                             v-bind:style="'background-image: url('+pic.url+')'"
                             v-bind:data-url="pic.url"></div>
                    </div>
                </div>
                <div v-else class="text-center text-muted p-3">暂无历史图片</div>
                <div class="text-center">
                    <a href="javascript:" class="btn btn-primary btn-sm image-list-reload">刷新</a>
                    <a href="javascript:" class="btn btn-primary btn-sm image-list-more">更多</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>var _upload_image_url = "<?=\Yii::$app->urlManager->createUrl(['upload/image'])?>";</script>
<script>
    (function () {
        var _args = null;
        $.fn.extend({
            pickImage: function (args) {
                var $this = this;
                $this.each(function () {
                    var _this = this;
                    _args = args;
                    if ($(_this).attr("data-pick-file"))
                        return true;
                    $(_this).attr("data-pick-file", true);
                    $(this).on("click", function () {
                        _args._this = this;
                        $(".pick-image-modal").modal("show");
                        if ($(".pick-image-modal").attr("data-bind-click"))
                            return;
                        $(".pick-image-modal").attr("data-bind-click", true);
                        $(document).on("click", ".upload-image-list .upload-image-item", function () {
                            _args.success({
                                code: 0,
                                data: {
                                    url: $(this).attr("data-url"),
                                }
                            }, _args._this);
                            try {
                                $(".pick-image-modal").modal("hide");
                            } catch (e) {
                            }
                        });
                    });
                });
            }
        });

        $(document).ready(function () {
            $("#pick_image_box").plupload({
                url: _upload_image_url,
                beforeUpload: function ($this, _this) {
                    $this.find(".uploading").show();
                },
                success: function (res, _this, $this) {
                    $(".pick-image-modal").modal("hide");
                    $this.find(".uploading").hide();
                    if (_args.success)
                        _args.success(res, _args._this);
                }
            });
        });


        var pic_image_modal = new Vue({
            el: "#pic_image_modal",
            data: {
                pic_list: [],
            }
        });

        var pic_list_page = 1;
        getPicList();

        function getPicList() {
            $(".image-list-more").btnLoading();
            $(".image-list-reload").btnLoading();
            $.ajax({
                url: "<?=Yii::$app->urlManager->createUrl(['mch/store/upload-file-list'])?>",
                data: {
                    type: "image",
                    dataType: "json",
                    page: pic_list_page,
                },
                dataType: "json",
                success: function (res) {
                    $(".image-list-more").btnReset();
                    $(".image-list-reload").btnReset();
                    if (res.code == 0) {
                        pic_list_page++;
                        for (var i in res.data.list) {
                            pic_image_modal.pic_list.push({
                                url: res.data.list[i].file_url,
                            });
                        }
                    }
                }
            });
        }

        $(".image-list-reload").on("click", function () {
            $(".image-list-more").btnLoading();
            $(".image-list-reload").btnLoading();
            $.ajax({
                url: "<?=Yii::$app->urlManager->createUrl(['mch/store/upload-file-list'])?>",
                data: {
                    type: "image",
                    page: 1,
                },
                dataType: "json",
                success: function (res) {
                    $(".image-list-more").btnReset();
                    $(".image-list-reload").btnReset();
                    if (res.code == 0) {
                        pic_list_page++;
                        pic_image_modal.pic_list = [];
                        for (var i in res.data.list) {
                            pic_image_modal.pic_list.push({
                                url: res.data.list[i].file_url,
                            });
                        }
                    }
                }
            });
        });
        $(".image-list-more").on("click", function () {
            getPicList();
        });


    })();


</script>