<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/13
 * Time: 19:52
 */
$urlManager = Yii::$app->urlManager;
$baseUrl = Yii::$app->request->baseUrl;
$staticBaseUrl = Yii::$app->request->baseUrl . '/statics';
$this->title = '商品规格及库存设置';
$this->params['active_nav_group'] = 2;
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl(['mch/group/goods/goods']);
}
?>
<style>
    .goods-pic {
        width: 5rem !important;
        height: 5rem;
        background-size: cover;
        background-color: #ddd;
        background-position: center;
    }

    .form {
        max-width: 70rem;
    }
</style>
<div class="panel mb-3" id="page">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" return="<?= $returnUrl ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class=" col-form-label">商品</label>
                    </div>
                    <div class="col-9">
                        <div class="mb-3 bg-faded" flex="dir:left box:first">
                            <div class="goods-pic"
                                 style="background-image: url(<?= $goods->cover_pic ?>)"></div>
                            <div class="p-2">
                                <div class="mb-1">名称：<?= $goods->name ?></div>
                                <div>团购价：<?= $goods->price ?>元</div>
                            </div>
                        </div>
                    </div>
                </div>

                <template v-if="attr_group_list && attr_group_list.length>0">
                    <div class="form-group row" v-for="(attr_group,index) in attr_group_list">
                        <div class="col-3 text-right">
                            <input class="attr-checkbox-all checkedAll" v-bind:data-index="index" type="checkbox">
                            <label class="">{{attr_group.attr_group_name}}</label>
                        </div>
                        <div class="col-9">
                            <label v-for="attr in attr_group.attr_list" class="mr-4">
                                <input class="attr-checkbox" type="checkbox" v-model="attr.checked">
                                <span>{{attr.attr_name}}</span>
                            </label>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="">选择规格</label>
                        </div>
                        <div class="col-9">
                            您还没有设置商品规格，请先<a href="<?= $urlManager->createUrl(['mch/store/attr']) ?>">添加商品规格</a>
                        </div>
                    </div>
                </template>


                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="">库存及售价明细</label>
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary mb-4 mm" href="javascript:" data-toggle="modal">批量设置</a>
                        <template v-if="checked_attr_list.length>0">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th><input class="select-all" type="checkbox" name="select-all"></th>
                                    <th v-for="item in checked_attr_list[0].attr_list">规格</th>
                                    <th>库存</th>
                                    <th>售价</th>
                                    <th>货号</th>
                                    <th>规格图片</th>
                                </tr>
                                </thead>
                                <tr v-for="(item,index) in checked_attr_list">
                                    <td><input class="select" type="checkbox" name="select" :data-index="index"></td>
                                    <td v-for="(attr,attr_index) in item.attr_list">
                                        <input type="hidden" style="width: 40px"
                                               v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_id]'"
                                               v-bind:value="attr.attr_id">

                                        <input type="hidden" style="width: 40px"
                                               v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_name]'"
                                               v-bind:value="attr.attr_name">
                                        <span>{{attr.attr_name}}</span>
                                    </td>
                                    <td>
                                        <input style="width: 40px" v-bind:name="'attr['+index+'][num]'"
                                               v-bind:value="item.num">
                                    </td>
                                    <td>
                                        <input style="width: 40px" v-bind:name="'attr['+index+'][price]'"
                                               v-bind:value="item.price">
                                    </td>
                                    <td>
                                        <input style="width: 100px" v-bind:name="'attr['+index+'][no]'"
                                               v-bind:value="item.no">
                                    </td>
                                    <td class="image-btn">
                                        <div class="image-picker"
                                             data-url="<?= $urlManager->createUrl(['upload/image']) ?>">
                                            <a href="javascript:"
                                               class="btn btn-secondary new-image-picker-btn">选择图片</a>
                                            <div class="image-picker-view-item">
                                                <input class="image-picker-input" type="hidden"
                                                       v-bind:name="'attr['+index+'][pic]'" v-bind:value="item.pic">
                                                <div class="image-picker-view" data-responsive="65:60"
                                                     v-bind:style="'background-image: url('+item.pic+');width: 65px;height: 60px;'">
                                                    <span class="picker-tip">325×300</span>
                                                    <span class="picker-delete">×</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            </table>
                            <div class="text-warning">售价填写0则表示保持原来的售价</div>
                        </template>
                        <template v-else>
                            <div>请先勾选规格</div>
                        </template>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                    </div>
                    <div class="col-9">
                        <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    </div>
                </div>
            </div>

        </form>
        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
             aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">批量设置</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <tr>
                                <td><label class="col-form-label">库存</label></td>
                                <td><input class="form-control update" type="number" data-index="num"></td>
                            </tr>
                            <tr>
                                <td><label class="col-form-label">售价</label></td>
                                <td><input class="form-control update" type="number" data-index="price"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>

<script>
    var Map = function () {
        this._data = [];
        this.set = function (key, val) {
            for (var i in this._data) {
                if (this._data[i].key == key) {
                    this._data[i].val = val;
                    return true;
                }
            }
            this._data.push({
                key: key,
                val: val,
            });
            return true;
        };
        this.get = function (key) {
            for (var i in this._data) {
                if (this._data[i].key == key)
                    return this._data[i].val;
            }
            return null;
        };
        this.delete = function (key) {
            for (var i in this._data) {
                if (this._data[i].key == key) {
                    this._data.splice(i, 1);
                }
            }
            return true;
        };
    };

    var map = new Map();
    var page = new Vue({
        el: "#page",
        data: {
            //所有规格
            attr_group_list: <?=json_encode($attr_group_list)?>,
            checked_attr_list: <?=$checked_attr_list ? $checked_attr_list : '[]'?>,
        },
    });

    for (var i in page.checked_attr_list) {
        var key = [];
        for (var j in page.checked_attr_list[i].attr_list) {
            key.push(page.checked_attr_list[i].attr_list[j].attr_id);
        }
        map.set(key.sort().toString(), {
            num: page.checked_attr_list[i].num,
            price: page.checked_attr_list[i].price,
        });
    }

    $(document).on("change", ".attr-checkbox", function () {
        var attr_group_list = [];
        for (var i in page.attr_group_list) {
            for (var j in page.attr_group_list[i].attr_list) {
                if (page.attr_group_list[i].attr_list[j].checked) {
                    if (!attr_group_list[i])
                        attr_group_list[i] = [];
                    var object = {
                        attr_id: page.attr_group_list[i].attr_list[j].attr_id,
                        attr_name: page.attr_group_list[i].attr_list[j].attr_name,
                    };
                    attr_group_list[i].push(object);
                }
            }
        }
        console.log(attr_group_list);

        page.checked_attr_list = getAttrList(attr_group_list);
    });

    $(document).on("change", ".checkedAll", function () {
        var index = $(this).data('index');
        var row_info = page.attr_group_list[index].attr_list;
        if ($(this).is(':checked')) {
            for (var i in row_info) {
                page.attr_group_list[index].attr_list[i].checked = true;
            }
        } else {
            for (var i in row_info) {
                page.attr_group_list[index].attr_list[i].checked = false;
            }
        }

        var attr_group_list = [];
        for (var i in page.attr_group_list) {
            for (var j in page.attr_group_list[i].attr_list) {
                if (page.attr_group_list[i].attr_list[j].checked) {
                    if (!attr_group_list[i])
                        attr_group_list[i] = [];
                    var object = {
                        attr_id: page.attr_group_list[i].attr_list[j].attr_id,
                        attr_name: page.attr_group_list[i].attr_list[j].attr_name,
                    };
                    attr_group_list[i].push(object);
                }
            }
        }

        console.log(attr_group_list);
        page.checked_attr_list = getAttrList(attr_group_list);

    });

    function getAttrList(attr_group_list) {
        var array = attr_group_list;
        var len = array.length;
        var results = [];
        var indexs = {};

        function specialSort(start) {
            start++;
            if (start > len - 1) {
                return;
            }
            if (!indexs[start]) {
                indexs[start] = 0;
            }
            if (!(array[start] instanceof Array)) {
                array[start] = [array[start]];
            }
            for (indexs[start] = 0; indexs[start] < array[start].length; indexs[start]++) {
                specialSort(start);
                if (start == len - 1) {
                    var temp = [];
                    for (var i = len - 1; i >= 0; i--) {
                        if (!(array[start - i] instanceof Array)) {
                            array[start - i] = [array[start - i]];
                        }
                        if (array[start - i][indexs[start - i]]) {
                            //console.log(JSON.stringify(array[start - i][indexs[start - i]]));
                            temp.push(array[start - i][indexs[start - i]]);
                        }
                    }
                    var key = [];
                    for (var i in temp) {
                        key.push(temp[i].attr_id);
                    }
                    var oldVal = map.get(key.sort().toString());
                    if (oldVal) {
                        results.push({
                            num: oldVal.num,
                            price: oldVal.price,
                            attr_list: temp
                        });
                    } else {
                        results.push({
                            num: 0,
                            price: 0,
                            attr_list: temp
                        });
                    }
                }
            }
        }

        specialSort(-1);
        return results;
    }
</script>
<script>
    $(document).on('click', '.mm', function () {
        $('.update').val('');
        if ($('.select:checked').length == 0) {
            $.myAlert({
                title: '提示',
                content: "请先勾选需要设置的项目"
            });
            return;
        } else {
            $('.bd-example-modal-sm').modal('show');
        }
    });
    $(document).on('click', '.select-all', function () {
        $('.select').prop('checked', $(this).prop('checked'));
    });
    $(document).on('input', '.update', function () {
        var index = $(this).data('index');
        var val = $(this).val();
        $('.select:checked').each(function (i) {
            var j = $(this).data('index');
            if (index == 'num') {
                page.checked_attr_list[j].num = val;
            } else if (index == 'price') {
                page.checked_attr_list[j].price = val;
            }
        });
    });

</script>