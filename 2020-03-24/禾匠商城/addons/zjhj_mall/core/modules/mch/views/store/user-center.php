<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '用户中心';
$this->params['active_nav_group'] = 1;
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
        overflow-x: hidden;
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
        overflow-x: hidden;
    }

    .mobile-box .mobile-content::-webkit-scrollbar {
        width: 2px;
    }

    .content-block.header-block {
        height: 89px;
        padding: 1rem;
        background-size: cover;
        background-position: center;
    }

    .content-block.menu-block {
        margin: .25rem 0 1rem;
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
        height: 47rem;
        overflow-y: auto;
    }

    .right-box .menu-box .menu-item {
        background: #fff;
        padding: .5rem;
        margin: .25rem 0;
    }

    .panel-body{
        overflow:hidden!important;
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
                            <div class="mobile-navbar">用户中心</div>
                            <div class="mobile-content">
                                <div class="content-block header-block" :style="c_user_center_bg">
                                    <div flex="dir:left box:first" v-if="top_style == 0">
                                        <div>
                                            <div style="display: inline-block;border: 2px solid #fff;background: #e3e3e3;width: 35px;height: 35px;border-radius: 999px"></div>
                                        </div>
                                        <div style="font-weight: bold;color: #fff;padding: .5rem 1rem">用户昵称</div>
                                    </div>
                                    <div flex="dir:left main:center" v-if="top_style == 1">
                                        <div class="text-center">
                                            <div style="display: inline-block;border: 2px solid #fff;background: #e3e3e3;width: 35px;height: 35px;border-radius: 999px"></div>
                                            <div style="font-weight: bold;color: #fff;">用户昵称</div>
                                        </div>
                                    </div>
                                    <div flex="dir:left box:first cross:center" v-if="top_style == 2" style="background-color: #fff;margin-top: 20px;border-radius: 5px;height: 44px;;">
                                        <div style="display: inline-block;border: 2px solid #fff;background: #e3e3e3;width: 35px;height: 35px;border-radius: 999px"></div>
                                        <div style="font-weight: bold;padding: .5rem 1rem">用户昵称</div>
                                    </div>
                                </div>
                                <div class="content-block order-block bg-white">
                                    <div style="padding: .35rem .75rem;border-bottom: 1px solid #eee">全部订单</div>
                                    <div flex="dir:left box:mean" v-if="orders">
                                        <div class="text-center pt-1 pb-1">
                                            <img :src="orders.status_0.icon" style="width: 17px;height: 16px">
                                            <div style="transform: scale(0.8);">{{orders.status_0.text}}</div>
                                        </div>
                                        <div class="text-center pt-1 pb-1">
                                            <img :src="orders.status_1.icon" style="width: 17px;height: 16px">
                                            <div style="transform: scale(0.8);">{{orders.status_1.text}}</div>
                                        </div>
                                        <div class="text-center pt-1 pb-1">
                                            <img :src="orders.status_2.icon" style="width: 17px;height: 16px">
                                            <div style="transform: scale(0.8);">{{orders.status_2.text}}</div>
                                        </div>
                                        <div class="text-center pt-1 pb-1">
                                            <img :src="orders.status_3.icon" style="width: 17px;height: 16px">
                                            <div style="transform: scale(0.8);">{{orders.status_3.text}}</div>
                                        </div>
                                        <div class="text-center pt-1 pb-1">
                                            <img :src="orders.status_4.icon" style="width: 17px;height: 16px">
                                            <div style="transform: scale(0.8);">{{orders.status_4.text}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-block menu-block">
                                    <div flex="dir:left box:justify cross:center" v-if="menu_style == 0"
                                         v-for="(item,index) in menus"
                                         style="background: #fff;border-bottom: 1px solid #eee;padding: .25rem .5rem;">
                                        <div>
                                            <img :src="item.icon" style="width: 15px;height: 15px;">
                                        </div>
                                        <div class="pl-2">{{item.name}}</div>
                                    </div>
                                    <div flex="dir:left cross:center" v-if="menu_style == 1"
                                         style="background: #fff;border-bottom: 1px solid #eee;padding: .25rem .5rem;flex-wrap: wrap">
                                        <div class="text-center" v-for="(item,index) in menus" style="width: 25%;">
                                            <img :src="item.icon" style="width: 15px;height: 15px;">
                                            <div>{{item.name}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-block copyright-block text-center" v-if="copyright">
                                    <img v-if="copyright.icon" :src="copyright.icon" style="height: 18px">
                                    <div class="fs-sm mb-2">{{copyright.text}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-box">
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">头部背景图片</label>
                        </div>
                        <div class="col-sm-6">
                            <div class="upload-group">
                                <div class="input-group">
                                    <input class="form-control file-input" name="user_center_bg"
                                           v-model.lazy="user_center_bg">
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
                                    <span class="upload-preview-tip">750&times;348</span>
                                    <img class="upload-preview-img" :src="user_center_bg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">头部样式</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <label class="radio-label">
                                <input id="radio1" :checked="top_style==0?'checked':''"
                                       value="0"
                                       name="top_style" type="radio" class="custom-control-input" v-model="top_style">
                                <span class="label-icon"></span>
                                <span class="label-text">样式一</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1"  :checked="top_style==1?'checked':''"
                                       value="1"
                                       name="top_style" type="radio" class="custom-control-input" v-model="top_style">
                                <span class="label-icon"></span>
                                <span class="label-text">样式二</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1"  :checked="top_style==2?'checked':''"
                                       value="2"
                                       name="top_style" type="radio" class="custom-control-input" v-model="top_style">
                                <span class="label-icon"></span>
                                <span class="label-text">样式三</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">订单栏</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <label class="radio-label">
                                <input id="radio1" :checked="is_order==0?'checked':''"
                                       value="0"
                                       name="is_order" type="radio" class="custom-control-input" v-model="is_order">
                                <span class="label-icon"></span>
                                <span class="label-text">隐藏</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1"  :checked="is_order==1?'checked':''"
                                       value="1"
                                       name="is_order" type="radio" class="custom-control-input" v-model="is_order">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">订单栏</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <div class="order-list" flex="dir:left box:mean" v-if="orders">
                                <div class="text-center pt-1 pb-1 edit-order" data-index="status_0">
                                    <img :src="orders.status_0.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{orders.status_0.text}}</div>
                                </div>
                                <div class="text-center pt-1 pb-1 edit-order" data-index="status_1">
                                    <img :src="orders.status_1.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{orders.status_1.text}}</div>
                                </div>
                                <div class="text-center pt-1 pb-1 edit-order" data-index="status_2">
                                    <img :src="orders.status_2.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{orders.status_2.text}}</div>
                                </div>
                                <div class="text-center pt-1 pb-1 edit-order" data-index="status_3">
                                    <img :src="orders.status_3.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{orders.status_3.text}}</div>
                                </div>
                                <div class="text-center pt-1 pb-1 edit-order" data-index="status_4">
                                    <img :src="orders.status_4.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{orders.status_4.text}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">我的钱包</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <label class="radio-label">
                                <input id="radio1" :checked="is_wallet==0?'checked':''"
                                       value="0"
                                       name="is_wallet" type="radio" class="custom-control-input" v-model="is_wallet">
                                <span class="label-icon"></span>
                                <span class="label-text">隐藏</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1"  :checked="is_wallet==1?'checked':''"
                                       value="1"
                                       name="is_wallet" type="radio" class="custom-control-input" v-model="is_wallet">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">我的钱包</label>
                        </div>

                        <div class="col-sm-6" style="max-width: 360px">
                            <div class="order-list" flex="dir:left box:mean" v-if="wallets">
                                <div class="text-center pt-1 pb-1 edit-wallet" data-index="status_2">
                                    <img :src="wallets.status_2.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{wallets.status_2.text}}</div>
                                </div>
                                <div class="text-center pt-1 pb-1 edit-wallet" data-index="status_0">
                                    <img :src="wallets.status_0.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{wallets.status_0.text}}</div>
                                </div>
                                <div class="text-center pt-1 pb-1 edit-wallet" data-index="status_1">
                                    <img :src="wallets.status_1.icon" style="width: 17px;height: 16px">
                                    <div style="transform: scale(0.8);">{{wallets.status_1.text}}</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">手动授权手机号</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <label class="radio-label">
                                <input id="radio2" :checked="manual_mobile_auth==0 ? 'checked' : ''"
                                       value=0
                                       v-model="manual_mobile_auth"
                                       name="switch" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">关闭</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio2" :checked="manual_mobile_auth==1 ? 'checked' : ''"
                                       value=1
                                       v-model="manual_mobile_auth"
                                       name="switch" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">菜单栏</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <label class="radio-label">
                                <input id="radio1" :checked="is_menu==0?'checked':''"
                                       value="0"
                                       name="is_menu" type="radio" class="custom-control-input" v-model="is_menu">
                                <span class="label-icon"></span>
                                <span class="label-text">隐藏</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1"  :checked="is_menu==1?'checked':''"
                                       value="1"
                                       name="is_menu" type="radio" class="custom-control-input" v-model="is_menu">
                                <span class="label-icon"></span>
                                <span class="label-text">开启</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">菜单栏样式</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <label class="radio-label">
                                <input id="radio1" :checked="menu_style==0?'checked':''"
                                       value="0"
                                       name="menu_type" type="radio" class="custom-control-input" v-model="menu_style">
                                <span class="label-icon"></span>
                                <span class="label-text">列表形式</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1"  :checked="menu_style==1?'checked':''"
                                       value="1"
                                       name="menu_type" type="radio" class="custom-control-input" v-model="menu_style">
                                <span class="label-icon"></span>
                                <span class="label-text">九宫格形式</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group-label col-sm-2 text-right">
                            <label class="col-form-label">菜单栏</label>
                        </div>
                        <div class="col-sm-6" style="max-width: 360px">
                            <div class="menu-box">
                                <div class="menu-header clearfix">
                                    <a class="float-right" href="javascript:" data-toggle="modal"
                                       data-target=".add-menu-modal">添加</a>
                                </div>
                                <div class="menu-list">
                                    <draggable :list="menus"
                                               :options="{animation: 200,}">
                                        <transition-group name="list-complete">
                                            <div class="menu-item" flex="dir:left box:justify cross:center"
                                                 v-for="(item,index) in menus"
                                                 :key="index">
                                                <div>
                                                    <img :src="item.icon" style="width: 21px;height: 21px;">
                                                </div>
                                                <div class="pl-3">{{item.name}}</div>
                                                <div>
                                                    <a :data-index="index" href="javascript:" class="edit-menu">编辑</a>
                                                    <a :data-index="index" href="javascript:" class="delete-menu">删除</a>
                                                </div>
                                            </div>
                                        </transition-group>
                                    </draggable>
                                </div>

                            </div>
                        </div>
                    </div>

                    <template v-if="copyright && false">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label">底部版权文字</label>
                            </div>
                            <div class="col-sm-6" style="max-width: 360px">
                                <input class="form-control" v-model="copyright.text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label">底部版权图标</label>
                            </div>
                            <div class="col-sm-6" style="max-width: 360px">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" name="copyright_icon"
                                               v-model.lazy="copyright.icon">
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
                                        <span class="upload-preview-tip">240&times;60</span>
                                        <img class="upload-preview-img" :src="copyright.icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2 text-right">
                                <label class="col-form-label">底部版权链接</label>
                            </div>
                            <div class="col-sm-6" style="max-width: 360px">
                                <div class="input-group page-link-input">
                                    <input class="form-control link-input"
                                           readonly
                                           name="copyright_url"
                                           v-model="copyright.url">
                                    <input class="link-open-type"
                                           name="copyright_open_type"
                                           v-model="copyright.open_type"
                                           type="hidden">
                                    <span class="input-group-btn">
                                    <a class="btn btn-secondary pick-link-btn" href="javascript:">选择链接</a>
                                </span>
                                </div>
                            </div>
                        </div>
                    </template>

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


        <!-- 我的钱包 -->
        <div class="modal edit-wallet-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog" role="document">
                <div class="panel">
                    <div class="panel-header">
                        <span>我的钱包编辑</span>
                        <a href="javascript:" class="panel-close" data-dismiss="modal">×</a>
                    </div>
                    <div class="panel-body" v-if="edit_wallet">

                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">标识</label>
                            </div>
                            <div class="col-sm-9" >
                                <span v-if="edit_wallet.id==='wallet'">我的钱包</span>
                                <span v-if="edit_wallet.id==='integral'">积分</span>
                                <span v-if="edit_wallet.id==='balance'">余额</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">名称</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control" v-model.lazy="edit_wallet.text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">图标</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" v-model.lazy="edit_wallet.icon"
                                               name="edit_wallet_icon">
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
                                        <img class="upload-preview-img" :src="edit_wallet.icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                            </div>
                            <div class="col-sm-9">
                                <a class="btn btn-primary edit-wallet-save" href="javascript:">确认</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal edit-order-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog" role="document">
                <div class="panel">
                    <div class="panel-header">
                        <span>订单栏图标编辑</span>
                        <a href="javascript:" class="panel-close" data-dismiss="modal">×</a>
                    </div>
                    <div class="panel-body" v-if="edit_order">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">名称</label>
                            </div>
                            <div class="col-sm-9">{{edit_order.text}}</div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">图标</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" v-model.lazy="edit_order.icon"
                                               name="edit_order_icon">
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
                                        <img class="upload-preview-img" :src="edit_order.icon">
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

        <div class="modal add-menu-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog modal-sm" role="document">
                <div class="panel">
                    <div class="panel-header">
                        <span>添加菜单</span>
                        <a href="javascript:" class="panel-close" data-dismiss="modal">×</a>
                    </div>
                    <div class="panel-body clearfix">
                        <div style="background: #f6f8f9;padding: .5rem">
                            <div flex="dir:left box:justify cross:center"
                                 style="padding: .5rem;background: #fff;margin: .25rem 0"
                                 v-if="menu_list" v-for="(item,index) in menu_list">
                                <div>
                                    <img :src="item.icon" style="width: 21px;height: 21px;display: inline-block">
                                </div>
                                <div class="pl-3">{{item.name}}</div>
                                <div>
                                    <a href="javascript:" class="add-menu" :data-index="index">添加</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal edit-menu-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog" role="document">
                <div class="panel">
                    <div class="panel-header">
                        <span>菜单编辑</span>
                        <a href="javascript:" class="panel-close" data-dismiss="modal">×</a>
                    </div>
                    <div class="panel-body" v-if="edit_menu">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">名称</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control" v-model.lazy="edit_menu.name">
                            </div>
                        </div>
                        <div class="form-group row" v-if="edit_menu.id=='fenxiao'">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">名称2<br>成为分销商</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control" v-model.lazy="edit_menu.name_1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                                <label class="col-form-label required">图标</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" v-model.lazy="edit_menu.icon"
                                               name="edit_menu_icon">
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
                                        <span class="upload-preview-tip">50&times;50</span>
                                        <img class="upload-preview-img" :src="edit_menu.icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group-label col-sm-3 text-right">
                            </div>
                            <div class="col-sm-9">
                                <a class="btn btn-primary edit-menu-save" href="javascript:">确认</a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Sortable.min.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/vuedraggable.min.js"></script>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            user_center_bg: null,
            orders: null,
            wallets: null,
            menus: null,
            edit_order: null,
            edit_wallet: null,
            edit_menu: null,
            menu_list: null,
            copyright: null,
            is_menu: null,
            menu_style: null,
            top_style: null,
            is_wallet: null,
            is_order: null,
            manual_mobile_auth: null,
        },
        computed: {
            c_user_center_bg: function () {
                return this.user_center_bg ? 'background-image:url(' + this.user_center_bg + ');' : '';
            }
        },
    });
    $('#app').show();
    var edit_order_index = null;
    var edit_wallet_index = null;
    var edit_menu_index = null;

    $.ajax({
        dataType: 'json',
        success: function (res) {
            app.user_center_bg = res.data.user_center_bg;
            app.orders = res.data.orders;
            app.wallets = res.data.wallets;
            app.menus = res.data.menus;
            app.copyright = res.data.copyright;
            app.menu_list = res.menu_list;
            app.is_menu = res.data.is_menu;
            app.menu_style = res.data.menu_style;
            app.top_style = res.data.top_style;
            app.is_wallet = res.data.is_wallet;
            app.is_order = res.data.is_order;
            app.manual_mobile_auth = res.data.manual_mobile_auth;
            $("html").getNiceScroll().resize();
            $("body").niceScroll();
            $(".mobile-content").niceScroll();
        }
    });

    $(document).on('change', 'input[name="user_center_bg"]', function () {
        app.user_center_bg = $(this).val();
    });
    $(document).on('change', 'input[name="edit_order_icon"]', function () {
        app.edit_order.icon = $(this).val();
    });
    $(document).on('change', 'input[name="edit_menu_icon"]', function () {
        app.edit_menu.icon = $(this).val();
    });
    $(document).on('change', 'input[name="copyright_icon"]', function () {
        app.copyright.icon = $(this).val();
    });
    $(document).on('change', 'input[name="copyright_url"]', function () {
        app.copyright.url = $(this).val();
    });
    $(document).on('change', 'input[name="copyright_open_type"]', function () {
        app.copyright.open_type = $(this).val();
    });

    $(document).on('change', 'input[name="edit_wallet_icon"]', function () {
        app.edit_wallet.icon = $(this).val();
    });

    $(document).on('click', '.edit-order', function () {
        edit_order_index = $(this).attr('data-index');
        app.edit_order = JSON.parse(JSON.stringify(app.orders[edit_order_index]));
        $('.edit-order-modal').modal('show');
    });

    $(document).on('click', '.edit-order-save', function () {
        for (var i in app.edit_order) {
            app.orders[edit_order_index][i] = app.edit_order[i];
        }
        $('.edit-order-modal').modal('hide');
    });

    $(document).on('click', '.edit-wallet', function () {
        edit_wallet_index = $(this).attr('data-index');
        app.edit_wallet = JSON.parse(JSON.stringify(app.wallets[edit_wallet_index]));
        $('.edit-wallet-modal').modal('show');
    });

    $(document).on('click', '.edit-wallet-save', function () {
        for (var i in app.edit_wallet) {
            app.wallets[edit_wallet_index][i] = app.edit_wallet[i];
        }
        $('.edit-wallet-modal').modal('hide');
    });

    $(document).on('click', '.add-menu', function () {
        var index = parseInt($(this).attr('data-index'));
        app.menus.push(app.menu_list[index]);
        $('.add-menu-modal').modal('hide');
    });
    $(document).on('click', '.delete-menu', function () {
        var index = parseInt($(this).attr('data-index'));
        app.menus.splice(index, 1);
    });
    $(document).on('click', '.edit-menu', function () {
        edit_menu_index = parseInt($(this).attr('data-index'));
        app.edit_menu = JSON.parse(JSON.stringify(app.menus[edit_menu_index]));
        $('.edit-menu-modal').modal('show');
    });
    $(document).on('click', '.edit-menu-save', function () {
        for (var i in app.edit_menu) {
            app.menus[edit_menu_index][i] = app.edit_menu[i];
        }
        $('.edit-menu-modal').modal('hide');
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
                    user_center_bg: app.user_center_bg,
                    orders: app.orders,
                    wallets: app.wallets,
                    menus: app.menus,
                    copyright: app.copyright,
                    is_menu: app.is_menu,
                    menu_style:app.menu_style,
                    top_style:app.top_style,
                    is_wallet:app.is_wallet,
                    is_order:app.is_order,
                    manual_mobile_auth: app.manual_mobile_auth,
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
