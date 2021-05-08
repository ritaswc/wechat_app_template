<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '导航设置';
$this->params['active_nav_group'] = 11;

//$navPickLink = Yii::$app->controller->getNavPickLink();

?>
<style>

.panel .nav-item {
    border: 1px solid #eee;
    border-radius: 3px;
    margin: 0 5px 5px 0;
    display: inline-block;
    padding: 6px;
    width: 80px;
    height: 80px;
    overflow: hidden;
    position: relative;
    vertical-align: middle;
}

.panel .nav-item .nav-icon {
    display: block;
    width: 35px;
    height: 35px;
    margin: 0 auto 10px auto;
}

.panel .nav-item .nav-text {
    display: block;
    text-align: center;
    font-size: .75rem;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

.panel .nav-delete,
.panel .nav-edit {
    position: absolute;
    bottom: 0;
    color: #fff !important;
    font-size: .75rem;
    width: 50%;
    text-align: center;
    display: block;
    padding: 2px 0;
    visibility: hidden;
    opacity: 0;
    transition: 200ms;

    background: rgba(0, 102, 212, 0.73);
    border-radius: 0 0 0 2px;
    left: 0;
}

.panel .nav-item:hover .nav-delete,
.panel .nav-item:hover .nav-edit {
    visibility: visible;
    opacity: 1;
}

.panel .nav-delete {
    background: rgba(255, 69, 68, 0.73);
    border-radius: 0 0 2px 0;
    right: 0;
    left: auto;
}

.panel .nav-add {
    cursor: pointer;
    border: 1px dashed #ccc;
}

.panel .nav-add .iconfont {
    display: block;
    font-size: 46px;
    color: #aaa;
    text-align: center;
}

.panel .nav-add:hover {
    background: #f6f6f6;
}

.panel .navigation-bar {
    text-align: center;
    padding: 8px;
    box-shadow: 0 1px 1px 1px rgba(0, 0, 0, .15);
    max-width: 200px;
}

.colorback {
    width: 66px;
    height: 30px;
    display: inline-block;
}
</style>
<link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/statics/mch/css/themes.css">
<div class="panel" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">顶部导航栏</label>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label>文字颜色：</label>
                        <label class="radio-label">
                            <input v-if="navigation_bar_color.frontColor=='#000000'"
                                   checked
                                   value="#000000" id="radio1" name="navigation_bar_color[frontColor]" type="radio"
                                   class="custom-control-input">
                            <input v-else
                                   value="#000000" id="radio1" name="navigation_bar_color[frontColor]" type="radio"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">黑色</span>
                        </label>
                        <label class="radio-label">
                            <input v-if="navigation_bar_color.frontColor=='#ffffff'"
                                   checked
                                   value="#ffffff" id="radio1" name="navigation_bar_color[frontColor]" type="radio"
                                   class="custom-control-input">
                            <input v-else
                                   value="#ffffff" id="radio1" name="navigation_bar_color[frontColor]" type="radio"
                                   class="custom-control-input">
                            <span class="label-icon"></span>
                            <span class="label-text">白色</span>
                        </label>
                    </div>
                    <label>背景颜色：</label>
                    <label class="radio-label">
                        <input name="navigation_bar_color[backgroundColor]"
                               v-model="navigation_bar_color.backgroundColor"
                               class="colorback"
                               id="start_click"
                               v-bind:style="{background:navigation_bar_color.backgroundColor}"
                        ></input>
                    </label>
                    <div id="color-picker" style="box-shadow:none" class="cp-default"></div>
                </div>
                <div class="col-sm-2">
                    <div class="navigation-bar"
                         :style="'color: '+navigation_bar_color.frontColor+';background: '+navigation_bar_color.backgroundColor">
                        顶部导航栏效果
                    </div>
                </div>
            </div>


            <div hidden>
                <input name="navbar[background_image]" v-bind:value="navbar.background_image">
                <input name="navbar[border_color]" v-bind:value="navbar.border_color">
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">底部导航图标</label>
                </div>
                <div class="col-sm-6">
                    <div style="display: inline-block" id="sortList">
                        <template v-for="(nav,i) in navbar.navs">
                            <div class="nav-item">
                                <img class="nav-icon" :src="nav.icon">
                                <div class="nav-text" :style="'color:'+nav.color">{{nav.text}}</div>
                                <a class="nav-delete" href="javascript:" :data-index="i">删除</a>
                                <a class="nav-edit" href="javascript:" :data-index="i">编辑</a>
                                <div hidden>
                                    <input :name="'navbar[navs][nav'+i+'][url]'" :value="nav.url">
                                    <input :name="'navbar[navs][nav'+i+'][icon]'" :value="nav.icon">
                                    <input :name="'navbar[navs][nav'+i+'][active_icon]'" :value="nav.active_icon">
                                    <input :name="'navbar[navs][nav'+i+'][text]'" :value="nav.text">
                                    <input :name="'navbar[navs][nav'+i+'][color]'" :value="nav.color">
                                    <input :name="'navbar[navs][nav'+i+'][active_color]'" :value="nav.active_color">
                                    <input :name="'navbar[navs][nav'+i+'][open_type]'" :value="nav.open_type">
                                    <template v-for="(param,j) in nav.params">
                                        <template v-if="nav.params.length > 0">
                                            <input :name="'navbar[navs][nav'+i+'][params][' + j + '][key]'"
                                                   :value="param.key">
                                            <input :name="'navbar[navs][nav'+i+'][params][' + j + '][value]'"
                                                   :value="param.value">
                                        </template>
                                        <template v-else>
                                            <input :name="'navbar[navs][nav'+i+'][params][' + j + ']'" :value="param">
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="nav-item nav-add"><i class="iconfont icon-add"></i></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">底部导航栏</label>
                </div>
                <div class="col-sm-4">
                    <label>背景颜色：</label>
                    <label class="radio-label">
                        <input name="navigation_bar_color[bottomBackgroundColor]"
                               v-model="navigation_bar_color.bottomBackgroundColor"
                               class="colorback"
                               id="bottom_click"
                               v-bind:style="{background:navigation_bar_color.bottomBackgroundColor}"
                        ></input>
                    </label>
                    <div id="bottom-color-picker" style="box-shadow:none" class="cp-default"></div>
                </div>

                <div class="col-sm-2">
                    <div class="navigation-bar"
                         :style="'color: #888;background: '+navigation_bar_color.bottomBackgroundColor">
                        底部导航栏效果
                    </div>
                </div>
            </div>

            <!--  <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">底部导航栏</label>
                </div>
                <div class="col-sm-4">
                    <label>背景颜色：</label>
                    <label class="radio-label">
                        <input name="navigation_bar_color[bottomBackgroundColor]"
                               v-model="navigation_bar_color.bottomBackgroundColor"
                               class="colorback"
                               v-bind:style="{background:navigation_bar_color.bottomBackgroundColor}"
                               data-toggle="modal"
                               data-target="#modalback_bottom"
                        ></input>
                    </label>
                </div>
                <div class="col-sm-2">
                    <div class="navigation-bar"
                         :style="'color: #888;background: '+navigation_bar_color.bottomBackgroundColor">
                        底部导航栏效果
                    </div>
                </div>
            </div>

            
            <div class="modal fade" id="modalback_bottom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" style="width:333px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span></span>
                            <a type="button" style="float:right" class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                        </div>
                        <div class="modal-body">
                            <div id="bottom-color-picker" style="box-shadow:none" class="cp-default"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">选择</button>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <a class="btn btn-secondary reset-navbar"
                       href="<?= Yii::$app->urlManager->createUrl(['mch/store/navbar-reset']) ?>">恢复默认设置</a>
                </div>
            </div>
        </form>

        <div class="modal fade nav-edit-modal" data-backdrop="static" style="z-index: 1041">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">导航菜单编辑</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-sm-4 text-right">
                                <label class="col-form-label required">图标</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" name="icon_upload">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary upload-file" href="javascript:" title="上传文件">
                                                <span class="iconfont icon-cloudupload"></span>
                                            </a>
                                        </span>
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary select-file" href="javascript:" title="从文件库选择">
                                                <span class="iconfont icon-viewmodule"></span>
                                            </a>
                                        </span>
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary delete-file" href="javascript:" title="删除文件">
                                                <span class="iconfont icon-close"></span>
                                            </a>
                                        </span>
                                    </div>
                                    <div class="upload-preview text-center upload-preview">
                                        <span class="upload-preview-tip">64&times;64</span>
                                        <img class="upload-preview-img" src="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 text-right">
                                <label class="col-form-label required">选中状态图标</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="upload-group">
                                    <div class="input-group">
                                        <input class="form-control file-input" name="active_icon_upload">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary upload-file" href="javascript:" title="上传文件">
                                                <span class="iconfont icon-cloudupload"></span>
                                            </a>
                                        </span>
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary select-file" href="javascript:" title="从文件库选择">
                                                <span class="iconfont icon-viewmodule"></span>
                                            </a>
                                        </span>
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary delete-file" href="javascript:" title="删除文件">
                                                <span class="iconfont icon-close"></span>
                                            </a>
                                        </span>
                                    </div>
                                    <div class="upload-preview text-center upload-preview">
                                        <span class="upload-preview-tip">64&times;64</span>
                                        <img class="upload-preview-img" src="">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div hidden>
                            <input v-model="editnav.icon">
                            <input v-model="editnav.active_icon">
                            <input v-model="editnav.color">
                            <input v-model="editnav.active_color">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 text-right">
                                <label class="col-form-label required">名称</label>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" v-model="editnav.text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 text-right">
                                <label class="col-form-label required">文字选中颜色</label>
                            </div>
                            <div class="col-sm-8 col-form-label">

                                <input v-model="editnav.active_color" id="active_click" class="colorback"
                                       v-bind:style="{background:editnav.active_color}"></input>
                                <div id="color-edit-picker" style="box-shadow:none" class="cp-default"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 text-right">
                                <label class="col-form-label required">文字未选中颜色</label>
                            </div>
                            <div class="col-sm-8 col-form-label">
                                <input v-model="editnav.color" id="default_click" class="colorback"
                                       v-bind:style="{background:editnav.color}"></input>
                                <div id="color-start-picker" style="box-shadow:none" class="cp-default"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 text-right">
                                <label class="col-form-label required">页面</label>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control editnav-url">
                                    <option value="-1">点击选择链接</option>
                                    <option v-for="(link,i) in pages"
                                            :selected="editnav.url==link.url"
                                            v-bind:value="i">
                                        {{link.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <template v-if="editnav">
                            <template v-if="editnav.params && editnav.params.length>0">
                                <div class="form-group row" v-for="(param,i) in editnav.params">
                                    <div class="col-sm-4 text-right">
                                        <label class="col-form-label required">{{param.key}}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control" v-model="param.value" :disabled="param.disabled">
                                        <label class="col-form-label">{{param.desc}}</label>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save-nav">确认</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Sortable.min.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/colorpicker.js"></script>
<script>
$("#color-picker").hide();
$("#bottom-color-picker").hide();
$("#color-start-picker").hide();
$("#color-edit-picker").hide();

$(document).on('click', '#start_click', function (event) {
    if ($("#color-picker").is(":hidden")) {
        $("#color-picker").show(); //如果元素为隐藏,则将它显现
        c.setHex(app.navigation_bar_color.backgroundColor);
    } else {
        $("#color-picker").hide(); //如果元素为显现,则将其隐藏
    }
    return false;
});
$(document).on('click', '#bottom_click', function (event) {
    if ($("#bottom-color-picker").is(":hidden")) {
        $("#bottom-color-picker").show(); //如果元素为隐藏,则将它显现
        bottom_c.setHex(app.navigation_bar_color.bottomBackgroundColor);
    } else {
        $("#bottom-color-picker").hide(); //如果元素为显现,则将其隐藏
    }
    return false;
});
$(document).on('click', '#active_click', function (event) {
    if ($("#color-edit-picker").is(":hidden")) {
        $("#color-edit-picker").show(); //如果元素为隐藏,则将它显现
        ec.setHex(editnav.active_color);
    } else {
        $("#color-edit-picker").hide(); //如果元素为显现,则将其隐藏
    }
    return false;
});
$(document).on('click', '#default_click', function (event) {
    if ($("#color-start-picker").is(":hidden")) {
        $("#color-start-picker").show(); //如果元素为隐藏,则将它显现
        start_c.setHex(editnav.color);
    } else {
        $("#color-start-picker").hide(); //如果元素为显现,则将其隐藏
    }
    return false;
});
</script>
<script>


var editnav = {
    index: null,
    url: '',
    icon: '',
    active_icon: '',
    text: '',
    color: '#888',
    active_color: '#ff4544',
    open_type: 'redirect',
    params: [],
};
var app = new Vue({
    el: '#app',
    data: {
        navbar: false,
        navigation_bar_color: false,
        editnav: editnav,
        pages: null
    },
});
getNavPickLink();


var ec = ColorPicker(
    document.getElementById('color-edit-picker'),
    function (hex, hsv, rgb) {
        app.editnav.active_color = hex;
    });

var c = ColorPicker(
    document.getElementById('color-picker'),
    function (hex, hsv, rgb) {
        app.navigation_bar_color['backgroundColor'] = hex;
    });

var start_c = ColorPicker(
    document.getElementById('color-start-picker'),
    function (hex, hsv, rgb) {
        app.editnav.color = hex;
    });

var bottom_c = ColorPicker(
    document.getElementById('bottom-color-picker'),
    function (hex, hsv, rgb) {
        app.navigation_bar_color['bottomBackgroundColor'] = hex;
    });

$.loading();
$.ajax({
    dataType: 'json',
    success: function (res) {
        $.loadingHide();
        if (res.code == 0) {
            app.navbar = res.data.navbar;
            app.navigation_bar_color = res.data.navigation_bar_color;
            setTimeout(function () {
                Sortable.create(document.getElementById('sortList'), {
                    animation: 150,
                });
            }, 300);
        }
    }
});

$(document).on('click', '.reset-navbar', function () {
    var href = $(this).attr('href');
    $.confirm({
        content: '确认恢复默认配置？',
        confirm: function () {
            $.loading();
            $.ajax({
                url: href,
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        location.reload();
                    }
                }
            })
        }
    });

    return false;
});

$(document).on('change', 'input[name="navigation_bar_color[frontColor]"]', function () {
    app.navigation_bar_color.frontColor = this.value;
});

$(document).on('click', '.nav-add', function () {
    app.editnav = editnav;
    $('input[name=icon_upload]').val('');
    $('input[name=icon_upload]').parents('.upload-group').find('.upload-preview-img').attr('src', '');
    $('input[name=active_icon_upload]').val('');
    $('input[name=active_icon_upload]').parents('.upload-group').find('.upload-preview-img').attr('src', '');
    getNavPickLink();
    $('.nav-edit-modal').modal('show');
});

function getNavPickLink() {
    $.ajax({
        url: '<?= $urlManager->createUrl('mch/store/nav-pick-link') ?>',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            app.pages = res;
        }
    })
}

$(document).on('click', '.nav-delete', function () {
    var index = parseInt($(this).attr('data-index'));
    app.navbar.navs.splice(index, 1);
});

$(document).on('click', '.nav-edit', function () {
    var index = parseInt($(this).attr('data-index'));
    app.editnav = JSON.parse(JSON.stringify(app.navbar.navs[index]));
    app.editnav.index = index;

    ec.setHex(app.editnav.active_color);
    $('input[name=icon_upload]').val(app.editnav.icon);
    $('input[name=icon_upload]').parents('.upload-group').find('.upload-preview-img').attr('src', app.editnav.icon);
    $('input[name=active_icon_upload]').val(app.editnav.active_icon);
    $('input[name=active_icon_upload]').parents('.upload-group').find('.upload-preview-img').attr('src', app.editnav.active_icon);
    $('.nav-edit-modal').modal('show');
});

$(document).on('change', 'input[name=icon_upload]', function () {
    app.editnav.icon = this.value;
});

$(document).on('change', 'input[name=active_icon_upload]', function () {
    app.editnav.active_icon = this.value;
});
$(document).on('click', '.save-nav', function () {
    var new_nav = {
        url: app.editnav.url,
        icon: app.editnav.icon,
        active_icon: app.editnav.active_icon,
        text: app.editnav.text,
        color: app.editnav.color,
        active_color: app.editnav.active_color,
        open_type: app.editnav.open_type,
        params: app.editnav.params,
    };
    if (new_nav.open_type === 'web') {
        for (let i in new_nav.params) {
            if (new_nav.params[i].key === 'web') {
                new_nav.params[i].value = encodeURIComponent(new_nav.params[i].value);
            }
        }
    }

    if (app.editnav.index !== null) {
        Vue.set(app.navbar.navs, app.editnav.index, new_nav)
    } else {
        app.navbar.navs.push(new_nav);
    }
    $('.nav-edit-modal').modal('hide');
});
$(document).on('change', '.editnav-url', function () {
    var i = $(this).val();
    // 没有选择链接状态
    if (i != -1) {
        app.editnav.url = app.pages[i].url;
        app.editnav.params = app.pages[i].params;
        if (app.pages[i].open_type) {
            app.editnav.open_type = app.pages[i].open_type;
        }
    }
//        var open_type = $($(this).children('option:selected')).data('index');
//        Vue.set(app.editnav, 'open_type', open_type);
});
</script>
