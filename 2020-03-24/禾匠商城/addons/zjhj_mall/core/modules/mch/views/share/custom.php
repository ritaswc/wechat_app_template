<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 17:02
 */
defined('YII_ENV') or exit('Access Denied');
/* @var $list \app\models\Setting */

/* @var $qrcode \app\models\Qrcode */

use yii\widgets\LinkPager;

$static = Yii::$app->request->baseUrl . '/statics';
$urlManager = Yii::$app->urlManager;
$this->title = '自定义设置';
$this->params['active_nav_group'] = 5;
?>
<style>
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
        background: #f5f7f9;
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

    .right-box .order-list > div {
        border: 1px solid #e3e3e3;
        border-right-width: 0;
        cursor: pointer;
    }

    .right-box .order-list > div:hover {
        background: #f6f8f9;
    }

    .right-box .order-list > div:last-child {
        border-right-width: 1px;
    }

    .content-block.head-block {
        padding: 12px;
        background-color: #ff4544;
    }

    .content-block.menu-block {
        background-color: #fff;;
    }

    .right-box .menu-box {
        border: 1px solid #e3e3e3;
    }

    .right-box .menu-box .menu-header {
        padding: .5rem;
        border-bottom: 1px solid #e3e3e3;
    }

    .right-box .menu-box .menu-list {
        padding: .5rem;
        background: #f6f8f9;
    }

    .right-box .menu-box .menu-item {
        background: #fff;
        padding: .5rem;
        margin: .25rem 0;
    }

</style>

<div class="panel mb-3" id="app" style="display: none">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="" method="post">

            <div flex="dir:left box:first">
                <div class="left-box">
                    <div class="mobile-box">
                        <div class="mobile-screen">
                            <div class="mobile-navbar">分销中心</div>
                            <div class="mobile-content">
                                <div v-if="words">
                                    <div class="content-block head-block">
                                        <div flex="dir:left box:left">
                                            <div>
                                                <div
                                                    style="display: inline-block;border: 2px solid #fff;background: #e3e3e3;width: 35px;height: 35px;border-radius: 999px"></div>
                                            </div>
                                            <div style="margin-left: 5px;color: #fff;">
                                                <div>用户昵称</div>
                                                <div>{{words.parent_name.name}}：用户昵称</div>
                                            </div>
                                        </div>
                                        <div flex="dir:left box:left" style="color: #fff;justify-content:space-between">
                                            <div>
                                                <div>{{words.can_be_presented.name}}</div>
                                                <div>0元</div>
                                            </div>
                                            <div>
                                                <div style="border: 1px solid #eee;padding: 2px;">{{words.cash.name}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div flex="dir:left box:mean" style="background-color: #fff;margin-bottom: 8px;padding: 10px 0;">
                                        <div class="text-center">
                                            <div style="color: #22af19;">{{words.already_presented.name}}</div>
                                            <div>0元</div>
                                        </div>
                                        <div class="text-center">
                                            <div style="color: #ff8f12;">{{words.order_money_un.name}}</div>
                                            <div>0元</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-block menu-block" v-if="menus">
                                    <div flex="dir:left box:left" style="flex-wrap:wrap">
                                        <div class="text-center" style="width: 33.3333333%;padding: 10px 0;"
                                             v-for="(item,index) in menus">
                                            <img :src="item.icon" style="width: 17px;height: 16px">
                                            <div style="transform: scale(0.8);">{{item.name}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-box">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">栏目</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <div class="order-list" flex="dir:left box:mean" v-if="menus">
                                <div class="text-center pt-1 pb-1 edit-order" :data-index="index"
                                     v-for="(item,index) in menus">
                                    <img :src="item.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{item.name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">文字</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <div class="menu-box">
                                <div class="menu-header clearfix">
                                    <a class="float-right edit-words" href="javascript:" data-toggle="modal"
                                       data-target=".edit-words-modal">编辑</a>
                                </div>
                                <div class="menu-list">
                                    <div class="menu-item" flex="dir:left box:justify cross:center"
                                         v-for="(item,index) in words"
                                         :key="index">
                                        <div class="pl-3" style="width: 50%;">{{item.default}}</div>
                                        <div class="pl-3">=></div>
                                        <div class="pl-3">{{item.name}}</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                        </div>
                        <div class="col-sm-6">
                            <a class="btn btn-primary save-user-center" href="javascript:">保存</a>
                        </div>
                    </div>

                </div>

            </div>
        </form>


        <div class="modal edit-order-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog" role="document">
                <div class="panel">
                    <div class="panel-header">
                        <span>栏目编辑</span>
                        <a href="javascript:" class="panel-close" data-dismiss="modal">×</a>
                    </div>
                    <div class="panel-body" v-if="edit_menus">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">名称</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control file-input" v-model.lazy="edit_menus.name"
                                       name="edit_menus_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">图标</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" v-model.lazy="edit_menus.icon"
                                               name="edit_menus_icon">
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
                                        <span class="upload-preview-tip">60&times;57</span>
                                        <img class="upload-preview-img" :src="edit_menus.icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                            </div>
                            <div class="col-sm-9">
                                <a class="btn btn-primary edit-order-save" href="javascript:">确认</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal edit-words-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog" role="document">
                <div class="panel">
                    <div class="panel-header">
                        <span>文字编辑</span>
                        <a href="javascript:" class="panel-close" data-dismiss="modal">×</a>
                    </div>
                    <div class="panel-body" v-if="words">
                        <div class="form-group row" v-for="(item,index) in edit_words">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">'{{item.default}}'</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control file-input" v-model.lazy="item.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                            </div>
                            <div class="col-sm-9">
                                <a class="btn btn-primary edit-words-save" href="javascript:">确认</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            words: null,
            menus: null,
            edit_words: null,
            edit_menus: null,
        },
    });
    $('#app').show();
    var edit_words_index = null;
    var edit_menus_index = null;

    $.ajax({
        dataType: 'json',
        success: function (res) {
            app.words = res.data.words;
            app.menus = res.data.menus;
        }
    });

    $(document).on('change', 'input[name="edit_menus_icon"]', function () {
        app.edit_menus.icon = $(this).val();
    });

    $(document).on('click', '.edit-order', function () {
        edit_menus_index = $(this).attr('data-index');
        app.edit_menus = JSON.parse(JSON.stringify(app.menus[edit_menus_index]));
        $('.edit-order-modal').modal('show');
    });

    $(document).on('click', '.edit-order-save', function () {
        for (var i in app.edit_menus) {
            app.menus[edit_menus_index][i] = app.edit_menus[i];
        }
        $('.edit-order-modal').modal('hide');
    });

    $(document).on('click', '.edit-words', function () {
        app.edit_words = app.words;
        $('.edit-words-modal').modal('show');
    });

    $(document).on('click', '.edit-words-save', function () {
        app.words = app.edit_words;
        $('.edit-words-modal').modal('hide');
    });

    $(document).on('click', '.save-user-center', function () {
        var btn = $(this);
        btn.btnLoading(btn.text());
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                _csrf: _csrf,
                data: JSON.stringify({
                    words: app.words,
                    menus: app.menus,
                }),
            },
            success: function (res) {
                $.alert({
                    content: res.msg,
                    confirm: function () {
                        if (res.code == 0) {
                            location.reload();
                        } else {
                            btn.btnReset();
                        }
                    }
                });
            }
        });
    });

</script>


