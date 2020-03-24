<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '区域允许购买';
$this->params['active_nav_group'] = 1;
?>
<style>
    .city-list {
        z-index: 999;
    }

    .more {
        font-size: 21px;
    }

    .block {
        cursor: pointer;
        height: 30px;
        line-height: 30px;
    }

    .block.active {
        background-color: #8bf4f8;
    }

</style>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否开启</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input <?= $model['is_enable'] == 1 ? 'checked' : 'checked' ?>
                            value="1"
                            name="is_enable"
                            type="radio"
                            class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                    <label class="radio-label">
                        <input <?= $model['is_enable'] == 0 ? 'checked' : null ?>
                            value="0"
                            name="is_enable"
                            type="radio"
                            class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">关闭</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">允许购买区域</label>
                </div>
                <div class="col-sm-6 col-form-label">
                    <div class="card mb-3" v-for="(item,index) in detail">
                        <div class="card-block">
                            <div class="mb-3">
                                <a class="del-rules-btn float-right" href="javascript:" v-bind:data-index="index">[-删除条目]</a>
                            </div>
                            <div>
                                <span>区域：</span>
                                <span v-for="(province,p_index) in item.province_list">
                                    <span>{{province.name}}</span>
                                    <input type="hidden"
                                           v-bind:name="'detail['+index+'][province_list]['+p_index+'][id]'"
                                           v-model="province.id">
                                    <input type="hidden"
                                           v-bind:name="'detail['+index+'][province_list]['+p_index+'][name]'"
                                           v-model="province.name">
                                </span>
                            </div>
                        </div>
                    </div>
                    <a class="show-rules-modal" href="javascript:">[+新增条目]</a>
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

        <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>添加区域</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <b>省份</b>
                        <div class="row">
                            <div class="col-sm-4" v-for="(province,index) in province_list"
                                 v-if="province.selected!=true">
                                <label>
                                    <input name="province"
                                           v-bind:id="'index_'+index"
                                           v-bind:data-index="index"
                                           v-bind:data-id="province.id"
                                           v-bind:data-name="province.name" v-bind:checked="province.show"
                                           type="checkbox">
                                    {{province.name}}
                                    <a data-toggle="collapse" v-bind:href="'#collapseExample'+index" role="button"
                                       aria-expanded="true" aria-controls="collapseExample" class="more">+</a>
                                </label>
                                <div class="collapse" v-bind:id="'collapseExample'+index">
                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-6" v-for="(city,c_index) in province.list"
                                                 v-if="city.selected!=true">
                                                <label>
                                                    <input name="city"
                                                           v-bind:id="'index_'+c_index"
                                                           v-bind:data-index="c_index"
                                                           v-bind:data-p_index="index"
                                                           v-bind:data-id="city.id"
                                                           v-bind:data-name="city.name" v-bind:checked="city.show"
                                                           type="checkbox">
                                                    {{city.name}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary add-rules-btn">确定</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade rules-modal bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>添加区域</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">省份</div>
                            <div class="col-sm-4">城市</div>
                            <div class="col-sm-4">地区</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="height: 600px;overflow-y: auto;border-right: 1px solid #ccc;">
                                <div flex="cross:center box:first" class="block"
                                     :class="index == p_index ? 'active' : ''"
                                     v-for="(province,index) in province_list"
                                     v-if="province.selected!=true">
                                    <input name="province" :data-index="index" v-bind:checked="province.show"
                                           type="checkbox">
                                    <div class="province" :data-index="index">{{province.name}}</div>
                                </div>
                            </div>
                            <div class="col-sm-4" style="height: 600px;overflow-y: auto;border-right: 1px solid #ccc;">
                                <div flex="cross:center box:first" class="block"
                                     :class="index == c_index ? 'active' : ''"
                                     v-for="(city,index) in province_list[p_index].list"
                                     v-if="city.selected!=true">
                                    <input name="city" v-bind:checked="city.show"
                                           type="checkbox" :data-index="index">
                                    <div class="city" :data-index="index">{{city.name}}</div>
                                </div>
                            </div>
                            <div class="col-sm-4" style="height: 600px;overflow-y: auto">
                                <div flex="cross:center box:first" class="block"
                                     :class="index == d_index ? 'active' : ''"
                                     v-for="(district,index) in province_list[p_index].list[c_index].list"
                                     v-if="district.selected!=true">
                                    <input name="district" :data-index="index" v-bind:checked="district.show"
                                           type="checkbox">
                                    <div class="district" :data-index="index">{{district.name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary add-rules-btn">确定</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


<script>
    var app = new Vue({
        el: "#app",
        data: {
            detail: <?=$model->detail ? $model->detail : '[]'?>,
            province_list: <?=Yii::$app->serializer->encode($province_list)?>,
            city_list: [],
            district_list: [],
            p_index: 0,
            c_index: 0,
            d_index: 0
        },
    });

    function reset() {
        var p_ok = true;
        var c_ok = true;
        var d_ok = true;
        for(var i in app.detail){
            for(var j in app.detail[i].province_list){
                for(var p in app.province_list){
                    app.province_list[p].show = false;
                    if(p_ok){
                        app.p_index = p;
                        p_ok = false;
                    }
                    if(app.province_list[p].id == app.detail[i].province_list[j].id){
                        app.province_list[p].selected = true;
                        p_ok = true;
                    }else{
                        app.province_list[p].selected = false;
                        for(var c in app.province_list[p].list){
                            app.province_list[p].list[c].show = false;
                            if(c_ok){
                                app.c_index = c;
                                c_ok = false;
                            }
                            if(app.detail[i].province_list[j].id == app.province_list[p].list[c].id){
                                app.province_list[p].list[c].selected = true;
                                c_ok = true;
                            }else{
                                app.province_list[p].list[c].selected = false;
                                for(var d in app.province_list[p].list[c].list){
                                    app.province_list[p].list[c].list[d].show = false;
                                    if(d_ok){
                                        app.d_index = d;
                                        d_ok = false;
                                    }
                                    if(app.detail[i].province_list[j].id == app.province_list[p].list[c].list[d].id){
                                        app.province_list[p].list[c].list[d].selected = true;
                                        d_ok = true;
                                    }else{
                                        app.province_list[p].list[c].list[d].selected = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    $(document).on("click", ".show-rules-modal", function () {
        reset();
        $(".rules-modal").modal("show");
    });

    $(document).on("click", ".rules-modal .add-rules-btn-1", function () {
        var province_list = [];
        $(".rules-modal input[name=province]").each(function () {
            if ($(this).prop("checked")) {
                var index = $(this).attr("data-index");
                app.province_list[index].selected = true;
            }
        });
        $(".rules-modal input[name=city]").each(function () {
            if ($(this).prop("checked")) {
                var index = $(this).attr("data-index");
                var p_index = $(this).attr("data-p_index");
                var id = $(this).attr("data-id");
                var name = $(this).attr("data-name");
                if (app.province_list[p_index]['list'].length <= 1) {
                    app.province_list[p_index].selected = true;
                }
                province_list.push({
                    id: id,
                    name: name,
                });
                app.province_list[p_index]['list'][index].selected = true;
            }
            console.log(province_list)
        });
        $(".rules-modal input[name=province]").prop("checked", false);
        if (province_list.length > 0) {
            app.detail.push({
                province_list: province_list,
            });
            $(".rules-modal").modal("hide");
        }
    });

    $(document).on("click", ".rules-modal .add-rules-btn", function () {
        var province_list = app.province_list;
        var new_detail = addDetail(province_list);
        if(new_detail.length > 0){
            app.detail.push({
                province_list:new_detail
            });
        }
        $(".rules-modal").modal("hide");
    });

    function addDetail(list)
    {
        var new_detail = [];
        for(var i in list){
            if(list[i].selected){
                continue;
            }
            if(list[i].show == true){
                new_detail.push({
                    id:list[i].id,
                    name:list[i].name,
                    level:'province'
                });
            }else{
                new_detail = new_detail.concat(addDetail(list[i].list));
            }
        }
        return new_detail;
    }

    $(document).on("change", ".rules-modal input[name=province]", function () {
        var index = $(this).attr("data-index");
        var checked = $(this).prop("checked");
        app.province_list[index].show = checked;
        $(this).next('.province').click();
        $(app.province_list[index].list).each(function (i) {
            app.province_list[index].list[i].show = checked;
            $(app.province_list[index].list[i].list).each(function (j) {
                app.province_list[index].list[i].list[j].show = checked;
            });
        });
    });

    $(document).on("change", ".rules-modal input[name=city]", function () {
        var index = $(this).attr("data-index");
        var checked = $(this).prop("checked");
        $(this).next('.city').click();
        app.province_list[app.p_index].list[index].show = checked;
        $(app.province_list[app.p_index].list[index].list).each(function (i) {
            app.province_list[app.p_index].list[index].list[i].show = checked;
        });
        checkProvince();
    });

    $(document).on("change", ".rules-modal input[name=district]", function () {
        var index = $(this).attr("data-index");
        var checked = $(this).prop("checked");
        app.province_list[app.p_index].list[app.c_index].list[index].show = checked;
        checkCity();
    });

    function checkProvince()
    {
        var ok = true;
        $(app.province_list[app.p_index].list).each(function(i){
            if(app.province_list[app.p_index].list[i].show == false){
                ok = false;
            }
        });
        app.province_list[app.p_index].show = ok;
    }

    function checkCity()
    {
        var ok = true;
        $(app.province_list[app.p_index].list[app.c_index].list).each(function(i){
            if(app.province_list[app.p_index].list[app.c_index].list[i].show == false){
                ok = false;
            }
        });
        app.province_list[app.p_index].list[app.c_index].show = ok;
        checkProvince();
    }

    $(document).on("click", ".del-rules-btn", function () {
        var index = $(this).attr("data-index");
        app.detail.splice(index, 1);
        reset();
    });

    $(document).on('click', '.province', function () {
        var index = $(this).data("index");
        app.p_index = index;
        app.c_index = 0;
        app.d_index = 0;
    });

    $(document).on('click', '.city', function () {
        var index = $(this).data("index");
        app.c_index = index;
        app.d_index = 0;
    });

    $(document).on('click', '.district', function () {
        var index = $(this).data("index");
        app.d_index = index;
    });

</script>