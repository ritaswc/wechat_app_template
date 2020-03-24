<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '广告设置';
$this->params['active_nav_group'] = 10;
$this->params['is_group'] = 1;
?>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" autocomplete="off">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">图片示例</label>
                </div>
                <div class="col-sm-8">
                    <img src="<?= Yii::$app->request->baseUrl ?>/statics/images/img-block-demo.png"
                         style="width: 100%;border: 1px solid #eee;">
                </div>
            </div>


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">注</label>
                </div>
                <div class="col-sm-9" style="padding-top: calc(.5rem - 1px * 2);">只放一张图片时小程序端不会对图片裁剪，图片宽度填充屏幕宽度，高度自适应
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">广告图片</label>
                </div>
                <div class="col-sm-8">
                    <div class="row mb-3" v-for="(item,i) in pic_list">
                        <div class="col-sm-5">

                            <div class="upload-group">
                                <div class="input-group">
                                    <input class="form-control file-input"
                                           v-bind:index="i"
                                           v-bind:name="'pic_list['+i+'][pic_url]'"
                                           v-model="item.pic_url">
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
                                    <span class="upload-preview-tip">大小参考示例</span>
                                    <img class="upload-preview-img" v-bind:src="item.pic_url">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-5">
                            <div class="input-group page-link-input">
                                <input class="form-control link-input"
                                       readonly
                                       v-bind:index="i"
                                       v-bind:name="'pic_list['+i+'][url]'"
                                       v-model:value="item.url"
                                       value="<?= $store->copyright_url ?>">
                                <input class="link-open-type"
                                       v-bind:index="i"
                                       v-bind:name="'pic_list['+i+'][open_type]'"
                                       v-model:value="item.open_type"
                                       type="hidden">
                                <span class="input-group-btn">
                                        <a class="btn btn-secondary pick-link-btn" href="javascript:"
                                           open-type="navigate,wxapp">选择链接</a>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <a class="btn btn-danger pic-delete" v-bind:data-index="i" href="javascript:">删除</a>
                        </div>
                    </div>
                    <a v-if="pic_list.length<4" class="btn btn-secondary add-pic" href="javascript:">添加</a>
                    <div v-else class="text-muted">最多上传4张图片</div>
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
<?php
if (empty($pic_list)) {
    $pic_list = [
        [
            'pic_url' => '',
            'url' => '',
        ],
    ];
}
?>
<script>
    var upload_url = "<?=$urlManager->createUrl(['upload/image'])?>";
    var app = new Vue({
        el: "#app",
        data: {
            pic_list: <?=json_encode((array)$pic_list, JSON_UNESCAPED_UNICODE)?>,
        }
    });

    $(document).on("click", ".pic-delete", function () {
        var i = $(this).attr("data-index");
        console.log(JSON.stringify(app.pic_list));
        app.pic_list.splice(i, 1);
    });

    $(document).on("click", ".add-pic", function () {
        app.pic_list.push({
            pic_url: '',
            url: '',
        });
        setTimeout(function () {
            setPlUpload();
        }, 100);
    });

    $(document).on('change', '.file-input', function () {
        var index = $(this).attr('index');
        app.pic_list[index].pic_url = $(this).val();
    });

    $(document).on("change", ".link-input", function () {
        var index = $(this).attr("index");
        app.pic_list[index].url = $(this).val();
    });

    $(document).on("change", ".link-open-type", function () {
        var index = $(this).attr("index");
        app.pic_list[index].open_type = $(this).val();
    });

    function setPlUpload() {
        $(".pic-upload").plupload({
            url: upload_url,
            beforeUpload: function ($this, _this) {
                console.log($this);
                $($this).btnLoading("Loading");
            },
            success: function (res, _this, $this) {
                $($this).btnReset().text("上传");
                if (res.code == 0) {
                    var i = $(_this).attr("data-index");
                    app.pic_list[i].pic_url = res.data.url;
                }
            }
        });
    }

    setTimeout(function () {
        setPlUpload();
    }, 1);
</script>