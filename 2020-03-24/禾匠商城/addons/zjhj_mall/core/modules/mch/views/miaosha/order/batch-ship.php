<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$urlManager = Yii::$app->urlManager;
$this->title = '批量发货';
?>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" v-if="form_list==''">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">导入模板</label>
                </div>
                <div class="col-sm-4">
                    <div class="csv-picker" data-url="<?= $urlManager->createUrl(['upload/temp-file']) ?>">
                        <div class="input-group">
                            <input class="csv-picker-input csv form-control" name="url"
                                    placeholder="请输入模板地址">
                            <a href="javascript:" class="btn btn-secondary csv-picker-btn">选择模板</a> 
                        </div>

                        <div class="csv-preview"></div> 
                    </div>
                </div>
            </div>
 
            <div class="form-group row"> 
                <div class="form-group-label col-2 text-right">
                    <label class="col-form-label">快递公司</label>
                </div>
                <div class="col-3">
                    <div class="input-group mb-3">
                        <input class="form-control" placeholder="请输入快递公司" type="text" autocomplete="off"
                               name="express" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right"
                                 style="max-height: 500px;overflow: auto">
                                <?php if (count($express_list['private'])) : ?>
                                    <?php foreach ($express_list['private'] as $item) : ?>
                                        <a class="dropdown-item" href="javascript:"><?= $item ?></a>
                                    <?php endforeach; ?>
                                    <div class="dropdown-divider"></div>
                                <?php endif; ?>
                                <?php foreach ($express_list['public'] as $item) : ?>
                                    <a class="dropdown-item" href="javascript:"><?= $item ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-file-btn" href="javascript:">发货</a>
                    <a class="btn btn-primary hide-status-btn" href="<?= $urlManager->createUrl(['mch/order/ship-model']) ?>" >默认格式下载</a>
                </div>
            </div>
        </form>
            <table class="table table-bordered bg-white" v-if="form_list!=''">
                <thead >
                <tr>
                    <th>订单不存在</th>
                    <th>订单取消</th>
                    <th>已发货商品</th>
                    <th>自提订单</th>
                    <th>未支付</th> 
                    <th>保存失败</th>
                    <th>保存成功</th>
                </tr>
                </thead>
                <col style="width: 15%">
                <col style="width: 15%">
                <col style="width: 15%">
                <col style="width: 15%">
                <col style="width: 15%">
                <col style="width: 15%">
                <col style="width: 15%">
                <tbody>
        
                    <tr v-for="(item,index) in form_list">
                    <td class="nowrap">{{item[0]}}</td>
                    <td class="nowrap">{{item[1]}}</td>
                    <td class="nowrap">{{item[2]}}</td>
                    <td class="nowrap">{{item[3]}}</td>
                    <td class="nowrap">{{item[4]}}</td>
                    <td class="nowrap">{{item[5]}}</td>
                    <td class="nowrap">{{item[6]}}</td>
                    </tr>
    
                </tbody>
            </table>
    </div>
</div>

<script type="text/javascript">
    var app = new Vue({
        el: "#app",
        data: {
            form_list: [],
        }
    });

    $(document).on('click', '.auto-form .auto-file-btn', function () {
        var form = $(this).parents('.auto-form');
        submit(form);
        return false;
    });

    function submit(form) {
        var btn = $(form.find('.auto-file-btn'));
        btn.btnLoading(btn.text());
        $.ajax({
            url: form.attr('action') || '',
            type: form.attr('method') || 'get',
            dataType: 'json',
            data: form.serialize(),
            success: function (res) {
                btn.btnReset();
                if(res.code==0){
                    app.form_list = res.data;
                };
                $.alert({
                    content: res.msg,
                });
            },
            error: function (e) {
                btn.btnReset();
                $.alert({
                    title: '<span class="text-danger">系统错误</span>',
                    content: e.responseText,
                });
            }
        });
    };

    // Custom example logic
    $(document).ready(function () {
        var csv_picker = $('.csv-picker');
        csv_picker.each(function (i) {
            var picker = this;
            var el = $(this);
            var btn = el.find('.csv-picker-btn');
            var url = el.data('url');
            var input = el.find('.csv-picker-input');
            var view = el.find('.csv-preview');

            function uploaderCsv() {

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
                            {title: "files", extensions: "csv"}
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
                            $('.csv-check').prop('href', res.data.url);
                            $('.csv-preview').find('span').html('100%');
                        },

                        UploadProgress: function (up, file) {
                            var percent = file.percent - 1;
                            $($("#" + file.id).find('b')[0]).html('<span>' + percent + "%</span>");
                        },

                        Error: function (up, err) {
                            console.log(err,11);
                            if(err.code=='-601'){
                                    $.alert({
                                        title: '<span class="text-danger">上传失败</span>',
                                        content: '文件扩展错误',
                                    });
                            }
                        },
                        UploadComplete: function (uploader, files) {
                            btn.btnReset();
                            uploader.destroy();
                            uploaderCsv();
                        }
                    }
                });
                uploader.init();
            }
 
            uploaderCsv();
        });
    });
    $(document).on('change', '.csv', function () {
        $('.csv-check').attr('href', this.value);
    });
</script>
