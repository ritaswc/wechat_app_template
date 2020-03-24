<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 14:26
 */
?>
<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */
$urlManager = Yii::$app->urlManager;
$this->title = '自定义表单';
$this->params['active_nav_group'] = 1;
?>
<style>.menu-header .menu-required,
    .menu-item .menu-required {
        width: 50px;;
    }

    .menu-header > div,
    .menu-item > div {
        width: 16.6666667%;
        padding: .5rem .75rem;
    }

    .menu-item {
        background: #fff;
        margin: .5rem 0;
    }

    .menu-item > div {
        padding: .5rem .75rem;
    }

    .menu-item .drop-btn {
        display: inline-block;
        padding: .25rem;
    }

    .menu-item .drop-btn .iconfont {
        font-size: .75rem;
        color: #666;
        font-weight: bold;
        text-decoration: none;
    }

    .menu-item .drop-btn .iconfont:hover {
        font-size: .75rem;
        color: #333;
        font-weight: bold;
        text-decoration: none;
        cursor: move;
    }
</style>
<div class="panel mb-3" id="app">
    <div class="panel-header">结算页面自定义表单</div>
    <div class="panel-body">
        <form class="auto-form" method="post" autocomplete="off">
            <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">是否开启表单</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="pt-1">
                            <label class="radio-label">
                                <input id="radio1" <?= $is_form == 1 ? 'checked' : null ?>
                                       value="1"
                                       name="is_form" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">是</span>
                            </label>
                            <label class="radio-label">
                                <input id="radio1" <?= $is_form == 0 ? 'checked' : null ?>
                                       value="0"
                                       name="is_form" type="radio" class="custom-control-input">
                                <span class="label-icon"></span>
                                <span class="label-text">否</span>
                            </label>
                        </div>
                        <div class="fs-sm text-danger">注：此表单只适用于结算页面</div>
                        <div class="fs-sm text-danger">注：开启自定义表单后，结算页面的买家留言将取消</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">自定义表单名称</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="form_name" value="<?= $form_name ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">表单设置</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="row mb-4">
                            <div class="col-sm-7">
                                <select class="form-control form-add-type" style="height: auto;">
                                    <option value="text">单行文本</option>
                                    <option value="textarea">多行文本</option>
                                    <option value="time">时间选择器</option>
                                    <option value="date">日期选择器</option>
                                    <option value="radio">单选</option>
                                    <option value="checkbox">多选</option>
                                    <option value="uploadImg">图片上传</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <a class="btn btn-primary form-add" href="javascript:">添加一个字段</a>
                            </div>
                        </div>
                        <div class="fs-sm text-danger">注：名称最好不要超过4个中文字</div>
                        <div style="background: #f6f8f9;padding: 1rem">

                            <div flex="dir:left" class="menu-header mb-2">
                                <div>类型</div>
                                <div class="text-center">名称</div>
                                <div class="text-center menu-required">必填</div>
                                <div class="text-center">提示语</div>
                                <div class="text-center" style="width: 200px;">默认值</div>
                                <div class="text-center">操作</div>
                            </div>
                            <div class="menu-list" id="sortList">
                                <div class="menu-item" flex="dir:left" v-for="(item,index) in form_list">
                                    <template v-if="item.type == 'text'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            单行文本
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                        :name="'form_list['+index+'][required]'"
                                                        type="checkbox" :data-index="index"
                                                        value="1" :checked="item.required==1"
                                                        class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'">
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right" style="width: 200px;">
                                            <input type="text" v-model="item.default" class="form-control"
                                                   :name="'form_list['+index+'][default]'">
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                    <template v-if="item.type=='textarea'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            多行文本
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                        :name="'form_list['+index+'][required]'"
                                                        type="checkbox" :data-index="index"
                                                        value="1" :checked="item.required==1"
                                                        class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'">
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right" style="width: 200px;">
                                            <input type="text" v-model="item.default" class="form-control"
                                                   :name="'form_list['+index+'][default]'">
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                    <template v-if="item.type=='time'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            时间选择器
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                        :name="'form_list['+index+'][required]'"
                                                        type="checkbox" :data-index="index"
                                                        value="1" :checked="item.required==1"
                                                        class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="hidden" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'">
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right" style="width: 200px;">
                                            <input type="time" v-model="item.default" class="form-control"
                                                   :name="'form_list['+index+'][default]'">
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                    <template v-if="item.type=='date'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            日期选择器
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                        :name="'form_list['+index+'][required]'"
                                                        type="checkbox" :data-index="index"
                                                        value="1" :checked="item.required==1"
                                                        class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:center">
                                            <input type="hidden" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'">
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right" style="width: 200px;">
                                            <input type="date" v-model="item.default" class="form-control"
                                                   :name="'form_list['+index+'][default]'">
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                    <template v-if="item.type == 'radio'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            单选
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                        :name="'form_list['+index+'][required]'"
                                                        type="checkbox" :data-index="index"
                                                        value="1" :checked="item.required==1"
                                                        class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="hidden" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'">
                                        </div>
                                        <div class="menu-switch" style="width: 200px;">
                                            <div>
                                                <input type="text" v-model="item.default" class="form-control"
                                                       :name="'form_list['+index+'][default]'">
                                            </div>
                                            <div class="fs-sm">多个请用英文逗号<kbd>,</kbd>隔开</div>
                                            <div class="fs-sm text-danger">注：第一个值默认选中</div>
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                    <template v-if="item.type == 'checkbox'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            多选
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                        :name="'form_list['+index+'][required]'"
                                                        type="checkbox" :data-index="index"
                                                        value="1" :checked="item.required==1"
                                                        class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="hidden" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'">
                                        </div>
                                        <div class="menu-switch" style="width: 200px;">
                                            <div>
                                                <input type="text" v-model="item.default" class="form-control"
                                                       :name="'form_list['+index+'][default]'">
                                            </div>
                                            <div class="fs-sm">多个请用英文逗号<kbd>,</kbd>隔开</div>
                                            <div class="fs-sm text-danger">注：第一个值默认选中</div>
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                    <template v-if="item.type=='uploadImg'">
                                        <div class="menu-name" flex="cross:center">
                                            <input type="hidden" v-model="item.id" :name="'form_list['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'form_list['+index+'][type]'">
                                            图片上传
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.name" class="form-control"
                                                   :name="'form_list['+index+'][name]'">
                                        </div>
                                        <div class="menu-required" flex="cross:center main:center">
                                            <label class="checkbox-label mb-0 mr-0">
                                                <input
                                                    :name="'form_list['+index+'][required]'"
                                                    type="checkbox" :data-index="index"
                                                    value="1" :checked="item.required==1"
                                                    class="custom-control-input re">
                                                <span class="label-icon"></span>
                                            </label>
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right">
                                            <input type="text" v-model="item.tip" class="form-control"
                                                   :name="'form_list['+index+'][tip]'" placeholder="请选择图片">
                                        </div>
                                        <div class="menu-switch" flex="cross:center main:right" style="width: 200px;">
                                        </div>
                                        <div class="menu-drop" flex="cross:center main:center">
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                            <span class="drop-btn">
                                        <i class="iconfont icon-paixu"></i>
                                    </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 text-right">
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/Sortable.min.js"></script>

<script>
    var app = new Vue({
        el: "#app",
        data: {
            form_list: <?=$form_list?>
        }
    });
    $(document).on('click', '.form-del', function () {
        var index = $(this).data('index');
        app.form_list.splice(index, 1);
    });
    $(document).on('click', '.form-add', function () {
        var aa = {};
        aa.type = $('.form-add-type').val();
        app.form_list.push(aa);
    });
    $(document).on('click', '.re', function () {
        var check = $(this).prop('checked');
        var index = $(this).data('index');
        if (check) {
            app.form_list[index].required = 1;
        } else {
            app.form_list[index].required = 0;
        }
    });
</script>
<script>
    var sort = Sortable.create(document.getElementById('sortList'), {
        animation: 250,
    }); // That's all.
</script>
