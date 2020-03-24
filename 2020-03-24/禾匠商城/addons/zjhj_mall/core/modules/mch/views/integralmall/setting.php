<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/8
 * Time: 14:30
 */

$urlManager = Yii::$app->urlManager;
$this->title = '积分商城设置';
//$this->params['active_nav_group'] = 10;
?>
<div class="panel mb-3" id="page">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off"
              return="<?= $urlManager->createUrl(['mch/miaosha/setting']) ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label">积分说明</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group short-row mb-2" v-if="attr_group_list">
                            <span class="input-group-addon">标题</span>
                            <input class="form-control add-attr-group-input" placeholder="如什么叫积分、积分有什么作用、积分如何获取">
                                    <span class="input-group-btn">
                                    <a class="btn btn-secondary add-attr-group-btn" href="javascript:">添加</a>
                                </span>
                        </div>
                        <div v-for="(attr_group,i) in attr_group_list" class="attr-group">
                            <div>
                                <b>{{attr_group.attr_group_name}}</b>
                                <a v-bind:index="i" href="javascript:" class="attr-group-delete">×</a>
                            </div>
                            <div class="attr-list">
                                <div v-for="(attr,j) in attr_group.attr_list" class="attr-item">
                                    <span class="attr-name">{{attr.attr_name}}</span>
                                    <a v-bind:group-index="i" v-bind:index="j" class="attr-delete"
                                       href="javascript:">×</a>
                                </div>
                                <div class="input-group attr-input-group" style="border-radius: 0">
                                        <span class="input-group-addon"
                                              style="padding: .35rem .35rem;font-size: .8rem">内容</span>
                                        <textarea class="form-control form-control-sm add-attr-input" id="seller_comments" name="seller_comments" cols="90" rows="5" style="resize: none;"></textarea>
                                            <span class="input-group-addon"
                                                  style="padding: .35rem .35rem;font-size: .8rem">
                                            <a v-bind:index="i" class="btn btn-secondary btn-sm add-attr-btn"
                                               href="javascript:">添加</a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">签到规则</label>
                    </div>
                    <div class="col-sm-6">
                        <textarea id="seller_comments" name="seller_comments" cols="90" rows="5" style="resize: none;"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">每日签到获得分数</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="name" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">连续签到N天有惊喜</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="name" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">什么叫惊喜</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="name" value="">
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
    </div>
</div>

<script>
    var page = new Vue({
        el: "#page",
        data: {
//            attr_group_list: JSON.parse('<?//=json_encode($goods->getAttrData(), JSON_UNESCAPED_UNICODE)?>//'),//可选规格数据
//            checked_attr_list: JSON.parse('<?//=json_encode($goods->getCheckedAttrData(), JSON_UNESCAPED_UNICODE)?>//'),//已选规格数据
            attr_group_list: [],
            checked_attr_list: [],
        }
    });
    $(document).on("click", ".add-attr-group-btn", function () {
        var name = $(".add-attr-group-input").val();
        name = $.trim(name);
        if (name == "")
            return;
        page.attr_group_list.push({
            attr_group_name: name,
            attr_list: [],
        });
        $(".add-attr-group-input").val("");
        page.checked_attr_list = getAttrList();
    });
    $(document).on("click", ".add-attr-btn", function () {
        var name = $(this).parents(".attr-input-group").find(".add-attr-input").val();
        var index = $(this).attr("index");
        name = $.trim(name);
        if (name == "")
            return;
        page.attr_group_list[index].attr_list.push({
            attr_name: name,
        });
        $(this).parents(".attr-input-group").find(".add-attr-input").val("");
        page.checked_attr_list = getAttrList();
    });
    $(document).on("click", ".attr-group-delete", function () {
        var index = $(this).attr("index");
        page.attr_group_list.splice(index, 1);
        page.checked_attr_list = getAttrList();
    });
    $(document).on("click", ".attr-delete", function () {
        var index = $(this).attr("index");
        var group_index = $(this).attr("group-index");
        page.attr_group_list[group_index].attr_list.splice(index, 1);
        page.checked_attr_list = getAttrList();
    });
    function getAttrList() {
        var array = [];
        for (var i in page.attr_group_list) {
            for (var j in page.attr_group_list[i].attr_list) {
                var object = {
                    attr_group_name: page.attr_group_list[i].attr_group_name,
                    attr_id: null,
                    attr_name: page.attr_group_list[i].attr_list[j].attr_name,
                };
                if (!array[i])
                    array[i] = [];
                array[i].push(object);
            }
        }
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
                            no: oldVal.no,
                            pic: oldVal.pic,
                            attr_list: temp
                        });
                    } else {
                        results.push({
                            num: 0,
                            price: 0,
                            no: '',
                            pic: '',
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
