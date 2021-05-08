<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/9/4
 * Time: 10:09
 */
defined('YII_ENV') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '包邮规则';
?>
<style>
    .city-list {
        z-index: 999;
    }

    .more {
        font-size: 21px;
    }

</style>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form-submit" method="post">

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">是否开启</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input :checked="is_enable==1"
                               value="1" v-model="is_enable"
                               name="is_enable"
                               type="radio"
                               class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">开启</span>
                    </label>
                    <label class="radio-label">
                        <input :checked="detail.is_enable==0" v-model="is_enable"
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
                    <label class="col-form-label">全地区金额</label>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <input class="form-control" v-model="total_price">
                        <span class="input-group-addon">元</span>
                    </div>
                    <div class="fs-sm">注：若某个地区单独设置，则该地区的金额以单独设置为准</div>
                    <div class="text-danger fs-sm">注：若填写-1，则表示不开启全地区设置</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">规则</label>
                </div>
                <div class="col-sm-6 col-form-label">
                    <div class="card mb-3" v-for="(item,index) in detail">
                        <div class="card-block">
                            <div class="mb-3">
                                <a class="del-rules-btn float-right" href="javascript:" v-bind:data-index="index">[-删除条目]</a>
                                <a class="edit-rules-btn float-right" href="javascript:" v-bind:data-index="index">[-编辑条目]</a>
                            </div>
                            <div>
                                <span>金额：{{item.price}}元</span>
                            </div>
                            <div>
                                <span>城市：</span>
                            <span class="mr-2" v-for="(province,p_index) in item.province_list">
                                <span v-text="province.name"></span>
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

        <!-- 添加运费规则 -->
        <div class="modal fade rules-modal bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>编辑规则</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="form-group-label col-sm-2">
                                <label class="col-form-label">金额</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" v-model="check_detail.price">
                                    <span class="input-group-addon">元</span>
                                </div>
                            </div>
                        </div>
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
                                            <div class="col-6" v-for="(city,c_index) in province.city"
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

    </div>
</div>


<script>
    var app = new Vue({
        el: "#app",
        data: {
            detail: <?=$ruleList ? $ruleList : []?>,
            province_list: <?=$province_list?>,
            check_detail: {},
            index: -1,
            is_enable:<?=$is_enable == 1 ? 1 : 0?>,
            total_price:<?=$total_price > -1 ? $total_price : -1?>,
        }
    });

    check_province();
    $(document).on("click", ".show-rules-modal", function () {
        app.check_detail = {};
        app.index = -1;
        check_province();
        $(".rules-modal").modal("show");
    });

    $(document).on("change", ".rules-modal input[name=province]", function () {
        var index = $(this).attr("data-index");
        if ($(this).prop("checked")) {
            app.province_list[index].show = true;
            for (var i in app.province_list[index]['city']) {
                app.province_list[index]['city'][i].show = true;
            }
        } else {
            app.province_list[index].show = false;
            for (var i in app.province_list[index]['city']) {
                app.province_list[index]['city'][i].show = false;
            }
        }
    });

    $(document).on("change", ".rules-modal input[name=city]", function () {
        var index = $(this).attr("data-index");
        var p_index = $(this).attr("data-p_index");
        var num = 0;
        var count = 0;

        if ($(this).prop("checked")) {
            app.province_list[p_index]['city'][index].show = true;

            for (var i in app.province_list[p_index]['city']) {
                if (app.province_list[p_index]['city'][i].show) {
                    num++;
                }
                if (app.province_list[p_index]['city'][i].selected == false) {
                    count++;
                }
            }
            if (num == app.province_list[p_index]['city'].length) {
                app.province_list[p_index].show = true;
            }
            if (num == count) {
                app.province_list[p_index].show = true;
            }
        } else {
            app.province_list[p_index]['city'][index].show = false;
            app.province_list[p_index].show = false;
        }
    });

    $(document).on("click", ".rules-modal .add-rules-btn", function () {
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
                if (app.province_list[p_index]['city'].length <= 1) {
                    app.province_list[p_index].selected = true;
                }
                province_list.push({
                    id: id,
                    name: name,
                });
                app.province_list[p_index]['city'][index].selected = true;
            }
        });

        $(".rules-modal input[name=province]").prop("checked", false);
        if (province_list.length > 0) {
            app.check_detail.price = parseFloat(app.check_detail.price);
            if (typeof app.check_detail.price != 'number') {
                app.check_detail.price = 0;
            }
            if (!app.check_detail.price) {
                app.check_detail.price = 0;
            }
            app.check_detail.province_list = province_list;
            if (app.index == -1) {
                app.detail.push({
                    price: app.check_detail.price,
                    province_list: province_list
                });
            } else {
                app.detail[app.index] = app.check_detail;
            }
        }
        check_province();
        $(".rules-modal").modal("hide");
    });

    $(document).on('click', '.del-rules-btn', function () {
        var index = $(this).attr("data-index");
        app.detail.splice(index, 1);
        check_province();
    });

    $(document).on('click', '.edit-rules-btn', function () {
        var index = $(this).attr("data-index");
        app.check_detail = app.detail[index];
        app.index = index;
        check_province_one();
        $(".rules-modal").modal("show");
    });

    function check_province() {
        var check_city_list = [];
        for (var a in app.detail) {
            for (var b in app.detail[a].province_list) {
                check_city_list.push({
                    id: app.detail[a].province_list[b].id
                });
            }
        }
        for (var i in app.province_list) {
            for (var c in app.province_list[i]['city']) {
                app.province_list[i]['city'][c].selected = false;
                app.province_list[i].selected = false;
                app.province_list[i]['city'][c].show = false;
                app.province_list[i].show = false;
                for (var j in check_city_list) {
                    if (check_city_list[j].id == app.province_list[i]['city'][c].id) {
                        app.province_list[i]['city'][c].selected = true;
                        app.province_list[i].selected = true;
                        app.province_list[i]['city'][c].show = true;
                        app.province_list[i].show = true;
                    }
                }
            }
        }
    }

    function check_province_one() {
        var check_city_list = [];
        for (var b in app.check_detail.province_list) {
            check_city_list.push({
                id: app.check_detail.province_list[b].id
            });
        }
        for (var i in app.province_list) {
            for (var c in app.province_list[i]['city']) {
                for (var j in check_city_list) {
                    if (check_city_list[j].id == app.province_list[i]['city'][c].id) {
                        app.province_list[i]['city'][c].selected = false;
                        app.province_list[i].selected = false;
                        app.province_list[i]['city'][c].show = true;
                        app.province_list[i].show = true;
                    }
                }
            }
        }
    }

    $(document).on('click', '.auto-form-btn', function () {
        var btn = $(this);
        btn.btnLoading(btn.text());
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                data: {
                    is_enable: app.is_enable,
                    rule_list: app.detail,
                    total_price: app.total_price
                },
                _csrf: _csrf
            },
            success: function (res) {
                if (res.code == 0) {
                    $.alert({
                        content: res.msg,
                        confirm: function () {
                            if (res.url) {
                                location.href = res.url;
                            } else {
                                location.reload();
                            }
                            setTimeout(function () {
                                btn.btnReset();
                            }, 30000);
                        }
                    });
                }
                if (res.code == 1) {
                    btn.btnReset();
                    $.alert({
                        content: res.msg,
                    });
                }
            },
            error: function (e) {
                btn.btnReset();
                $.alert({
                    title: '<span class="text-danger">系统错误</span>',
                    content: e.responseText,
                });
            }
        });
    });
</script>

