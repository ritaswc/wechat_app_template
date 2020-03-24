<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 9:27
 */
defined('YII_ENV') or exit('Access Denied');
use \app\models\Level;

/* @var \app\models\Level $level */
$urlManager = Yii::$app->urlManager;
$this->title = '会员设置';
$this->params['active_nav_group'] = 4;
?>
<style>
    .attr-item .attr-delete {
        padding: .35rem .75rem;
        background: #d4cece;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
    }

    .attr-item .attr-delete:hover {
        text-decoration: none;
        color: #fff;
        background: #ff4544;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">

        <form method="post" class="auto-form" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/user/level']) ?>">

            <div class="form-body">
                <input type="hidden" name="scene" value="edit">

                <div class="form-group row">                    
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">等级</label>
                    </div>
                    <div class="col-5">
                        <select class="form-control" name="level">
                            <?php for ($i = 0; $i <= 100; $i++) : ?>
                                <option
                                    value="<?= $i ?>" <?= ($level->level == $i) ? "selected" : "" ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <div class="text-muted fs-sm">数字越大等级越高</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right"></div>
                    <div class="col-sm-6">
                        <div class="text-muted fs-sm text-danger">会员满足条件等级从低到高自动升级，高等级不会自动降级</div>
                        <div class="text-muted fs-sm text-danger">如需个别调整，请前往<a
                                href="<?= $urlManager->createUrl(['mch/user/index']) ?>">会员列表</a>调整
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">等级名称</label>
                    </div>
                    <div class="col-5">
                        <input class="form-control" name="name" value="<?= $level->name ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">升级条件</label>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <span class="input-group-addon bg-white">累计完成订单金额满</span>
                            <input class="form-control" name="money" type="number"
                                   value="<?= $level->money ? $level->money : 1 ?>">
                            <span class="input-group-addon bg-white">元</span>
                        </div>
                        <div class="text-muted fs-sm">会员升级条件</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">折扣</label>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <input class="form-control" name="discount" value="<?= $level->discount ?>">
                            <span class="input-group-addon bg-white">折</span>
                        </div>
                        <div class="text-muted fs-sm">请输入0.1~10之间的数字</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">会员等级状态</label>
                    </div>
                    <div class="col-5">
                        <div class="pt-1">
                            <label class="custom-control custom-radio">
                                <input id="radio1"
                                       value="1" <?= $level->status == 1 ? "checked" : "" ?>
                                       name="status" type="radio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">启用</span>
                            </label>
                            <label class="custom-control custom-radio">
                                <input id="radio2"
                                       value="0" <?= $level->status == 0 ? "checked" : "" ?>
                                       name="status" type="radio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">禁用</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">购买价格</label>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <input class="form-control" name="price" value="<?= $level->price ?>">
                            <span class="input-group-addon bg-white">元</span>
                        </div>
                        <div class="text-muted fs-sm">输入0 会员将不可购买</div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">会员图片</label>
                    </div>
                    <div class="col-5">
                        <div class="upload-group">
                            <div class="input-group">
                                <input class="form-control file-input" name="image"
                                value="<?= $level->image ?>">
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
                                <span class="upload-preview-tip">620&times;320</span>
                                <img class="upload-preview-img" src="<?= $level->image ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">会员权益(购买会员关闭)</label>
                    </div>

                    <div class="col-9">
                        <div>
                            <table class="table table-bordered attr-table">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="input-group">
                                            <span>小标题</span>

                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>小标题图标</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>小标题介绍</span>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span>操作</span>
                                            <a href="javascript:" class="bat">新增</a></span>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tr v-for="(item,index) in title_list">
                                    <td>
                                        <input class="form-control form-control-sm" style="width: 100px"
                                               v-bind:name="'synopsis['+index+'][title]'"
                                               v-model="item.title" >
                                               <br>
                                               <div class="text-muted fs-sm">建议标题字数在4字以内</div>
                                    </td>
                                     <td>
                                        <div class="upload-group">
                                            <div class="input-group" v-bind:data-index="index">
                                                <input class="form-control file-input" v-bind:name="'synopsis['+index+'][pic]'"
                                                    style="width: 40px"
                                                   v-model="item.pic" >
                                                <span class="input-group-btn">
                                                    <a class="btn btn-secondary upload-title-pic" href="javascript:" data-toggle="tooltip"
                                                    data-placement="bottom" title="上传文件">
                                                        <span class="iconfont icon-cloudupload"></span>
                                                    </a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a class="btn btn-secondary select-title-pic" href="javascript:" data-toggle="tooltip"
                                                        data-placement="bottom" title="从文件库选择">
                                                        <span class="iconfont icon-viewmodule"></span>
                                                    </a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a class="btn btn-secondary delete-title-pic" href="javascript:" data-toggle="tooltip"
                                                    data-placement="bottom" title="删除文件">
                                                    <span class="iconfont icon-close"></span>
                                                    </a>
                                                </span>
                                            </div>
                                            <img v-if="item.pic" v-bind:src="item.pic"
                                             style="width: 50px;height: 50px;margin: 2px 0;border-radius: 2px">
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm" style="min-height: 100px;" v-bind:name="'synopsis['+index+'][content]'"
                                               v-model="item.content" ><?= $level->buy_prompt ?></textarea>
                                    </td>
                                    <td>
                                        <a v-bind:data-index="index" class="btn btn-secondary delete-title" href="javascript:">删除</a>
                                    </td>
                                </tr>
                            </table>
                     
                        </div>
                    </div>
                </div> 

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">会员权益(购买会员开启)</label>
                    </div>
                    <div class="col-5">
                        <textarea class="form-control" name="detail" style="min-height: 100px;"><?= $level->detail ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">会员购买提示</label>
                    </div>
                    <div class="col-5">
                        <textarea class="form-control" name="buy_prompt" style="min-height: 100px;"><?= $level->buy_prompt ?></textarea>
                        <div class="text-muted fs-sm">购买此会员介绍</div>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                    </div>
                    <div class="col-5">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                        <input type="button" class="btn btn-default ml-4" 
                               name="Submit" onclick="javascript:history.back(-1);" value="返回">
                    </div>
                </div>
             </div>
        </form>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            title_list: <?=json_encode((array)$level->synopsis, JSON_UNESCAPED_UNICODE)?>,
            index:'',
        },
    });

    $(document).on('click', '.bat', function () {
        app.title_list.push({
                pic: '',
                content: '',
                title: '',
            });

    });
    $(document).on('click', '.delete-title', function () {
        var index = $(this).data('index');
        app.title_list.splice(index,1);
    });
</script>

<!-- 规格图片 -->
<script>
    $(document).on('click', '.upload-title-pic', function () {
        var btn = $(this);

        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        $.upload_file({
            accept: 'image/*',
            start: function (res) {
                btn.btnLoading('');
            },
            success: function (res) {
                if (res.code === 1) {
                    $.alert({
                        content: res.msg
                    });
                    return;
                }
                input.val(res.data.url).trigger('change');

                app.title_list[index].pic = res.data.url;
            },
            complete: function (res) {
                btn.btnReset();
            },
        });
    });

    $(document).on('click', '.select-title-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        $.select_file({
            success: function (res) {
                input.val(res.url).trigger('change');
                app.title_list[index].pic = res.url;
            }
        });
    });
    $(document).on('click', '.delete-title-pic', function () {
        var btn = $(this);
        var input = btn.parents('.input-group').find('.form-control');
        var index = btn.parents('.input-group').attr('data-index');
        input.val('').trigger('change');
        app.title_list[index].pic = '';
    });
</script>
