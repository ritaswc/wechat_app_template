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
$this->title = '图片魔方编辑';
$this->params['active_nav_group'] = 1;
?>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/store/home-block']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">默认样式</label>
                </div>
                <div class="col-sm-8">
                    <img class="mb-3" src="<?= Yii::$app->request->baseUrl ?>/statics/images/img-block-demo.png"
                         style="width: 100%;border: 1px solid #eee;">
                    <div class="alert alert-info rounded-0">单图的图片高度不限定，高度根据原图比例自动调整</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">样式一</label>
                </div>
                <div class="col-sm-8">
                    <img class="mb-3" src="<?= Yii::$app->request->baseUrl ?>/statics/images/img-block-demo-1.png"
                         style="width: 100%;border: 1px solid #eee;">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">样式二</label>
                </div>
                <div class="col-sm-8">
                    <img class="mb-3" src="<?= Yii::$app->request->baseUrl ?>/statics/images/img-block-demo-2.png"
                         style="width: 100%;border: 1px solid #eee;">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">板块名称</label>
                </div>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">板块图片</label>
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
                    <label class="col-form-label">板块样式</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input id="radio1" <?=$model->style == 0 ? "checked" : ""?>
                               value="0"
                               name="style" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">默认样式</span>
                    </label>
                    <label class="radio-label" v-if="pic_list.length>1">
                        <input id="radio1" <?=$model->style == 1 ? "checked" : ""?>
                               value="1"
                               name="style" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">样式一</span>
                    </label>
                    <label class="radio-label" v-if="pic_list.length==4">
                        <input id="radio1" <?=$model->style == 2 ? "checked" : ""?>
                               value="2"
                               name="style" type="radio" class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">样式二</span>
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

<?php
$model_data = json_decode($model->data, true);
$pic_list = $model_data['pic_list'];
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
            pic_list: <?=json_encode($pic_list, JSON_UNESCAPED_UNICODE)?>,
        }
    });

    $(document).on("click", ".pic-delete", function () {
        var i = $(this).attr("data-index");
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