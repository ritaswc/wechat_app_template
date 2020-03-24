<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '包邮规则编辑';
$this->params['active_nav_group'] = 1;
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
        <form class="auto-form" method="post"
              return="<?= Yii::$app->urlManager->createUrl(['mch/store/free-delivery-rules']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">包邮金额</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="price" value="<?= $model->price ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">城市选择</label>
                </div>
                <div class="col-sm-6 col-form-label">
                    <div class="card mb-3" v-show="detail && detail!=''">
                        <div class="card-block">
                            <div class="mb-3">
                                <a class="del-rules-btn float-right" href="javascript:" v-bind:data-index="0">[-编辑条目]</a>
                            </div>
                            <div>
                                <span>省份：</span>
                            <span v-for="(province,p_index) in detail">
                                <span v-text="province.name"></span>
                                <input type="hidden"
                                       v-bind:name="'city['+p_index+'][id]'"
                                       v-model="province.id">
                                <input type="hidden"
                                       v-bind:name="'city['+p_index+'][name]'"
                                       v-model="province.name">
                            </span>
                            </div>
                        </div>
                    </div>

                    <a class="show-rules-modal" v-show="!detail || detail==''" href="javascript:">[+新增条目]</a>
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

        <!-- 添加运费规则 -->
        <div class="modal fade rules-modal bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>添加省份</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4" v-for="(province,index) in province_list">
                                <!-- v-if="province.selected!=true"> -->
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
                                            <div class="col-6" v-for="(city,c_index) in province.city">
                                                <!--  v-if="city.selected!=true"> -->
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
            detail: <?=$model->city ? $model->city : '[]'?>,
            province_list: [],
        }
    });
    <?php foreach ($province_list as $province) :?>
    var city = [];
        <?php foreach ($province['city'] as $value) :?>
    city.push({
        id:<?=$value['id']?>,
        name: "<?=$value['name']?>",
        // selected: false,
        show: false
    });
        <?php endforeach;?>
    app.province_list.push({
        id:<?=$province['id']?>,
        name: "<?=$province['name']?>",
        city: city,
        // selected: false,
        show: false,
    });
    <?php endforeach;?>


    $(document).on("click", ".show-rules-modal", function () {
        $(".rules-modal").modal("show");
    });

    $(document).on("click", ".rules-modal .add-rules-btn", function () {
        var province_list = [];
        $(".rules-modal input[name=city]").each(function () {
            if ($(this).prop("checked")) {
                var index = $(this).attr("data-index");
                var p_index = $(this).attr("data-p_index");
                var id = $(this).attr("data-id");
                var name = $(this).attr("data-name");

                province_list.push({
                    id: id,
                    name: name,
                });
            }
        });

        $(".rules-modal input[name=province]").prop("checked", false);
        if (province_list.length > 0) {
            app.detail = province_list;
            $(".rules-modal").modal("hide");
        }

        if (province_list == '') {
            app.detail = '';
            $(".rules-modal").modal("hide");
        }
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
                // if (app.province_list[p_index]['city'][i].selected == false){
                //  count ++;
                // }
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


    $(document).on("click", ".del-rules-btn", function () {
        var array = [];
        for (var i in app.detail) {
            array.push(parseInt(app.detail[i].id));
        }
        var sentinel = 0;
        for (var i in app.province_list) {
            var city = app.province_list[i].city;
            sentinel = 0;
            for (var item in city) {
                if (array.indexOf(city[item].id) != -1) {
                    sentinel++;

                    app.province_list[i].city[item].show = true;
                }
            }
            if (sentinel == city.length) {

                app.province_list[i].show = true;
            }
        }
        ;
        $(".rules-modal").modal("show");
    });

</script>