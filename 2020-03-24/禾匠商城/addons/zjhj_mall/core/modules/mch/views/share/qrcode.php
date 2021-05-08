<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:46
 */
defined('YII_ENV') or exit('Access Denied');
/* @var $list \app\models\Setting */
/* @var $qrcode \app\models\Qrcode */
use yii\widgets\LinkPager;

$static = Yii::$app->request->baseUrl . '/statics';
$urlManager = Yii::$app->urlManager;
$this->title = '推广海报设置';
$this->params['active_nav_group'] = 5;
?>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="app" class="" style="display: table;width: 100%">
            <form class="form auto-form bg-white p-3" method="post" autocomplete="off" style="max-width: 100%">
                <div class="step1" style="display: table-cell;width: 450px;margin-right: 10px;">
                    <div class="form-group">
                        <label class="col-form-label">第一步：先上传背景图。推荐大小750×1200</label>
                    </div>
                    <div class="form-group row">
                        <div class="upload-group short-row">
                            <div class="input-group">
                                <input class="form-control file-input" name="model[qrcode_bg]"
                                       value="<?= $qrcode->qrcode_bg ? $qrcode->qrcode_bg : Yii::$app->request->hostInfo . $static . '/images/2.png' ?>">
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
                                <span class="upload-preview-tip">750&times;1200</span>
                                <img class="upload-preview-img"
                                     src="<?= $qrcode->qrcode_bg ? $qrcode->qrcode_bg : Yii::$app->request->hostInfo . $static . '/images/2.png' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-9 offset-sm-3">
                            <a class="btn btn-primary next" href="javascript:">下一步</a>
                        </div>
                    </div>
                </div>
                <div class="step2" hidden style="display: inline-table;width: 800px;">
                    <div class="form-group">
                        <label class="col-form-label">第二步：调整头像、二维码及用户昵称的大小和位置</label>
                    </div>
                    <div style="display: inline-block;width: 450px;margin-right: 10px;position: relative;float: left">
                        <img class="qrcode_bg"
                             src="<?= $qrcode->qrcode_bg ? $qrcode->qrcode_bg : Yii::$app->request->hostInfo . $static . '/images/2.png' ?>"
                             width="300px" height="480px">
                        <img class="avatar" src="<?= $static ?>/images/avatar.png"
                             style="background-color: #fff;border: 1px #bbb solid;z-index:10"
                             v-bind:style="{width:avatar_w+'px',height:avatar_w+'px',position:'absolute',top:avatar_y+'px',left:avatar_x+'px',borderRadius:avatar_w+'px'}">
                        <!--                                <img class="avatar" src="-->
                        <? //= $static ?><!--/images/avatar.png"-->
                        <!--                                     style="width: 72px;height: 72px;border: 1px #fff solid;background-color: #fff;position: absolute;top:116px;left: 12px;;border-radius: 72px;">-->
                        <!--                                <img class="qrcode" src="-->
                        <? //= $static ?><!--/images/1.png"-->
                        <!--                                     style="width:168px;height: 168px;position: absolute;top: 210px;left: 74px;border-radius: 168px;">-->
                        <img class="qrcode" v-if="qrcode_c == 1" src="<?= $static ?>/images/1.png"
                             v-bind:style="{width:qrcode_w+'px',position:'absolute',top:qrcode_y+'px',left:qrcode_x+'px',borderRadius:qrcode_w+'px'}">
                        <img class="qrcode" v-else src="<?= $static ?>/images/1.png"
                             v-bind:style="{width:qrcode_w+'px',position:'absolute',top:qrcode_y+'px',left:qrcode_x+'px'}">
                        <span class="font" style="width: 150px;line-height: 1;"
                              v-bind:style="{position:'absolute',top:font_y+'px',left:font_x+'px',fontSize:font_w+'px',color:font_c}">用户昵称</span>
                        <!--                                <span class="font" style="position: absolute;top:128px;left: 118px;;">用户昵称</span>-->
                    </div>
                    <div style="display: inline-block;width: 330px;float: left">
                        <div class="form-group row">
                            <label class="col-5 text-right">头像宽度</label>
                            <div class="col-7">
                                <input v-model="avatar_w" class="form-control" type="number" name="model[avatar_w]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">头像距左宽度</label>
                            <div class="col-7">
                                <input v-model="avatar_x" class="form-control" type="number" name="model[avatar_x]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">头像距上高度</label>
                            <div class="col-7">
                                <input v-model="avatar_y" class="form-control" type="number" name="model[avatar_y]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">二维码宽度</label>
                            <div class="col-7">
                                <input v-model="qrcode_w" class="form-control" type="number" name="model[qrcode_w]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">二维码样式</label>
                            <div class="col-7">
                                <!--
                                <label>
                                    <input v-model="qrcode_c" type="radio" name="model[qrcode_c]" value="1">圆形
                                </label>
                                <label>
                                    <input v-model="qrcode_c" type="radio" name="model[qrcode_c]" value="0">方形
                                </label>
                                -->
                                <label class="radio-label">
                                    <input v-model="qrcode_c" type="radio" name="model[qrcode_c]" value="1">
                                    <span class="label-icon"></span>
                                    <span class="label-text">圆形</span>
                                </label>
                                <label class="radio-label">
                                    <input v-model="qrcode_c" type="radio" name="model[qrcode_c]" value="0">
                                    <span class="label-icon"></span>
                                    <span class="label-text">方形</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">二维码距左宽度</label>
                            <div class="col-7">
                                <input v-model="qrcode_x" class="form-control" type="number" name="model[qrcode_x]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">二维码距上高度</label>
                            <div class="col-7">
                                <input v-model="qrcode_y" class="form-control" type="number" name="model[qrcode_y]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">用户昵称大小</label>
                            <div class="col-7">
                                <input v-model="font_w" class="form-control" type="number" name="model[font_w]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">用户昵称距左宽度</label>
                            <div class="col-7">
                                <input v-model="font_x" class="form-control" type="number" name="model[font_x]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">用户昵称距上高度</label>
                            <div class="col-7">
                                <input v-model="font_y" class="form-control" type="number" name="model[font_y]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5 text-right">用户昵称颜色</label>
                            <input type="hidden" name="model[font_c]" class="color" v-model="first">
                            <div class="col-7">
                                <ul class="form-control"
                                    style="list-style: none;overflow-y: scroll;height: 200px;cursor: pointer;">
                                    <li class="form-control"
                                        v-for="(item,index) in color" v-bind:data-index="index"
                                        v-bind:style="{color:item.color}">用户昵称
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                            </div>
                            <div class="col-9">
                                <div class="text-danger mb-3">注：数据修改请前往“商城管理=><a
                                        href="<?= $urlManager->createUrl(['mch/cache/index']) ?>">清除缓存</a>”，清除临时图片
                                </div>
                                <a class="btn btn-primary prev" href="javascript:">上一步</a>
                                <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var app = new Vue({
        el: "#app",
        data: {
            avatar_w: "<?=($avatar_w || $avatar_w === '0') ? $avatar_w : 63?>",
            avatar_x: "<?=($avatar_x || $avatar_x === '0') ? $avatar_x : 20?>",
            avatar_y: "<?=($avatar_y || $avatar_y === '0') ? $avatar_y : 118?>",
            qrcode_w: "<?=($qrcode_w || $qrcode_w === '0') ? $qrcode_w : 169?>",
            qrcode_x: "<?=($qrcode_x || $qrcode_x === '0') ? $qrcode_x : 65?>",
            qrcode_y: "<?=($qrcode_y || $qrcode_y === '0') ? $qrcode_y : 210?>",
            font_x: "<?=$font_x || $font_x === '0' ? $font_x : 140?>",
            font_y: "<?=$font_y || $font_y === '0' ? $font_y : 121?>",
            font_w: "<?=$font_w || $font_w === '0' ? $font_w : 20?>",
            font_c: "<?=$font_c || $font_c === 0 ? $font_c : '#333333'?>",
            first: "<?=$first ? $first : 0?>",
            color:<?=$color?>,
            qrcode_c: "<?=$qrcode_c?>"
        }
    });
</script>
<script>
    $(document).on('click', 'li', function () {
        var index = $(this).data('index');
        var color = app.color[index].color;
        $('.color').val(app.color[index].id);
        app.font_c = color;
        app.first = app.color[index].id;
    });
</script>
<script>
    $(document).on('click', '.next', function () {
        $('.step1').prop('hidden', true);
        $('.step2').prop('hidden', false);
        $('.qrcode_bg').prop('src', $('.file-input').val());
    });
    $(document).on('click','.prev',function(){
        $('.step1').prop('hidden', false);
        $('.step2').prop('hidden', true);
    });
</script>

