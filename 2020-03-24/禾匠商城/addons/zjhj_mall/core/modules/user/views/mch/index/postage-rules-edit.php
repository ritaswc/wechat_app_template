<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '运费规则编辑';
$this->params['active_nav_group'] = 1;
?>
<style>
    .city-list{
        z-index: 999;
    }
    .more{
        font-size: 21px;
    }

</style>

<div class="panel mb-3" id="app">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post"
              return="<?= Yii::$app->urlManager->createUrl(['user/mch/index/postage-rules']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">规则名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="name" value="<?= $model->name ?>">
                </div>
            </div>
            <div class="form-group row" hidden>
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">快递公司</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="express" value="<?= $model->express ?>">
                    <select class="form-control" name="express_id" hidden>
                        <option value="0">无</option>
                        <?php foreach ($express_list as $express) : ?>
                            <option value="<?= $express->id ?>" <?= $express->id == $model->express_id ? 'selected' : '' ?>><?= $express->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">计费方式</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-label">
                        <input <?= $model['type'] == 1 ? 'checked' : 'checked' ?>
                                value="1"
                                name="type"
                                type="radio"
                                class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">按重计费</span>
                    </label>
                    <label class="radio-label">
                        <input <?= $model['type'] == 2 ? 'checked' : null ?>
                                value="2"
                                name="type"
                                type="radio"
                                class="custom-control-input">
                        <span class="label-icon"></span>
                        <span class="label-text">按件计费</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">运费规则</label>
                </div>
                <div class="col-sm-6 col-form-label">
                    <div class="card mb-3" v-for="(item,index) in detail">
                        <div class="card-block">
                            <div class="mb-3">
                                <span><span class="show-frist"> 首重/件(克/个)：</span>{{item.frist}}</span>
                                <span><span class="show-frist-price">首费(元) ：</span>{{item.frist_price}}</span>
                                <span><span class="show-second">续重/件(克/个) ：</span>{{item.second}}</span>
                                <span><span>续费(元) ：</span>{{item.second_price}}</span>

                                <a class="del-rules-btn float-right" href="javascript:" v-bind:data-index="index">[-删除条目]</a>
                            </div>
                            <input type="hidden" v-bind:name="'detail['+index+'][frist]'"
                                   v-model="item.frist">
                            <input type="hidden" v-bind:name="'detail['+index+'][frist_price]'"
                                   v-model="item.frist_price">
                            <input type="hidden" v-bind:name="'detail['+index+'][second]'"
                                   v-model="item.second">
                            <input type="hidden" v-bind:name="'detail['+index+'][second_price]'"
                                   v-model="item.second_price">

                            <div>
                                <span>省份：</span>
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
                        <b>添加运费规则</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="frist-title">首重(克) ：</b>
                                <input name="frist" class="form-control mb-3 frist" value="" step="1" type="number">
                            </div>
                            <div class="col-6">
                                <b class="frist-price-title">首费(元) ：</b>
                                <input name="frist_price" class="form-control mb-3 frist_price" placeholder="默认0"
                                       value="0"
                                       step="1" type="number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <b class="second-title">续重(克) ：</b>
                                <input name="second" class="form-control mb-3 second" placeholder="默认0" value="1"
                                       step="1"
                                       type="number">
                            </div>
                            <div class="col-6">
                                <b class="second-price-title">续费（元）：</b>
                                <input name="second_price" class="form-control mb-3 second_price" placeholder="默认0"
                                       value="0" step="1" type="number">
                            </div>
                        </div>

                        <b>省份</b>
                        <div class="row">
                            <div class="col-sm-4" v-for="(province,index) in province_list" v-if="province.selected!=true">
                                <label>
                                    <input name="province"
                                           v-bind:id="'index_'+index"
                                           v-bind:data-index="index"
                                           v-bind:data-id="province.id"
                                           v-bind:data-name="province.name" v-bind:checked="province.show" type="checkbox">
                                    {{province.name}}
                                    <a data-toggle="collapse" v-bind:href="'#collapseExample'+index" role="button" aria-expanded="true" aria-controls="collapseExample" class="more">+</a>
                                </label>
                                <div class="collapse" v-bind:id="'collapseExample'+index">
                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-6" v-for="(city,c_index) in province.city" v-if="city.selected!=true">
                                                <label>
                                                    <input name="city"
                                                           v-bind:id="'index_'+c_index"
                                                           v-bind:data-index="c_index"
                                                           v-bind:data-p_index="index"
                                                           v-bind:data-id="city.id"
                                                           v-bind:data-name="city.name" v-bind:checked="city.show" type="checkbox">
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
            detail: <?=$model->detail ? $model->detail : '[]'?>,
            province_list: [],
        },
    });
    <?php foreach ($province_list as $province) :?>
    var city = [];
        <?php foreach ($province['city'] as $value) :?>
        city.push({
            id:<?=$value['id']?>,
            name: "<?=$value['name']?>",
            selected: false,
            show: false
        });
        <?php endforeach;?>
    app.province_list.push({
        id:<?=$province['id']?>,
        name: "<?=$province['name']?>",
        city:city,
        selected: false,
        show:false,
    });
    <?php endforeach;?>
//    for (var i in app.province_list) {
//        var selected = false;
//        for (var j in app.detail) {
//            for (var k in app.detail[j].province_list) {
//                if (app.detail[j].province_list[k].id == app.province_list[i].id)
//                    selected = true;
//            }
//        }
//        app.province_list[i].selected = selected;
//    }

    for (var i in app.province_list) {
        var num = 0;
        for (var c in app.province_list[i]['city']) {

            var selected = false;
            for (var j in app.detail) {
                for (var k in app.detail[j].province_list) {
                    if (app.detail[j].province_list[k].id == app.province_list[i]['city'][c].id)
                        selected = true;
                }
            }
            app.province_list[i]['city'][c].selected = selected;

            if (app.province_list[i]['city'][c].selected == true){
                num ++;
                if (app.province_list[i]['city'].length <= num){
                    app.province_list[i].selected = true;
                }
            }

        }

    }

    $(document).on('change', ".custom-control-input", function () {
        app.detail = [];
        for (var i in app.province_list) {
            app.province_list[i].selected = false;
        }

        changeType();
    });

    function changeType() {
        var type = $('.custom-control-input:checked').val();
        if (type == 1) {
            $('.frist-title,.show-frist').text('首重(克) ：');
            $('.frist-price-title,.show-frist-price').text('首费(元) ：');
            $('.second-title,.show-second').text('续重(克) ：');
            $('.frist').val('1000');
            $('.second').val('1000');
        } else {
            $('.frist-title,.show-frist').text('首件(个) ：');
            $('.frist-price-title,.show-frist-price').text('运费(元) ：');
            $('.second-title,.show-second').text('续件(个) ：');
            $('.frist').val('1');
            $('.second').val('1');
        }
    }

    $(document).on("click", ".show-rules-modal", function () {
        changeType();
        $(".rules-modal").modal("show");
    });

    $(document).on("click", ".rules-modal .add-rules-btn", function () {
        var frist = $(".rules-modal input[name=frist]").val();
        var frist_price = $(".rules-modal input[name=frist_price]").val();
        var second = $(".rules-modal input[name=second]").val();
        var second_price = $(".rules-modal input[name=second_price]").val();
        var province_list = [];
        $(".rules-modal input[name=province]").each(function () {
            if ($(this).prop("checked")) {
                var index = $(this).attr("data-index");
//                var id = $(this).attr("data-id");
//                var name = $(this).attr("data-name");
//                for (var i in app.province_list[index]['city']){
//                    app.province_list[index]['city'][i].selected = true;
//                    province_list.push({
//                        id: app.province_list[index]['city'][i].id,
//                        name: app.province_list[index]['city'][i].name,
//                    });
//                }
                app.province_list[index].selected = true;
//
            }
        });
        $(".rules-modal input[name=city]").each(function () {
            if ($(this).prop("checked")) {
                var index = $(this).attr("data-index");
                var p_index = $(this).attr("data-p_index");
                var id = $(this).attr("data-id");
                var name = $(this).attr("data-name");
//                for (var i in app.province_list[index]['city']){
//                    app.province_list[p_index]['city'][index].selected = true;
//                }
                if (app.province_list[p_index]['city'].length <= 1){
                    app.province_list[p_index].selected = true;
                }
                province_list.push({
                    id: id,
                    name: name,
                });
//                app.province_list[index].selected = true;
                app.province_list[p_index]['city'][index].selected = true;
            }
        });

        $(".rules-modal input[name=province]").prop("checked", false);
        if (province_list.length > 0) {
            app.detail.push({
                frist: frist,
                frist_price: frist_price,
                second: second,
                second_price: second_price,
                province_list: province_list,
            });
            $(".rules-modal").modal("hide");
        }
    });

    $(document).on("change", ".rules-modal input[name=province]", function () {
        var index = $(this).attr("data-index");
        if ($(this).prop("checked")) {
            app.province_list[index].show = true;
            for (var i in app.province_list[index]['city']){
                app.province_list[index]['city'][i].show = true;
            }
        }else {
            app.province_list[index].show = false;
            for (var i in app.province_list[index]['city']){
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

            for (var i in app.province_list[p_index]['city']){
                if (app.province_list[p_index]['city'][i].show){
                    num++;
                }
                if (app.province_list[p_index]['city'][i].selected == false){
                    count ++;
                }
            }
            if (num == app.province_list[p_index]['city'].length){
                app.province_list[p_index].show = true;
            }
            if (num == count){
                app.province_list[p_index].show = true;
            }
        }else{
            app.province_list[p_index]['city'][index].show = false;
            app.province_list[p_index].show = false;
        }
    });


//    $(".rules-modal input[name=province]").change(function () {
//        console.log(1);
//        if ($(this).prop("checked")) {
//            var index = $(this).attr("data-index");
//            var p_index = $(this).attr("data-p_index");
//            var id = $(this).attr("data-id");
//            var name = $(this).attr("data-name");
////                for (var i in app.province_list[index]['city']){
////                    app.province_list[p_index]['city'][index].selected = true;
////                }
//            if (app.province_list[p_index]['city'].length <= 1){
//                app.province_list[p_index].selected = true;
//            }
//            province_list.push({
//                id: id,
//                name: name,
//            });
////                app.province_list[index].selected = true;
//            app.province_list[p_index]['city'][index].selected = true;
//        }
//    })
    $(document).on("click", ".del-rules-btn", function () {
        var index = $(this).attr("data-index");
        var province_list = app.detail[index].province_list;
        app.detail.splice(index, 1);
        for (var i in app.province_list) {
            for (var c in app.province_list[i]['city']) {
                for (var j in province_list) {
                    if (province_list[j].id == app.province_list[i]['city'][c].id) {
                        app.province_list[i]['city'][c].selected = false;
                        app.province_list[i].selected = false;
                        app.province_list[i]['city'][c].show = false;
                        app.province_list[i].show = false;
                    }
                }
            }
        }
    });

//    for (var c in app.province_list[i]['city']) {
//        var num = 0;
//        var selected = false;
//        for (var j in app.detail) {
//            for (var k in app.detail[j].province_list) {
//                if (app.detail[j].province_list[k].id == app.province_list[i]['city'][c].id)
//                    selected = true;num++;
//
//                if (app.province_list[i]['city'].length == num){
//                    app.province_list[i].selected = true;
//                }
//            }
//        }
//        app.province_list[i]['city'][c].selected = selected;
//    }

</script>