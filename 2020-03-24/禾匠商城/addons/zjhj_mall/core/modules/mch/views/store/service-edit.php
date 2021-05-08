<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/4
 * Time: 13:43
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '商品服务编辑';
$this->params['active_nav_group'] = 9;
$statics = Yii::$app->request->baseUrl . '/statics';
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/store/service']) ?>">
            <input style="display: none;" name="id" value="<?= $service['id'] ?>">
            <div class="form-group row">
                <div class="col-2 text-right">
                    <label class=" col-form-label">服务内容</label>
                </div>
                <div class="col-6">
                    <input class="form-control short-row" name="service"
                           value="<?= $service['service'] ?>">
                    <div class="fs-sm text-muted">例子：正品保障,极速发货,7天退换货。多个请使用英文逗号<kbd>,</kbd>分隔
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
    </div>
</div>

<script type="text/javascript">
    // Custom example logic
    $(document).ready(function () {
        var video_picker = $('.video-picker');
        video_picker.each(function (i) {
            var picker = this;
            var el = $(this);
            var btn = el.find('.video-picker-btn');
            var url = el.data('url');
            var input = el.find('.video-picker-input');
            var view = el.find('.video-preview');

            function uploaderVideo() {

                var el_id = $.randomString(32);
                btn.attr("id", el_id);

                var uploader = new plupload.Uploader({
                    runtimes: 'html5,flash,silverlight,html4',
                    browse_button: el_id, // you can pass an id...
                    url: url,
                    flash_swf_url: '<?=$statics?>/mch/js/Moxie.swf',
                    silverlight_xap_url: '<?=$statics?>/mch/js/Moxie.xap',

                    filters: {
                        max_file_size: '50mb',
                        mime_types: [
                            {title: "Video files", extensions: "mp4"}
                        ]
                    },

                    init: {
                        PostInit: function () {

                        },

                        FilesAdded: function (up, files) {
                            $('.form-error').hide();
                            uploader.start();
                            btn.btnLoading("正在上传");
                            uploader.disableBrowse(true);

                            plupload.each(files, function (file) {
                                console.log(file)
                                view.html('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>');
                            });
                        },
                        FileUploaded: function (uploader, file, responseObject) {
                            if (responseObject.status == undefined || responseObject.status != 200) {
                                return true;
                            }
                            var res = $.parseJSON(responseObject.response);
                            if (res.code != 0) {
                                $('.form-error').html(res.msg).show();
                                return true;
                            }
                            $(input).val(res.data.url);
                            $('.video-check').prop('href', res.data.url);
                            $('.video-preview').find('span').html('100%');
                        },

                        UploadProgress: function (up, file) {
                            var percent = file.percent - 1;
                            $($("#" + file.id).find('b')[0]).html('<span>' + percent + "%</span>");
//                            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                        },

                        Error: function (up, err) {
                            $('.form-error').html('文件大小超出配置').show();
//                            document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                        },
                        UploadComplete: function (uploader, files) {
                            btn.btnReset();
                            uploader.destroy();
                            uploaderVideo();
                        }
                    }
                });
                uploader.init();
            }

            uploaderVideo();
        });
    });
    $(document).on('change', '.video', function () {
        $('.video-check').attr('href', this.value);
    });
    $('.num').html($("textarea[name='model[content]']").val().length);
    $(document).on('input propertychange', "textarea[name='model[content]']", function () {
        var a = $(this).val().length;
        $('.form-error').hide();
        if (a > 100) {
            var num = $(this).val().substr(0, 100);
            $(this).val(num);
            $('.form-error').html('详情介绍不能超过100个字').show();
        } else {
            $('.num').html(a)
        }
    });
</script>







